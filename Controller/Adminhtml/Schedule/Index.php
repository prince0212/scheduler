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

/**
 * Manage schedules controller class
 */
class Index extends \Magetrend\Scheduler\Controller\Adminhtml\Schedule
{
    /**
     * Manage schedules action
     */
    public function execute()
    {
        if ($this->getRequest()->getParam('ajax')) {
            $this->_forward('grid');
            return;
        }

        $this->_view->loadLayout();

        $this->_setActiveMenu('Magetrend_Scheduler::shedule');
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Manage Schedules'));

        $this->_addBreadcrumb(__('Scheduler'), __('Scheduler'));

        $this->_view->renderLayout();
    }
}
