<?php
define('IFRAME_REQUEST' , true);

ob_start();
require_once( preg_replace( "/wp-content.*/", "wp-load.php", __FILE__ ) );
require_once( preg_replace( "/wp-content.*/", "/wp-admin/includes/admin.php", __FILE__ ) );
/** WordPress Administration Bootstrap */
require_once( preg_replace( "/wp-content.*/", "/wp-admin/admin.php", __FILE__ ) );
ob_end_clean();

header( 'Content-Type: ' . get_option( 'html_type' ) . '; charset=' . get_option( 'blog_charset' ) );

if ( ! current_user_can( 'edit_posts' ) || ! current_user_can( get_post_type_object( 'post' )->cap->create_posts ) )
    wp_die( __( 'Access Denied.' ) );

// let's create our post
$post       = get_default_post_to_edit( 'post', true );
$post_ID    = $post->ID;

// Set Variables
$title = isset( $_GET['t'] ) ? trim( strip_tags( html_entity_decode( stripslashes( $_GET['t'] ) , ENT_QUOTES) ) ) : '';

$selection = '';
if ( !empty($_GET['s']) ) {
    $selection = str_replace( '&apos;', "'", stripslashes( $_GET['s'] ) );
    $selection = trim( htmlspecialchars( html_entity_decode($selection, ENT_QUOTES) ) );
}

if ( ! empty($selection) ) {
    // $selection = preg_replace('/(\r?\n|\r)/', '</p><p>', $selection);
    // $selection = '<p>' . str_replace('<p></p>', '', $selection) . '</p>';
}

$url = isset($_GET['u']) ? esc_url($_GET['u']) : '';
$image = isset($_GET['i']) ? $_GET['i'] : '';

function linkmarklet_post()
{

    $settings = get_option( LINKMARKLET_PREFIX . 'settings' );

    // set our time (if applicable)
    $timeframe_min  = isset( $settings['future_publish']['min'] ) ? intval( $settings['future_publish']['min'] ) : false;
    $timeframe_max  = isset( $settings['future_publish']['max'] ) ? intval( $settings['future_publish']['max'] ) : false;
    $publish_start  = isset( $settings['future_publish']['start'] ) ? intval( $settings['future_publish']['start'] ) : false;
    $publish_end    = isset( $settings['future_publish']['end'] ) ? intval( $settings['future_publish']['end'] ) : false;

    // by default it'll be right now
    $timestamp      = (int) current_time( 'timestamp' );
    $timestamp_gmt  = (int) current_time( 'timestamp', 1 );

    $future_publish = false;

    // check to see if we need to bump our publish time
    if( $timeframe_min !== false && $timeframe_max !== false )
    {
        // set the post date

        // figure out our start time which is either right now, or the future-most post

        $args = array(
                'numberposts'   => 1,       // just want the newest post
                'post_status'   => 'any'    // regardless of format
            );
        $posts_array = get_posts( $args );

        // if there are any posts, we can check it out
        $post_timestamp = false;
        if( $posts_array )
        {
            foreach( $posts_array as $post )
            {
                setup_postdata( $post );
                $post_timestamp = strtotime( $post->post_date );
            }
        }

        // get the future-most timestamp and use that
        if( $post_timestamp > $timestamp )
        {
            $future_publish = true;

            // our timestamps need to be adjusted
            $timestamp      = $post_timestamp;
            $timestamp_gmt  = $post_timestamp + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );

            // determine how many seconds we'll offset
            $offset = rand( $timeframe_min, $timeframe_max ) * 60;

            // the post is scheduled so we need to offset both
            $timestamp      = $timestamp + $offset;
            $timestamp_gmt  = $timestamp_gmt + $offset;
        }
    }

    // we need to check to see if we're within the posting window (if set)
    if( $publish_start !== false && $publish_end !== false )
    {
        // our publish window needs to be put within today's context
        $publish_start  = date( 'U', strtotime( date( 'Y-m-d' ) . ' ' . $publish_start . ':00:00' ) );
        $publish_end    = date( 'U', strtotime( date( 'Y-m-d' ) . ' ' . $publish_end . ':00:00' ) );

        // check to see if we're too early
        if( $timestamp < $publish_start )
        {
            $future_publish     = true;
            $timestamp          = $publish_start;
            $timestamp_gmt      = $publish_start - ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
        }

        // check to see if we're too late
        if( $timestamp > $publish_end )
        {
            // need to push it to tomorrow's start time
            $future_publish     = true;
            $timestamp          = $publish_start + ( 24 * HOUR_IN_SECONDS );
            $timestamp_gmt      = ( $publish_start + ( 24 * HOUR_IN_SECONDS ) ) - ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
        }
    }


    $post       = get_default_post_to_edit();
    $post       = get_object_vars( $post );
    $post_ID    = $post['ID'] = intval( $_POST['post_id'] );

    if( !current_user_can( 'edit_post', $post_ID ) )
        wp_die( __( 'You are not allowed to edit this post.' ) );

    // set our category
    $post['post_category']  = !empty( $settings['category'] ) ? intval( $settings['category'] ) : 0;

    // set our post properties
    $post['post_title']     = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
    $content                = isset( $_POST['content'] ) ? esc_textarea( $_POST['content'] ) : '';

    // set the post_content and status
    $post['post_content']   = $content;
    $post['post_status']    = 'draft';

    // Markdown on Save?
    if( is_plugin_active( 'markdown-on-save/markdown-on-save.php' ) && !empty( $settings['markdown'] ) )
    {
        // we need to set up our post data to tell Markdown on Save we want to use it
        $post['cws_using_markdown']     = 1;
        $post['_cws_markdown_nonce']    = wp_create_nonce( 'cws-markdown-save' );
    }

    // set the time attributes we want
    if( $future_publish )
    {
        $post['edit_date']      = date( 'Y-m-d H:i:s', $timestamp );
        $post['post_date']      = date( 'Y-m-d H:i:s', $timestamp );
        $post['post_date_gmt']  = date( 'Y-m-d H:i:s', $timestamp_gmt );
    }

    // set our post format
    if( isset( $settings['post_format'] ) )
    {
        if( current_theme_supports( 'post-formats', $settings['post_format'] ) )
        {
            set_post_format( $post_ID, $settings['post_format'] );
        }
        else
        {
            set_post_format( $post_ID, false );
        }
    }

    // set the category
    $post['post_category'] = array( $post['post_category'] );

    // set the slug
    $post['post_name'] = sanitize_title( $_POST['slug'] );

    // update what we've set
    $post_ID = wp_update_post( $post );

    // mark as published if that's the intention
    if ( isset( $_POST['publish'] ) && current_user_can( 'publish_posts' ) )
    {
        if( $future_publish )
        {
            $post['post_status']    = 'future';
        }
        else
        {
            $post['post_status']    = 'publish';
        }
    }

    // we also need to add our custom field link
    $custom_field = isset( $settings['custom_field'] ) ? $settings['custom_field'] : '';
    if( !empty( $custom_field ) )
        update_post_meta( $post_ID, $custom_field, mysql_real_escape_string( $_POST['url'] ) );

    // set our post tags if applicable
    if( !empty( $settings['support_tags'] ) && !empty( $_POST['tags'] ) )
        wp_set_post_tags( $post_ID, $_POST['tags'] );

    // our final update
    $post_ID = wp_update_post( $post );

    return $post_ID;
}


?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Linkmarklet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <style>
        * { -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; }
        body {
            background:#fff;
            font:12px Helvetica, Arial, sans-serif;
            color:#020204;
            margin:0;
            padding:0;
        }
        .hidden {
            display:none !important;
        }
        div.actions {
            border-top:1px solid #373739;
            padding:8px;
            overflow:hidden;
            zoom:1;
            background: #2a292e;
            background: -moz-linear-gradient(top,  #2a292e 0%, #201f24 70%, #020204 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#2a292e), color-stop(70%,#201f24), color-stop(100%,#020204));
            background: -webkit-linear-gradient(top,  #2a292e 0%,#201f24 70%,#020204 100%);
            background: -o-linear-gradient(top,  #2a292e 0%,#201f24 70%,#020204 100%);
            background: -ms-linear-gradient(top,  #2a292e 0%,#201f24 70%,#020204 100%);
        }
        div.actions input {
            display:block;
            float:right;
            color:#EAEBED;
            font-weight:normal;
            margin:0;
            border-radius:5px;
            border:1px solid #000;
            background: #2a292e;
            background: -moz-linear-gradient(top,  #2a292e 0%, #201f24 70%, #020204 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#2a292e), color-stop(70%,#201f24), color-stop(100%,#020204));
            background: -webkit-linear-gradient(top,  #2a292e 0%,#201f24 70%,#020204 100%);
            background: -o-linear-gradient(top,  #2a292e 0%,#201f24 70%,#020204 100%);
            background: -ms-linear-gradient(top,  #2a292e 0%,#201f24 70%,#020204 100%);
            padding:5px 11px;
            cursor:pointer;
        }
        div.actions input:first-of-type {
            float:left;
        }
        div.field {
            overflow:hidden;
            zoom:1;
            position:relative;
            border-top:1px solid #CBCBCB;
            padding:8px;
        }
        div.field label {
            width:110px;
            position:absolute;
            left:8px;
            top:12px;
            color:#bbb;
        }
        div.field input {
            display:block;
            width:100%;
            padding-left:110px;
            font-size:12px;
            border:0;
            -webkit-appearance:none;
        }
        div.field input:focus {
            outline:none;
        }
        div.textarea label {
            display:none;
        }
        div.textarea {
            min-height:200px;
        }
        div.field textarea {
            display:block;
            width:100%;
            min-height:200px;
            height:100%;
            font-size:12px;
            border:0;
            -webkit-appearance:none;
            resize:none;
        }
        div.field textarea:focus {
            outline:none;
        }
        .message {
            padding:15px;
            max-width:600px;
            margin:0 auto;
        }
        .message p {
            border-radius:5px;
            text-align:center;
            background:#efefef;
            border:1px solid #ccc;
            padding:8px 15px;
        }
        .message a {
            color:#238FF1;
        }
    </style>
</head>
<body>
<?php
    if( isset( $_REQUEST['_wpnonce'] ) )
    {
        check_admin_referer( 'linkmarklet-press-this' );
        $posted = $post_ID = linkmarklet_post();
        ?>

        <div class="message">
            <p>Entry posted. <a onclick="window.opener.location.replace(this.href); window.close();" href="<?php echo get_permalink( $posted ); ?>">View post</a></p>
        </div>

<?php } else { ?>
    <?php $settings = get_option( LINKMARKLET_PREFIX . 'settings' ); ?>
    <form action="" method="post">
        <div class="hidden">
            <?php wp_nonce_field( 'linkmarklet-press-this' ); ?>
            <input type="hidden" name="post_type" id="post_type" value="text"/>
            <input type="hidden" name="autosave" id="autosave" />
            <input type="hidden" id="original_post_status" name="original_post_status" value="draft" />
            <input type="hidden" id="prev_status" name="prev_status" value="draft" />
            <input type="hidden" id="post_id" name="post_id" value="<?php echo (int) $post_ID; ?>" />
        </div>
        <div class="actions" id="row-actions">
            <input type="submit" name="save" id="save" value="Save" />
            <input type="submit" name="publish" id="publish" value="Publish" />
        </div>
        <div class="field textfield" id="row-title">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="<?php echo $title; ?>" />
        </div>
        <div class="field textfield" id="row-url">
            <label for="url">Link URL</label>
            <input type="text" name="url" id="url" value="<?php echo $url; ?>" />
        </div>
        <div class="field textfield" id="row-slug">
            <label for="slug">Slug</label>
            <input type="text" name="slug" id="slug" value="<?php if( isset( $settings['prepopulate_slug'] ) ) { echo sanitize_title( $title ); } ?>" />
        </div>
        <?php if( !empty( $settings['support_tags'] ) ) : ?>
            <div class="field textfield" id="row-tags">
                <label for="url">Tags</label>
                <input type="text" name="tags" id="tags" value="" />
            </div>
        <?php endif; ?>
        <div class="field textarea" id="row-content">
            <label for="content">Content</label>
            <textarea name="content" id="content"><?php echo $selection; ?></textarea>
        </div>
    </form>
<?php } ?>
<script type="text/javascript">
    function reposition(){
        var window_height   = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight || 0;
        var actions         = document.getElementById('row-actions').offsetHeight;
        var title           = document.getElementById('row-title').offsetHeight;
        var url             = document.getElementById('row-url').offsetHeight;
        var slug            = document.getElementById('row-slug').offsetHeight;
        var height          = window_height - actions - title - url - slug - 25;
        <?php if( !empty( $settings['support_tags'] ) ) : ?>
        var tags            = document.getElementById('row-tags').offsetHeight;
        height = height - tags;
        <?php endif; ?>
        document.getElementById('content').style.height = height + 'px';
    }
    reposition();
    window.onresize = function(event) {
        reposition();
    }
</script>
</body>
</html>
