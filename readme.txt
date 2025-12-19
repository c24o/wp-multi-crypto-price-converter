=== Multi Crypto Price Converter ===
Contributors: c24o
Tags: crypto, cryptocurrency, bitcoin, converter, price, calculator, coingecko, block
Requires at least: 6.4
Tested up to: 6.7
Stable tag: 0.1.0
Requires PHP: 8.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A WordPress plugin that lists cryptocurrency prices and allows instant conversion of amounts between any of the listed coins.

== Description ==

Multi Crypto Price Converter is a modern WordPress plugin that allows you to display a list of cryptocurrency prices and perform instant amount conversions between them.

Built with performance and usability in mind, it uses a React-based block that updates prices dynamically without refreshing the page. It is perfect for financial blogs, crypto news sites, or personal portfolios.

= Features =

*   **Instant Conversion:** Convert amounts between USD and any listed cryptocurrency, or between different cryptocurrencies instantly.
*   **Live Prices:** Fetches the latest prices from reliable API sources (currently supports CoinGecko).
*   **Block Editor Ready:** Includes a custom Gutenberg block ("Multi Crypto Price Converter") that you can place anywhere on your site.
*   **Customizable Coin List:** Select exactly which coins you want to display from the admin settings.
*   **Performance Optimized:** Implements server-side caching to minimize API requests and ensure fast load times.
*   **API Flexibility:** Supports CoinGecko (Demo and Paid tiers).

= Disclaimer =

This plugin provides data for informational purposes only. It is not a trading platform or an exchange. Prices may vary from actual market rates.

== Installation ==

1.  Upload the plugin files to the `/wp-content/plugins/multi-crypto-price-converter` directory, or install the plugin through the WordPress plugins screen.
2.  Activate the plugin through the 'Plugins' screen in WordPress.
3.  Navigate to **Settings > Multi Crypto Price Converter** to configure the plugin.
4.  Select the client (currently only CoinGecko is supported) and type in your API key.
5.  Select the cryptocurrencies you wish to make available in the block.
6.  Go to the Block Editor (Pages/Posts) and add the **Multi Crypto Price Converter** block to your content.

== Frequently Asked Questions ==

= Where does the price data come from? =
Currently, the plugin retrieves data from the CoinGecko API.

= Do I need an API Key? =
Yes, it requires to get a CoinGecko API (demo or paid tier).

= Can I add more coins? =
You can select up to 50 coins in the plugin settings page.

= Does it support other currencies besides USD? =
Currently, the base currency for calculation is USD.

== Screenshots ==

1. Frontend Converter: The converter block displaying prices and allowing user input.
2. Settings - API: Configuration screen for the API source.
3. Settings - Coins: Selection screen for available cryptocurrencies.

== Changelog ==

= 0.1.0 =
*   Initial release.
*   Added CoinGecko API client.
*   Added Frontend Converter block.
*   Added Admin Settings page.
