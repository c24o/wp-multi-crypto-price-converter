"use strict";
/**
 * Admin Settings.
 *
 * Handles the Select2 initialization and coin selection limit enforcement
 * in the plugin settings page.
 *
 * @package Multi_Crypto_Convert
 */
var MccAdminSettings;
(function (MccAdminSettings) {
    function initializeCoinsInput() {
        const coinsInputSelector = '.mcc-coins-select2';
        const coinsInput = jQuery(coinsInputSelector);
        // Initialize Select2 with search functionality.
        coinsInput.select2({
            maximumSelectionLength: mccSettings.maxCoins,
        });
    }
    MccAdminSettings.initializeCoinsInput = initializeCoinsInput;
})(MccAdminSettings || (MccAdminSettings = {}));
// Start the script.
jQuery(MccAdminSettings.initializeCoinsInput);
