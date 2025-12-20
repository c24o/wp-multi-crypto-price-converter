/**
 * Admin Settings.
 *
 * Handles the Select2 initialization and coin selection limit enforcement
 * in the plugin settings page.
 *
 * @package Multi_Crypto_Price_Converter
 */

interface MccSettings {
	maxCoins: number;
}

declare const mccSettings: MccSettings;

namespace MccAdminSettings {

	export function initializeCoinsInput(): void {
		const coinsInputSelector: string = '.mcc-coins-select2';
		const coinsInput = jQuery(coinsInputSelector);

		// Initialize Select2 with search functionality.
		coinsInput.select2({
			maximumSelectionLength: mccSettings.maxCoins,
		});
	}
}

// Start the script.
jQuery(MccAdminSettings.initializeCoinsInput);
