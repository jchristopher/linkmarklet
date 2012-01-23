<div class="wrap">
    <div id="icon-options-general" class="icon32"><br /></div>
    <h2>Linkmarklet Options</h2>
    <form action="options.php" method="post">
        <div id="poststuff" class="metabox-holder">
            <?php settings_fields( '_iti_linkmarklet_settings' ); ?>
            <?php do_settings_sections( '_iti_linkmarklet_options' ); ?>
        </div>
        <p class="submit">
            <input type="submit" class="button-primary" value="Save Options" />
        </p>
    </form>
</div>
