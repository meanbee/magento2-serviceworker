<?php

namespace Meanbee\ServiceWorker\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\Store;

class InstallData implements InstallDataInterface
{
    const CMS_TEMPLATE_DIR = "cms";

    /** @var \Magento\Cms\Model\PageFactory $pageFactory */
    protected $pageFactory;

    /** @var \Magento\Cms\Model\PageRepository $pageRepository */
    protected $pageRepository;

    public function __construct(
        \Magento\Cms\Model\PageFactory $pageFactory,
        \Magento\Cms\Model\PageRepository $pageRepository
    ) {
        $this->pageFactory = $pageFactory;
        $this->pageRepository = $pageRepository;
    }

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     *
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /**
         * Add the Offline notification CMS page
         */
        $page = $this->pageFactory->create();

        if (!$page->checkIdentifier("offline", Store::DEFAULT_STORE_ID)) {
            $page
                ->setData([
                    "identifier"      => "offline",
                    "stores"          => [Store::DEFAULT_STORE_ID],
                    "is_active"       => 1,
                    "title"           => "Offline",
                    "content_heading" => "Offline",
                    "content"         => $this->getCmsTemplate("offline.html"),
                    "page_layout"     => "1column",
                ]);

            $this->pageRepository->save($page);
        }

        $setup->endSetup();
    }

    /**
     * Get the template HTML for a CMS page or block from a data file.
     *
     * @param string $identifier
     *
     * @return string
     */
    public function getCmsTemplate($identifier)
    {
        $file = implode(DIRECTORY_SEPARATOR, [
            __DIR__,
            static::CMS_TEMPLATE_DIR,
            $identifier
        ]);

        if (is_file($file) && is_readable($file)) {
            return file_get_contents($file);
        }

        return "";
    }
}
