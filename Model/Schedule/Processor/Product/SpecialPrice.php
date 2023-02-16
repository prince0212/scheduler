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

namespace Magetrend\Scheduler\Model\Schedule\Processor\Product;

use Magetrend\Scheduler\Api\Data\ScheduleInterface;
use Magetrend\Scheduler\Model\Config\Source\PriceAction;

/**
 * Proessor class: Update product special price
 */
class SpecialPrice extends \Magetrend\Scheduler\Model\Schedule\Processor\Product
{
    /**
     * @var array
     */
    public $attributes = ['special_price', 'special_from_date', 'special_to_date'];

    /**
     * @param ScheduleInterface $schedule
     * @return bool
     */
    public function canProcess($schedule)
    {
        if (!$schedule->getData(ScheduleInterface::PRODUCT_SPECIAL_PRICE)) {
            return false;
        }

        return parent::canProcess($schedule);
    }

    /**
     * Update object data
     * @param $schedule
     * @param $product
     */
    public function process($schedule, $product)
    {
        $this->backupData($schedule, $product->getId(), $product, $this->attributes);

        $method = $schedule->getData(ScheduleInterface::PRODUCT_SPECIAL_PRICE_ACTION);
        $value = $schedule->getData(ScheduleInterface::PRODUCT_SPECIAL_PRICE_VALUE);

        $currentPrice = $product->getPrice();
        $specialPrice = $currentPrice;
        switch ($method) {
            case PriceAction::MINUS_FIXED:
                $specialPrice = $currentPrice - $value;
                break;
            case PriceAction::PLUS_FIXED:
                $specialPrice = $currentPrice + $value;
                break;
            case PriceAction::MINUS_PERCENTAGE:
                $specialPrice = $currentPrice - ($currentPrice * ($value / 100));
                break;
            case PriceAction::PLUS_PERCENTAGE:
                $specialPrice = $currentPrice + ($currentPrice * ($value / 100));
                break;
            case PriceAction::REPLACE:
                $specialPrice = $value;
                break;
        }

        $specialPrice = $specialPrice > 0 ? $specialPrice : 0;
        $product->setData('special_price', $specialPrice);
        $product->setData('special_from_date', $schedule->getDateFrom());
        $product->setData('special_to_date', $schedule->getDateTo());

        $this->saveSpecialPrice($product);
    }

    /**
     * Revert data from backup
     * @param $schedule
     * @param $product
     */
    public function revert($schedule, $product)
    {
        $backups = $schedule->getBackup();
        $storeId = $schedule->getStoreId();
        if (empty($backups) || !isset($backups[$product->getId()])) {
            return;
        }

        $backupData = $backups[$product->getId()];
        $specialPrice = isset($backupData['special_price'])?$backupData['special_price']:null;
        $from = isset($backupData['special_from_date'])?$backupData['special_from_date']:null;
        $to = isset($backupData['special_to_date'])?$backupData['special_to_date']:null;

        $product->setData('special_price', $specialPrice);
        $product->setData('special_from_date', $from);
        $product->setData('special_to_date', $to);

        $this->saveSpecialPrice($product);
    }

    /**
     * Save product special price
     * @param $product
     */
    public function saveSpecialPrice($product)
    {
        $this->productResource->saveAttribute($product, 'special_from_date');
        $this->productResource->saveAttribute($product, 'special_to_date');

        $product->setStoreId(0);
        $this->productResource->saveAttribute($product, 'special_price');
    }
}
