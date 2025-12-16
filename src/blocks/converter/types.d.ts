/**
 * Type declarations for @codeamp/block-components
 *
 * Provides TypeScript type definitions for the MultiSelectControl component
 * since the package doesn't include type definitions.
 *
 * @package Multi_Crypto_Convert
 */

declare module '@codeamp/block-components' {

	interface MultiSelectControlProps {
		__next40pxDefaultSize?: boolean;
		__nextHasNoMarginBottom?: boolean;
		label?: string;
		value?: string[];
		options?: Array< { label: string; value: string } >;
		onChange?: ( value: string[] ) => void;
	}

	export const MultiSelectControl: React.FC< MultiSelectControlProps >;
}
