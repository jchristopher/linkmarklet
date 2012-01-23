<?php
ob_start();
require_once( preg_replace( "/wp-content.*/", "wp-load.php", __FILE__ ) );
require_once( preg_replace( "/wp-content.*/", "/wp-admin/includes/admin.php", __FILE__ ) );
ob_end_clean();

?>
<ol>
    <li>Bookmark this page</li>
    <li>Edit the bookmark</li>
    <li>Remove <strong><?php echo LINKMARKLET_URL . '/remove-this.php?'; ?></strong> and leave everything else</li>
    <li>Save your bookmarklet</li>
</ol>
