<?php
// phpcs:ignoreFile
declare(strict_types=1);

/** @var Symfony\Component\Finder\Finder $finder */
$finder = Isolated\Symfony\Component\Finder\Finder::class;

return [
	// The prefix configuration. If a non-null value is used, a random prefix
	// will be generated instead.
	//
	// For more see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#prefix
	'prefix' => 'Multi_Crypto_Price_Converter\Vendor',

	// The base output directory for the prefixed files.
	// This will be overridden by the 'output-dir' command line option if present.
	'output-dir' => 'build',

	// By default when running php-scoper add-prefix, it will prefix all relevant code found in the current working
	// directory. You can however define which files should be scoped by defining a collection of Finders in the
	// following configuration key.
	//
	// This configuration entry is completely ignored when using Box.
	//
	// For more see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#finders-and-paths
	'finders' => [
		// Files in the vendor folder.
		$finder::create()
			->files()
			->ignoreVCS( true )
			->notName( '/LICENSE|.*\\.md|.*\\.dist|Makefile|composer\\.json|composer\\.lock/' )
			->exclude(
				[
					'doc',
					'test',
					'test_old',
					'tests',
					'Tests',
					'vendor-bin',
				]
			)
			->in( 'vendor' ),

		// Files in includes.
		$finder::create()
			->files()
			->in( 'includes' ),

		// Main plugin file.
		$finder::create()
			->files()
			->name( 'multi-crypto-price-converter.php' )
			->in( '.' ),
	],

	// List of excluded files, i.e. files for which the content will be left untouched.
	// Paths are relative to the configuration file unless if they are already absolute
	//
	// For more see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#patchers
	'exclude-files' => [
		// 'src/an-excluded-file.php',
	],

	// PHP version (e.g. `'7.2'`) in which the PHP parser and printer will be configured into. This will affect what
	// level of code it will understand and how the code will be printed.
	// If none (or `null`) is configured, then the host version will be used.
	'php-version' => '8.0',

	// When scoping PHP files, there will be scenarios where some of the code being scoped indirectly references the
	// original namespace. These will include, for example, strings or string manipulations. PHP-Scoper has limited
	// support for prefixing such strings. To circumvent that, you can define patchers to manipulate the file to your
	// heart contents.
	//
	// For more see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#patchers
	'patchers' => [
		static function ( string $filePath, string $prefix, string $contents ): string {
			// Change the contents here.

			return $contents;
		},
	],

	// List of symbols to consider internal i.e. to leave untouched.
	//
	// For more information see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#excluded-symbols
	'exclude-namespaces' => [
		'Multi_Crypto_Price_Converter',
		'WP',
	],
	'exclude-classes' => [
		'WP',
		'/^WP_.*$/',
	],
	'exclude-functions' => [
		'/^wp_.*$/',
		'/^add_.*$/',
		'apply_filters',
		'do_action',
	],
	'exclude-constants' => [
		// 'STDIN',
	],

	// List of symbols to expose.
	//
	// For more information see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#exposed-symbols
	'expose-global-constants' => true,
	'expose-global-classes' => true,
	'expose-global-functions' => true,
	'expose-namespaces' => [
		// 'Acme\Foo'                     // The Acme\Foo namespace (and sub-namespaces)
		// '~^PHPUnit\\\\Framework$~',    // The whole namespace PHPUnit\Framework (but not sub-namespaces)
		// '~^$~',                        // The root namespace only
		// '',                            // Any namespace
	],
	'expose-classes' => [],
	'expose-functions' => [],
	'expose-constants' => [],
];
