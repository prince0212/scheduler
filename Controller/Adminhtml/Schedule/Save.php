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

namespace Magetrend\Scheduler\Controller\Adminhtml\Schedule;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\NoSuchEntityException;
use Magetrend\Scheduler\Api\Data\ScheduleInterface;

/**
 * Save schedule controller class
 */
class Save extends \Magetrend\Scheduler\Controller\Adminhtml\Schedule
{
    /**
     * @var \Magetrend\Scheduler\Helper\Data
     */
    public $moduleHelper;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param \Magetrend\Scheduler\Api\ScheduleRepositoryInterface $scheduleRepository
     * @param \Magetrend\Scheduler\Api\ScheduleManagementInterface $scheduleManagement
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Stdlib\DateTime\Filter\DateTime $dateTimeFilter
     * @param \Magetrend\Scheduler\Helper\Data $moduleHelper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magetrend\Scheduler\Api\ScheduleRepositoryInterface $scheduleRepository,
        \Magetrend\Scheduler\Api\ScheduleManagementInterface $scheduleManagement,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Stdlib\DateTime\Filter\DateTime $dateTimeFilter,
        \Magetrend\Scheduler\Helper\Data $moduleHelper
    ) {
        $this->moduleHelper = $moduleHelper;
        parent::__construct(
            $context,
            $scheduleRepository,
            $scheduleManagement,
            $resultPageFactory,
            $registry,
            $logger,
            $dateTimeFilter
        );
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if (isset($data['schedule'])) {
            $id = $this->getRequest()->getParam('schedule_id');
            try {
                $filterValues = [ScheduleInterface::DATE_FROM => $this->dateTimeFilter];
                if (isset($data['schedule'][ScheduleInterface::DATE_TO])
                    && !empty($data['schedule'][ScheduleInterface::DATE_TO])) {
                    $filterValues[ScheduleInterface::DATE_TO] = $this->dateTimeFilter;
                }
                $inputFilter = new \Zend_Filter_Input($filterValues, [], $data['schedule']);
                $data['schedule'] = $inputFilter->getUnescaped();

                if (isset($data['schedule']['date_from']) && !empty($data['schedule']['date_from'])) {
                    $data['schedule']['date_from'] = $this->moduleHelper->dateRemoveOffset(
                        $data['schedule']['date_from']
                    );
                }

                if (isset($data['schedule']['date_to']) && !empty($data['schedule']['date_to'])) {
                    $data['schedule']['date_to'] = $this->moduleHelper->dateRemoveOffset(
                        $data['schedule']['date_to']
                    );
                }

                if (isset($data['rule'])) {
                    $data['schedule']['rule'] = $data['rule'];
                }

                $model = $this->scheduleManagement->save($data['schedule'], $id);

                $this->messageManager->addSuccess(__('The schedule has been saved.'));
                $this->_getSession()->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->logger->critical($e);
                $this->messageManager->addException($e, __('Something went wrong while saving the schedule.'));
            }

            $this->_getSession()->setFormData($data);

            if ($id) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            } else {
                return $resultRedirect->setPath('*/*/new', [
                    'type' => $this->getRequest()->getParam('type')
                ]);
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
