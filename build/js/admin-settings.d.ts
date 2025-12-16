/**
 * Admin Settings.
 *
 * Handles the Select2 initialization and coin selection limit enforcement
 * in the plugin settings page.
 *
 * @package Multi_Crypto_Convert
 */
interface MccSettings {
    maxCoins: number;
}
declare const mccSettings: MccSettings;
declare namespace MccAdminSettings {
    function initializeCoinsInput(): void;
}
