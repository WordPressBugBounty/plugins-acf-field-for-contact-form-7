=== ACF Field For CF7 ===
Contributors: dilipbheda, krishaweb, girishpanchal
Tags: acf, contactform7, advanced custom fields, contact form, field
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.7
Copyright: (c) 2012-2025 KrishaWeb Technologies PVT LTD (info@krishaweb.com)
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Adds a 'Contact Form 7' field type for the Advanced Custom Fields WordPress plugin.

== Description ==

ACF Field for Contact Form 7 allows you to seamlessly integrate Contact Form 7 forms into your Advanced Custom Fields (ACF) setup. It adds a custom field type to ACF that lets you select CF7 forms from the admin panel and output them directly in your templates. This simplifies form management and eliminates the need to manually insert shortcodes. Ideal for developers who want a cleaner, more dynamic way to embed forms in custom layouts.

== Key Features: ==

•   Single or Multiple Forms: Choose one CF7 form in a single ACF field.
•   Markup Returned Automatically: Selected form(s) output the CF7 shortcode markup directly—ready to display via the_field() or get_field().
•   Lightweight & Fast: Adds minimal load (~10 KB) to your site; small memory and speed impact.
•   ACF-compatible: Works seamlessly with ACF 3–5 and tested up to WordPress 6.8.

== Use Cases: ==

•   Developer-Centric Page Layouts: Define custom ACF fields in page builder templates or theme templates, letting editors easily select CF7 forms—no more shortcode errors.
•   Content Editor Avoids Mistakes: Editors pick from a clean dropdown list instead of pasting form shortcodes, reducing the chance of broken forms or syntax errors.
•   Modular Form Integration: Use in widget areas, theme customizer panels, or Gutenberg block templates (with Pro), letting site-wide layouts dynamically include forms.

== Checkout the advanced features of ACF Field For CF 7 Pro: ==

•   Fully compatible with Gutenberg blocks.
•   Supports integration with Widgets and Theme Customizer.

<a rel="nofollow" href="https://store.krishaweb.com/product/acf-field-contact-form-7-pro/">Download the ACF Field For CF 7 Pro</a>

= Compatibility =

This ACF field type is compatible with :
* ACF 3
* ACF 4
* ACF 5

== Installation ==

1. Copy the `acf-field-for-contact-form-7` folder into your `wp-content/plugins` folder.
2. Activate the Advanced Custom Fields: Contact Form 7 Field plugin via the plugins admin page.
3. Create a new field via ACF and select the Contact Form 7 type.

== Frequently Asked Questions ==

= How to use =

This example shows how to get the value of field `field_name` from the current post.

`echo get_field( 'field_name' );`


This example shows how to get form (contact form 7) object.

`function get_acf_cf7_object() {
	return true;
}
add_filter( 'acf_cf7_object', 'get_acf_cf7_object' );`

= I have an idea for a great way to improve this plugin =

Great! I’d love to hear from you at <a href="mailto:support@krishaweb.com">support@krishaweb.com</a>

== Changelog ==

= 1.7 =
* Compatibility and Security update.

= 1.6 =
* Improve plugin notice.

= 1.5 =
* Improve plugin notice.

= 1.4 =
* Added: ACF pro option page support.

= 1.3 =
* Fixed: ACF Group field issue.

= 1.2 =
* Tested upto 5.0

= 1.1 =
* Filter added to get form object.

= 1.0 =
* Initial Release.
