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

namespace Magetrend\Scheduler\Api\Data;

/**
 * Interface for schedule
 */
interface ScheduleInterface
{
    const ID = 'entity_id';

    const IS_ACTIVE = 'is_active';

    const NAME = 'name';

    const DESCRIPTION = 'description';

    const DATE_FROM = 'date_from';

    const DATE_TO = 'date_to';

    const STATUS = 'status';

    const TYPE = 'type';

    const CONDITIONS = 'conditions_serialized';

    const STORE_IDS = 'store_ids';

    const PRODUCT_IDS = 'product_ids';

    const PRODUCT_SPECIAL_PRICE = 'product_special_price';

    const PRODUCT_SPECIAL_PRICE_ACTION = 'product_special_price_action';

    const PRODUCT_SPECIAL_PRICE_VALUE = 'product_special_price_value';

    const PRODUCT_STATUS = 'product_status';

    const PRODUCT_NEW_STATUS = 'product_new_status';

    const PRODUCT_STOCK_STATUS = 'product_stock_status';

    const PRODUCT_NEW_STOCK_STATUS = 'product_new_stock_status';

    const PRODUCT_CATEGORIES = 'product_categories';

    const PRODUCT_ADD_TO_CATEGORIES = 'product_add_to_categories';

    const PRODUCT_REMOVE_FROM_CATEGORIES = 'product_remove_from_categories';

    const CMS_PAGE_IDS = 'cms_page_ids';

    const CMS_PAGE_STATUS = 'cms_page_status';

    const CMS_PAGE_NEW_STATUS = 'cms_page_new_status';

    const CMS_BLOCK_IDS = 'cms_block_ids';

    const CMS_BLOCK_STATUS = 'cms_block_status';

    const CMS_BLOCK_NEW_STATUS = 'cms_block_new_status';

    const CATEGORY_IDS = 'category_ids';

    const CATEGORY_STATUS = 'category_status';

    const CATEGORY_NEW_STATUS = 'category_new_status';

    const STATUS_PAUSED = 'paused';

    const STATUS_SCHEDULED = 'scheduled';

    const STATUS_RUNNING = 'running';

    const STATUS_FINISHED = 'finished';

    const STATUS_MISSED = 'missed';

    const TYPE_PRODUCT = 1;

    const TYPE_CATEGORY = 2;

    const TYPE_CMS_PAGE = 3;

    const TYPE_CMS_BLOCK = 4;

    /**
     * Returns date when process schedule
     * @param bool $addOffset
     * @return mixed
     */
    public function getDateFrom($addOffset = false);

    /**
     * Returns date when revert schedule
     * @param bool $addOffset
     * @return mixed
     */
    public function getDateTo($addOffset = false);

    /**
     * Is schedule editable at current status
     * @return mixed
     */
    public function canEdit();

    /**
     * Process schedule
     * @return mixed
     */
    public function process();

    /**
     * Revert schedule
     * @return mixed
     */
    public function revert();
}
