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

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
	const coins = attributes.coins && 0 < attributes.coins.length
		? attributes.coins.split(',')
		: [];
	const coinsList = [
		{
			value: 'abc',
			label: 'ABC Coin',
		},
		{
			value: 'def',
			label: 'DEF Coin',
		},
		{
			value: 'ghi',
			label: 'GHI Coin',
		},
		{
			value: 'jkl',
			label: 'JKL Coin',
		},
	];

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Coins', 'multi-crypto-convert' ) }>
					<MultiSelectControl
						__next40pxDefaultSize
						label={ __( 'Select Coins', 'multi-crypto-convert' ) }
						value={ coins }
						options={ coinsList }
						onChange={ ( selectedCoins ) => setAttributes( { coins: selectedCoins.join(',') } ) }
						__nextHasNoMarginBottom={ true }
					/>
				</PanelBody>
			</InspectorControls>

			{ coins && coins.length > 0 ? (
				<div { ...useBlockProps() }>
					<span>{ __( 'Selected coins:', 'multi-crypto-convert' ) }</span>
					<ul>
						{ coins.map( ( coin ) => (
							<li key={ coin }>{ coinsList.find( coinItem => coinItem.value === coin )?.label || coin }</li>
						) ) }
					</ul>
				</div>
			) : (
				<p { ...useBlockProps() }>
					{ __( 'No coins selected.', 'multi-crypto-convert' ) }
				</p>
			) }
		</>
	);
}
