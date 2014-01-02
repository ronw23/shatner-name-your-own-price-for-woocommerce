<div class="wrap">
    <h2>Shatner Settings</h2>
    <form method="post" action="options.php"> 
        <?php @settings_fields('wp_plugin_template-group-shatner'); ?>
        <?php @do_settings_fields('wp_plugin_template-group-shatner'); ?>

        <?php do_settings_sections('wp_plugin_template_shatner'); ?>

        <?php @submit_button(); ?>
    </form>
</div>
