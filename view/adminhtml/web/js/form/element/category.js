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
    'jquery',
    'Magento_Ui/js/form/element/ui-select',
], function ($, element) {
    'use strict';
    return element.extend({
        onUpdate: function () {
            var parent = this._super();
            $('#'+this.hiddenElementId).val(this.value().join(','));
            return parent;
        },

        getInitialValue: function () {
            var values = [this.default],
                value;

            values.some(function (v) {
                if (v !== null && v !== undefined) {
                    value = v;

                    return true;
                }

                return false;
            });

            return this.normalizeData(value);
        }
    });
});
