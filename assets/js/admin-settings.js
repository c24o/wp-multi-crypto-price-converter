/**
 * Admin Settings JavaScript
 *
 * Handles the "Refresh Coins List" button functionality.
 *
 * @package Multi_Crypto_Convert
 */

( function () {
	'use strict';

	const maxCoins = mccSettings.maxCoins;
	const $select = jQuery( '.mcc-coins-select2' );

	// Initialize Select2 with search functionality.
	$select.select2( {
		width: '100%',
		search: {
			matcher: function( params, data ) {
				const searchTerm = params.term.toLowerCase();
				if ( ! searchTerm ) {
					return data;
				}

				const matchesName = data.text.toLowerCase().includes( searchTerm );
				const matchesSymbol = jQuery( data.element ).data( 'symbol' ).includes( searchTerm );

				if ( matchesName || matchesSymbol ) {
					return data;
				}

				return null;
			}
		}
	} );

	// Enforce maximum coin limit on change.
	$select.on( 'change', function() {
		const selectedCount = jQuery( this ).val().length;
		if ( selectedCount > maxCoins ) {
			// Get the current value before the last change.
			const currentValue = $select.val() || [];
			// Revert to the first maxCoins selections.
			$select.val( currentValue.slice( 0, maxCoins ) ).trigger( 'change' );
		}
	} );
} )();
