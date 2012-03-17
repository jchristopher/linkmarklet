<?php
/*
Plugin Name: Linkmarklet
Plugin URI: http://wordpress.org/extend/plugins/linkmarklet/
Description: Alternate to Press This!
Author: Jonathan Christopher
Version: 0.2
Author URI: http://mondaybynoon.com/
*/

if( !defined( 'IS_ADMIN' ) )
    define( 'IS_ADMIN', is_admin() );

define( 'LINKMARKLET_VERSION', '0.2' );
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

        add_settings_field(
            LINKMARKLET_PREFIX . 'prepopulate_slug',
            'Pre-populate Slug',
            array( 'Linkmarklet', 'edit_prepopulate_slug' ),
            LINKMARKLET_PREFIX . 'options',
            LINKMARKLET_PREFIX . 'options'
        );

        add_settings_field(
            LINKMARKLET_PREFIX . 'bookmarklet',
            'Bookmarklet',
            array( 'Linkmarklet', 'edit_bookmarklet' ),
            LINKMARKLET_PREFIX . 'options',
            LINKMARKLET_PREFIX . 'options'
        );
    }

    function validate_settings( $input )
    {
        return $input;
    }

    function edit_bookmarklet()
    {
        $linkmarklet = "javascript:var%20d=document,w=window,e=w.getSelection,k=d.getSelection,x=d.selection,s=(e?e():(k)?k():(x?x.createRange().text:0)),f='" . LINKMARKLET_URL . "',l=d.location,e=encodeURIComponent,u=f+'?u='+e(l.href)+'&t='+e(d.title)+'&s='+e(s)+'&v=4';a=function(){if(!w.open(u,'t','toolbar=0,resizable=1,scrollbars=1,status=1,width=720,height=570'))l.href=u;};if%20(/Firefox/.test(navigator.userAgent))%20setTimeout(a,%200);%20else%20a();void(0)";
        ?>
        <p>Drag the Linkmarklet bookmarklet to your bookmark bar: <a href="<?php echo $linkmarklet; ?>">Linkmarklet</a></p>
        <p><strong>Alternative:</strong> Open <a href="<?php echo LINKMARKLET_URL . '/remove-this.php?' . $linkmarklet; ?>">this URL</a> in a new page, save it as a bookmark, <em>remove everything</em> before <code>javascript:</code>, and save</p>
        <?php
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

    function edit_prepopulate_slug()
    {
        $settings           = get_option( LINKMARKLET_PREFIX . 'settings' );
        $prepopulate_slug   = isset( $settings['prepopulate_slug'] ) ? $settings['prepopulate_slug'] : '';
        ?>
            <input name="<?php echo LINKMARKLET_PREFIX; ?>settings[prepopulate_slug]" type="checkbox" id="linkmarklet_prepopulate_slug" value="1" <?php if( $prepopulate_slug ) : ?>checked="checked"<?php endif; ?>/> <span class="description">Auto-generate a slug</span>
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
