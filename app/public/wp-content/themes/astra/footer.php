<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<?php astra_content_bottom(); ?>
	</div> <!-- ast-container -->
	</div><!-- #content -->
<?php 
	astra_content_after();
		
	astra_footer_before();
		
	astra_footer();
		
	astra_footer_after(); 
?>
	</div><!-- #page -->
<?php 
	astra_body_bottom();    
	wp_footer(); 
?>
<script>
(function() {
  const tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
  if (tz && document.cookie.indexOf('client_timezone') === -1) {
    const d = new Date();
    d.setTime(d.getTime() + (7*24*60*60*1000)); // 7 días
    document.cookie = "client_timezone=" + encodeURIComponent(tz) + ";expires=" + d.toUTCString();
    location.reload();
  }
})();
</script>

	</body>
</html>
