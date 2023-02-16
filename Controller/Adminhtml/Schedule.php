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

namespace  Magetrend\Scheduler\Controller\Adminhtml;

/**
 *  Schedule controller class
 */
class Schedule extends \Magento\Backend\App\Action
{
    /**
     * @var \Magetrend\Scheduler\Api\ScheduleRepositoryInterface
     */
    public $scheduleRepository;

    /**
     * @var \Magetrend\Scheduler\Api\ScheduleManagementInterface
     */
    public $scheduleManagement;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    public $registry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public $resultPageFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    public $logger;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\Filter\DateTime
     */
    public $dateTimeFilter;

    /**
     * Schedule constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magetrend\Scheduler\Api\ScheduleRepositoryInterface $scheduleRepository
     * @param \Magetrend\Scheduler\Api\ScheduleManagementInterface $scheduleManagement
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Stdlib\DateTime\Filter\DateTime $dateTimeFilter
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magetrend\Scheduler\Api\ScheduleRepositoryInterface $scheduleRepository,
        \Magetrend\Scheduler\Api\ScheduleManagementInterface $scheduleManagement,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Stdlib\DateTime\Filter\DateTime $dateTimeFilter
    ) {
        $this->scheduleRepository = $scheduleRepository;
        $this->scheduleManagement = $scheduleManagement;
        $this->resultPageFactory = $resultPageFactory;
        $this->registry = $registry;
        $this->logger = $logger;
        $this->dateTimeFilter = $dateTimeFilter;
        parent::__construct($context);
    }

    /**
     * Default action
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Scheduler / Manage Schedules'));
        $this->_view->renderLayout();
    }

    /**
     * Check if user has enough privileges
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magetrend_Scheduler::schedule');
    }
}
