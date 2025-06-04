<?php
/**
 * Provide a admin area for demo listing
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @package    Gutena_Kit
 * @subpackage Gutena_Kit/admin/partials
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

 $demos = gutendkit_categorize_demo_list();
 if ( ! empty($demos) && is_array($demos) ) {
    $index = 0;
?>
<div class="gutenakit-demo-import-page">
    <div class="gutenakit-header">
            <div class="gutenakit-admin-logo">
                <img src="<?php echo esc_url(GUTENA_KIT_PLUGIN_URL . 'admin/img/logo.png') ?>" alt="<?php esc_html_e('logo', 'gutena-kit'); ?>">
            </div>
    </div>
    <div class="gutenakit-admin-body">
        <div class="gutenakit-admin-container">
            <?php foreach ( $demos as $demo ) {?>
            <div class="gutenakit-demo-card">
                <div class="gutenakit-demo-card-img">
                    <img src="<?php echo esc_url($demo['default']['import_preview_image_url']); ?>" alt="<?php echo esc_html($demo['default']['import_file_name']); ?>"/>
                </div>
                <div class="gutenakit-demo-card-footer">
                    <div class="gutenakit-demo-title">
                        <a href="<?php echo esc_url( $demo['default']['preview_url'] ); ?>" target="_blank" >
                            <?php echo esc_html($demo['default']['import_file_name']); ?>
                        </a>
                    </div>
                    <div class="gutenakit-demo-import-btn">
                        <a href="<?php echo esc_url(admin_url( 'options.php?page=merlin&demo_index='.$index.'')); ?>" class="gutenakit-import-demo-link button button-primary">
                        <?php esc_html_e('Import', 'gutena-kit'); ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php $index++; } ?>
        </div>
    </div>
</div>
<?php } ?>