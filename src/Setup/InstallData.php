<?php

namespace Meanbee\ServiceWorker\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\Store;
use Meanbee\ServiceWorker\Model\Config\Source\CachingStrategy;
use Magento\Cms\Model\PageFactory;
use Magento\Cms\Model\PageRepository;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Filesystem\DriverInterface;

class InstallData implements InstallDataInterface
{
    public const CMS_TEMPLATE_DIR = "cms";

    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var PageRepository
     */
    protected $pageRepository;

    /**
     * @var WriterInterface
     */
    protected $configWriter;

    /**
     * @var Json
     */
    protected $serializer;

    /**
     * @var DriverInterface
     */
    protected $fileDriver;

    /**
     * Construct.
     *
     * @param PageFactory $pageFactory
     * @param PageRepository $pageRepository
     * @param WriterInterface $configWriter
     * @param Json $serializer
     * @param DriverInterface $fileDriver
     */
    public function __construct(
        PageFactory $pageFactory,
        PageRepository $pageRepository,
        WriterInterface $configWriter,
        Json $serializer,
        DriverInterface $fileDriver
    ) {
        $this->pageFactory = $pageFactory;
        $this->pageRepository = $pageRepository;
        $this->configWriter = $configWriter;
        $this->serializer = $serializer;
        $this->fileDriver = $fileDriver ?? ObjectManager::getInstance()->get(File::class);
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
        $context = $context;
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

        /**
         * Add custom strategies
         */
        $strategies = [
            ["path" => "checkout/", "strategy" => CachingStrategy::NETWORK_ONLY],
            ["path" => "customer/account/create*", "strategy" => CachingStrategy::NETWORK_ONLY],
            ["path" => "checkout/account/login*", "strategy" => CachingStrategy::NETWORK_ONLY],
        ];

        $this->configWriter->save(
            "web/serviceworker/custom_strategies",
            $this->serializer->serialize($strategies)
        );

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
        
        if ($this->fileDriver->isFile($file) && $this->fileDriver->isReadable($file)) {
            
            return $this->fileDriver->fileGetContents($file);
        }

        return "";
    }
}
