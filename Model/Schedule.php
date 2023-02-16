<?php
/**
 * MB "Vienas bitas" (Magetrend.com)
 *
 * @category MageTrend
 * @package  Magetend/Scheduler
 * @author   Edvinas St. <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     http://www.magetrend.com/magento-2-scheduler
 */

namespace Magetrend\Scheduler\Model;

use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\App\CacheInterface;
use Magetrend\Scheduler\Api\Data\ScheduleInterface;
use Magetrend\Scheduler\Helper\Data as ModuleHelper;

/**
 * Schedule object class
 */
class Schedule extends \Magento\Rule\Model\AbstractModel implements ScheduleInterface
{
    /**
     * @var \Magento\CatalogRule\Model\Rule\Condition\CombineFactory
     */
    public $combineFactory;

    /**
     * @var \Magento\CatalogRule\Model\Rule\Action\CollectionFactory
     */
    public $actionCollectionFactory;

    /**
     * @var ResourceModel\Schedule
     */
    public $resource;

    /**
     * @var ScheduleProcessor
     */
    public $scheduleProcessor;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    public $productRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    public $searchCriteriaBuilder;

    /**
     * @var Schedule\ProductInstance
     */
    public $productInstance;

    /**
     * @var Schedule\CmsPageInstance
     */
    public $cmsPageInstance;

    /**
     * @var Schedule\CmsBlockInstance
     */
    public $cmsBlockInstance;

    /**
     * @var Schedule\CategoryInstance
     */
    public $categoryInstance;

    /**
     * @var ModuleHelper
     */
    public $moduleHelper;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilderFactory
     */
    public $transportBuilderFactory;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    public $inlineTranslation;

    /**
     * @var \Magetrend\Scheduler\Api\ScheduleRepositoryInterface
     */
    public $scheduleRepository;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    public $timezone;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    public $date;

    /**
     * Schedule instance
     * @var \Magetrend\Scheduler\Model\Schedule\AbstractInstance
     */
    private $instance = null;

    /**
     * @var int
     */
    private $storeId = 0;

    /**
     * @var array
     */
    private $backup = [];

    /**
     * @var array
     */
    public $productsIds;

    public $cacheManager;

    /**
     * Schedule constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\CatalogRule\Model\Rule\Condition\CombineFactory $combineFactory
     * @param \Magento\CatalogRule\Model\Rule\Action\CollectionFactory $actionCollectionFactory
     * @param ResourceModel\Schedule $scheduleResource
     * @param ScheduleProcessor $scheduleProcessor
     * @param Schedule\ProductInstance $productInstance
     * @param Schedule\CmsPageInstance $cmsPageInstance
     * @param Schedule\CmsBlockInstance $cmsBlockInstance
     * @param Schedule\CategoryInstance $categoryInstance
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ModuleHelper $moduleHelper
     * @param \Magento\Framework\Mail\Template\TransportBuilderFactory $transportBuilderFactory
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magetrend\Scheduler\Api\ScheduleRepositoryInterface $scheduleRepository
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param null $resource
     * @param null $resourceCollection
     * @param array $data
     * @param null $extensionFactory
     * @param null $customAttributeFactory
     * @param \Magento\Framework\Serialize\Serializer\Json|null $serializer
     * @param \Magento\Framework\App\Cache\Manager $cacheManager,
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\CatalogRule\Model\Rule\Condition\CombineFactory $combineFactory,
        \Magento\CatalogRule\Model\Rule\Action\CollectionFactory $actionCollectionFactory,
        \Magetrend\Scheduler\Model\ResourceModel\Schedule $scheduleResource,
        \Magetrend\Scheduler\Model\ScheduleProcessor $scheduleProcessor,
        \Magetrend\Scheduler\Model\Schedule\ProductInstance $productInstance,
        \Magetrend\Scheduler\Model\Schedule\CmsPageInstance $cmsPageInstance,
        \Magetrend\Scheduler\Model\Schedule\CmsBlockInstance $cmsBlockInstance,
        \Magetrend\Scheduler\Model\Schedule\CategoryInstance $categoryInstance,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magetrend\Scheduler\Helper\Data $moduleHelper,
        \Magento\Framework\Mail\Template\TransportBuilderFactory $transportBuilderFactory,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magetrend\Scheduler\Api\ScheduleRepositoryInterface $scheduleRepository,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\App\Cache\Manager $cacheManager,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [],
        ExtensionAttributesFactory $extensionFactory = null,
        AttributeValueFactory $customAttributeFactory = null,
        \Magento\Framework\Serialize\Serializer\Json $serializer = null
    ) {
        $this->combineFactory = $combineFactory;
        $this->actionCollectionFactory = $actionCollectionFactory;
        $this->resource = $scheduleResource;
        $this->scheduleProcessor = $scheduleProcessor;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->productInstance = $productInstance;
        $this->cmsPageInstance = $cmsPageInstance;
        $this->cmsBlockInstance = $cmsBlockInstance;
        $this->categoryInstance = $categoryInstance;
        $this->moduleHelper = $moduleHelper;
        $this->transportBuilderFactory = $transportBuilderFactory;
        $this->inlineTranslation = $inlineTranslation;
        $this->scheduleRepository = $scheduleRepository;
        $this->timezone = $timezone;
        $this->date = $date;
        $this->cacheManager = $cacheManager;
        parent::__construct(
            $context,
            $registry,
            $formFactory,
            $localeDate,
            $resource,
            $resourceCollection,
            $data,
            $extensionFactory,
            $customAttributeFactory,
            $serializer
        );
    }

    /**
     * Daclare resoure model
     */
    protected function _construct()
    {
        $this->_init('Magetrend\Scheduler\Model\ResourceModel\Schedule');
    }

    /**
     * Getter for rule conditions collection
     *
     * @return \Magento\Rule\Model\Condition\Combine
     */
    public function getConditionsInstance()
    {
        return $this->combineFactory->create();
    }

    /**
     * Getter for rule actions collection
     *
     * @return \Magento\CatalogRule\Model\Rule\Action\Collection
     */
    public function getActionsInstance()
    {
        return $this->actionCollectionFactory->create();
    }

    /**
     * @param string $formName
     * @return string
     */
    public function getConditionsFieldSetId($formName = '')
    {
        return $formName . 'rule_conditions_fieldset_' . $this->getId();
    }

    /**
     * Returns products ids by catalog rule
     * @return array
     */
    public function getListProductIds()
    {
        $productCollection = \Magento\Framework\App\ObjectManager::getInstance()->create(
            '\Magento\Catalog\Model\ResourceModel\Product\Collection'
        );
        $productFactory = \Magento\Framework\App\ObjectManager::getInstance()->create(
            '\Magento\Catalog\Model\ProductFactory'
        );
        $this->productsIds = [];
        $this->setCollectedAttributes([]);
        $this->getConditions()->collectValidatedAttributes($productCollection);
        \Magento\Framework\App\ObjectManager::getInstance()->create(
            '\Magento\Framework\Model\ResourceModel\Iterator'
        )->walk(
            $productCollection->getSelect(),
            [[$this, 'callbackValidateProduct']],
            [
                'attributes' => $this->getCollectedAttributes(),
                'product' => $productFactory->create()
            ]
        );
        return $this->productsIds;
    }
    
    /**
     * Callback function for product matching
     *
     * @param array $args
     * @return void
     */
    public function callbackValidateProduct($args)
    {
        $product = clone $args['product'];
        $product->setData($args['row']);
        $websites = $this->getWebsitesMap();
        foreach ($websites as $websiteId => $defaultStoreId) {
            $product->setStoreId($defaultStoreId);
            if ($this->getConditions()->validate($product)) {
                $this->productsIds[] = $product->getId();
            }
        }
    }

    /**
     * Prepare website map
     *
     * @return array
     */
    public function getWebsitesMap()
    {
        $map = [];
        $websites = \Magento\Framework\App\ObjectManager::getInstance()->create(
            '\Magento\Store\Model\StoreManagerInterface'
        )->getWebsites();
        foreach ($websites as $website) {
            // Continue if website has no store to be able to create catalog rule for website without store
            if ($website->getDefaultStore() === null) {
                continue;
            }
            $map[$website->getId()] = $website->getDefaultStore()->getId();
        }
        return $map;
    }

    /**
     * Returns schedule status
     * @return mixed|string
     */
    public function getFrontendStatus()
    {
        if (!$this->getIsActive()) {
            return self::STATUS_PAUSED;
        }

        return $this->getData(self::STATUS);
    }

    /**
     * {@inheritdoc}
     */
    public function getDateFrom($addOffset = false)
    {
        $date = $this->getData(self::DATE_FROM);
        if ($addOffset) {
            $date = $this->moduleHelper->dateAddOffset($date);
        }

        return $date;
    }

    /**
     * {@inheritdoc}
     */
    public function getDateTo($addOffset = false)
    {
        $date = $this->getData(self::DATE_TO);
        if ($addOffset) {
            $date = $this->moduleHelper->dateAddOffset($date);
        }

        return $date;
    }

    /**
     * {@inheritdoc}
     */
    public function canEdit()
    {
        if ($this->getId() && $this->getStatus() != self::STATUS_SCHEDULED) {
            return false;
        }

        return true;
    }

    /**
     * Process schedule
     */
    public function process()
    {
        if (!$this->moduleHelper->isActive()) {
            $this->setStatus(self::STATUS_MISSED);
            $this->scheduleRepository->save($this);
            return;
        }

        $scheduleLifeTime = $this->moduleHelper->getScheduleLifeTime();
        $currentTime = strtotime($this->date->gmtDate());
        if (strtotime($this->getDateFrom()) + $scheduleLifeTime <= $currentTime) {
            $this->setStatus(self::STATUS_MISSED);
            $this->scheduleRepository->save($this);
            return;
        }

        $storeIds = $this->getStores();
        foreach ($storeIds as $storeId) {
            $this->setStoreId($storeId);
            $this->scheduleProcessor->process($this);
        }

        $this->setStatus($this->getDateTo()?ScheduleInterface::STATUS_RUNNING:ScheduleInterface::STATUS_FINISHED);
        $this->scheduleRepository->save($this);
        $this->sendStartNotification();
        $this->cacheManager->flush([\Magento\PageCache\Model\Cache\Type::TYPE_IDENTIFIER]);
    }

    /**
     * Revert data
     */
    public function revert()
    {
        $storeIds = $this->getStores();
        foreach ($storeIds as $storeId) {
            $this->setStoreId($storeId);
            $this->scheduleProcessor->revert($this);
        }

        $this->setStatus(self::STATUS_FINISHED);
        $this->scheduleRepository->save($this);
        $this->sendEndNotification();
        $this->cacheManager->flush([\Magento\PageCache\Model\Cache\Type::TYPE_IDENTIFIER]);
    }

    /**
     * Returns shedule instance
     * @return \Magetrend\Scheduler\Model\Schedule\AbstractInstance
     */
    public function getInstance()
    {
        if ($this->instance === null) {
            switch ($this->getType()) {
                case self::TYPE_PRODUCT:
                    $this->instance = $this->productInstance;
                    break;
                case self::TYPE_CMS_PAGE:
                    $this->instance = $this->cmsPageInstance;
                    break;
                case self::TYPE_CMS_BLOCK:
                    $this->instance = $this->cmsBlockInstance;
                    break;
                case self::TYPE_CATEGORY:
                    $this->instance = $this->categoryInstance;
                    break;
            }

            $this->instance->setSchedule($this);
        }

        return $this->instance;
    }

    /**
     * Returns assigned store ids
     * @return array
     */
    public function getStores()
    {
        $stores = $this->resource->getStoresByScheduleId($this->getId());
        if (empty($stores)) {
            $stores = [0];
        }

        return $stores;
    }

    /**
     * Returns backup data
     * @return mixed
     */
    public function getBackup()
    {
        $storeId = $this->getStoreId();
        if (isset($this->backup[$storeId])) {
            return $this->backup[$storeId];
        }

        $backupList = $this->resource->getBackupScheduleId($this->getId());
        $this->backup[$storeId] = [];

        if (!empty($backupList)) {
            foreach ($backupList as $backupItem) {
                if ($backupItem['store_id'] == $storeId) {
                    $jsonBackup = json_decode($backupItem['backup_data'], true);
                    $this->backup[$storeId][$backupItem['related_object_id']] = $jsonBackup;
                }
            }
        }

        return $this->backup[$storeId];
    }

    /**
     * Set bakcup data
     * @param $data
     */
    public function setBackup($data)
    {
        $this->backup[$this->getStoreId()] = $data;
    }

    /**
     * Returns current store id of schedule
     * @return int
     */
    public function getStoreId()
    {
        return $this->storeId;
    }

    /**
     * Set store id
     * @param $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
        return $this;
    }

    /**
     * Send notification when schedule will be processed
     */
    public function sendStartNotification()
    {
        $templateId = $this->moduleHelper->getConfig(ModuleHelper::XML_PATH_NOTIFICATION_START_TEMPLATE);
        $this->sendNotification($templateId);
    }

    /**
     * Send notifiaction when data will be reverted
     */
    public function sendEndNotification()
    {
        $templateId = $this->moduleHelper->getConfig(ModuleHelper::XML_PATH_NOTIFICATION_END_TEMPLATE);
        $this->sendNotification($templateId);
    }

    /**
     * Send email notification
     * @param $templateId
     */
    public function sendNotification($templateId)
    {
        $sendTo = $this->moduleHelper->getConfig(ModuleHelper::XML_PATH_NOTIFICATION_SEND_TO);
        $this->inlineTranslation->suspend();
        $message = $this->transportBuilderFactory->create()
            ->setTemplateIdentifier($templateId)
            ->setTemplateOptions([
                'area' => \Magento\Framework\App\Area::AREA_ADMINHTML,
                'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
            ])
            ->setTemplateVars([
                'schedule' => $this
            ])
            ->addTo($sendTo)
            ->setFrom($this->moduleHelper->getSender());
        $transport = $message->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }

    /**
     * Returns products ids by rule
     * @return array
     */
    public function getProductsByConditions()
    {
        $conditions = $this->getData('conditions_serialized');
        if (empty($conditions)) {
            return [];
        }

        $conditions = $this->serializer->unserialize($conditions);

        if (!isset($conditions['conditions']) || empty($conditions['conditions'])) {
            return [];
        }

        return $this->getListProductIds();
    }

    /**
     * Returns formated schedule process date
     * @param bool $addOffset
     * @return string
     */
    public function getFormattedDateFrom($addOffset = false)
    {
        $date = $this->getDateFrom($addOffset);
        return $this->timezone->formatDate(
            $this->timezone->date($date),
            \IntlDateFormatter::LONG,
            \IntlDateFormatter::MEDIUM
        );
    }

    /**
     * Returns formated data restore date
     * @param $addOffset
     * @return string
     */
    public function getFormattedDateTo($addOffset)
    {
        $date = $this->getDateTo($addOffset);
        return $this->timezone->formatDate(
            $this->timezone->date($date),
            \IntlDateFormatter::LONG,
            \IntlDateFormatter::MEDIUM
        );
    }
}
