<?php
/*
Plugin Name: Linkmarklet
Plugin URI: https://github.com/jchristopher/linkmarklet
Description: Alternative to Press This! specifically geared to linkblogging. Quickly post while saving a link to a Custom Field you define.
Author: Jonathan Christopher
Version: 0.7
Author URI: http://mondaybynoon.com/
*/

if( !defined( 'IS_ADMIN' ) )
    define( 'IS_ADMIN', is_admin() );

define( 'LINKMARKLET_VERSION',  '0.7' );
define( 'LINKMARKLET_PREFIX',   '_iti_linkmarklet_' );
define( 'LINKMARKLET_DIR',      WP_PLUGIN_DIR . '/' . basename( dirname( __FILE__ ) ) );
define( 'LINKMARKLET_URL',      rtrim( plugin_dir_url( __FILE__ ), '/' ) );

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
            LINKMARKLET_PREFIX . 'post_format',
            'Post Format',
            array( 'Linkmarklet', 'edit_post_format' ),
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
            LINKMARKLET_PREFIX . 'future_publish',
            'Future Publish',
            array( 'Linkmarklet', 'edit_future_publish' ),
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
            LINKMARKLET_PREFIX . 'support_tags',
            'Support Tags',
            array( 'Linkmarklet', 'edit_support_tags' ),
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

        if( is_plugin_active( 'markdown-on-save/markdown-on-save.php' ) )
        {
            add_settings_field(
                LINKMARKLET_PREFIX . 'markdown',
                'Markdown on Save',
                array( 'Linkmarklet', 'edit_markdown' ),
                LINKMARKLET_PREFIX . 'options',
                LINKMARKLET_PREFIX . 'options'
            );
        }
    }

    function validate_settings( $input )
    {
        return $input;
    }

    function edit_bookmarklet()
    {
        $linkmarklet = "javascript:var%20d=document,w=window,e=w.getSelection,k=d.getSelection,x=d.selection,s=(e?e():(k)?k():(x?x.createRange().text:0)),f='" . LINKMARKLET_URL . "',l=d.location,e=encodeURIComponent,u=f+'?u='+e(l.href.replace(new RegExp('(https?:\/\/)','gm'),''))+'&t='+e(d.title)+'&s='+e(s)+'&v=4&m='+(((l.href).indexOf('https://',0)===0)?1:0);a=function(){if(!w.open(u,'t','toolbar=0,resizable=1,scrollbars=1,status=1,width=720,height=570'))l.href=u;};if%20(/Firefox/.test(navigator.userAgent))%20setTimeout(a,%200);%20else%20a();void(0)";
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
        $categories = get_categories( 'hide_empty=0' );
        ?>
        <select name="<?php echo LINKMARKLET_PREFIX; ?>settings[category]">
            <option value="0">- No Category -</option>
            <?php foreach( $categories as $category ) : ?>
                <option value="<?php echo $category->cat_ID; ?>"<?php if( isset( $settings['category'] ) && $settings['category'] == $category->cat_ID ) : ?> selected="selected"<?php endif; ?>><?php echo $category->cat_name; ?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    function edit_post_format()
    {
        if( !current_theme_supports( 'post-formats' ) )
        {
            echo 'Your active theme does not support Post Formats.';
            return;
        }

        $settings       = get_option( LINKMARKLET_PREFIX . 'settings' );
        $post_formats   = get_theme_support( 'post-formats' );
        ?>
            <select name="<?php echo LINKMARKLET_PREFIX; ?>settings[post_format]">
                <?php if ( is_array( $post_formats[0] ) ) : ?>
                    <option value="0">Standard</option>
                    <?php foreach( $post_formats[0] as $post_format ) : ?>
                        <option value="<?php echo esc_attr( $post_format ); ?>"<?php if( isset( $settings['post_format'] ) && $settings['post_format'] == $post_format ) : ?> selected="selected"<?php endif; ?>><?php echo ucfirst( $post_format ); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        <?php
    }

    function edit_custom_field()
    {
        $settings       = get_option( LINKMARKLET_PREFIX . 'settings' );
        $custom_field   = isset( $settings['custom_field'] ) ? $settings['custom_field'] : '';
        ?>
            <input name="<?php echo LINKMARKLET_PREFIX; ?>settings[custom_field]" type="text" id="linkmarklet_custom_field" value="<?php echo $custom_field; ?>" class="regular-text"> <span class="description">Your <strong>Link</strong> will be saved to this Custom Field</span>
        <?php
    }

    function edit_future_publish()
    {
        $settings       = get_option( LINKMARKLET_PREFIX . 'settings' );
        $timeframe_min  = !isset( $settings['future_publish']['min'] ) || $settings['future_publish']['min'] === '' ? '' : intval( $settings['future_publish']['min'] );
        $timeframe_max  = !isset( $settings['future_publish']['max'] ) || $settings['future_publish']['max'] === '' ? '' : intval( $settings['future_publish']['max'] );
        $publish_start  = !isset( $settings['future_publish']['start'] ) || $settings['future_publish']['start'] === '' ? '' : intval( $settings['future_publish']['start'] );
        $publish_end    = !isset( $settings['future_publish']['end'] ) || $settings['future_publish']['end'] === '' ? '' : intval( $settings['future_publish']['end'] );
        ?>
            Delay publishing by using a range of <input name="<?php echo LINKMARKLET_PREFIX; ?>settings[future_publish][min]" type="text" id="linkmarklet_future_publish_min" value="<?php echo $timeframe_min; ?>" class="small-text" /> to <input name="<?php echo LINKMARKLET_PREFIX; ?>settings[future_publish][max]" type="text" id="linkmarklet_future_publish_max" value="<?php echo $timeframe_max; ?>" class="small-text" /> minutes. I would also like to publish only between the hours of <input name="<?php echo LINKMARKLET_PREFIX; ?>settings[future_publish][start]" type="text" id="linkmarklet_future_publish_start" value="<?php echo $publish_start; ?>" class="small-text" /> and <input name="<?php echo LINKMARKLET_PREFIX; ?>settings[future_publish][end]" type="text" id="linkmarklet_future_publish_end" value="<?php echo $publish_end; ?>" class="small-text" /><br /><span class="description">Leave empty to disable. 24 hour clock.</span>
        <?php
    }

    function edit_prepopulate_slug()
    {
        $settings           = get_option( LINKMARKLET_PREFIX . 'settings' );
        $prepopulate_slug   = isset( $settings['prepopulate_slug'] ) ? true : false;
        ?>
            <input name="<?php echo LINKMARKLET_PREFIX; ?>settings[prepopulate_slug]" type="checkbox" id="linkmarklet_prepopulate_slug" value="1" <?php if( $prepopulate_slug ) : ?>checked="checked"<?php endif; ?>/> <span class="description">Auto-generate a slug</span>
        <?php
    }

    function edit_support_tags()
    {
        $settings       = get_option( LINKMARKLET_PREFIX . 'settings' );
        $support_tags   = isset( $settings['support_tags'] ) ? true : false;
        ?>
            <input name="<?php echo LINKMARKLET_PREFIX; ?>settings[support_tags]" type="checkbox" id="linkmarklet_support_tags" value="1" <?php if( $support_tags ) : ?>checked="checked"<?php endif; ?>/> <span class="description">Include a field for tags</span>
        <?php
    }

    function edit_markdown()
    {
        $settings   = get_option( LINKMARKLET_PREFIX . 'settings' );
        $markdown   = isset( $settings['markdown'] ) ? true : false;
        ?>
            <input name="<?php echo LINKMARKLET_PREFIX; ?>settings[markdown]" type="checkbox" id="linkmarklet_markdown" value="1" <?php if( $markdown ) : ?>checked="checked"<?php endif; ?>/> <span class="description">Use Markdown on Save when publishing</span>
        <?php
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
