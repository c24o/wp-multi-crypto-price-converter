/**
 * React component for the frontend view of the Multi Crypto Price Converter block.
 */
import { ReactElement, useEffect, useState } from 'react';
import { __ } from '@wordpress/i18n';

interface PricesMap {
	[key: string]: number;
}

interface FrontendConverterProps {
	coins: string[];
}

const UPDATE_INTERVAL_MS = 60000;

/**
 * Fetch prices from the REST API endpoint.
 *
 * @param {string} coinsParam - Comma-separated coin symbols.
 * @param {AbortSignal} [signal] - Optional abort signal for the fetch request.
 * @return {Promise<PricesMap>} A map of coin symbols to their prices in USD.
 */
async function fetchPrices( coinsParam: string, signal?: AbortSignal ): Promise<PricesMap> {
	try {
		const response = await fetch( `/wp-json/mcc/v1/prices?coins=${ coinsParam }`, { signal } );
		if ( ! response.ok ) {
			throw new Error( `HTTP error! status: ${ response.status }` );
		}
		const data = await response.json();
		if ( data.success && data.data ) {
			const pricesMap: PricesMap = {};
			data.data.forEach( ( item: { symbol: string, price_usd: string } ) => {
				pricesMap[ item.symbol ] = parseFloat( item.price_usd );
			} );
			return pricesMap;
		}
		throw new Error( data.message || 'Invalid API response' );
	} catch ( error ) {
		if ( error instanceof Error && error.name === 'AbortError' ) {
			throw error;
		}
		console.error( 'Failed to fetch prices:', error );
		return {};
	}
}

/**
 * Format the converted amount to display it.
 *
 * @param {string} coin - The coin to display.
 * @param {number} amount - The amount to format.
 * @return {string} The amount formatted to 8 decimal places.
 */
function formatConvertedAmount( coin: string, amount: number ): string {
	if ( ! amount || ! coin ) return '';
	if ( 'usd' === coin ) return amount.toFixed( 2 );
	return amount.toFixed( 8 );
}

/**
 * Format a number as a USD currency string.
 *
 * @param {number} price - The price to format.
 * @return {string} The formatted price string.
 */
function formatPrice( price: number ): string {
	if ( ! price || price === 0 ) {
		return '—';
	}
	return new Intl.NumberFormat( 'en-US', {
		style: 'currency',
		currency: 'USD',
		minimumFractionDigits: 2,
		maximumFractionDigits: 2,
	} ).format( price );
}

export default function FrontendConverter( { coins }: FrontendConverterProps ): ReactElement {
	const [ prices, setPrices ] = useState<PricesMap>( {} );
	const [ baseCoin, setBaseCoin ] = useState<string>( 'usd' );
	const [ baseAmount, setBaseAmount ] = useState<number | ''>( 1 );
	const [ isLoading, setIsLoading ] = useState<boolean>( true );
	const [ error, setError ] = useState<boolean>( false );

	useEffect( () => {
		const controller = new AbortController();
		const coinsParam = coins.join( ',' );
		let timeoutId: ReturnType<typeof setTimeout>;

		const updatePrices = async () => {
			try {
				const fetchedPrices = await fetchPrices( coinsParam, controller.signal );
				// Only update if we have data, otherwise keep old prices or show error
				if ( Object.keys( fetchedPrices ).length > 0 ) {
					setPrices( fetchedPrices );
					setError( false );
				} else {
					setError( true );
				}
			} catch ( err ) {
				// Ignore abort errors
				if ( err instanceof Error && err.name !== 'AbortError' ) {
					setError( true );
				}
			} finally {
				if ( ! controller.signal.aborted ) {
					setIsLoading( false );
					// Schedule the next update only after the current one finishes (Recursive Polling)
					timeoutId = setTimeout( updatePrices, UPDATE_INTERVAL_MS );
				}
			}
		};

		// Start the polling loop.
		updatePrices();

		// Cleanup timeout and abort fetch on component unmount/update.
		return () => {
			clearTimeout( timeoutId );
			controller.abort();
		};
	}, [ coins ] );

	/**
	 * Calculates the display value for a specific coin based on the current base coin.
	 *
	 * @param {string} targetCoin - The coin to calculate for.
	 * @return {string | number} The calculated amount or the raw input if it's the base.
	 */
	const getDisplayAmount = ( targetCoin: string ): string | number => {
		if ( baseAmount === '' || baseAmount === 0 ) return '';
		if ( targetCoin === baseCoin ) return baseAmount;
		if ( 'usd' !== baseCoin && ! prices[ baseCoin ] ) return '';
		if ( 'usd' !== targetCoin && ! prices[ targetCoin ] ) return '';

		const usdValue = 'usd' === baseCoin ? baseAmount : baseAmount * prices[ baseCoin ];
		const targetValue = 'usd' === targetCoin ? usdValue : usdValue / prices[ targetCoin ];
		return formatConvertedAmount( targetCoin, targetValue );
	};

	/**
	 * Focus the input when clicking on the row cells.
	 *
	 * @param {React.MouseEvent<HTMLTableRowElement>} e - The click event.
	 */
	const handleRowClick = ( e: React.MouseEvent<HTMLTableRowElement> ) => {
		if ( 'INPUT' === ( e.target as HTMLElement ).tagName ) {
			return;
		}
		const input = e.currentTarget.querySelector( 'input' );
		if ( input ) {
			input.focus();
		}
	};

	return (
		<figure className="wp-block-table is-style-stripes">
			<table>
				<thead>
					<tr>
						<th scope="col" className="mcpc-converter-th-coin">{ __( 'Coin', 'multi-crypto-price-converter' ) }</th>
						<th scope="col" className="mcpc-converter-th-price">{ __( 'Price (USD)', 'multi-crypto-price-converter' ) }</th>
						<th scope="col" className="mcpc-converter-th-amount">{ __( 'Amount', 'multi-crypto-price-converter' ) }</th>
					</tr>
					<tr
						className={ `mcpc-converter-row${ 'usd' === baseCoin ? ' mcpc-row-active' : '' }` }
						onClick={ handleRowClick }
					>
						<td>
							{ __( 'USD', 'multi-crypto-price-converter' ) }
						</td>
						<td>
							{ formatPrice( 1.0 ) /* USD is always 1 USD */ }
						</td>
						<td>
							<input
								id="mcpc-usd-amount"
								type="number"
								className="mcpc-amount-input"
								aria-label={ __( 'Amount in USD', 'multi-crypto-price-converter' ) }
								value={ getDisplayAmount( 'usd' ) }
								onChange={ ( e ) => {
									setBaseCoin( 'usd' );
									setBaseAmount( e.target.value === '' ? '' : parseFloat( e.target.value ) );
								} }
								placeholder="1.00"
								step="0.01"
								min="0"
							/>
						</td>
					</tr>
				</thead>
				<tbody>
					{ isLoading ? (
						<tr>
							<td colSpan={ 3 }>{ __( 'Loading prices...', 'multi-crypto-price-converter' ) }</td>
						</tr>
					) : error ? (
						<tr>
							<td colSpan={ 3 } className="mcpc-error">{ __( 'Error loading prices.', 'multi-crypto-price-converter' ) }</td>
						</tr>
					) : (
						coins.map( ( coin ) => {
							const price = prices[ coin ];
							const isCurrentCoin = coin === baseCoin;
							const inputId = `mcpc-amount-${ coin }`;
							return (
								<tr
									key={ coin }
									data-coin={ coin }
									className={ isCurrentCoin ? 'mcpc-row-active mcpc-converter-row' : 'mcpc-converter-row' }
									onClick={ handleRowClick }
								>
									<td>
										{ coin.toUpperCase() }
									</td>
									<td>
										{ formatPrice( price ) }
									</td>
									<td>
										<input
											id={ inputId }
											type="number"
											aria-label={ `Amount in ${ coin.toUpperCase() }` }
											className="mcpc-amount-input"
											value={ getDisplayAmount( coin ) }
											onChange={ ( e ) => {
												setBaseCoin( coin );
												setBaseAmount( e.target.value === '' ? '' : parseFloat( e.target.value ) );
											} }
											placeholder="-"
											step="0.00000001"
											min="0"
										/>
									</td>
								</tr>
							);
						} )
					) }
				</tbody>
			</table>
		</figure>
	);
}
