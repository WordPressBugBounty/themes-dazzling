/*========= About Theme =========*/

Theme Name: Dazzling
Theme URI: https://colorlib.com/wp/themes/dazzling/
Version: 2.3.0
Requires at least: WP 5.2
Tested up to: WP 7.1
Requires PHP: 7.4

Author: Aigars Silkalns
Author URI: https://colorlib.com/wp/
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
-------------------------------------------------------
Dazzling theme, Copyright 2014-2026 colorlib.com
Dazzling WordPress theme is distributed under the terms of the GNU GPL
Dazzling is based on Underscores http://underscores.me/, (C) 2012-2017 Automattic, Inc.
-------------------------------------------------------

/*========= Credits =========*/

Dazzling theme uses:

* Font Awesome 7.3.1 (https://fontawesome.com/) - icons licensed under CC BY 4.0, fonts under the SIL OFL 1.1 (https://scripts.sil.org/OFL), code under the MIT license
* Bootstrap 3.4.1 (https://getbootstrap.com/) licensed under MIT license (https://github.com/twbs/bootstrap/blob/main/LICENSE) - the JavaScript is patched in two places to run on jQuery 4, see the header of inc/js/bootstrap.min.js
* WP Bootstrap Navwalker licensed under the GPLv2 license (https://www.gnu.org/licenses/gpl-2.0.html)
* FlexSlider 2.7.2 by WooThemes licensed under the GPLv2 license (https://www.gnu.org/licenses/gpl-2.0.html)
* Open Sans (https://fonts.google.com/specimen/Open+Sans) by Steve Matteson, licensed under the SIL Open Font License 1.1 (https://openfontlicense.org/) - bundled, loaded only when chosen as the body font
* TGM Plugin Activation (http://tgmpluginactivation.com/) licensed under the GPLv2 license

/*========= Description =========*/

Dazzling is a clean, modern, minimal and fully responsive flat design WordPress WooCommerce theme well suited for blogs, static and ecommerce websites. Theme can be used for travel, corporate, portfolio, photography, green thinking, nature, health, personal and any other creative and minimalistic style website. Dazzling theme is highly customizable with unlimited color options, slider, call for action button, several widget areas and much more that can be adjusted via the WordPress Customizer. The theme is built using Bootstrap 3, which makes it responsive and mobile friendly. It features infinite scroll, SEO friendly structure, logo upload, full-screen slider, call for action section, social media icons, popular post widget and translation ready setup. This theme supports WooCommerce and Jigoshop ecommerce plugins. Dazzling is also available in Spanish, Mexican Spanish, Brazilian Portuguese, Portuguese, French, Russian, Finnish, Swedish, Dutch, Hungarian, German, Persian, Ukrainian, Lithuanian, Italian, Danish, Turkish and Polish. It is Multilingual ready and compatible with WPML plugin. It is probably the best free WordPress theme built for eStores and business websites.

For questions, comments or bug reports, visit Colorlib support forum (https://colorlibsupport.com/).

/*========= Installation =========*/

You can install the theme through the WordPress installer under "Appearance" > "Themes" > "Add New" by searching for "Dazzling".

Alternatively you can download the file, unzip it and move the unzipped contents to the "wp-content/themes" folder of your WordPress installation. You will then be able to activate the theme.

Afterwards you can continue theme setup and customization via WordPress Dashboard - Appearance - Customize. For detailed theme documentation, please visit https://colorlib.com/wp/support/dazzling

/*========= Theme Features =========*/

* Bootstrap 3.4.1 integration, compatible with jQuery 4
* Responsive design
* Unlimited color variations via the WordPress Customizer
* Featured slider (FlexSlider 2.7.2)
* Call for action section
* Layout manager - right sidebar, left sidebar, no sidebar or full width
* Seven widget areas - sidebar, three homepage and three footer
* WooCommerce support
* Jigoshop support
* SEO friendly
* Image centric approach
* Internationalized & localization - 18 languages bundled, WPML compatible
* Drop-down Menu
* Cross-browser compatibility
* Threaded Comments
* Gravatar ready
* Font Awesome 7.3.1 icons, self-hosted and subsetted
* Social icons for 30 networks, from a WordPress menu
* Block editor styles and wide/full block alignment

/*========= Documentation =========*/

Theme documentation is available on https://colorlib.com/wp/support/dazzling

/*========= Changelog =========*/

== Changelog ==

= 2.3.0 - 01.10.2026 =
* Fixed WooCommerce's AJAX add to cart: every "Add to cart" button in the shop reloaded the page, because the theme rewrote the button's class names. The menu cart now updates as products are added, and two deprecation notices it logged on every page are gone.
* Fixed the word "dazzling" printed before the footer credits on every site that had not filled in Footer information.
* The site title is the theme's green again on sites that never picked a header text colour; it had been black since 2.1, while the Customizer showed green. Unticking "Display Site Title and Tagline" no longer hides a logo image.
* Works with jQuery 4 (without jQuery Migrate): the featured slider, mobile menu, dropdowns and tabs no longer break. Bootstrap 3.4.1 is patched in two lines for it; a small compatibility file restores what jQuery 4 removed.
* Fixed a PHP warning on every page once a single Typography setting had been changed, and the "translation loading was triggered too early" notice on every request.
* Customizer: no longer loads Facebook's and Twitter's scripts; the slider category offers "All categories" and the slider options show only when the slider is on; a button title with an apostrophe is no longer saved with a backslash; the Typography font, weight and colour fields have labels; several descriptions corrected. Other > Custom CSS is moved into WordPress' Additional CSS automatically.
* Popular Posts widget: two copies on a page no longer control each other's tabs, comments on password-protected posts are no longer quoted in it, and views are no longer counted for previews and Customizer refreshes.
* Social icons: X, Bluesky, Mastodon, Threads, TikTok, Telegram, WhatsApp, Discord, Reddit, Medium, Behance, GitLab, Twitch and e-mail links show their icons (other links get a link icon instead of a blank space), every icon has an accessible name, and the icons are visible on the white sidebar.
* Password-protected posts: the password field is no longer pre-filled with the search text, and a wrong password returns to the post with WordPress' error message.
* Layout: a post's own layout choice no longer changes the layout of the blog or archive it appears first in; No Sidebar and Full Width pages no longer build a hidden sidebar; wide and full-width blocks span the page on layouts without a sidebar.
* Block editor: the editor uses the theme's font, colours and content width; block widget titles match the classic widget titles; galleries keep their columns with WordPress' HTML5 gallery markup.
* Open Sans, offered under Typography, is now actually loaded (bundled with the theme) when chosen.
* Accessibility: a skip link, named landmarks, labelled search, menu, back-to-top and slider buttons, one h1 per page, links in post text underlined, and darker menu, tagline and footer link colours for WCAG AA contrast.
* Performance: slider files load only where the slider shows, no category query on every page, versioned assets so updates reach returning visitors (style.css was cached under the WordPress version).
* The menu walker is renamed so a plugin bundling its own Bootstrap walker can no longer cause a fatal error. Child themes using wp_bootstrap_navwalker keep working.
* Tested with WordPress 7.1 and PHP 8.5, with WooCommerce 11. New build, lint and CI tooling; the code passes the WordPress coding standards.

= 2.2.8 - 17.09.2026 =
* Fixed six icons drawn as empty boxes: the Archives, Categories and Recent Comments widget bullets, the comment icon in the tabbed widget, and the slider's previous and next arrows. They still named the Font Awesome 4 family, which the theme stopped shipping in 2.2.1
* readme.txt and the style.css header are current again. 2.2.6 and 2.2.7 went out with the 2016 readme (GPL v3, "Tested up to: WP 4.7") and a header listing five theme tags

= 2.2.7 - 14.09.2026 =
* Fixed 2.2.5 breaking every child theme with a fatal error (#74). 2.2.5 loaded the theme's internal files with get_theme_file_path() called without a path, which returns the child theme's directory outright, so a child theme white-screened on the first include. They load from the parent theme again, as in 2.1.x. These are bootstrap files, not templates, so letting a child theme override them would mean silently losing every Customizer setting the parent registers
* readme.txt said "Tested up to: WP 4.7" while style.css said 7.1; they agree now

= 2.2.6 - 11.09.2026 =
* Fixed icons written as fa-regular drawing the solid variant instead of the outline. The bundled Font Awesome subset was missing the :root custom properties and the .fa-solid, .fa-regular and .fa-brands rules that bind an icon class to a font face; the subset now carries Font Awesome's own rules. No font files changed

= 2.2.5 - 11.09.2026 =
* Fixed the featured slider printing "Slider is not properly configured" onto the front page. The slider required both a category and a count to be set, but the category setting defaults to empty, so enabling the slider without opening the category select put that sentence on the site. The category is optional now; with none chosen the slider shows the latest posts (#61, #71)
* Fixed the slider's markup opening one list item per slide and closing exactly one at the end, because the closing </li> sat outside the loop. Three slides emitted three opening tags and a single closing tag, which left FlexSlider miscounting its slides (#59)
* Fixed blurry slides. the_post_thumbnail() was called with no size, so the slider served the default thumbnail and stretched it to full width. It requests the full size now (#36)
* Posts with no featured image no longer produce an empty slide, and the slider query resets the post data it changed
* Theme files are included with get_theme_file_path() rather than get_template_directory(), so a child theme can override them (#63)

= 2.2.4 - 11.09.2026 =
* Corrected the capitalisation of "WordPress" in 3 obsolete translation strings. WordPress.org's automated theme scan reports any spelling of WordPress other than that exact form as a required fix, and it reads .po files.

= 2.2.3 - 11.09.2026 =
* Added the Requires at least, Tested up to and Requires PHP headers that style.css was missing; WordPress.org's Theme Check reports both of the latter as required.
* The Popular Posts widget used esc_attr_e() for two tab labels that sit between tags rather than inside an attribute; they use esc_html_e().
* The screenshot was 880x660 and 628 KB. It is now 1200x900, the size WordPress.org asks for, at 280 KB.

= 2.2.2 - 11.09.2026 =
* Fixed the Font Awesome webfonts returning 404. The stylesheets reference url(../webfonts/...), which resolves next to the stylesheet, and the fonts had been placed a directory too high -- so no icon rendered at all.
* The bundled Font Awesome is now subsetted to the glyphs the theme renders, loaded by default, with the complete build still shipped for sites that need it: add_filter( 'dazzling_full_fontawesome', '__return_true' ). Measured on WordPress 7.1 and PHP 8.5, median of three Lighthouse runs: performance 90 to 94, First Contentful Paint 0.45s faster, font payload 83 KB to 2 KB.

= 2.2.1 - 11.09.2026 =
* Replaced Font Awesome 4.4.0, released in 2015, with a self-hosted Font Awesome 7.3.1. Only woff2 is shipped: the eot, svg, ttf and woff copies could never be downloaded, because a browser takes the first format it supports from the @font-face src list. Bundled icon fonts drop from 700 KB to 356 KB
* No v4 or v5 compatibility shim is loaded. Three classes that Font Awesome 7 does not have were rewritten to native names -- fa-folder-open-o, fa-pencil-square-o and fa-comment-o become fa-regular fa-folder-open, fa-regular fa-pen-to-square and fa-regular fa-comment. All 13 icon classes the theme renders were verified against the bundled name map
* The social icons set a codepoint on a .fa element and relied on Font Awesome 4 keeping every glyph in one family. Version 5 moved brands into a separate family, so under 7 those icons would have rendered nothing. Each rule now names its family: Font Awesome 7 Brands at weight 400 for the 17 brand glyphs, Font Awesome 7 Free at weight 900 for the feed icon, which is not a brand
* The search button used a Bootstrap glyphicon, which pulled Bootstrap's icon font on every page carrying a search form. It uses Font Awesome, already loaded. Menu glyphicon support is untouched

= 2.2.0 - 11.09.2026 =
Security and maintenance release.

* Security: the Customizer's colour sanitiser returned its input unchanged when validation failed, so arbitrary text could be stored through a colour setting and was then printed into the inline <style> block on every page. Invalid values are rejected, and every colour is re-validated as it is printed, because options saved before this change may still hold arbitrary text
* Security: the per-post layout metabox saved whatever was submitted, and header.php printed that value unescaped into a class attribute -- so a user who could edit a post could store markup that ran for every visitor. The submitted layout is checked against the theme's own list, and the class is escaped where it is printed
* Security: the legacy custom CSS option was run through html_entity_decode(), which turned an escaped "</style><script>" back into live markup. It is stripped of tags instead
* Security: the social widget never overrode update(), so its title was stored exactly as submitted and echoed unescaped. Both widgets now sanitise on save and escape on output
* Security: four title attributes called the_title() rather than the_title_attribute(), so a quote in a post title broke out of the attribute. The call-for-action text and link, and the next-attachment URL in image.php, are escaped
* Updated Bootstrap from 3.3.6 to 3.4.1, which fixes CVE-2019-8331 -- cross-site scripting through the data-template attribute of tooltips and popovers. The bundled copy was not stock: two dropdown rules had been edited into it, and style.css depends on them to reveal sub-menus for keyboard users. Those rules now live in style.css, so the vendored Bootstrap is stock
* Updated FlexSlider from 2.5.0 to 2.7.2
* Dropped Internet Explorer support: html5shiv, Respond.js, the "lt IE 9" conditional comment printed into every page head, and the X-UA-Compatible meta tag. Internet Explorer reached end of support in June 2022
* The repository had been stuck at 2.1.0 since 2017 while WordPress.org shipped 2.1.1 through 2.1.3, so the two were different code. They match again
* $_POST reads in the metabox, and $post->ID in header.php, are guarded -- both warn on PHP 8

Older releases are listed in the theme's README.md: https://github.com/puikinsh/Dazzling#changelog
