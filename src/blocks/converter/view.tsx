/**
 * Frontend script for the Multi-Crypto Converter block. This file serves as the
 * entry point for rendering the React-based frontend component.
 *
 * It finds all instances of the converter block on the page and mounts the
 * `FrontendConverter` component into them, passing the selected coins as a prop.
 */
import { createRoot } from '@wordpress/element';
import FrontendConverter from './FrontendConverter';

/**
 * Initialize all converter blocks on the page
 */
function initializeBlocks() {
	const blockWrappers = document.querySelectorAll<HTMLElement>( '.wp-block-multi-crypto-convert-converter' );
	blockWrappers.forEach( ( div ) => {
		const coinsString = div.dataset.coins;
		if ( coinsString ) {
			const coins = coinsString.split( ',' ).map( ( coin ) => coin.trim() ).filter( Boolean );
			if ( coins.length > 0 ) {
				const root = createRoot( div );
				root.render( <FrontendConverter coins={ coins } /> );
			}
		}
	} );
}

// Initialize blocks on DOMContentLoaded.
if ( document.readyState === 'loading' ) {
	document.addEventListener( 'DOMContentLoaded', initializeBlocks );
} else {
	initializeBlocks();
}
