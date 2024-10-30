<?php
/**
* Izz0ware Advanced 2017
*
* @package     Izz0wareAdvanced2017
* @author      Izaac Johansson
* @copyright   2017 Izz0ware
* @license     GPL-2.0+
*
* @wordpress-plugin
* Plugin Name: Izz0ware Advanced 2017
* Plugin URI:  http://izz0ware.info/
* Description: Adds some options for the Twenty Seventeen theme or childtheme thereof.
* Version:     0.1.0
* Author:      Izaac Johansson
* Author URI:  http://izz0ware.info/
* Text Domain: izz0adv2017
* License:     GPL-2.0+
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/
/**
* Contains methods for customizing the theme customization screen.
*
* @link http://codex.wordpress.org/Theme_Customization_API
* @since Izz0wareAdvanced2017 1.0
*/
include('class-qwall-notice.php');

class izz0_advanced_2017 {
    /**
    * This hooks into 'customize_register' (available as of WP 3.4) and allows
    * you to add new sections and controls to the Theme Customize screen.
    *
    * Note: To enable instant preview, we have to actually write a bit of custom
    * javascript. See live_preview() for more.
    *
    * @see add_action('customize_register',$func)
    * @param \WP_Customize_Manager $wp_customize
    * @link http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
    * @since Izz0wareAdvanced2017 1.0
    */
    public static function register ( $wp_customize ) {
        //1. Define a new section (if desired) to the Theme Customizer
        $wp_customize->add_section(
        'izz0adv2017_options',
        array(
        'title' => __( 'Izz0ware Advanced 2017', 'izz0adv2017' ),
        'priority' => 35,
        )
        );
        
        //2. Register new settings to the WP database...
        $wp_customize->add_setting(
        'num_of_sections',
        array(
        'default' => 4,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'transport' => 'refresh',
        'sanitize_callback' => array(__CLASS__, 'num_of_sections_callback'),
        )
        );
        $wp_customize->add_setting(
        'footer_image',
        array(
        'default' => null,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'transport' => 'refresh',
        )
        );
        $wp_customize->add_setting(
        'header_logo',
        array(
        'default' => null,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'transport' => 'refresh',
        )
        );
        
        //3. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
        $wp_customize->add_control( new WP_Customize_Image_Control(
        $wp_customize,
        'header_logo',
        array(
        'label' => __( 'Header Logo', 'izz0adv2017' ),
        'section' => 'izz0adv2017_options',
        )
        ) );
        $wp_customize->add_control( new WP_Customize_Control(
        $wp_customize, //Pass the $wp_customize object (required)
        'num_of_sections',
        array(
        'type' => 'number',
        'label' => __( 'Number of One Page sections', 'izz0adv2017' ),
        'section' => 'izz0adv2017_options',
        )
        ) );
        $wp_customize->add_control( new WP_Customize_Image_Control(
        $wp_customize,
        'footer_image',
        array(
        'label' => __( 'Footer Image', 'izz0adv2017' ),
        'section' => 'izz0adv2017_options',
        )
        ) );
    }
    
    public static function num_of_sections_callback( $value ) {
        if(is_int($value)) {
            $nv = $value;
        }else{
            $nv = intval($value);
        }
        if( $nv < 0 ) $nv = 1;
        if( $nv > 26 ) $nv = 25;
        
        return $nv;
    }
    public static function num_of_sections_value() {
        return get_theme_mod('num_of_sections', 4);
    }
    
    /**
    * This will output the custom WordPress settings to the live theme's WP head.
    *
    * Used by hook: 'wp_head'
    *
    * @see add_action('wp_head',$func)
    * @since Izz0wareAdvanced2017 1.0
    */
    public static function header_output() {
        $css = '<style type="text/css">'.PHP_EOL;
        // Add below line for EVERY CSS selector.
        //$css .= self::generate_css( 'SELECTOR', 'STYLE', 'MOD_NAME', 'PREFIX', 'POSTFIX').PHP_EOL;
        $css .= '</style>'.PHP_EOL;
    }
    
    /**
    * This outputs the javascript needed to automate the live settings preview.
    * Also keep in mind that this function isn't necessary unless your settings
    * are using 'transport'=>'postMessage' instead of the default 'transport'
    * => 'refresh'
    *
    * Used by hook: 'customize_preview_init'
    *
    * @see add_action('customize_preview_init',$func)
    * @since Izz0wareAdvanced2017 1.0
    */
    public static function live_preview() {
        /*
        wp_enqueue_script(
        'izz0adv2017-themecustomizer', // Give the script a unique ID
        get_template_directory_uri() . '/assets/js/theme-customizer.js', // Define the path to the JS file
        array(  'jquery', 'customize-preview' ), // Define dependencies
        '', // Define a version (optional)
        true // Specify whether to put in footer (leave this true)
        );
        */
    }
    
    /**
    * This will generate a line of CSS for use in header output. If the setting
    * ($mod_name) has no defined value, the CSS will not be output.
    *
    * @uses get_theme_mod()
    * @param string $selector CSS selector
    * @param string $style The name of the CSS *property* to modify
    * @param string $mod_name The name of the 'theme_mod' option to fetch
    * @param string $prefix Optional. Anything that needs to be output before the CSS property
    * @param string $postfix Optional. Anything that needs to be output after the CSS property
    * @param bool $echo Optional. Whether to print directly to the page (default: true).
    * @return string Returns a single line of CSS with selectors and a property.
    * @since Izz0wareAdvanced2017 1.0
    */
    public static function generate_css( $selector, $style, $mod_name, $prefix='', $postfix='', $echo=false ) {
        $return = '';
        $mod = get_theme_mod($mod_name);
        if ( ! empty( $mod ) ) {
            $return = sprintf('%s { %s:%s; }',
            $selector,
            $style,
            $prefix.$mod.$postfix
            );
            if ( $echo ) {
                echo $return;
            }
        }
        return $return;
    }
    
    public static function add_footer_image() {
        $img = get_theme_mod('footer_image', null);
        
        if ( $img != null ) :
            $id = izz0_advanced_2017::get_image_id($img);
        $thumbnail = wp_get_attachment_image_src( $id, 'twentyseventeen-featured-image' );
        
        // Calculate aspect ratio: h / w * 100%.
        $ratio = $thumbnail[2] / $thumbnail[1] * 100;
        ?>

  <div class="panel-image" style="background-image: url(<?php echo esc_url( $thumbnail[0] ); ?>);">
    <div class="panel-image-prop" style="padding-top: <?php echo esc_attr( $ratio ); ?>%"></div>
  </div>
  <!-- .panel-image -->

  <?php endif;
    }
    
    public static function get_header_logo($text) {
        $img = get_theme_mod('header_logo', null);
        if($img != null) {
            $id = \izz0_advanced_2017::get_image_id($img);
            $img_src = wp_get_attachment_image_src( $id, 'izz0ware_header_logo' );
            if($img_src != false)
            $text = '<img src="'.$img_src[0].'" style="max-width: 800px; max-height: 100px; width: auto; height: auto;"/>';
        }
        return $text;
    }
    
    public static function get_image_id($image_url) {
        global $wpdb;
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
        return $attachment[0];
    }
}

// This plugin only works with Twenty Seventeen, so make sure it's the active theme, or parent to the active theme.
$theme = get_template(); //wp_get_theme(); // gets the current theme
if ('twentyseventeen' == $theme) {
    // if you're here twenty twelve is the active theme or is
    // the current theme's parent theme
    add_image_size( 'izz0ware_header_logo', 800, 100 );
    
    // Setup the Theme Customizer settings and controls...
    add_action( 'customize_register' , array( 'izz0_advanced_2017' , 'register' ) );
    
    // Output custom CSS to live site
    add_action( 'wp_head' , array( 'izz0_advanced_2017' , 'header_output' ) );
    
    // Enqueue live preview javascript in Theme Customizer admin screen
    add_action( 'customize_preview_init' , array( 'izz0_advanced_2017' , 'live_preview' ) );
    
    add_filter('twentyseventeen_front_page_sections', array( 'izz0_advanced_2017', 'num_of_sections_value' ));
    add_action( 'get_footer', array( 'izz0_advanced_2017', 'add_footer_image' ));
}else{
    $class = array('notice-error', 'is-dismissible');
    $message = "<b>Izz0ware Advanced 2017</b></br>It does not look like you have either Twenty Seventeen, or a child theme thereof active.</br>Please note that this plugin only works with Twenty Seventeen!";
    
    new QWall_Notice($message, $class, 'Don\'t show again!', 'izz0_adv_2017_wrong_theme_ignore', 'izz0adv2017-ignore-notice');
}