=== Sale Flash Customizer for WooCommerce ===
Contributors: algoritmika, thankstoit, anbinder, karzin
Tags: woocommerce, sale, flash, sale flash, woo commerce
Requires at least: 4.4
Tested up to: 6.8
Stable tag: 2.0.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Customize WooCommerce products sale flash.

== Description ==

The **Sale Flash Customizer for WooCommerce** plugin lets you take full control of product sale flash in WooCommerce.

Gain full control over the sale flash messages in your WooCommerce store, allowing you to customize display options and enhance visibility of sale information, such as discounts and pricing on your product pages.

Take your WooCommerce store's sale notifications to the next level with the Sale Flash Customizer for WooCommerce plugin.

This plugin gives you the flexibility to display detailed sale information directly on the product, going beyond the standard "Sale!" notification. Showcase the exact discount amount or percentage to entice potential buyers and encourage them to make a purchase.

To personalize the sale flash, adjust the plugin options to display the preferred details, you can choose to highlight the amount saved or represent it as a percentage, offering a clearer picture of the deal to your customers.

### 🚀 Change Default Sale Flash Messages ###

Transform your global sales flash with this feature by replacing the default 'Sale!' text with detailed discount messages.

For instance, set a store-wide message like "20% Off This Week!" or "Save on Your Favorites!" to capture customer attention and convey specific sale details more effectively.

### 🚀 Customize Sales Messages on Product, Category, and Tag ###

Customize sales messages at a more granular level for individual products, specific categories, or tags.

This allows for tailored messaging, such as "Special Offer on Electronics!" for a category or "Limited Time Discount!" for a particular product line, which can enhance the relevance of sales promotions.

### 🚀 Control Sales Flash Messages Visibility ###

Display or conceal sales messages across your store, including specific sections like product archives or single product pages.

Giving you a flexibility to showcase sales messages where they're most effective and aligning with your theme.

### 🚀 Enhanced Sales Message Using Shortcodes ###

Leverage a variety of shortcodes to dynamically display sale information. For example, use `[alg_wc_sfc_discount_percent]` to show the percentage discount, or `[alg_wc_sfc_sale_price]` to display the sale price next to products.

These shortcodes offer a way to convey precise and compelling sales information directly to customers.

### 🚀 WPML and Polylang Compatibility ###

Ensure your sales messages are accessible to a global audience with WPML and Polylang compatibility.

Use the `[alg_wc_sfc_translate]` shortcode to translate your sales messages, catering to a diverse customer base in their preferred languages.

### ✅ Main Features ###

* set sale flash **globally**,
* set sale flash **per product**,
* set sale flash **per category**,
* set sale flash **per tag**,
* hide sale flash **everywhere**,
* hide sale flash **on archives (categories) only**,
* hide sale flash **on single page only**,
* use **shortcodes** in sale flash: `[alg_wc_sfc_discount]`, `[alg_wc_sfc_discount_percent]`, `[alg_wc_sfc_sale_price]`, `[alg_wc_sfc_regular_price]`, `[alg_wc_sfc_meta]`,
* **WPML and Polylang** compatible (with the `[alg_wc_sfc_translate]` shortcode).
* **"High-Performance Order Storage (HPOS)"** compatible.

### 🗘 Feedback ###

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* Head to the plugin [GitHub Repository](https://github.com/thanks-to-it/sale-flash-customizer-for-woocommerce) to find out how you can pitch in.

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Start by visiting plugin settings at "WooCommerce > Settings > Sale Flash Customizer".

== Changelog ==

= 2.0.0 - 23/05/2025 =
* Fix - Translations - Text domain fixed.
* Dev - "Per product", "Per Category", and "Per Tag" options moved to the free version.
* Dev - Security - Escape output.
* Dev - Security - Input sanitized output.
* Dev - "High-Performance Order Storage (HPOS)" compatibility declared.
* Dev - PHP v8.2 compatibility (dynamic properties).
* Dev - Code refactoring.
* WC tested up to: 9.8.
* Tested up to: 6.8.

= 1.3.1 - 19/06/2023 =
* WC tested up to: 7.8.
* Tested up to: 6.2.

= 1.3.0 - 27/11/2022 =
* Dev - The plugin is initialized on the `plugins_loaded` action now.
* Dev - Localisation - The `load_plugin_textdomain()` function moved to the `init` action.
* Dev - Code refactoring.
* Tested up to: 6.1.
* WC tested up to: 7.1.
* Readme.txt updated.
* Deploy script added.

= 1.2.0 - 27/03/2020 =
* Dev - `[alg_wc_sfc_meta]` shortcode added.
* Dev - `[alg_wc_sfc_sale_price]` shortcode added.
* Dev - `[alg_wc_sfc_regular_price]` shortcode added.
* Dev - Code refactoring.
* Dev - Admin settings descriptions updated.
* WC tested up to: 4.0.
* Tested up to: 5.3.

= 1.1.1 - 27/07/2019 =
* Tested up to: 5.2.
* WC tested up to: 3.6.

= 1.1.0 - 16/03/2019 =
* Dev - `[alg_wc_sfc_discount]` shortcode added.
* Dev - `[alg_wc_sfc_discount_percent]` shortcode added.
* Dev - `[alg_wc_sfc_translate]` shortcode added.
* Dev - `%discount%` and `%discount_percent%` predefined values deprecated (replaced with shortcodes).
* Dev - Major code refactoring.
* Dev - Plugin URI updated.

= 1.0.0 - 24/04/2018 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
