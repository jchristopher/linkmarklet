<?php
/*
Plugin Name: Linkmarklet
Plugin URI: http://wordpress.org/extend/plugins/linkmarklet/
Description: Alternate to Press This!
Author: Jonathan Christopher
Version: 0.1
Author URI: http://mondaybynoon.com/
*/

if( !defined( 'IS_ADMIN' ) )
    define( 'IS_ADMIN', is_admin() );

define( 'LINKMARKLET_VERSION', '0.1' );
define( 'LINKMARKLET_PREFIX', '_iti_linkmarklet_' );
define( 'LINKMARKLET_DIR', WP_PLUGIN_DIR . '/' . basename( dirname( __FILE__ ) ) );
define( 'LINKMARKLET_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );

add_action( 'admin_menu', array( 'Linkmarklet', 'assets' ) );
add_action( 'admin_init', array( 'Linkmarklet', 'register_settings' ) );

class Linkmarklet
{
    function __construct()
    {

    }

    function register_settings()
    {
        // flag our settings
        register_setting(
            LINKMARKLET_PREFIX . 'settings',
            LINKMARKLET_PREFIX . 'settings',
            array( 'Linkmarklet', 'validate_settings' )
        );

        add_settings_section(
            LINKMARKLET_PREFIX . 'options',
            'Options',
            array( 'Linkmarklet', 'edit_options' ),
            LINKMARKLET_PREFIX . 'options'
        );

        add_settings_field(
            LINKMARKLET_PREFIX . 'category',
            'Category',
            array( 'Linkmarklet', 'edit_category' ),
            LINKMARKLET_PREFIX . 'options',
            LINKMARKLET_PREFIX . 'options'
        );

        add_settings_field(
            LINKMARKLET_PREFIX . 'custom_field',
            'Link Custom Field',
            array( 'Linkmarklet', 'edit_custom_field' ),
            LINKMARKLET_PREFIX . 'options',
            LINKMARKLET_PREFIX . 'options'
        );
    }

    function validate_settings( $input )
    {
        return $input;
    }

    function edit_options()
    {

    }

    function edit_category()
    {
        $settings   = get_option( LINKMARKLET_PREFIX . 'settings' );
        $categories = get_categories( 'hide_empty=0');
        ?>
        <select name="<?php echo LINKMARKLET_PREFIX; ?>settings[category]">
            <option value="0">- No Category -</option>
            <?php foreach( $categories as $category ) : ?>
                <option value="<?php echo $category->cat_ID; ?>"<?php if( isset( $settings['category'] ) && $settings['category'] == $category->cat_ID ) : ?> selected="selected"<?php endif; ?>><?php echo $category->cat_name; ?></option>
            <?php endforeach; ?>
        </select>
        <?
    }

    function edit_custom_field()
    {
        $settings       = get_option( LINKMARKLET_PREFIX . 'settings' );
        $custom_field   = isset( $settings['custom_field'] ) ? $settings['custom_field'] : '';
        ?>
            <input name="<?php echo LINKMARKLET_PREFIX; ?>settings[custom_field]" type="text" id="linkmarklet_custom_field" value="<?php echo $custom_field; ?>" class="regular-text"> <span class="description">Your <strong>Link</strong> will be saved to this Custom Field</span>
        <?
    }

    function assets()
    {
        // add options menu
        add_options_page( 'Settings', 'Linkmarklet', 'manage_options', __FILE__, array( 'Linkmarklet', 'options' ) );
    }

    function options()
    {
        include 'linkmarklet-options.php';
    }
}
