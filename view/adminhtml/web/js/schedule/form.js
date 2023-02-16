/**
 * MB "Vienas bitas" (Magetrend.com)
 *
 * @category MageTrend
 * @package  Magetend/Scheduler
 * @author   Edvinas St. <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     http://www.magetrend.com/magento-2-scheduler
 */

define([
    'jquery'
], function ($) {
    'use strict';

    function enableReadOnly() {
        $('#catalog_category_products_table').find('input, select').attr('disabled', 'disabled');
        $('.mt-sheduler-field-readonly').attr('disabled', 'disabled');
        $('#schedule_tabs_products_content').find('.admin__data-grid-header-row').hide();

        hideWithOverlay([
            '.field-product_add_to_categories .admin__field-control',
            '.field-product_remove_from_categories .admin__field-control',
            '#schedule_tabs_conditions_content .rule-tree-wrapper',
        ]);
    }

    function hideWithOverlay(selectors) {
        $.each(selectors, function (index, selector) {
            var element = $(selector);
            var overlay = $('<div class="field-overlay"></div>').css({
                "position": "absolute",
                "top": 0,
                "left": 0,
                "width":'100%',
                "height": '100%',
                "background": "#e5e5e5",
                "display": "block",
                "opacity": 0.4
            });

            element.css({
                position: "relative"
            }).append(overlay);

        });
    }


    return function (config) {
        if (config.readonly) {
            enableReadOnly();
        }
    };
});
