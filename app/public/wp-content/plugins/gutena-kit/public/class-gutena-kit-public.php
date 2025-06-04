<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @package    Gutena_Kit
 * @subpackage Gutena_Kit/public
 */

 // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Gutena_Kit
 * @subpackage Gutena_Kit/public
 */
class Gutena_Kit_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $gutena_kit    The ID of this plugin.
	 */
	private $gutena_kit;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $gutena_kit       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $gutena_kit, $version ) {
		$this->gutena_kit = $gutena_kit;
		$this->version    = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->gutena_kit . '-block', GUTENA_KIT_PLUGIN_URL . 'public/css/block-editor' . GUTENA_KIT_MIN_FILE . '.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->gutena_kit, GUTENA_KIT_PLUGIN_URL . 'public/css/gutena-kit-public' . GUTENA_KIT_MIN_FILE . '.css', array(), $this->version, 'all' );
	}

	public function add_post_css() {
		$gutenakit_post_config = get_post_meta( get_the_ID(), 'gutenakit_post_config', true );
		if ( ! empty( $gutenakit_post_config ) && ! empty( $gutenakit_post_config['gutenakit_css'] ) ) {
			echo '<style id="gutena-kit-post-css">' . esc_html( $gutenakit_post_config['gutenakit_css'] ) . '</style>';
		}
	}

	/**
	 * Registers editor stylesheets
	 */
	public function add_editor_styles() {
		add_editor_style( GUTENA_KIT_PLUGIN_URL . 'public/css/block-editor' . GUTENA_KIT_MIN_FILE . '.css' );

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Gutena_Kit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Gutena_Kit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		// wp_enqueue_script( $this->gutena_kit, plugin_dir_url( __FILE__ ) . 'js/gutena-kit-public.js', array( 'wp-hooks' ), $this->version, false );	
	}

	/**
	 * Add typography support to Blocks
	 */
	public function add_block_typography_support( $metadata ) {
		if ( in_array( $metadata['name'], array( 'core/button', 'core/paragraph', 'core/heading' ), true ) && isset( $metadata['supports']['typography'] ) ) {
			if ( empty( $metadata['supports']['typography']['__experimentalFontFamily'] ) ) {
				$metadata['supports']['typography']['__experimentalFontFamily'] = true;
			}
			if ( empty( $metadata['supports']['typography']['lineHeight'] ) ) {
				$metadata['supports']['typography']['lineHeight'] = true;
			}
			if ( empty( $metadata['supports']['typography']['__experimentalFontStyle'] ) ) {
				$metadata['supports']['typography']['__experimentalFontStyle'] = true;
			}
			if ( empty( $metadata['supports']['typography']['__experimentalFontWeight'] ) ) {
				$metadata['supports']['typography']['__experimentalFontWeight'] = true;
			}
		}
		return $metadata;
	}

	/**
	 * Add Spacing support to Blocks
	 */
	public function add_block_space_support( $metadata ) {
		if ( isset( $metadata['supports'] ) ) {
			// Margin Padding support
			if ( in_array( $metadata['name'], array( 'core/paragraph' ), true ) ) {
				$metadata['supports']['spacing']['margin']  = array( 'top', 'bottom' );
				$metadata['supports']['spacing']['padding'] = true;
			}

			// BlockGap Support
			if ( in_array( $metadata['name'], array( 'core/columns' ), true ) ) {
				// $metadata['supports']['spacing']['blockGap'] = true;
			}
			// Layout support like align wide
			if ( in_array( $metadata['name'], array( 'core/media-text' ), true ) && isset( $metadata['supports']['align'] ) ) {
				$metadata['supports']['__experimentalLayout'] = true;
			}
		}
		return $metadata;
	}

	public function add_block_attribute( $metadata ) {
		// For custom added controls Css generation
		$metadata['attributes']['gutenaKitID']['type']    = 'string';// Unique ID
		$metadata['attributes']['gutenaKitCSS']['type']   = 'object';// Block CSS
		$metadata['attributes']['gutenaKitClass']['type'] = 'object';// Block classes
		$metadata['attributes']['gutenaKitStyle']['type'] = 'object';// Block Style Controls

		// Media Text Block extra controls
		if ( 'core/media-text' === $metadata['name'] ) {
			// Add gap controls between media and text
			$metadata['attributes']['gutenaKitGridGap'] = array(
				'type'    => 'string',
				'default' => '0px',
			);
		}

		if ( 'core/buttons' === $metadata['name'] ) {
			$metadata['attributes']['gutenaAdvancedButtons'] = [
				'type'    => 'boolean',
				'default' => false,
			];
		}

		if ( 'core/button' === $metadata['name'] ) {
			$metadata['attributes']['gutenaAdvancedButton'] = [
				'type'    => 'boolean',
				'default' => false,
			];

			$attributes = [
				'uniqueId'        => 'string',
				'btnSize'         => 'object',
				'btnFontSize'     => 'string',
				'btnBorder'       => 'object',
				'btnColors'       => 'object',
				'btnIcon'         => 'string',
				'btnIconSVG'      => 'string',
				'btnIconPosition' => 'string',
				'btnIconSize'     => 'string',
				'btnIconGap'      => 'string',
				'blockStyles'     => 'object',
			];

			foreach ( $attributes as $key => $value ) {
				$metadata['attributes'][ $key ]['type'] = $value;
			}
		}

		return $metadata;
	}

	// block_type_metadata Filter
	public function edit_block_metadata( $metadata ) {
		if ( ! empty( $metadata['name'] ) ) {
			$metadata = $this->add_block_typography_support( $metadata );
			$metadata = $this->add_block_attribute( $metadata );
			$metadata = $this->add_block_space_support( $metadata );
		}
		return $metadata;
	}

	// Get gutena Kit settings
	private function get_gutena_kit_settings_css( $attrs ) {
		static $css_slug_array = array();
		
		if ( empty( $attrs['gutenaKitID'] ) || empty( $attrs['gutenaKitStyle'] ) ) {
			return $attrs;
		}
	
		//Check css properties in array format
		if ( empty( $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$attrs['gutenaKitStyle']['cssJson'] = array();
		} elseif ( ! is_array( $attrs['gutenaKitStyle']['cssJson'] ) ) {
			return $attrs;
		}
	
		//global typography flag
		$global_typography = false;
	
		// global typography data merge with main cssJson
		if ( ! empty( $attrs['gutenaKitStyle']['globalTypography'] ) && ! in_array( $attrs['gutenaKitStyle']['globalTypography'], $css_slug_array, true ) ) {
			$global_typography = $attrs['gutenaKitStyle']['globalTypography'];
			//global typography
			global $gutena_kit_global_typography;
	
			//check if global typography exists 
			if ( ! isset( $gutena_kit_global_typography ) ) {
				$gutena_kit_global_typography = get_option( 'gutena_kit_global_typography', array() );
			}
			//global typography array: [slug => global-typography]
			$global_typography_array = array();
	
			//Prepare global typography array by ungrouping 
			if ( ! empty( $gutena_kit_global_typography ) && is_array( $gutena_kit_global_typography ) ) {
				foreach ( $gutena_kit_global_typography as $gt ) {
					$global_typography_array = array_merge( $global_typography_array, $gt );
				}
			} else {
				$global_typography = false;
			}
	
			if ( false !== $global_typography && ! empty( $global_typography_array[ $global_typography ] ) && ! empty( $global_typography_array[ $global_typography ]['cssJson'] ) ) { 
				//print_r($global_typography_array);exit;
				//$attrs['gutenaKitStyle']['cssJson'] = array_merge( $attrs['gutenaKitStyle']['cssJson'],$global_typography_array[ $global_typography ]['cssJson'] );
				array_push( $css_slug_array, $global_typography );
			}else {
				$global_typography = false;
			}
		}
	
		//print_r($attrs['gutenaKitStyle']['cssJson']);exit;
	
		$media_query_tab = apply_filters('gutena-kit-media-query-tab', '1080px' );
		$media_query_mobile = apply_filters('gutena-kit-media-query-mobile', '767px' );
		$media_query_desktop = intval( $media_query_tab ) + 1;
		$media_query_desktop = $media_query_desktop.'px';
	
		$attrs['gutenaKitCSS']['generatedCss'] = '';
		$preVar = '';//--gutenakit--
		//Css 
		$css = '';
		$gk_id = ' .gutenakitid-'.$attrs['gutenaKitID'];
	
		/*******************
		 Default :start
		 *********************/
		//spacing
		foreach ( array( 'margin', 'padding' ) as $spacing ) {
			foreach ( array( 'top', 'right', 'bottom', 'left' ) as $position ) {
				if ( true === array_key_exists( $preVar.'default-'.$spacing.'-'.$position, $attrs['gutenaKitStyle']['cssJson'] ) ) {
					$css .= ' '.$spacing.'-'.$position.':'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'default-'.$spacing.'-'.$position ] .' !important;';
				}
			}
		}

		// Text color
		if ( true === array_key_exists( $preVar.'color-normal-text', $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$css .= ' color:'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'color-normal-text' ] .' !important;';
		}

		// Background color
		if ( true === array_key_exists( $preVar.'color-normal-background', $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$css .= ' background:'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'color-normal-background' ] .' !important;';
		}

		// Border
		if ( true === array_key_exists( $preVar.'border-normal', $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$css .= ' border:'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'border-normal' ] .' !important;';
		} else {
			foreach ( array( 'top', 'right', 'bottom', 'left' ) as $position ) {
				if ( true === array_key_exists( $preVar.'border-normal-'.$position, $attrs['gutenaKitStyle']['cssJson'] ) ) {
					$css .= ' border-'.$position.':'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'border-normal-'.$position ] .' !important;';
				}
			}
		}

		// Border radius
		if ( true === array_key_exists( $preVar.'border-normal-radius', $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$css .= ' border-radius:'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'border-normal-radius' ] .' !important;';
		}

		// Box Shadow
		if ( true === array_key_exists( $preVar.'boxshadow-normal', $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$css .= 'box-shadow:'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'boxshadow-normal' ] .' !important;';
		}

		// Overlay parent
		if ( true === array_key_exists( $preVar.'overlay-normal-background', $attrs['gutenaKitStyle']['cssJson'] ) || true === array_key_exists( $preVar.'overlay-hover-background', $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$css .= ' position: relative;';
		}

		//typography
		if ( empty( $global_typography ) ) {
			foreach ( array( 'font-size', 'line-height', 'font-family', 'font-style', 'font-weight', 'letter-spacing', 'text-transform', 'text-decoration' ) as $font_property ) {
				if ( true === array_key_exists( $preVar.''.$font_property, $attrs['gutenaKitStyle']['cssJson'] ) ) {
					$css .= ' '.$font_property.':'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.''.$font_property ] .' !important;';
				}
			}
		}

		
		if ( true === array_key_exists( $preVar.'textcontentgap', $attrs['gutenaKitStyle']['cssJson'] ) ) {
				//text content gap, usually use for inline image gap
			$css .= ' display:flex; gap:'.$attrs['gutenaKitStyle']['cssJson'][ $preVar.'textcontentgap' ].';';
		}

		//translate3d in desktop
		if ( true === array_key_exists( $preVar.'translate3d-default', $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$css .= ' transform: '.$attrs['gutenaKitStyle']['cssJson'][ $preVar.'translate3d-default' ].';';
		}

		// Link color
		if ( true === array_key_exists( $preVar.'zindex', $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$css .= ' position: relative; z-index: '.$attrs['gutenaKitStyle']['cssJson'][ $preVar.'zindex' ].';';
		}

		if ( ! empty( $css ) ) {
			$attrs['gutenaKitCSS']['generatedCss'] .= $gk_id .'{ ' . $css . '}';
		}

		//Hide in desktop
		if ( true === array_key_exists( $preVar.'display-default', $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$attrs['gutenaKitCSS']['generatedCss'] .= '@media only screen and (min-width: '.$media_query_desktop .') { '  . $gk_id .'{ display: '.$attrs['gutenaKitStyle']['cssJson'][ $preVar.'display-default' ].'; } } ';
		}
	
		//Add global typography
		if ( ! empty( $global_typography ) && ! empty( $global_typography_array[ $global_typography ]['cssJson'] ) ) {
			$css = '';
			//typography
			foreach ( array( 'font-size', 'line-height', 'font-family', 'font-style', 'font-weight', 'letter-spacing', 'text-transform', 'text-decoration' ) as $font_property ) {
				if ( true === array_key_exists( $preVar.'gt-'.$font_property, $global_typography_array[ $global_typography ]['cssJson'] ) ) {
					$css .= ' '.$font_property.':'. $global_typography_array[ $global_typography ]['cssJson'][ $preVar.'gt-'.$font_property ] .' !important;';
				}
			}
			
			if ( ! empty( $css ) ) {
				$attrs['gutenaKitCSS']['generatedCss'] .= ' .has-gutenakit-gt-'.$global_typography.'{' . $css . '}';
			}
		}
		/*******************
		 Default :end
		 *********************/
	
		/************************
		 Block hover : START
		**************************/
		$css = '';
		// Text color
		if ( true === array_key_exists( $preVar.'color-hover-text', $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$css .= ' color:'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'color-hover-text' ] .' !important;';
		}
	
		// Background color
		if ( true === array_key_exists( $preVar.'color-hover-background', $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$css .= ' background:'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'color-hover-background' ] .' !important;';
		}
	
		// Border
		if ( true === array_key_exists( $preVar.'border-hover', $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$css .= ' border:'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'border-hover' ] .' !important;';
		} else {
			foreach ( array( 'top', 'right', 'bottom', 'left' ) as $position ) {
				if ( true === array_key_exists( $preVar.'border-hover-'.$position, $attrs['gutenaKitStyle']['cssJson'] ) ) {
					$css .= ' border-'.$position.':'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'border-hover-'.$position ] .' !important;';
				}
			}
		}
	
		// Border radius
		if ( true === array_key_exists( $preVar.'border-hover-radius', $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$css .= ' border-radius:'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'border-hover-radius' ] .' !important;';
		}
	
		// Box Shadow
		if ( true === array_key_exists( $preVar.'boxshadow-hover', $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$css .= 'box-shadow:'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'boxshadow-hover' ] .' !important;';
		}
	
		if ( ! empty( $css ) ) {
			$attrs['gutenaKitCSS']['generatedCss'] .= $gk_id .':hover {' . $css . '}';
		}
		/************************
		 Block hover : END
		**************************/
	
		// Link color
		if ( true === array_key_exists( $preVar.'linkdecorationline', $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$attrs['gutenaKitCSS']['generatedCss'] .= $gk_id .' a {
				text-decoration-line:'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'linkdecorationline' ] .' !important;
			}';
		}
	
		// Link color
		if ( true === array_key_exists( $preVar.'color-normal-link', $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$attrs['gutenaKitCSS']['generatedCss'] .= $gk_id .' a {
				color:'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'color-normal-link' ] .' !important;
			}';
		}
	
		// Link hover color
		if ( true === array_key_exists( $preVar.'color-hover-link', $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$attrs['gutenaKitCSS']['generatedCss'] .= $gk_id .' a:hover {
				color:'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'color-hover-link' ] .' !important;
			}';
		}
	
		// overlay color
		if ( true === array_key_exists( $preVar.'overlay-normal-background', $attrs['gutenaKitStyle']['cssJson'] ) && true === array_key_exists( $preVar.'overlay-normal-opacity', $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$attrs['gutenaKitCSS']['generatedCss'] .= $gk_id .':before {
				content:"";
				background:'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'overlay-normal-background' ] .'; 
				opacity: '. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'overlay-normal-opacity' ] .';
				position: absolute;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
			}';
		}
	
		// overlay color
		if ( true === array_key_exists( $preVar.'overlay-hover-background', $attrs['gutenaKitStyle']['cssJson'] ) && true === array_key_exists( $preVar.'overlay-hover-opacity', $attrs['gutenaKitStyle']['cssJson'] ) ) {
			$attrs['gutenaKitCSS']['generatedCss'] .= $gk_id .':hover:before {
				content:"";
				background:'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'overlay-hover-background' ] .'; 
				opacity: '. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'overlay-hover-opacity' ] .';
				position: absolute;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
			}';
		}
	
		/************************
		 Block Tablet : START
		**************************/
		
			$css = '';
			//Hide in Tablet
			if ( true === array_key_exists( $preVar.'display-tablet', $attrs['gutenaKitStyle']['cssJson'] ) ) {
				$css .= ' display: '.$attrs['gutenaKitStyle']['cssJson'][ $preVar.'display-tablet' ].';';
			} else {
	
				//spacing
				foreach ( array( 'margin', 'padding' ) as $spacing ) {
					foreach ( array( 'top', 'right', 'bottom', 'left' ) as $position ) {
						if ( true === array_key_exists( $preVar.'tablet-'.$spacing.'-'.$position, $attrs['gutenaKitStyle']['cssJson'] ) ) {
							$css .= ' '.$spacing.'-'.$position.':'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'tablet-'.$spacing.'-'.$position ] .' !important;';
						}
					}
				}
	
				if ( empty( $global_typography ) ) {
					//typography
					foreach ( array( 'font-size', 'line-height' ) as $font_property ) {
						if ( true === array_key_exists( $preVar.'t-'.$font_property, $attrs['gutenaKitStyle']['cssJson'] ) ) {
							$css .= ' '.$font_property.':'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'t-'.$font_property ] .' !important;';
						}
					}
				}
	
				//translate3d in tablet
				if ( true === array_key_exists( $preVar.'translate3d-tablet', $attrs['gutenaKitStyle']['cssJson'] ) ) {
					$css .= ' transform: '.$attrs['gutenaKitStyle']['cssJson'][ $preVar.'translate3d-tablet' ].';';
				}           
}
	
			if ( ! empty( $css ) ) {
				$css = $gk_id .' {' . $css . '} ';
			}
			//$attrs['gutenaKitCSS']['generatedCss'] .= '@media only screen and (min-width: 768px) and (max-width: '.$media_query_tab.') { .' . $gk_id .' {';
	
			// global typography for mobile
			if ( ! empty( $global_typography ) && ! empty( $global_typography_array[ $global_typography ]['cssJson'] ) ) {
				$css .= '.has-gutenakit-gt-'.$global_typography.'{';
				//typography
				foreach ( array( 'font-size', 'line-height' ) as $font_property ) {
					if ( true === array_key_exists( $preVar.'gt-t-'.$font_property, $global_typography_array[ $global_typography ]['cssJson'] ) ) {
						$css .= ' '.$font_property.':'. $global_typography_array[ $global_typography ]['cssJson'][ $preVar.'gt-t-'.$font_property ] .' !important;';
					}
				}
				$css .= '}'; 
			}
	
			if ( ! empty( $css ) ) {
				$attrs['gutenaKitCSS']['generatedCss'] .= '@media only screen and (min-width: 768px) and (max-width: '.$media_query_tab.') { ' . $css . '} ';
			}
		/************************
		 Block Tablet : END
		**************************/
	
		/************************
		 Block Mobile : START
		**************************/
			$css = '';
			//Hide in Mobile
			if ( true === array_key_exists( $preVar.'display-mobile', $attrs['gutenaKitStyle']['cssJson'] ) ) { 
				$css .= ' display: '.$attrs['gutenaKitStyle']['cssJson'][ $preVar.'display-mobile' ].';';
			} else {
				//spacing
				foreach ( array( 'margin', 'padding' ) as $spacing ) {
					foreach ( array( 'top', 'right', 'bottom', 'left' ) as $position ) {
						if ( true === array_key_exists( $preVar.'mobile-'.$spacing.'-'.$position, $attrs['gutenaKitStyle']['cssJson'] ) ) {
							$css .= ' '.$spacing.'-'.$position.':'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'mobile-'.$spacing.'-'.$position ] .' !important;';
						}
					}
				}
	
				if ( empty( $global_typography ) ) {
					//typography
					foreach ( array( 'font-size', 'line-height' ) as $font_property ) {
						if ( true === array_key_exists( $preVar.'m-'.$font_property, $attrs['gutenaKitStyle']['cssJson'] ) ) {
							$css .= ' '.$font_property.':'. $attrs['gutenaKitStyle']['cssJson'][ $preVar.'m-'.$font_property ] .' !important;';
						}
					}
				}
	
				//translate3d in mobile
				if ( true === array_key_exists( $preVar.'translate3d-mobile', $attrs['gutenaKitStyle']['cssJson'] ) ) {
					$css .= ' transform: '.$attrs['gutenaKitStyle']['cssJson'][ $preVar.'translate3d-mobile' ].';';
				}
			}
	
			if ( ! empty( $css ) ) {
				$css = $gk_id .' {' . $css . '} ';
			}
	
			// global typography for mobile
			if ( ! empty( $global_typography ) && ! empty( $global_typography_array[ $global_typography ]['cssJson'] ) ) {
				$css .= '.has-gutenakit-gt-'.$global_typography.'{';
				//typography
				foreach ( array( 'font-size', 'line-height' ) as $font_property ) {
					if ( true === array_key_exists( $preVar.'gt-m-'.$font_property, $global_typography_array[ $global_typography ]['cssJson'] ) ) {
						$css .= ' '.$font_property.':'. $global_typography_array[ $global_typography ]['cssJson'][ $preVar.'gt-m-'.$font_property ] .' !important;';
					}
				}
				$css .= '}'; 
			}
	
			if ( ! empty( $css ) ) {
				$attrs['gutenaKitCSS']['generatedCss'] .= '@media only screen and (max-width: '.$media_query_mobile .') { '  . $css . '} ';
			}
		/************************
		 Block Mobile : END
		**************************/
		return $attrs;
		
	}

	// Print style css and global typography once only 
	private function enqueue_block_control_css( $attrs ) {
		$attrs = $this->get_gutena_kit_settings_css( $attrs );

		if ( ! empty( $attrs['gutenaKitID'] ) && ! empty( $attrs['gutenaKitCSS'] ) ) {
			$this->enqueue_block_gutenakit_styles( $attrs['gutenaKitCSS'] , 11 );
		}
	}

	/**
	 * Render Block customization call on render_block filter
	 */
	public function render_block_customization( $block_content, $block ) {
		
		if ( ! empty( $block['attrs']['gutenaKitID'] ) && ( ! empty( $block['attrs']['gutenaKitCSS'] ) || ! empty( $block['attrs']['gutenaKitStyle'] ) ) ) {
			
			//get block class
			$block_classes = ( ! empty( $block['attrs']['gutenaKitClass'] ) && ! empty( $block['attrs']['gutenaKitClass']['blockClasses'] ) ) ? $block['attrs']['gutenaKitClass']['blockClasses'] : '';

			$block_classes = gutenakit_block_id_classname_prefix() . $block['attrs']['gutenaKitID'] . ' '.$block_classes;

			//check if block content has class and its position must inside first html element
			$class_pos = strpos( $block_content, 'class="' );
			if ( false === $class_pos || $class_pos > strpos( $block_content, '>' ) ) {
				//if class not exists add class attribute
				$block_content = preg_replace(
					'/' . preg_quote( '>', '/' ) . '/',
					' class="' . esc_attr( $block_classes ) . '" > ',
					$block_content,
					1
				);
			} else {
				//add classes inside class attribute
				$block_content = preg_replace(
					'/' . preg_quote( 'class="', '/' ) . '/',
					'class="' . esc_attr( $block_classes ) . ' ',
					$block_content,
					1
				);
			}
			
			// Enqueue block css
			$this->enqueue_block_control_css( $block['attrs'] );
		}

		// print styles to head for advance button block variation
		if ( ! empty( $block['blockName'] ) && ! empty( $block['attrs'] ) ) {
			add_action(
				'wp_head',
				function() use ( $block ) {
					$attributes = $block['attrs'];
					$prefix     = str_replace( '/', '-', str_replace( 'core', 'gutena-advanced', $block['blockName'] ) );
	
					if ( ! empty( $attributes['blockStyles'] ) && is_array( $attributes['blockStyles'] ) ) {
						// print css
						printf(
							'<style id="' . $prefix . '-block-inline-css-%1$s">.' . $prefix . '-%1$s { %2$s }</style>',
							$attributes['uniqueId'],
							$this->render_css( $attributes['blockStyles'] ),
						);
					}
	
					if ( 'core/button' === $block['blockName'] && ! empty( $attributes['btnIconSVG'] ) ) { ?>
						<style id="gutena-advanced-button-inline-css-<?php esc_attr_e( $attributes['uniqueId'] ); ?>">
							.<?php esc_attr_e( $prefix ); ?>-<?php esc_attr_e( $attributes['uniqueId'] ); ?>.has-icon .wp-block-button__link:<?php esc_attr_e( $attributes['btnIconPosition'] ); ?> {
								content: '';
								-webkit-mask-image: url( 'data:image/svg+xml; utf8, <?php echo $attributes['btnIconSVG']; ?>' );
								mask-image: url( 'data:image/svg+xml; utf8, <?php echo $attributes['btnIconSVG']; ?>' );
							}
						</style>
						<?php
					}
				}
			);
		}
		
		return $block_content;
	}

	// enqueue block styles saved by gutena kit
	private function enqueue_block_gutenakit_styles( $style, $priority = 10 ) {
		if ( empty( $style ) || ! is_array( $style ) || ! function_exists( 'wp_strip_all_tags' ) ) {
			return;
		}

		// Join all css
		$style = implode( ' ', array_values( $style ) );

		// Filter Css
		$style = apply_filters( 'gutena_kit_block_style', $style );

		$action_hook_name = 'wp_footer';
		if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) {
			$action_hook_name = 'wp_head';
		}
		add_action(
			$action_hook_name,
			static function () use ( $style ) {
				echo '<style>' . wp_strip_all_tags( $style ) . "</style>\n";
			},
			$priority
		);
	}

	// Register Gutena category if not exists
	public function register_block_category( $block_categories, $editor_context ) {
		$fields = wp_list_pluck( $block_categories, 'slug' );

		if ( ! empty( $editor_context->post ) && ! in_array( 'gutena', $fields, true ) ) {
			array_push(
				$block_categories,
				array(
					'slug'  => 'gutena',
					'title' => __( 'Gutena', 'gutena-forms' ),
				)
			);
		}

		return $block_categories;
	}

	/**
	 * Generate dynamic styles
	 *
	 * @param array $styles
	 * @return string
	 */
	private function render_css( $styles ) {
		$style = array();
		foreach ( (array) $styles as $key => $value ) {
			$style[] = $key . ': ' . $value;
		}

		return join( ';', $style );
	}
}