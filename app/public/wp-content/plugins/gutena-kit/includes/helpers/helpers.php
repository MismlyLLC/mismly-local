<?php 
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//block id classname prefix
if ( ! function_exists( 'gutenakit_block_id_classname_prefix' ) ) {
	function gutenakit_block_id_classname_prefix(){
		return 'gutenakitid-';
	}
}

if ( ! function_exists( 'gutendkit_str_replace_first' ) ) {
    function gutendkit_str_replace_first( $search, $replace, $given_string ) {
        return implode( $replace, explode( $search, $given_string, 2 ));
    }
}

if ( ! function_exists( 'gutendkit_uniqid_base36' ) ) {
	function gutendkit_uniqid_base36( $more_entropy = false ) {
		static $id_counter = 0;
		$s = uniqid( '', $more_entropy );
		if ( ! $more_entropy)
			return base_convert( $s, 16, 36 );
			
		$hex = substr( $s, 0, 13 );
		$dec = $s[13] . substr( $s, 15 ); // skip the dot
		return base_convert( $hex, 16, 36 ) . base_convert( $dec, 10, 36 ). (string) ++$id_counter;
	}
}

if ( ! function_exists( 'gutendkit_last_str_replace' ) ) {
	function gutendkit_last_str_replace( $search, $replace, $given_string ) {
		$pos = strrpos( $given_string, $search );
		if ( $pos > 1 ) {
			$given_string = substr_replace( $given_string, $replace, $pos, strlen( $search ) );
		}
		return $given_string;
	}
}

if ( ! function_exists( 'gutenakit_is_remove_wp_emoji' ) ) {
	function gutenakit_is_remove_wp_emoji(){
		return apply_filters( 'gutenakit_remove_wp_emoji', true );
	}
}

//Check admin capabilities
if ( ! function_exists( 'is_gutenakit_admin' ) ) {
	function is_gutenakit_admin( $check_permission = 'manage_options' ) {
		if ( ! function_exists( 'wp_get_current_user' ) ) { 
			require_once( ABSPATH . "wp-includes/pluggable.php" ); 
		}
		if ( ! function_exists( 'current_user_can' ) ) { 
			require_once( ABSPATH . "wp-includes/capabilities.php" ); 
		}
		return ( function_exists( 'current_user_can' ) && current_user_can( $check_permission ) );
	}
}

//Check admin capabilities
if ( ! function_exists( 'gutenakit_sanitize_array' ) ) {
	function gutenakit_sanitize_array( $array ) {
		if ( ! empty( $array ) ) {
			foreach ( (array) $array as $key => $value ) {
				if ( is_array( $value ) ) {
					$array[ $key ] = gutenakit_sanitize_array( $value );
				} else {
					$array[ $key ] = sanitize_text_field( $value );
				}
			}
		}
		return $array;
	}
}

//Editor Block list
if ( ! function_exists( 'gutenakit_block_list' ) ) {
	function gutenakit_block_list(){
		$inactive_blocks = get_option( 'gutena_inactive_blocks', array() );
		//'gutena/newsletter' = block name taken from block.json
		return array(
			'gutena/newsletter'        => array(
				'dirname'  => 'newsletter-block-gutena',
				'filepath' => 'newsletter-block-gutena/newsletter-block-gutena.php',
				'active'   => empty( $inactive_blocks['gutena/newsletter'] ),      
			), 
			'gutena/post-featured-tag' => array(
				'dirname'  => 'post-featured-tag-block-gutena',
				'filepath' => 'post-featured-tag-block-gutena/post-featured-tag-block-gutena.php',
				'active'   => empty( $inactive_blocks['gutena/post-featured-tag'] ),       
			), 
			'gutena/instagram-gallery' => array(
				'dirname'  => 'photofeed-block-gutena',
				'filepath' => 'photofeed-block-gutena/photofeed-block-gutena.php',
				'active'   => empty( $inactive_blocks['gutena/instagram-gallery'] ),       
			), 
		);
	}
}


//define global typography
if ( ! function_exists( 'get_gutena_kit_global_typography' ) ) {
	function get_gutena_kit_global_typography(){
		//global typography
		global $gutena_kit_global_typography;

		//check if global typography exists 
		if ( ! isset( $gutena_kit_global_typography ) ) {
			$gutena_kit_global_typography = get_option( 'gutena_kit_global_typography', array() );
		}

		return $gutena_kit_global_typography;
	}
}

//Get global typography css
if ( ! function_exists( 'get_gutena_kit_global_typography_css' ) ) {
	function get_gutena_kit_global_typography_css( $slug = false ) {
		//get global typography
		get_gutena_kit_global_typography();

		global $gutena_kit_global_typography;

		//check if global typography exists 
		if ( empty( $gutena_kit_global_typography ) && ! is_array( $gutena_kit_global_typography ) ) {
			return '';
		}

		//Specific typography
		if ( ! empty( $slug ) ) {
			return ( ! empty( $gutena_kit_global_typography[ $slug ] ) && ! empty( $gutena_kit_global_typography[ $slug ]['css'] ) ) ? $gutena_kit_global_typography[ $slug ]['css'] : '';
		} else {
			$css = '';
			foreach ( $gutena_kit_global_typography as $typography ) {
				$css .= empty( $typography['css'] ) ? '' : $typography['css'];
			}
			return $css;
		}

		return '';
	}
}


//Block advance controls css only for refrence 
if ( ! function_exists( 'gutenakit_block_additional_controls_css' ) ) {
	function gutenakit_block_additional_controls_css( $editor = false ) {
		$media_query_tab = apply_filters('gutena-kit-media-query-tab', '1080px' );
		$media_query_mobile = apply_filters('gutena-kit-media-query-mobile', '767px' );
		$editor_class = $editor ? ' .editor-styles-wrapper ' : ' ';
		return "
		$editor_class.has-gutenakit-padding-default{
			padding-top:var(--gutenakit--default-padding-top,initial);
			padding-right:var(--gutenakit--default-padding-right,initial);
			padding-bottom:var(--gutenakit--default-padding-bottom,initial);
			padding-left:var(--gutenakit--default-padding-left,initial);
		}

		".( $editor ? " .editor-styles-wrapper .block-editor-block-list__layout.is-root-container .has-gutenakit-margin-default ," : $editor_class ).".has-gutenakit-margin-default{
			margin-top:var(--gutenakit--default-margin-top,initial);
			margin-right:var(--gutenakit--default-margin-right,initial);
			margin-bottom:var(--gutenakit--default-margin-bottom,initial);
			margin-left:var(--gutenakit--default-margin-left,initial);
		}

		$editor_class.has-gutenakit-color-normal {
			color:var(--gutenakit--color-normal-text,initial);
			background:var(--gutenakit--color-normal-background,initial);
		}

		$editor_class.has-gutenakit-color-hover:hover {
			color:var(--gutenakit--color-hover-text,initial);
			background:var(--gutenakit--color-hover-background,initial);
		}

		$editor_class.has-gutenakit-color-normal-link a {
			color:var(--gutenakit--color-normal-link,initial);
		}

		$editor_class.has-gutenakit-color-hover-link a:hover {
			color:var(--gutenakit--color-hover-link,initial);
		}

		$editor_class.has-gutenakit-border-normal {
			border:var(--gutenakit--border-normal,initial);
			border-top:var(--gutenakit--border-normal-top,initial);
			border-right:var(--gutenakit--border-normal-right,initial);
			border-bottom:var(--gutenakit--border-normal-bottom,initial);
			border-left:var(--gutenakit--border-normal-left,initial);
			border-radius:var(--gutenakit--border-normal-radius,initial);
		}

		$editor_class.has-gutenakit-border-hover:hover {
			border:var(--gutenakit--border-hover,initial);
			border-top:var(--gutenakit--border-hover-top,initial);
			border-right:var(--gutenakit--border-hover-right,initial);
			border-bottom:var(--gutenakit--border-hover-bottom,initial);
			border-left:var(--gutenakit--border-hover-left,initial);
			border-radius:var(--gutenakit--border-hover-radius,initial);
		}

		$editor_class.has-gutenakit-boxshadow-normal {
			box-shadow:var(--gutenakit--boxshadow-normal,initial);
		}

		$editor_class.has-gutenakit-boxshadow-hover:hover {
			box-shadow:var(--gutenakit--boxshadow-hover,initial);
		}

		$editor_class.has-gutenakit-overlay-normal, 
		$editor_class.has-gutenakit-overlay-hover, {
			position: relative;
		}

		$editor_class.has-gutenakit-overlay-normal:before{
			content:'';
			background:var(--gutenakit--overlay-normal-background,initial); 
			opacity: var(--gutenakit--overlay-normal-opacity,initial);
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
		}

		$editor_class.has-gutenakit-overlay-hover:hover:before{
			content:'';
			background:var(--gutenakit--overlay-hover-background,initial); 
			opacity: var(--gutenakit--overlay-hover-opacity,initial);
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
		}

		$editor_class.has-gutenakit-hide-in-default{
			display:none;
		}

		$editor_class.has-gutenakit-g-typography {
			font-size:var(--gutenakit--gt--font-size,initial);
			line-height:var(--gutenakit--gt--line-height,initial);
			font-family:var(--gutenakit--gt--font-family,initial);
			font-style:var(--gutenakit--gt--font-style,initial);
			font-weight:var(--gutenakit--gt--font-weight,initial);
			letter-spacing:var(--gutenakit--gt--letter-spacing,initial);
			text-transform:var(--gutenakit--gt--text-transform,initial);
			text-decoration:var(--gutenakit--gt--text-decoration,initial);
		}
		
		$editor_class.has-gutenakit-typography-d,
		$editor_class.has-gutenakit-typography-d-t,
		$editor_class.has-gutenakit-typography-d-t-m,
		$editor_class.has-gutenakit-typography-d.has-gutenakit-g-typography,
		$editor_class.has-gutenakit-typography-d-t.has-gutenakit-g-typography,
		$editor_class.has-gutenakit-typography-d-t-m.has-gutenakit-g-typography
		 {
			font-size:var(--gutenakit--font-size,initial);
			line-height:var(--gutenakit--line-height,initial);
			font-family:var(--gutenakit--font-family,initial);
			font-style:var(--gutenakit--font-style,initial);
			font-weight:var(--gutenakit--font-weight,initial);
			letter-spacing:var(--gutenakit--letter-spacing,initial);
			text-transform:var(--gutenakit--text-transform,initial);
			text-decoration:var(--gutenakit--text-decoration,initial);
		}
		
		@media only screen and (min-width: 768px) and (max-width: $media_query_tab) {
			
			$editor_class.has-gutenakit-padding-tablet{
				padding-top:var(--gutenakit--tablet-padding-top,initial);
				padding-right:var(--gutenakit--tablet-padding-right,initial);
				padding-bottom:var(--gutenakit--tablet-padding-bottom,initial);
				padding-left:var(--gutenakit--tablet-padding-left,initial);
			}

			$editor_class.has-gutenakit-margin-tablet{
				margin-top:var(--gutenakit--tablet-margin-top,initial);
				margin-right:var(--gutenakit--tablet-margin-right,initial);
				margin-bottom:var(--gutenakit--tablet-margin-bottom,initial);
				margin-left:var(--gutenakit--tablet-margin-left,initial);
			}

			$editor_class.has-gutenakit-g-typography:not(.has-gutenakit-f-typography) {
				font-size:var(--gutenakit--gt--t-font-size,initial);
			}

			$editor_class.has-gutenakit-g-typography {
				line-height:var(--gutenakit--gt--t-line-height,var(--gutenakit--line-height,initial));
			}

			$editor_class.has-gutenakit-typography-t,
			$editor_class.has-gutenakit-typography-d-t,
			$editor_class.has-gutenakit-typography-t-m,
			$editor_class.has-gutenakit-typography-d-t-m {
				font-size:var(--gutenakit--t-font-size,initial);
			}

			$editor_class.has-gutenakit-hide-in-tablet{
				display:none;
			}
			
		}

		@media only screen and (max-width: $media_query_mobile) {

			$editor_class.has-gutenakit-padding-mobile{
				padding-top:var(--gutenakit--mobile-padding-top,initial);
				padding-right:var(--gutenakit--mobile-padding-right,initial);
				padding-bottom:var(--gutenakit--mobile-padding-bottom,initial);
				padding-left:var(--gutenakit--mobile-padding-left,initial);
			}

			$editor_class.has-gutenakit-margin-mobile{
				margin-top:var(--gutenakit--mobile-margin-top,initial);
				margin-right:var(--gutenakit--mobile-margin-right,initial);
				margin-bottom:var(--gutenakit--mobile-margin-bottom,initial);
				margin-left:var(--gutenakit--mobile-margin-left,initial);
			}

			$editor_class.has-gutenakit-g-typography:not(.has-gutenakit-f-typography) {
				font-size:var(--gutenakit--gt--m-font-size,initial);
			}

			$editor_class.has-gutenakit-g-typography {
				line-height:var(--gutenakit--gt--m-line-height,var(--gutenakit--line-height,initial));
			}

			$editor_class.has-gutenakit-typography-m,
			$editor_class.has-gutenakit-typography-d-m,
			$editor_class.has-gutenakit-typography-t-m,
			$editor_class.has-gutenakit-typography-d-t-m {
				font-size:var(--gutenakit--m-font-size,initial);
			}

			$editor_class.has-gutenakit-hide-in-mobile{
				display:none;
			}
		}

		";
		
	}
}