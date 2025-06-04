<?php 
/**
 * Class used for install required plugins and setup demo content
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Merlin' ) || class_exists( 'GutenakitDemoSetup' ) ) {
	return;
}

class GutenakitDemoSetup extends Merlin{

	/**
	 * The flag, to mark, if the child theme step should be enabled.
	 *
	 * @var boolean $child_theme_step_enabled
	 */
	protected $child_theme_step_enabled = false;

	/**
	 * The flag, to mark, if gutena theme activared 
	 */
	protected $is_gutena_theme_activated = false;
    /**
	 * Constructor
	 */
	function __construct( $config = array(), $strings = array() ) {

        parent::__construct( $config, $strings );
		$this->set_required_plugins();
        $this->remove_actions();
        $this->add_actions();
        $this->add_filters();
		$this->child_theme_step_enabled   = $config['child_theme_step_enabled'];
		$this->is_gutena_theme_activated = $config['is_gutena_theme_activated'];
	}

    private function get_import_files_details_list() {
		return $this->import_files;
	}

    private function remove_actions(){
        if ( function_exists( 'remove_action' ) ) {
            remove_action( 'after_switch_theme', array( $this, 'switch_theme' ) );
            remove_action( 'admin_init', array( $this, 'steps' ), 30, 0 );
        }
    }

    private function add_actions(){
        add_action( 'admin_init', array( $this, 'steps' ), 20, 0 );
		//Execute before Import
		add_action( 'import_start', array( $this, 'before_import_reset' ) );
        //Execute custom code after the whole import has finished
        add_action( 'merlin_after_all_import', array( $this, 'after_import_setup' ));
    }

    private function add_filters(){
        //Add demos details list for import
        add_filter( 'merlin_import_files', array( $this, 'local_import_files' ) );
    }
    
   /**
	 * Setup steps.
	 */
	public function steps() {

		$this->steps = array(
			'welcome' => array(
				'name'    => esc_html__( 'Welcome', 'gutena-kit' ),
				'view'    => array( $this, 'welcome' ),
				'handler' => array( $this, 'welcome_handler' ),
			),
		);

		if ( $this->child_theme_step_enabled ) {
			$this->steps['child'] = array(
				'name' => esc_html__( 'Child', 'gutena-kit' ),
				'view' => array( $this, 'child' ),
			);
		}

		if ( $this->license_step_enabled ) {
			$this->steps['license'] = array(
				'name' => esc_html__( 'License', 'gutena-kit' ),
				'view' => array( $this, 'license' ),
			);
		}

		// Show the plugin importer, only if TGMPA is included.
		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			$this->steps['plugins'] = array(
				'name' => esc_html__( 'Plugins', 'gutena-kit' ),
				'view' => array( $this, 'plugins' ),
			);
		}

		// Show the content importer, only if there's demo content added.
		if ( ! empty( $this->import_files ) ) {
			$this->steps['content'] = array(
				'name' => esc_html__( 'Content', 'gutena-kit' ),
				'view' => array( $this, 'content' ),
			);
		}

		$this->steps['ready'] = array(
			'name' => esc_html__( 'Ready', 'gutena-kit' ),
			'view' => array( $this, 'ready' ),
		);

		$this->steps = apply_filters( $this->theme->template . '_merlin_steps', $this->steps );
	}

	private function set_required_plugins() {
		if ( ! isset( $_GET['demo_index'] ) || ! is_numeric( $_GET['demo_index'] ) ) {
			return;
		}

		$selected_import_index = (int) sanitize_text_field( wp_unslash( $_GET['demo_index'] ) );

		//set_transient for required plugins for tgmpa plugin registeration 
		$demo = gutendkit_demo_deatils_list( $selected_import_index );
		
		if ( ! empty( $demo ) && ! empty( $demo['required_plugins'] ) && is_array( $demo['required_plugins'] ) ) {
			//set_transient for 30 minutes
			set_transient( 'gutenakit_demo_required_plugins', $demo['required_plugins'], 1800 );
		}
	}

    /**
	 * Page setup
	 */
	protected function content() {
		if ( ! is_gutenakit_admin() ) {
			return;
		}
		
        $selected_import_index = ( isset( $_GET['demo_index'] ) && is_numeric( $_GET['demo_index'] ) ) ? (int) sanitize_text_field( wp_unslash( $_GET['demo_index'] ) ) : 0;
		$import_info = $this->get_import_data_info( $selected_import_index );

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Text strings.
		$header    = $strings['import-header'];
		$paragraph = $strings['import'];
		$action    = $strings['import-action-link'];
		$skip      = $strings['btn-skip'];
		$next      = $strings['btn-next'];
		$import    = $strings['btn-import'];

		$multi_import = ( 1 < count( $this->import_files ) ) ? 'is-multi-import' : null;
		?>

		<div class="merlin__content--transition">

			<?php echo wp_kses( $this->svg( array( 'icon' => 'content' ) ), $this->svg_allowed_html() ); ?>

			<svg class="icon icon--checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
				<circle class="icon--checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="icon--checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
			</svg>

			<h1><?php echo esc_html( $header ); ?></h1>

			<p><?php echo esc_html( $paragraph ); ?></p>

			<?php if ( 1 < count( $this->import_files ) ) : ?>	
				<div class="merlin__select-control-wrapper">

					<select class="merlin__select-control js-merlin-demo-import-select">
						<?php foreach ( $this->import_files as $index => $import_file ) : ?>
							<option value="<?php echo esc_attr( $index ); ?>" <?php echo ( $selected_import_index === $index) ? 'selected' : ''; ?> ><?php echo esc_html( $import_file['import_file_name'] ); ?></option>
						<?php endforeach; ?>
					</select>

					<div class="merlin__select-control-help">
						<span class="hint--top" aria-label="<?php echo esc_attr__( 'Select Demo', 'gutena-kit' ); ?>">
							<?php echo wp_kses( $this->svg( array( 'icon' => 'downarrow' ) ), $this->svg_allowed_html() ); ?>
						</span>
					</div>
				</div>
			<?php endif; ?>

			<a id="merlin__drawer-trigger" class="merlin__button merlin__button--knockout"><span><?php echo esc_html( $action ); ?></span><span class="chevron"></span></a>

		</div>

		<form action="" method="post" class="<?php echo esc_attr( $multi_import ); ?>">

			<ul class="merlin__drawer merlin__drawer--import-content js-merlin-drawer-import-content">
				<?php foreach ( $import_info as $slug => $available ) : ?>
					<?php
					if ( ! $available ) {
						continue;
					}
					?>

					<li class="merlin__drawer--import-content__list-item status status--Pending" data-content="<?php echo esc_attr( $slug ); ?>">
						<input type="checkbox" name="default_content[<?php echo esc_attr( $slug ); ?>]" class="checkbox checkbox-<?php echo esc_attr( $slug ); ?>" id="default_content_<?php echo esc_attr( $slug ); ?>" value="1" checked>
						<label for="default_content_<?php echo esc_attr( $slug ); ?>">
							<i></i><span><?php echo esc_html( ucfirst( str_replace( '_', ' ', $slug ) ) ); ?></span>
						</label>
					</li>

				<?php endforeach; ?>
			</ul>
			
			<p><?php 
			esc_html_e( "Please note that your existing global styles and templates will be overwritten. If you have already customized global styles and templates, then it may be wise to take a backup.", "gutena-kit" ); 
			 ?></p>

			<footer class="merlin__content__footer">

				<a id="close" href="<?php echo esc_url( $this->step_next_link() ); ?>" class="merlin__button merlin__button--skip merlin__button--closer merlin__button--proceed"><?php echo esc_html( $skip ); ?></a>

				<a id="skip" href="<?php echo esc_url( $this->step_next_link() ); ?>" class="merlin__button merlin__button--skip merlin__button--proceed"><?php echo esc_html( $skip ); ?></a>

				<a href="<?php echo esc_url( $this->step_next_link() ); ?>" class="merlin__button merlin__button--next button-next" data-callback="install_content">
					<span class="merlin__button--loading__text"><?php echo esc_html( $import ); ?></span>

					<div class="merlin__progress-bar">
						<span class="js-merlin-progress-bar"></span>
					</div>

					<span class="js-merlin-progress-bar-percentage">0%</span>
				</a>

				<?php wp_nonce_field( 'merlin' ); ?>
			</footer>
		</form>

	<?php
		$this->logger->debug( __( 'The content import step has been displayed', 'gutena-kit' ) );
	}

	/*Before Demo import reset or clear customization i.e. custom templates and template_part*/
	public function before_import_reset(){
		if ( $this->is_gutena_theme_activated ) {
			//Reset Templates: post_type:wp_template
			$this->reset_templates( array(), 'wp_template' );
			//Reset post_type:wp_template_part
			$this->reset_templates( array(), 'wp_template_part' );
		}
	}
    
	/*After Demo Import Set Home Page*/
    public function after_import_setup( $index ) {
	   //delete required plugin transient
	   delete_transient( 'gutenakit_demo_required_plugins' );
	   //get demo details
	   $demo = gutendkit_demo_deatils_list( $index );
	   $main_menu = empty( $demo['main_menu'] ) ? 'Main Menu' : sanitize_text_field( $demo['main_menu'] );
	   $home_page = empty( $demo['home_page'] ) ? 'Home' : sanitize_text_field( $demo['home_page'] );
	   $blog_page = empty( $demo['blog_page'] ) ? 'Blog' : sanitize_text_field( $demo['blog_page'] );
	   $site_logo = empty( $demo['site_logo'] ) ? 'logo' : sanitize_text_field( $demo['site_logo'] );
	   
       // Assign menus to their locations.
       $main_menu = get_term_by( 'name', $main_menu, 'nav_menu' );
   
	   if ( ! empty( $main_menu ) && ! empty( $main_menu->term_id ) ) {
			set_theme_mod(
				'nav_menu_locations', array(
					'main_nav'  => $main_menu->term_id,
					'main-menu' => $main_menu->term_id,
				)
			);
		}

		//set site logo
		if ( ! empty( $demo['site_logo'] ) ) {
			$site_logo = get_page_by_title( $demo['site_logo'], OBJECT, 'attachment' );
			if ( ! empty( $site_logo ) && ! empty( $site_logo->ID ) ) {
				set_theme_mod( 'custom_logo', $site_logo->ID );
			}
		}

		//set site icon
		if ( ! empty( $demo['site_icon'] ) ) {
			$site_icon = get_page_by_title( $demo['site_icon'], OBJECT, 'attachment' );
			if ( ! empty( $site_icon ) && ! empty( $site_icon->ID ) ) {
				update_option( 'site_icon', $site_icon->ID );
			}
		}
	   
       // Assign front page and posts page (blog page).
       $home_page = get_page_by_title( $home_page );
       $blog_page  = get_page_by_title( $blog_page );
   
       update_option( 'show_on_front', 'page' );

	   if ( ! empty( $home_page ) && ! empty( $home_page->ID ) ) {
			update_option( 'page_on_front', (int) $home_page->ID );
	   } elseif ( ! empty( $demo['demo_slug'] ) && 'blog' === $demo['demo_slug'] ) {
			update_option( 'page_on_front', 0 );
	   }
       
	   if ( ! empty( $blog_page ) && ! empty( $blog_page->ID ) ) {
       		update_option( 'page_for_posts', (int) $blog_page->ID );
	   }

	   //Set global styles
	   if ( ! empty( $demo['style_variation'] ) ) {
		$demo['style_variation'] = is_array( $demo['style_variation'] ) ? wp_json_encode( $demo['style_variation'] ) : $demo['style_variation'];
		$this->reset_set_global_styles( $demo['style_variation'] );
	   }

	    //Set global typography
		if ( ! empty( $demo['gutena_kit_global_typography'] ) && is_array( $demo['gutena_kit_global_typography'] ) ) {
			//check for multisite
			if ( defined( 'MULTISITE' ) && true === MULTISITE && function_exists( 'get_sites' ) && class_exists( 'WP_Site_Query' ) && function_exists( 'get_blog_option' ) && function_exists( 'get_current_blog_id' ) ) {
				//update current site option
				update_blog_option( get_current_blog_id(), 'gutena_kit_global_typography' , gutenakit_sanitize_array( $demo['gutena_kit_global_typography'] ) ) ;
			} else {
				update_option( 'gutena_kit_global_typography', gutenakit_sanitize_array( $demo['gutena_kit_global_typography'] ) );
			}
		}
	   
    }

	//Set OR Reset Global Styles
	private function reset_set_global_styles( $style_content=null ) {
		 /**Global Styles settings
		* https://developer.wordpress.org/reference/classes/wp_theme_json_resolver/get_user_data_from_wp_global_styles/
	    */
		require_once( ABSPATH . "wp-includes/class-wp-theme-json-resolver.php" );
		$style_post = WP_Theme_JSON_Resolver::get_user_data_from_wp_global_styles( wp_get_theme() );

		$post_content = json_decode( $style_post['post_content'] , true );
		
		if ( ! empty( $style_post ) && ! empty( $style_post['ID'] ) && is_numeric( $style_post['ID']) && $style_post['ID'] > 0 && ! empty( $style_post['post_type'] ) && 'wp_global_styles' === $style_post['post_type'] ) {
			if ( ! empty( $style_content) ) {
				//Set
				wp_update_post( array(
					'ID'           => $style_post['ID'],
					'post_content' => sanitize_text_field( $style_content ),
				));
			} elseif ( ! empty( $post_content['settings'] ) && $this->is_gutena_theme_activated ) {
				//Reset
				$post_content['settings'] = array();
				wp_update_post( array(
					'ID'           => $style_post['ID'],
					'post_content' => sanitize_text_field( wp_json_encode( $post_content ) ),
				));
			}
		}
	}

	
	//reset custom templates for gutena theme only
	private function reset_templates( $query_array, $template_type ) {
		if ( ! function_exists( 'get_block_templates' ) ) {
			return;
		}
		//get templates array
		$templates = get_block_templates( $query_array, $template_type );
		
		if ( ! empty( $templates) ) {
			foreach ( $templates as $template ) {
				if ( ! empty( $template->wp_id ) && is_numeric( $template->wp_id ) && $template->wp_id > 0 && ! empty( $template->id ) && 'custom' === $template->source && $template->type === $template_type ) {
					$parts = explode( '//', $template->id, 2 );
					if ( ! empty( $parts[0]) && 'gutena' === $parts[0] ) {
						wp_delete_post( $template->wp_id, true );
					}
				}
			}
		}
	}

    /**
     * Define the demo import files
     */
    public function local_import_files() {
        return gutendkit_demo_deatils_list();
    }
}