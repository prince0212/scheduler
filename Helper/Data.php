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

namespace Magetrend\Scheduler\Helper;

use Magento\Framework\App\Helper\Context;

/**
 * Module helper class
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_GENERAL_IS_ACTIVE = 'scheduler/general/is_active';

    const XML_PATH_SCHEDULE_LIFETIME = 'scheduler/schedule/lifetime';

    const XML_PATH_NOTIFICATION_START_TEMPLATE = 'scheduler/notification/start_email_template';

    const XML_PATH_NOTIFICATION_END_TEMPLATE = 'scheduler/notification/end_email_template';

    const XML_PATH_NOTIFICATION_SEND_TO = 'scheduler/notification/send_to';

    const XML_PATH_NOTIFICATION_SEND_START_EMAIL = 'scheduler/notification/send_start_email';

    const XML_PATH_NOTIFICATION_SEND_END_EMAIL = 'scheduler/notification/send_end_email';

    /**
     * @var \Magento\Framework\Registry
     */
    public $registry;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    public $timezone;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    public $date;

    /**
     * Data constructor.
     * @param Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {
        $this->registry = $registry;
        $this->storeManager = $storeManager;
        $this->date = $date;
        $this->timezone = $timezone;
        parent::__construct($context);
    }

    /**
     * Is module active in system config
     *
     * @param null $store
     * @return bool
     */
    public function isActive($store = null)
    {
        if ($store == -1) {
            $store = $this->storeManager->getStore()->getId();
        }

        if ($this->scopeConfig->getValue(
            self::XML_PATH_GENERAL_IS_ACTIVE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        )) {
            return true;
        }
        return false;
    }

    /**
     * Return lifetime of schedule
     * @return int|string
     */
    public function getScheduleLifeTime()
    {
        $lifeTime = $this->scopeConfig->getValue(
            self::XML_PATH_SCHEDULE_LIFETIME,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return is_numeric($lifeTime)?($lifeTime*60):3600;
    }

    /**
     * Returns system config value
     * @param $path
     * @param null $store
     * @return mixed
     */
    public function getConfig($path, $store = null)
    {
        return $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Remove offset from date
     * Returns 0 GMT date
     * @param $date
     * @return string
     */
    public function dateRemoveOffset($date)
    {
        if (empty($date)) {
            return $date;
        }

        $time = strtotime($date);
        $offset = $this->timezone->date()->getOffset();
        return $this->date->date('Y-m-d H:i:s', $time - $offset);
    }

    /**
     * Add date offset to get local time
     * @param $date
     * @return string
     */
    public function dateAddOffset($date)
    {
        if (empty($date)) {
            return $date;
        }

        $time = strtotime($date);
        $offset = $this->timezone->date()->getOffset();
        return $this->date->date('Y-m-d H:i:s', $time + $offset);
    }

    /**
     * Returns general contact sender
     * @param null|int $store
     * @return string
     */
    public function getSender($store = null)
    {
        return $this->scopeConfig->getValue(
            'trans_email/ident_general',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
