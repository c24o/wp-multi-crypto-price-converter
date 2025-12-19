/**
 * React component for the frontend view of the Multi-Crypto Converter block.
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
 * @return {Promise<PricesMap>} A map of coin symbols to their prices in USD.
 */
async function fetchPrices( coinsParam: string ): Promise<PricesMap> {
	try {
		const response = await fetch( `/wp-json/mcc/v1/prices?coins=${ coinsParam }` );
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
		console.error( 'Failed to fetch prices:', error );
		return {};
	}
}

/**
 * Calculate the converted amount.
 *
 * @param {number} usdAmount - The amount in USD.
 * @param {number} price - The price of the cryptocurrency.
 * @return {number} The converted amount, formatted to 8 decimal places.
 */
function calculateConversion( usdAmount: number, price: number ): number {
	if ( ! usdAmount || ! price || price === 0 ) {
		return 0;
	}
	return usdAmount / price;
}

/**
 * Format the converted amount to display it.
 *
 * @param {number} amount - The amount to format.
 * @return {string} The amount formatted to 8 decimal places.
 */
function formatConvertedAmount( amount: number ): string {
	return amount ? amount.toFixed( 8 ) : '';
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
	const [ amounts, setAmounts ] = useState<PricesMap>( { 'usd': 1 } );
	const [ lastBaseCoin, setLastBaseCoin ] = useState<string>( 'usd' );
	const [ isLoading, setIsLoading ] = useState<boolean>( true );

	useEffect( () => {
		const coinsParam = coins.join( ',' );

		const updatePrices = async () => {
			const fetchedPrices = await fetchPrices( coinsParam );
			setPrices( fetchedPrices );
			setIsLoading( false );
		};

		// Fetch prices immediately and then set an interval.
		updatePrices();
		const intervalId = setInterval( updatePrices, UPDATE_INTERVAL_MS );

		// Cleanup interval on component unmount.
		return () => clearInterval( intervalId );
	}, [ coins ] );

	// Recalculate conversions when prices are updated.
	useEffect( () => {
		convertCoinsAmounts( lastBaseCoin, amounts[ lastBaseCoin ] );
	}, [ prices ] );

	/**
	 * Convert the amounts of the coins to the equivalent amount of the base
	 * coin used.
	 *
	 * @param {string} baseCoin - The coin to use as a base.
	 * @param {number} amountOverride - The new amount for the base coin.
	 */
	const convertCoinsAmounts = ( baseCoin: string, amountOverride: number ) => {
		setLastBaseCoin( baseCoin );
		const newAmounts = { ...amounts };
		newAmounts[ baseCoin ] = amountOverride;

		// Convert the current amount to USD.
		const usdAmount = 'usd' !== baseCoin ? amountOverride * prices[ baseCoin ] : amountOverride;
		if ( 'usd' !== baseCoin ) {
			newAmounts.usd = usdAmount;
		}

		// Convert all the other coins.
		for ( const [ targetCoin, targetPrice ] of Object.entries( prices ) ) {
			if ( baseCoin !== targetCoin ) {
				newAmounts[ targetCoin ] = calculateConversion( usdAmount, targetPrice );
			}
		}
		setAmounts( newAmounts );
	};

	/**
	 * Handler of the coin amounts changes.
	 *
	 * @param e - The input changed.
	 * @param coin - The coin being changed.
	 */
	const handleAmountChange = ( e: React.ChangeEvent<HTMLInputElement>, coin: string ) => {
		const value = e.target.value;
		// Allow empty input to be treated as 0, otherwise parse as float.
		const newAmount = value === '' ? 0 : parseFloat( value );
		convertCoinsAmounts( coin, newAmount );
	};

	/**
	 * Focus the input when clicking on the row cells.
	 *
	 * @param {React.MouseEvent<HTMLTableRowElement>} e - The click event.
	 */
	const handleRowClick = ( e: React.MouseEvent<HTMLTableRowElement> ) => {
		if ( ( e.target as HTMLElement ).tagName === 'INPUT' ) {
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
						<th className="mcc-converter-th-coin">{ __( 'Cryptocurrency', 'multi-crypto-convert' ) }</th>
						<th className="mcc-converter-th-price">{ __( 'Price (USD)', 'multi-crypto-convert' ) }</th>
						<th className="mcc-converter-th-amount">{ __( 'Converted Amount', 'multi-crypto-convert' ) }</th>
					</tr>
					<tr
						className={ `mcc-converter-row${ 'usd' === lastBaseCoin ? ' mcc-row-active' : '' }` }
						onClick={ handleRowClick }
					>
						<td>
							{ __( 'USD', 'multi-crypto-convert' ) }
						</td>
						<td>
							{ formatPrice( 1 ) }
						</td>
						<td>
							<input
								id="mcc-usd-amount"
								type="number"
								className="mcc-amount-input"
								value={ 'usd' === lastBaseCoin ? amounts.usd : amounts.usd.toFixed( 2 ) }
								onChange={ ( e ) => handleAmountChange( e, 'usd' ) }
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
							<td colSpan={ 3 }>{ __( 'Loading prices...', 'multi-crypto-convert' ) }</td>
						</tr>
					) : (
						coins.map( ( coin ) => {
							const price = prices[ coin ];
							const isCurrentCoin = coin === lastBaseCoin;
							return (
								<tr
									key={ coin }
									data-coin={ coin }
									className={ isCurrentCoin ? 'mcc-row-active' : '' }
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
											type="number"
											className="mcc-amount-input"
											value={ isCurrentCoin ? amounts[ coin ] : formatConvertedAmount( amounts[ coin ] ) }
											onChange={ ( e ) => handleAmountChange( e, coin ) }
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
