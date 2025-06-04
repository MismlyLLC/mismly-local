<?php 
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'gutendkit_extract_css_from_block' ) ) {
	function gutendkit_extract_css_from_block( $blocks ) {
		$gutenakit_ids = array();
		$gutenakit_css = '';
		$gutenakit_id = '';//for duplicateIDs
		$duplicate_blocks = [];
		//Consisting block loop
		foreach ( $blocks as $block ) {
			
			if ( ! empty( $block['attrs'] ) && ! empty( $block['attrs']['gutenaKitID'] ) && ! empty( $block['attrs']['gutenaKitCSS'] ) ) {
				
				if ( ! in_array( $block['attrs']['gutenaKitID'], $gutenakit_ids, true ) ) {
					$gutenakit_ids[] = $block['attrs']['gutenaKitID'];    
				}else {
					 $duplicate_blocks[] = $block;
				}
				//collect css content
				foreach ( $block['attrs']['gutenaKitCSS'] as $css ) {
					if ( ! empty( $css ) ) {
						$gutenakit_css .= ' .'.gutenakit_block_id_classname_prefix().$block['attrs']['gutenaKitID'].' '.$css.' ';
					}
				}           
			}

			if ( ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
				$res = gutendkit_extract_css_from_block( $block['innerBlocks'] );
				$gutenakit_css .= $res['gutenakit_css'];
				$gutenakit_ids = array_merge( $gutenakit_ids,$res['gutenakit_ids'] );
				$duplicate_blocks = array_merge( $duplicate_blocks,$res['duplicate_blocks'] );
			}
		}
		return array(
			'gutenakit_ids'    => $gutenakit_ids,
			'gutenakit_css'    => $gutenakit_css,
			'duplicate_blocks' => $duplicate_blocks,
		);
	}
}

//demo category
if ( ! function_exists( 'gutendkit_demo_category_list' ) ) {
	function gutendkit_demo_category_list( ) {
		return array(
			'agency',
			'business',
			'blog',
		);
	}
}
//Demo List
if ( ! function_exists( 'gutendkit_categorize_demo_list' ) ) {
	function gutendkit_categorize_demo_list() {
		$demos = array();
		if ( function_exists( 'wp_json_file_decode' ) ) { 
			
			$demo_main = array(
				'creatives'    => array(
					'fontFamily' => array(
						'Outfit',
						'Inter',
					),
					'colors'     => array(
						'#0DA88C',
						'#21222F',
						'#484952',
						'#E2F2EF',
						'#F6B642',
						'#015D61',
					),
				),
				'seo'    => array(
					'fontFamily' => array(
						'Nunito',
					),
					'colors'     => array(
						'#F4AB00',
						'#5B25B3',
						'#2C1258',
						'#604B83',
						'#ECE1FF',
					),
				),
				'architecture' => array(
					'fontFamily' => array(
						'Urbanist',
					),
					'colors'     => array(
						'#FAB702',
						'#252740',
						'#575B7A',
						'#E7F0FF',
						'#FFFFFF',
					),
				),
				'consulting'   => array(
					'fontFamily' => array(
						'Outfit',
						'Inter',
					),
					'colors'     => array(
						'#FF7D3E',
						'#165153',
						'#293340',
						'#4C4C65',
						'#FFF2EC',
					),
				),
				'agency'       => array(
					'fontFamily' => array(
						'Manrope',
						'Inter',
					),
					'colors'     => array(
						'#E7694E',
						'#202020',
						'#555555',
						'#FFF3F0',
						'#FFFFFF',
					),
				),
				'business'     => array(
					'fontFamily' => array(
						'Manrope',
						'Inter',
					),
					'colors'     => array(
						'#3F6DE4',
						'#252740',
						'#575B7A',
						'#E7F0FF',
						'#FFFFFF',
					),
				),
				'saas_company' => array(
					'fontFamily' => array(
						'Inter',
					),
					'colors'     => array(
						'#3F6DE4',
						'#252740',
						'#575B7A',
						'#E7F0FF',
						'#FFFFFF',
					),
				),
			);

			//Create demos details array 
			foreach ( $demo_main as $demo_slug => $demo_custom_details ) {
				if ( file_exists( GUTENA_KIT_DIR_PATH . 'includes/demo-import/demo-files/'.$demo_slug.'/settings.json' ) && is_array( $demo_custom_details ) ) {
					$demos[ $demo_slug ] = array(
						'default' => array_merge(
							wp_json_file_decode( GUTENA_KIT_DIR_PATH . 'includes/demo-import/demo-files/'.$demo_slug.'/settings.json', array( 'associative' => true ) ),
							$demo_custom_details
						),
					);
				}
			}       
}

		return $demos;
	}
}

//demo deatils list OR make an array of all demos 
if ( ! function_exists( 'gutendkit_demo_deatils_list' ) ) {
	function gutendkit_demo_deatils_list( $index = false ) {
		$demos = gutendkit_categorize_demo_list();

		if ( empty( $demos ) ) {
			return $demos;
		}

		$demo = array();
		
		foreach ( $demos as $demo_array ) {
			foreach ( $demo_array as $demo_details ) {
				$demo[] = $demo_details;
			}
		}
		return (false === $index ) ? $demo : $demo[ $index ];
	}
}

//Check if gutena theme activated
if ( ! function_exists( 'is_gutena_theme_activated' ) ) {
	function is_gutena_theme_activated() {
		$theme = wp_get_theme();
		return ! ( false === stripos( $theme->get( 'Name' ), 'gutena' ) );
	}
}

//file_get_contents
if ( ! function_exists( 'gutenakit_file_get_contents' ) ) {
	function gutenakit_file_get_contents( $url ) {
		$response = wp_remote_get( esc_url_raw( $url ) );
		return ( ! is_wp_error( $response ) ) ? wp_remote_retrieve_body( $response ) : '';
	}
}

//file_put_contents
if ( ! function_exists( 'gutenakit_file_put_contents' ) ) {
	function gutenakit_file_put_contents( $file_path, $contents ) {

		if ( 'direct' === get_filesystem_method() ) {
			/* you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL */
			$creds = request_filesystem_credentials( wp_nonce_url( admin_url() ), '', false, false, array() );
		
			/* initialize the API */
			if ( ! WP_Filesystem( $creds ) ) {
				/* any problems and we exit */
				return false;
			}   
		
			global $wp_filesystem;
			/* do our file manipulations below */
			return $wp_filesystem->put_contents( $file_path, $contents );

		} else {
			return false;
		}
	}
}