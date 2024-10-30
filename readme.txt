=== Izz0ware Advanced 2017 ===  
Contributors: izaacj  
Tags: customization, twentyseventeen  
Requires at least: 4.7  
Tested up to: 4.7.3  
Stable tag: 0.1.0.0  
License: GPLv2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds some options for the default Twenty Seventeen theme, or childtheme thereof.

*Only works with Twenty Seventeen or a childtheme thereof!*

== Description ==  
Adds some options for the default Twenty Seventeen theme, or childtheme thereof.

Currently supported options are:

* **Header Logo** - Use an image instead of the Title text in the header. *Require minor theme modification*
* **Number of One Page sections** - Define how many sections you'd like to have on the Frontpage. Defaults to 4 (as-in Twenty Seventeen theme by default).
* **Footer Image** - Display an image between the last section and the footer, just like between any other sections.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the plugin files to the `/wp-content/plugins/izz0ware-advanced-2017` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress.
1. Use the Appearance->Customize screen to access the plugins options.

== FAQ ==

= Header Logo =

For the header logo feature you'll need to make some minor changes to your theme, preferably by using a childtheme.

The file you need to modify is:
* Twenty Seventeen directly: `wp-content/themes/twentyseventeen/template-parts/header/site-branding.php`
* Childtheme: `wp-content/themes/[childthemename]/template-parts/header/site-branding.php` - If this is not in your childtheme, copy it from Twenty Seventeen.

Locate this `<div class="site-branding-text">` and make a new line right below it. That's where you'll paste the following code:
```php
<?php
   $text = get_bloginfo('name');
   if(class_exists('izz0_advanced_2017')) {
      $text = \izz0_advanced_2017::get_header_logo($text);
   }
?>
```

When that is added, theres only two minor changes left to do.

Locate:
```html
<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
```
And replace `<?php bloginfo( 'name' ); ?>` with `<?= $text; ?>`.

Locate:
```html
<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
```
And replace `<?php bloginfo( 'name' ); ?>` with `<?= $text; ?>`.

Now you're done! Header Logo should work as soon as you set an image in the Customizer.

== Screenshots ==

*None at the moment*

== Changelog ==

= 0.1.0 =
* Initial release
