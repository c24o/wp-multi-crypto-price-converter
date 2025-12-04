/**
 * Admin Settings JavaScript
 *
 * Handles the "Refresh Coins List" button functionality.
 *
 * @package Multi_Crypto_Convert
 */

( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		const refreshBtn = document.getElementById( 'mcc-refresh-coins-btn' );
		const statusDiv = document.getElementById( 'mcc-refresh-status' );
		const statusMessage = document.getElementById( 'mcc-status-message' );
		const coinCount = document.getElementById( 'mcc-coin-count' );

		if ( ! refreshBtn ) {
			return;
		}

		refreshBtn.addEventListener( 'click', function ( e ) {
			e.preventDefault();

			// Get the nonce from the button.
			const nonce = refreshBtn.getAttribute( 'data-nonce' );

			// Disable the button and show loading state.
			refreshBtn.disabled = true;
			refreshBtn.textContent = mccSettings.refreshing || 'Refreshing...';

			// Make the AJAX request.
			const formData = new FormData();
			formData.append( 'action', mccSettings.action );
			formData.append( 'nonce', nonce );

			fetch( mccSettings.ajaxUrl, {
				method: 'POST',
				body: formData,
			} )
				.then( ( response ) => response.json() )
				.then( ( data ) => {
					statusDiv.style.display = 'block';
					statusDiv.className = data.success
						? 'notice notice-success settings-error'
						: 'notice notice-error settings-error';

					if ( data.success ) {
						statusMessage.textContent = data.data.message;

						// Update the coin count.
						if ( data.data.coin_count !== undefined ) {
							coinCount.textContent = data.data.coin_count;
						}
					} else {
						statusMessage.textContent =
							data.data.message || 'An error occurred.';
					}

					// Re-enable the button.
					refreshBtn.disabled = false;
					refreshBtn.textContent =
						mccSettings.buttonText || 'Refresh Coins List';
				} )
				.catch( ( error ) => {
					statusDiv.style.display = 'block';
					statusDiv.className = 'notice notice-error settings-error';
					statusMessage.textContent =
						'Network error: ' + error.message;

					// Re-enable the button.
					refreshBtn.disabled = false;
					refreshBtn.textContent =
						mccSettings.buttonText || 'Refresh Coins List';
				} );
		} );
	} );
} )();
