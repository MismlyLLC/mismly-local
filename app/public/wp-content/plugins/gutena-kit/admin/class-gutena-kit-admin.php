<?php
/**
 * The admin-specific functionality of the plugin.
 * @package    Gutena_Kit
 * @subpackage Gutena_Kit/admin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Gutena_Kit_Admin {

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
	 * Gutena theme slug.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $gutena_theme    Gutena theme slug.
	 */
	private $gutena_theme;

	/**
	 * Gutena block plugin list.
	 *
	 * @access   private
	 * @var      array    $gutena_plugins    The current version of this plugin.
	 */
	private $gutena_plugins;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $gutena_kit       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $gutena_kit, $version ) {
		$this->gutena_kit = $gutena_kit;
		$this->gutena_plugins = array( 
			'gutena-accordion', 
			'gutena-forms', 
			'newsletter-block-by-gutena',
			'photofeed-block-by-gutena',
			'gutena-lightbox',
			'gutena-tabs',
			'post-featured-tag-block-by-gutena',
			'gutena-star-ratings',
			'gutena-testimonial',
			'gutena-team',
		);
		$this->gutena_theme = 'gutena';
		$this->version        = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? time() : $version;
		$this->load_dependencies();
	}

	/**
	 * Load the required dependencies for admin area.
	 */
	private function load_dependencies() {
		/**
		 * Admin helper functions
		 */
		require_once GUTENA_KIT_DIR_PATH . 'includes/helpers/admin-helpers.php';
		//Add TGM for plugin activation
		require_once GUTENA_KIT_DIR_PATH . 'includes/demo-import/tgmpa/class-tgm-plugin-activation.php';
		//Merlin WP for demo import
		require_once GUTENA_KIT_DIR_PATH . 'includes/demo-import/merlin/class-merlin.php';
		//Extend and modify Merlin WP
		require_once GUTENA_KIT_DIR_PATH . 'includes/demo-import/class-gutenakit-demosetup.php';

		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			add_action( 'tgmpa_register', array( $this, 'gutena_kit_register_required_plugins' ) );
		}
		
		if ( ! class_exists( 'Merlin' ) || ! class_exists( 'GutenakitDemoSetup' ) ) {
			return;
		}
		//add admin gutena kit stylesheet on merlin demo import page
		if ( isset( $_GET['page'] ) && 'merlin' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) {
			add_action( 'admin_print_styles', array( $this, 'enqueue_styles' ) );
		}
		/**
		 * Set directory locations, text strings, and settings.
		 */
		global $gutenakit_merlin_class;
		$gutenakit_merlin_class = new GutenakitDemoSetup(
		
			$config = array(
				'base_path'                 => GUTENA_KIT_DIR_PATH . 'includes/demo-import',
				'base_url'                  => GUTENA_KIT_PLUGIN_URL.'includes/demo-import',
				'directory'                 => 'merlin', //where Merlin WP is placed.
				'merlin_url'                => 'merlin', // The wp-admin page slug where Merlin WP loads.
				'parent_slug'               => 'options.php', // The wp-admin parent page slug for the admin menu item.
				'capability'                => 'manage_options', // The capability required for this menu to be displayed to the user.
				'child_action_btn_url'      => '', // URL for the 'child-action-link'.
				'dev_mode'                  => true, // Enable development mode for testing.
				'license_step'              => false, // EDD license activation step.
				'license_required'          => false, // Require the license activation step.
				'license_help_url'          => '', // URL for the 'license-tooltip'.
				'edd_remote_api_url'        => '', // EDD_Theme_Updater_Admin remote_api_url.
				'edd_item_name'             => '', // EDD_Theme_Updater_Admin item_name.
				'edd_theme_slug'            => '', // EDD_Theme_Updater_Admin item_slug.
				'ready_big_button_url'      => function_exists( 'get_bloginfo' ) ? get_bloginfo( 'url' ) : '', // Link for the big button on the ready step.
				//Added key values by gutenakit
				'child_theme_step_enabled'  => false,
				'is_gutena_theme_activated' => is_gutena_theme_activated(),
			),
			$strings = array(
				'admin-menu'               => esc_html__( 'Demo Setup', 'gutena-kit' ),
		
				/* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
				'title%s%s%s%s'            => esc_html__( '%1$s%2$s Demo &lsaquo; Demo Setup: %3$s%4$s', 'gutena-kit' ),
				'return-to-dashboard'      => esc_html__( 'Return to the dashboard', 'gutena-kit' ),
				'ignore'                   => esc_html__( 'Disable this wizard', 'gutena-kit' ),
		
				'btn-skip'                 => esc_html__( 'Skip', 'gutena-kit' ),
				'btn-next'                 => esc_html__( 'Next', 'gutena-kit' ),
				'btn-start'                => esc_html__( 'Start', 'gutena-kit' ),
				'btn-no'                   => esc_html__( 'Cancel', 'gutena-kit' ),
				'btn-plugins-install'      => esc_html__( 'Install', 'gutena-kit' ),
				'btn-child-install'        => esc_html__( 'Install', 'gutena-kit' ),
				'btn-content-install'      => esc_html__( 'Install', 'gutena-kit' ),
				'btn-import'               => esc_html__( 'Import', 'gutena-kit' ),
				'btn-license-activate'     => esc_html__( 'Activate', 'gutena-kit' ),
				'btn-license-skip'         => esc_html__( 'Later', 'gutena-kit' ),
		
				/* translators: Theme Name */
				'license-header%s'         => esc_html__( 'Activate %s', 'gutena-kit' ),
				/* translators: Theme Name */
				'license-header-success%s' => esc_html__( '%s is Activated', 'gutena-kit' ),
				/* translators: Theme Name */
				'license%s'                => esc_html__( 'Enter your license key to enable remote updates and theme support.', 'gutena-kit' ),
				'license-label'            => esc_html__( 'License key', 'gutena-kit' ),
				'license-success%s'        => esc_html__( 'The theme is already registered, so you can go to the next step!', 'gutena-kit' ),
				'license-json-success%s'   => esc_html__( 'Your theme is activated! Remote updates and theme support are enabled.', 'gutena-kit' ),
				'license-tooltip'          => esc_html__( 'Need help?', 'gutena-kit' ),
		
				/* translators: Theme Name */
				'welcome-header%s'         => esc_html__( 'Welcome to %s', 'gutena-kit' ),
				'welcome-header-success%s' => esc_html__( 'Welcome to Gutena Kit', 'gutena-kit' ),
				'welcome%s'                => esc_html__( 'This wizard will set up your theme, install plugins, and import content. It is optional & should take only a few minutes.', 'gutena-kit' ),
				'welcome-success%s'        => esc_html__( 'You may have already run this demo setup wizard. If you would like to proceed anyway, click on the "Start" button below.', 'gutena-kit' ),
		
				'child-header'             => esc_html__( 'Install Child Theme', 'gutena-kit' ),
				'child-header-success'     => esc_html__( 'You\'re good to go!', 'gutena-kit' ),
				'child'                    => esc_html__( 'Let\'s build & activate a child theme so you may easily make theme changes.', 'gutena-kit' ),
				'child-success%s'          => esc_html__( 'Your child theme has already been installed and is now activated, if it wasn\'t already.', 'gutena-kit' ),
				'child-action-link'        => esc_html__( 'Learn about child themes', 'gutena-kit' ),
				'child-json-success%s'     => esc_html__( 'Awesome. Your child theme has already been installed and is now activated.', 'gutena-kit' ),
				'child-json-already%s'     => esc_html__( 'Awesome. Your child theme has been created and is now activated.', 'gutena-kit' ),
		
				'plugins-header'           => esc_html__( 'Install Plugins', 'gutena-kit' ),
				'plugins-header-success'   => esc_html__( 'You\'re up to speed!', 'gutena-kit' ),
				'plugins'                  => esc_html__( 'Let\'s install some essential WordPress plugins to get your site up to speed.', 'gutena-kit' ),
				'plugins-success%s'        => esc_html__( 'The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.', 'gutena-kit' ),
				'plugins-action-link'      => esc_html__( 'Advanced', 'gutena-kit' ),
		
				'import-header'            => esc_html__( 'Import Content', 'gutena-kit' ),
				'import'                   => esc_html__( 'Let\'s import content to your website, to help you get familiar with the theme.', 'gutena-kit' ),
				'import-action-link'       => esc_html__( 'Advanced', 'gutena-kit' ),
		
				'ready-header'             => esc_html__( 'All done. Have fun!', 'gutena-kit' ),
		
				/* translators: Theme Author */
				'ready%s'                  => esc_html__( 'Your demo has been all set up. Enjoy your new demo by %s.', 'gutena-kit' ),
				'ready-action-link'        => esc_html__( 'Extras', 'gutena-kit' ),
				'ready-big-button'         => esc_html__( 'View your website', 'gutena-kit' ),
				'ready-link-1'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://wordpress.org/support/', esc_html__( 'Explore WordPress', 'gutena-kit' ) ),
				'ready-link-2'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://wordpress.org/support/theme/gutena/', esc_html__( 'Get Theme Support', 'gutena-kit' ) ),
				'ready-link-3'             => sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'customize.php' ), esc_html__( 'Start Customizing', 'gutena-kit' ) ),
			)
		);
	}

	/**
	 * Register required plugins
	 */
	public function gutena_kit_register_required_plugins(){
		if ( ! function_exists( 'tgmpa' ) || ! function_exists( 'get_transient' ) ) {
			return;
		}

		//Get required plugin list for demo
		$plugins = get_transient( 'gutenakit_demo_required_plugins' );

		if ( empty( $plugins ) || ! is_array( $plugins ) ) {
			return;
		}

		$config = array(
			'id'           => 'gutena-kit',                 // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'plugins.php',            // Parent menu slug.
			'capability'   => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => false,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.

		);

		tgmpa( $plugins, $config );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		//Add css for merlin importer
        wp_add_inline_style( 'merlin', '
		.merlin__content--transition svg{
			max-width: 150px;
			margin: auto;
		}
		' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook_suffix ) {
		wp_enqueue_script( $this->gutena_kit, GUTENA_KIT_PLUGIN_URL . 'admin/js/gutena-kit-admin.js', array(), $this->version, false );
	}

	//On activate plugin redirect admin to gutena kit on boarding : not for MULTISITE
	public function gutenakit_onboarding_redirect( $plugin, $network_activation ) {
		if ( 
			( empty( $_GET['rest_route'] ) || '/wp/v2/plugins/gutena-kit/gutena-kit' !==  sanitize_url( $_GET['rest_route'] ) ) && 
			( empty( $_POST['slug'] ) || 'gutena-kit' !==  sanitize_key( $_POST['slug'] ) )  && 
			! empty( $plugin ) && 'gutena-kit/gutena-kit.php' === $plugin &&
			function_exists( 'is_multisite' ) && ! is_multisite() &&
			! empty( get_option( 'gutenakit_onboarding', 1 ) ) && 
			function_exists( 'wp_safe_redirect' ) && 
			function_exists( 'admin_url' ) && 
			wp_safe_redirect( esc_url( admin_url( 'themes.php?page=gutenakit_admin_dashboard' ) ) ) ) {
			exit;
		}
	}

	//Adds a Gutena submenu page to the Appearance main menu 
	public function add_admin_menu(){
		//remove theme gutena menu
		remove_submenu_page( 'themes.php', 'gutena-theme-dashboard' );
		
		$page_hook_suffix = add_submenu_page( 'themes.php', 'Gutena', 'Gutena', 'manage_options', 'gutenakit_admin_dashboard', array( $this, 'demo_import_page' ));

		if ( ! empty( $page_hook_suffix ) ) {
			add_action( 'admin_print_styles-' . $page_hook_suffix, array( $this, 'demo_import_dashboard_styles' ) );
			add_action( 'admin_print_scripts-' . $page_hook_suffix, array( $this, 'demo_import_dashboard_scripts' ) );
	   }
	}

	public function admin_dashboard_page(){
		require_once GUTENA_KIT_DIR_PATH. 'admin/partials/admin-dashboard.php';
	}

	/**
	 * List Demos and Import
	 */
	public function demo_import_page(){
		echo '<div id="gutenakit-admin-dashboard-page"></div>';
	}

	/**
	 * Demo import styles
	 */
	public function demo_import_dashboard_styles() {
		wp_enqueue_style( 'gutena-theme-dashboard-style', GUTENA_KIT_PLUGIN_URL . 'includes/demo-import/admin-dashboard/build/style-index.css', array( 'wp-components' ), $this->version, 'all' );
	}

	//Check if theme require to install or activate
	private function get_gutena_theme_action() {
		$gutena_theme_action = 'install';
		if ( function_exists( 'gutena_setup' ) ) {
			$gutena_theme_action = '';
		} else {
			$gutena_theme = wp_get_theme( $this->gutena_theme );
			//Installation require
			$gutena_theme_action = 'install';
			//If gutena theme already installed
			if ( $gutena_theme->exists() ) {
				$gutena_theme_action = 'activate';
			}
		}

		return $gutena_theme_action;
	}
 
	/**
	 * Demo import script
	 */
	public function demo_import_dashboard_scripts() {
		if ( function_exists( 'wp_json_file_decode' ) ) {
			wp_enqueue_script( 'gutena-kit-admin-dashboard', GUTENA_KIT_PLUGIN_URL . 'includes/demo-import/admin-dashboard/build/index.js', array( 'wp-components', 'wp-dom-ready', 'wp-element', 'wp-i18n' ), $this->version, true );

			//Demo related data
			wp_localize_script( 
				'gutena-kit-admin-dashboard', 
				'gutenakit_demo_info',
				array(
					'gutena_theme_available' => function_exists( 'gutena_setup' ) ? 1 : 0,
					'gutena_theme_action'    => $this->get_gutena_theme_action(),
					'show_demo_type_filter'  => '0',
					'show_category_filter'   => '0',
					'category'               => gutendkit_demo_category_list(),
					'demo_list'              => gutendkit_categorize_demo_list(),
					'logo'                   => esc_url( GUTENA_KIT_PLUGIN_URL . 'admin/img/logo.png' ),
					'link_icon'              => esc_url( GUTENA_KIT_PLUGIN_URL . 'admin/img/link.svg' ),
					'pro_icon'               => esc_url( GUTENA_KIT_PLUGIN_URL . 'admin/img/pro-tag.svg' ),
					'warning_icon'           => esc_url( GUTENA_KIT_PLUGIN_URL . 'admin/img/warning.svg' ),
					'success_icon_green'     => esc_url( GUTENA_KIT_PLUGIN_URL . 'admin/img/round-check-green.svg' ),
					'success_icon_white'     => esc_url( GUTENA_KIT_PLUGIN_URL . 'admin/img/round-check-white.svg' ),
					'download_icon'          => esc_url( GUTENA_KIT_PLUGIN_URL . 'admin/img/download.svg' ),
					'logo'                   => esc_url( GUTENA_KIT_PLUGIN_URL . 'admin/img/logo.png' ),
					'demo_import_url'        => esc_url( admin_url( 'options.php?page=merlin&demo_index=' ) ),
					'styles'                 => file_exists( GUTENA_KIT_DIR_PATH . 'includes/demo-import/demo-files/styles/all_styles.json' ) ? wp_json_file_decode( GUTENA_KIT_DIR_PATH . 'includes/demo-import/demo-files/styles/all_styles.json', array( 'associative' => true ) ) : array(),
				)
			);

			//gutena official url
			$gutena_url = esc_url( 'https://gutena.io/' );

			//Admin dashboard details
			wp_localize_script( 
				'gutena-kit-admin-dashboard' , 
				'gutenakit_dahboard_info',
				 array(
					 'tabs'            => array(
						 'welcome'       => function_exists( 'gutena_setup' ) ? array(
							 'tab_title'     => esc_html__( 'Getting Started', 'gutena-kit' ),
							 'title'         => esc_html__( 'Welcome to Gutena!', 'gutena-kit' ),
							 'description'   => esc_html__( 'Gutena is a free block theme for WordPress with modern block patterns in-built. It comes packed with beautiful design patterns which suits a variety of use cases. Gutena aims to be at the forefront of WordPress FSE (Full Site Editing) philosophy. ', 'gutena-kit' ),
							 'button_text'   => esc_html__( 'Visit Website', 'gutena-kit' ),
							 'button_link'   => $gutena_url,
							 'video_link'   => esc_url( 'https://youtu.be/I-x5dqNeKEA' ),
							 'video_img_url' => esc_url( GUTENA_KIT_PLUGIN_URL . 'admin/img/video.png' ),
						 ) : array(
							'tab_title'     => esc_html__( 'Getting Started', 'gutena-kit' ),
							'title'         => esc_html__( 'Welcome to Gutena Kit!', 'gutena-kit' ),
							'description'   => esc_html__( 'Power-up the Gutenberg editor with Gutena Kit. It gives you advanced controls, powerful blocks and beautiful pre-made templates that help you build websites in less time! These templates can be imported in just a few clicks.', 'gutena-kit' ),
							'button_text'   => esc_html__( 'Visit Website', 'gutena-kit' ),
							'button_link'   => $gutena_url,
							'video_link'   => esc_url( 'https://youtu.be/I-x5dqNeKEA' ),
							'video_img_url' => esc_url( GUTENA_KIT_PLUGIN_URL . 'admin/img/video-gutena-kit.png' ),
						),
						 'templates'     => array(
							 'tab_title' => esc_html__( 'Starter Templates', 'gutena-kit' ),
						 ),
						 'blockSettings' => array(
							 'tab_title' => esc_html__( 'Block Settings', 'gutena-kit' ),
						 ),
						 'doc'           => array(
							 'tab_title' => esc_html__( 'Knowledge Base', 'gutena-kit' ),
							 'title'     => esc_html__( 'Topics', 'gutena-kit' ),
							 'topics'    => array(
								 array(
									 'heading'     => esc_html__( 'How to Add a Favicon in Block Theme?', 'gutena-kit' ),
									 'link'        => esc_url( $gutena_url . 'how-to-add-a-favicon-in-block-theme' ),
									 'description' => esc_html__( '', 'gutena-kit' ),
								 ),
								 array(
									 'heading'     => esc_html__( 'How to Create Sidebar with Block Theme?', 'gutena-kit' ),
									 'link'        => esc_url( $gutena_url . 'how-to-create-sidebar-with-block-theme' ),
									 'description' => esc_html__( '', 'gutena-kit' ),
								 ),
								 array(
									 'heading'     => esc_html__( 'How to fix demo page navigation link issue?', 'gutena-kit' ),
									 'link'        => esc_url( $gutena_url . 'fix-navigation-link-not-working-issue' ),
									 'description' => esc_html__( '', 'gutena-kit' ),
								 ),
							 ),
							 'support'   => array(
								 'title'              => esc_html__( 'Need Help?', 'gutena-kit' ),
								 'description'        => esc_html__( 'Have a question, we are happy to help! Get in touch with our support team.', 'gutena-kit' ),
								 'documentation_link' => esc_url( $gutena_url.'/blog' ),
								 'link_text'          => esc_html__( 'Submit Ticket', 'gutena-kit' ),
								 'link_url'           => esc_url( 'https://wordpress.org/support/plugin/gutena-kit/' ),
							 ),
							 'changelog' => array(
								 'title'       => esc_html__( 'Releases and fixes', 'gutena-kit' ),
								 'description' => $this->get_changelog(),
							 ),
						 ),
					 ),
					 'logo'            => esc_url( GUTENA_KIT_PLUGIN_URL . 'admin/img/logo.png' ),
					 'onboarding'      => ! empty( get_option( 'gutenakit_onboarding', 1 ) ),
					 'onboarding_info' => array( 
						 'heading'  => esc_html__( 'Gutena Kit Onboarding Wizard', 'gutena-kit' ),
						 'step_one' => array(
							 'step_name'   => esc_html__( 'Get started', 'gutena-kit' ),
							 'title'       => esc_html__( 'Welcome to Gutena Kit', 'gutena-kit' ),
							 'description' => esc_html__( 'Power-up the Gutenberg editor with Gutena Kit. It gives you advanced controls, powerful blocks and beautiful pre-made templates that help you build websites in less time!', 'gutena-kit' ),
							 'button_text' => esc_html__( 'Get started', 'gutena-kit' ),
						 ),
						 'step_two' => array(
							 'step_name' => esc_html__( 'Choose Blocks', 'gutena-kit' ),
							 'title'     => esc_html__( 'Choose Blocks', 'gutena-kit' ),
							 'blocks'    => array(
								 array(
									 'slug'       => 'gutena-accordion',
									 'name'       => esc_html__( 'Accordion', 'gutena-kit' ),
									 'is_enabled' => class_exists( 'Gutena_Accordion' ),
								 ),
								 array(
									 'slug'       => 'gutena-forms',
									 'name'       => esc_html__( 'Forms', 'gutena-kit' ),
									 'is_enabled' => class_exists( 'Gutena_Forms' ),
								 ),
								 array(
									 'slug'       => 'newsletter-block-by-gutena',
									 'name'       => esc_html__( 'Newsletter', 'gutena-kit' ),
									 'is_enabled' => class_exists( 'Gutena_Newsletter' ),
								 ),
								 array(
									 'slug'       => 'photofeed-block-by-gutena',
									 'name'       => esc_html__( 'PhotoFeed', 'gutena-kit' ),
									 'is_enabled' => class_exists( 'Gutena_Instagram_Gallery' ),
								 ),
								 array(
									 'slug'       => 'gutena-lightbox',
									 'name'       => esc_html__( 'Lightbox', 'gutena-kit' ),
									 'is_enabled' => class_exists( 'Gutena_Lightbox' ),
								 ),
								 array(
									'slug'       => 'gutena-tabs',
									'name'       => esc_html__( 'Tabs', 'gutena-kit' ),
									'is_enabled' => class_exists( 'Gutena_Tabs' ),
								),
								 array(
									 'slug'       => 'post-featured-tag-block-by-gutena',
									 'name'       => esc_html__( 'Recent Post Tag', 'gutena-kit' ),
									 'is_enabled' => class_exists( 'Gutena_Post_Featured_Tag' ),
								 ),
								 array(
									'slug'       => 'gutena-star-ratings',
									'name'       => esc_html__( 'Star Ratings', 'gutena-kit' ),
									'is_enabled' => class_exists( 'Gutena_Star_Ratings' ),
								),
								array(
									'slug'       => 'gutena-testimonial',
									'name'       => esc_html__( 'Testimonial Slider', 'gutena-kit' ),
									'is_enabled' => class_exists( 'Gutena_Testimonial' ),
								),
								array(
									'slug'       => 'gutena-team',
									'name'       => esc_html__( 'Team Slider and Grid', 'gutena-kit' ),
									'is_enabled' => class_exists( 'Gutena_Team' ),
								),
							 ),
						 ),
					 ),
					 'nonce'           => wp_create_nonce( 'gutena-kit-nonce' ),
					 'ajax_url'        => esc_url( admin_url('admin-ajax.php') ),
					 'theme_slug'      => $this->gutena_theme,
				 )
			);
		}
	}

	private function disable_gutenaKit_on_boarding() {
		return update_option( 'gutenakit_onboarding', '0' );
	}

	//Manage gutena block plugins
	public function manage_gutena_block_plugins_ajax() {
		
		if ( 1 !== check_ajax_referer( 'gutena-kit-nonce', 'gutena_kit_security' ) && function_exists( 'is_gutenakit_admin' ) && true !== is_gutenakit_admin( 'install_plugins' ) ) {
			wp_send_json_error(
				array(
					'error'   => 1,
					'message' => esc_html__( 'Sorry, you are not allowed to access this page', 'gutena-kit' ),
				)
			);
		}

		//Skip on-boarding 
		if ( ! empty( $_POST['skip_settings'] ) && empty( $_POST['slug'] ) && 'skip' === sanitize_key( wp_unslash( $_POST['skip_settings'] ) ) ) {

			//Disable on boarding
			$this->disable_gutenaKit_on_boarding();

			wp_send_json_success(
				array(
					'error'   => 0,
					'message' => esc_html__( 'On boarding choice updated successfully!', 'gutena-kit' ),
				)
			);

		} elseif ( empty( $_POST['slug'] ) || empty( $_POST['activate_action'] ) ) {
			wp_send_json_error(
				array(
					'error'   => 1,
					'message' => esc_html__( 'Required input missing', 'gutena-kit' ),
				)
			);
		}
		
		$slug = sanitize_key( wp_unslash( $_POST['slug'] ) );

		//Check if require plugin is one of the gutena plugin
		if ( ! in_array( $slug, $this->gutena_plugins ) ) {
			wp_send_json_error(
				array(
					'error'   => 1,
					'message' => esc_html__( 'Not a gutena block', 'gutena-kit' ),
				)
			);
		}

		$activate_action = sanitize_key( wp_unslash( $_POST['activate_action'] ) );
		
		//Validate action for plugin
		$action = $this->validate_plugin_action( $slug, $activate_action );

		if ( false === $action ) {
			//No action required: Send success message
			wp_send_json_success(
				array(
					'error'   => 0,
					'message' => $slug.' '.esc_html__( 'block status saved successfully!', 'gutena-kit' ),
				)
			);
		}

		//check activate_plugin function exists 
		if ( ! function_exists( 'activate_plugin' ) && file_exists( ABSPATH . 'wp-admin/includes/plugin.php' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		$error = false;
		$msg = '' ;
		//If require to install plugin
		if ( 'install-plugin' === $action ) {
			//check plugins_api function exists 
			if ( ! function_exists( 'plugins_api' ) && file_exists( ABSPATH . 'wp-admin/includes/plugin-install.php' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			}
				//check WP_Upgrader class exists 
			if ( ! class_exists( 'WP_Upgrader' ) && file_exists( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
			}
			//check WP_Ajax_Upgrader_Skin class exists 
			if ( ! class_exists( 'WP_Ajax_Upgrader_Skin' ) && file_exists( ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php' );
			}
			//check Plugin_Upgrader class exists 
			if ( ! class_exists( 'Plugin_Upgrader' ) && file_exists( ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php' );
			}

			//make sure all required function and class available
			if ( function_exists( 'activate_plugin' ) && function_exists( 'plugins_api' ) && class_exists( 'WP_Upgrader' ) && class_exists( 'WP_Ajax_Upgrader_Skin' ) && class_exists( 'Plugin_Upgrader' ) ) {
				$api = plugins_api(
					'plugin_information',
					array(
						'slug'   => $slug,
						'fields' => array(
							'short_description' => false,
							'sections'          => false,
							'requires'          => false,
							'rating'            => false,
							'ratings'           => false,
							'downloaded'        => false,
							'last_updated'      => false,
							'added'             => false,
							'tags'              => false,
							'compatibility'     => false,
							'homepage'          => false,
							'donate_link'       => false,
						),
					)
				);
				if ( ! is_wp_error( $api ) && ! empty( $api->download_link ) ) {
					$upgrader = new Plugin_Upgrader( new WP_Ajax_Upgrader_Skin() );
					$result = $upgrader->install( $api->download_link );
					if ( empty( $result ) || is_wp_error( $result ) ) {
						$error = true;
						$msg = esc_html__( 'Failed to install', 'gutena-kit' );
					} else {
						$plugin_path = $this->get_plugin_path( $slug );
						if ( $plugin_path ) {
							$result = activate_plugin( $plugin_path, '', false, true );
							if ( is_wp_error( $result ) ) {
								$error = true;
								$msg = esc_html__( 'Failed to activate ', 'gutena-kit' );
							}
						}
					}
				}
			}
		} elseif ( 'activate-plugin' === $action ) {
			$plugin_path = $this->get_plugin_path( $slug );
			if ( $plugin_path ) {
				$result = activate_plugin( $plugin_path, '', false, true );
				if ( is_wp_error( $result ) ) {
					$error = true;
					$msg = esc_html__( 'Failed to activate ', 'gutena-kit' );
				}
			}
		} elseif ( 'deactivate-plugin' === $action ) {
			$plugin_path = $this->get_plugin_path( $slug );
			if ( $plugin_path ) {
				$result = deactivate_plugins( $plugin_path, '', false, true );
				if ( is_wp_error( $result ) ) {
					$error = true;
					$msg = esc_html__( 'Failed to deactivate ', 'gutena-kit' );
				}
			}
		}

		$slug = ucfirst( str_replace( "-", " ", $slug ) );

		//If error found
		if ( $error ) {
			wp_send_json_error(
				array(
					'error'   => 1,
					'message' => $msg.' '.$slug.' block plugin.',
				)
			);
		} 
		
		if ( ! empty( $_POST['skip_settings'] ) && 'skip' === sanitize_key( wp_unslash( $_POST['skip_settings'] ) ) ) {
			//Disable on boarding
			$this->disable_gutenaKit_on_boarding();
		}

		//Send success message
		wp_send_json_success(
			array(
				'error'   => 0,
				'message' => $slug.' '.esc_html__( 'block status saved successfully!', 'gutena-kit' ),
			)
		);
		
	}

	//get plugin path for activation or deactivation. return false if not found
	private function get_plugin_path( $slug ) {
		
		//Include reuired file for get_plugins function
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( ! function_exists( 'get_plugins' ) ) {
			return false;
		}
		
		/**Checks the plugins directory and retrieve all plugin files with plugin data
		e.g. array('gutena-accordion/gutena-accordion.php' => Array()) **/
		$plugins = array_keys( get_plugins() );
        
		//initialize 
		$plugin_path = false;

		//match plugin slug
		foreach ( $plugins as $plugin ) {
			$path = explode("/",$plugin);
			if ( $slug === $path[0] ) {
				$plugin_path = $plugin;
				break;
			}
		}

		return ( false === $plugin_path || false === stripos( $plugin_path, "gutena" ) || false === stripos( $plugin_path, ".php" ) ) ? false : $plugin_path;
	}

	//Validate plugin action : install, activate or deactivate
	private function validate_plugin_action( $slug, $activate_action ) {
		//Include reuired file for get_plugins function
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( ! function_exists( 'get_plugins' ) ) {
			return false;
		}
		
		//Checks the plugins directory and retrieve all plugin files with plugin data
		//e.g. array('gutena-accordion/gutena-accordion.php' => Array())
		$plugins = array_keys( get_plugins() );
		$plugins = array_map( function( $plugin_path ) {
			$plugin_path = explode("/",$plugin_path);
			return( $plugin_path[0] );
		}, $plugins );

		$activated_plugins = array();
		$apl = array();//active_plugins list

		/**Active Plugin List : array('newsletter-block-by-gutena/newsletter-block-gutena.php') **/
		//check for multisite
		if ( function_exists( 'is_multisite' ) && is_multisite() && function_exists( 'get_sites' ) && class_exists( 'WP_Site_Query' ) && function_exists( 'get_blog_option' ) && function_exists( 
			'get_current_blog_id' ) && function_exists( 'get_site_option' ) ) {
			// Network activated
			$apl = array_keys( get_site_option('active_sitewide_plugins', array() ) );
			//current site
			$site_plugins = get_blog_option( get_current_blog_id(), 'active_plugins' , array() ) ;

			$apl = array_merge( $apl, $site_plugins );

		} else {
			$apl = get_option('active_plugins');
		}

		//active plugin list
		$apl = array_map( function( $plugin_path ) {
			$plugin_path = explode("/",$plugin_path);
			return( $plugin_path[0] );
		}, $apl );

		
		$action = false;

		//If action require to activate plugin - check if installed or not 
		if ( 'activate' === $activate_action ) {
			if ( in_array( $slug, $plugins ) && ! in_array( $slug, $apl ) ) {
				//Plugin installed but not active 
				$action = 'activate-plugin';
			} elseif ( ! in_array( $slug, $plugins ) ) {
				$action = 'install-plugin';
			} 
		} elseif ( 'deactivate' === $activate_action && in_array( $slug, $plugins ) && in_array( $slug, $apl ) ) {
			//check if plugin is active 
			$action = 'deactivate-plugin';
		} 

		return $action;
	}

	//Install and activate gutena theme
	public function activate_gutena_theme_ajax() {

		if ( 1 !== check_ajax_referer( 'gutena-kit-nonce', 'gutena_kit_security' ) || ( function_exists( 'is_gutenakit_admin' ) && true !== is_gutenakit_admin( 'install_plugins' ) ) ) {
			wp_send_json_error(
				array(
					'error'   => 1,
					'message' => esc_html__( 'Sorry, you are not allowed to access this page', 'gutena-kit' ),
				)
			);
		}
	
		//theme already activated
		if ( function_exists( 'gutena_setup' ) ) {
			//No action required: send success message
			wp_send_json_success(
				array(
					'error'   => 0,
					'message' => esc_html__( 'Gutena theme activated successfully!', 'gutena-kit' ),
				)
			);
		}

		if ( empty( $_POST['theme_slug'] ) || $this->gutena_theme !== sanitize_key( wp_unslash( $_POST['theme_slug'] ) ) ) {
			wp_send_json_error(
				array(
					'error'   => 1,
					'message' => esc_html__( 'Required input missing', 'gutena-kit' ),
				)
			);
		}
	
		//For activating or switching theme
		if ( ! function_exists( 'switch_theme' ) && file_exists( ABSPATH . 'wp-includes/theme.php' ) ) {
			include_once ABSPATH . 'wp-includes/theme.php';
		}
		
		$action = $this->get_gutena_theme_action();
		
		$error = false;
		$msg = esc_html__( 'Failed to activate ', 'gutena-kit' );
	
		if ( 'install' === $action ) {
			//check themes_api function exists 
			if ( ! function_exists( 'themes_api' ) && file_exists( ABSPATH . 'wp-admin/includes/theme.php' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/theme.php' );
			}
	
			//check WP_Upgrader class exists 
			if ( ! class_exists( 'WP_Upgrader' ) && file_exists( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
			}
			//check WP_Ajax_Upgrader_Skin class exists 
			if ( ! class_exists( 'WP_Ajax_Upgrader_Skin' ) && file_exists( ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php' );
			}
			//check Theme_Upgrader class exists 
			if ( ! class_exists( 'Theme_Upgrader' ) && file_exists( ABSPATH . 'wp-admin/includes/class-theme-upgrader.php' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/class-theme-upgrader.php' );
			}
		
			//make sure all required function and class available
			if ( function_exists( 'switch_theme' ) && function_exists( 'themes_api' ) && class_exists( 'WP_Upgrader' ) && class_exists( 'WP_Ajax_Upgrader_Skin' ) && class_exists( 'Theme_Upgrader' ) ) {
				//Get theme information
				$api = themes_api(
					'theme_information',
					array(
						'slug'   => $this->gutena_theme,
						'fields' => array(
							'downloadlink' => true,
						),
					)
				);
	
				if ( ! is_wp_error( $api ) && ! empty( $api->download_link ) && false !== stripos( $api->download_link, $this->gutena_theme ) ) {
					$upgrader = new Theme_Upgrader( new WP_Ajax_Upgrader_Skin() );
					$result = $upgrader->install( $api->download_link );
					if ( empty( $result ) || is_wp_error( $result ) ) {
						$error = true;
						$msg = esc_html__( 'Failed to install', 'gutena-kit' );
					} else {
						$action = 'activate';
					}
				}
			}
		}
	
		if ( function_exists( 'switch_theme' ) && function_exists( 'wp_get_theme' ) && ! function_exists( 'gutena_setup' ) && 'activate' === $action ) {
			$theme = wp_get_theme( $this->gutena_theme );
			//If gutena theme already installed
			if ( $theme->exists() ) {
				$result = switch_theme( $this->gutena_theme );
				if ( is_wp_error( $result ) ) {
					$error = true;
					$msg = esc_html__( 'Failed to activate ', 'gutena-kit' );
				}
			}
		}
	
		if ( $error ) {
			wp_send_json_error(
				array(
					'error'   => 1,
					'message' => $msg.' '.$this->gutena_theme.' theme.',
				)
			);
		} 

		//send success message
		wp_send_json_success(
			array(
				'error'   => 0,
				'message' => esc_html__( 'Gutena theme activated successfully!', 'gutena-kit' ),
			)
		);
	}

	//Get Changelog from readme.txt file
	private function get_changelog() {
		$response = '';
		if ( function_exists( 'wp_remote_get' ) ) {
			//get readme file
			$response = wp_remote_get( GUTENA_KIT_PLUGIN_URL . 'readme.txt', array(
				'sslverify' => false
			) );

			if ( ! is_wp_error( $response ) ) {
				//get content 
				$response = wp_remote_retrieve_body( $response );
				$response = explode( '== Changelog ==', $response, 2 );
				if ( 2 === count( $response ) ) {
					if ( ! empty($response[1]) ) {
						$response = $response[1];
						$response = str_ireplace( "= ", "<hr><br><span class='version'>", $response );
						$response = str_ireplace( " =", "</span><br>", $response );
					}
				}
			} else {
				$response = '';
			}
		}
		return $response;
	}

	/**
	 * Gutena Kit scripts and styles for block editor
	 */
	public function add_blocks_and_settings() {
		$asset_file = include( GUTENA_KIT_DIR_PATH . 'includes/block-editor/build/index.asset.php' );

		wp_enqueue_script( 'gutena-kit-block-editor', GUTENA_KIT_PLUGIN_URL . 'includes/block-editor/build/index.js', $asset_file['dependencies'], $asset_file['version'], true );
		wp_enqueue_style( 'gutena-kit-block-editor', GUTENA_KIT_PLUGIN_URL . 'includes/block-editor/build/index.css', array(), $this->version );

		wp_enqueue_style( 'gutena-kit-block-editor-style', GUTENA_KIT_PLUGIN_URL . 'admin/css/gutena-kit-editor.css', array(), $this->version, 'all' );

		//enqueue global typography
		global $gutena_kit_global_typography;

		//check if global typography exists 
		if ( ! isset( $gutena_kit_global_typography ) ) {
			$gutena_kit_global_typography = get_option( 'gutena_kit_global_typography', array() );
		}

		wp_localize_script( 
			'gutena-kit-block-editor' , 
			'gutena_kit_block_editor',
			array(
				'save_typography_action' => 'save_global_typography',
				'nonce'                  => wp_create_nonce( 'gutena-kit-save' ),
				'ajax_url'               => esc_url( admin_url('admin-ajax.php') ),
				'globalTypography'       => $gutena_kit_global_typography,
				'media_query_tab'        => apply_filters('gutena-kit-media-query-tab', '1080px' ),
				'media_query_mobile'     => apply_filters('gutena-kit-media-query-mobile', '767px' ),
			)
		);
	}

	/**
	 * Save global typography
	 */
	public function save_global_typography(){
		check_ajax_referer( 'gutena-kit-save', 'nonce' );
		
		if ( ! function_exists( 'is_gutenakit_admin' ) || true !== is_gutenakit_admin() ) {
			wp_send_json(
				array(
					'status'  => 'error',
					'message' => __( 'Incorrect permission', 'gutena-kit' ),
				)
			);
		}

		if ( empty( $_POST['typography'] ) ) {
			wp_send_json(
				array(
					'status'  => 'error',
					'message' => __( 'Missing typography name', 'gutena-kit' ),
				)
			);
		}

		$typography = gutenakit_sanitize_array( json_decode( stripslashes( $_POST['typography'] ), true ) );
		if ( empty( $typography['slug'] ) ) {
			wp_send_json(
				array(
					'status'  => 'error',
					'message' => __( 'Missing typography name', 'gutena-kit' ),
					'details' => __( 'Missing typography name slug', 'gutena-kit' ),
				)
			);
		}

		$typography['slug'] = sanitize_key( $typography['slug'] );
		$typography_group = empty( $_POST['typography_group'] ) ? 'default' : sanitize_key( wp_unslash( $_POST['typography_group'] ) );
		
		// Get global typography
		$global_typography = get_option( 'gutena_kit_global_typography', array() );

		$message = '';
		if ( isset( $_POST['delete_typography'] ) ) {
			$typography_to_delete = sanitize_key( wp_unslash( $_POST['delete_typography'] ) );
			if ( $typography['slug'] === $typography_to_delete && 
				! empty( $global_typography[ $typography_group ] ) && 
				! empty( $global_typography[ $typography_group ][ $typography['slug'] ] ) ) {
				// destroy a single element of an array
				unset( $global_typography[ $typography_group ][ $typography['slug'] ] );
			}
			$message = __( 'Typography deleted', 'gutena-kit' );
		} else {
			// slug : typography 
			$global_typography[ $typography_group ][ $typography['slug'] ] = $typography;
			$message = __( 'Typography saved', 'gutena-kit' );
		}

		// Set global typography
		update_option( 'gutena_kit_global_typography', $global_typography );

		//Success
		wp_send_json(
			array(
				'status'           => 'success',
				'message'          => $message,
				'globalTypography' => $global_typography,
			)
		);
	}
}