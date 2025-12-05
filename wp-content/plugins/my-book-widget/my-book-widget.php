<?php
/**
 * Plugin Name: My Book Widget
 * Description: A custom Elementor widget to display books from CSV or JSON.
 * Version: 1.0.0
 * Author: Drrrose
 * Text Domain: my-book-widget
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Plugin Class
 */
final class My_Book_Widget_Plugin {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.4';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 * @var \My_Book_Widget_Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @return \My_Book_Widget_Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ] );
	}

	/**
	 * On Plugins Loaded
	 *
	 * Checks if Elementor is loaded and verifies versions.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function on_plugins_loaded() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		$this->init();
	}

	/**
	 * Init
	 *
	 * Initialize the plugin components.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {
		// Include helper functions
		require_once( __DIR__ . '/includes/helpers.php' );

		// Register Widget
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );

		// Register Scripts & Styles
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_scripts' ] );
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_styles' ] );
	}

	/**
	 * Register Widgets
	 *
	 * Load and register the new widgets.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {
		require_once( __DIR__ . '/includes/widget-class.php' );
		$widgets_manager->register( new \My_Book_Widget() );
	}

	/**
	 * Register Scripts
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_scripts() {
		wp_register_script( 'my-book-widget-js', plugins_url( 'assets/js/widget.js', __FILE__ ), [ 'jquery' ], self::VERSION, true );
	}

	/**
	 * Register Styles
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_styles() {
		wp_register_style( 'my-book-widget-css', plugins_url( 'assets/css/widget.css', __FILE__ ), [], self::VERSION );
	}

	/**
	 * Admin Notice: Missing Main Plugin
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'my-book-widget' ),
			'<strong>' . esc_html__( 'My Book Widget', 'my-book-widget' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'my-book-widget' ) . '</strong>'
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin Notice: Minimum Elementor Version
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'my-book-widget' ),
			'<strong>' . esc_html__( 'My Book Widget', 'my-book-widget' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'my-book-widget' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin Notice: Minimum PHP Version
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'my-book-widget' ),
			'<strong>' . esc_html__( 'My Book Widget', 'my-book-widget' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'my-book-widget' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}
}

My_Book_Widget_Plugin::instance();
