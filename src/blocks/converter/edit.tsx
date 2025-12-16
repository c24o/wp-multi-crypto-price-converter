/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';

import { PanelBody } from '@wordpress/components';
import { MultiSelectControl } from '@codeamp/block-components';
import { useState, useEffect } from '@wordpress/element';

interface BlockAttributes {
	coins: string;
}

interface SetAttributesFn {
	(attributes: Partial<BlockAttributes>): void;
}

interface CoinOption {
	value: string;
	label: string;
}

interface EditProps {
	attributes: BlockAttributes;
	setAttributes: SetAttributesFn;
}

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {JSX.Element} Element to render.
 */
export default function Edit({ attributes, setAttributes }: EditProps): JSX.Element {
	const [coinsList, setCoinsList] = useState<CoinOption[]>([]);
	const [isLoading, setIsLoading] = useState<boolean>(true);

	const coins: string[] = attributes.coins && 0 < attributes.coins.length
		? attributes.coins.split(',')
		: [];

	// Fetch selected coins from the admin settings via REST API.
	useEffect(() => {
		const fetchSelectedAvailableCoins = async () => {
			try {
				const response = await fetch('/wp-json/mcc/v1/selected-available-coins');
				if (!response.ok) {
					throw new Error('Failed to fetch the selected available coins.');
				}
				const data = await response.json();

				if (data.success && data.data) {
					setCoinsList(data.data);
				} else {
					setCoinsList([]);
				}
			} catch (error) {
				console.error('Error fetching the selected available coins:', error);
				setCoinsList([]);
			} finally {
				setIsLoading(false);
			}
		};

		fetchSelectedAvailableCoins();
	}, []);

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Coins', 'multi-crypto-convert')}>
					{isLoading ? (
						<p>{__('Loading coins...', 'multi-crypto-convert')}</p>
					) : coinsList.length === 0 ? (
						<p>{__('No coins available. Please configure coins in the plugin settings.', 'multi-crypto-convert')}</p>
					) : (
						<MultiSelectControl
							__next40pxDefaultSize
							label={__('Select Coins', 'multi-crypto-convert')}
							value={coins}
							options={coinsList}
							onChange={(selectedCoins: string[]) => setAttributes({ coins: selectedCoins.join(',') })}
							__nextHasNoMarginBottom={true}
						/>
					)}
				</PanelBody>
			</InspectorControls>

			{coins && coins.length > 0 ? (
				<div {...useBlockProps()}>
					<span>{__('Selected coins:', 'multi-crypto-convert')}</span>
					<ul>
						{coins.map((coin) => (
							<li key={coin}>{coinsList.find((coinItem) => coinItem.value === coin)?.label || coin}</li>
						))}
					</ul>
				</div>
			) : (
				<p {...useBlockProps()}>
					{__('No coins selected.', 'multi-crypto-convert')}
				</p>
			)}
		</>
	);
}
