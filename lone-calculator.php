<?php
/**
 * Plugin Name: Jin Mortgage Calculator
 * Plugin URI: https://example.com/jin-mortgage-calculator
 * Description: A comprehensive mortgage calculator for WordPress
 * Version: 1.0.0
 * Author: Om Sekhar
 * Author URI: https://example.com
 * License: GPL2
 * Text Domain: jin-mortgage-calculator
 * Domain Path: /languages
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'LONE_CALCULATOR_VERSION', '1.0.0' );
define( 'LONE_CALCULATOR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'LONE_CALCULATOR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include necessary files
require_once LONE_CALCULATOR_PLUGIN_DIR . 'includes/class-calculator.php';
require_once LONE_CALCULATOR_PLUGIN_DIR . 'includes/class-shortcode.php';
require_once LONE_CALCULATOR_PLUGIN_DIR . 'includes/ajax-handlers.php';
require_once LONE_CALCULATOR_PLUGIN_DIR . 'includes/class-admin.php';

/**
 * Initialize the plugin
 */
function lone_calculator_init() {
	// Load text domain
	load_plugin_textdomain(
		'jin-mortgage-calculator',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);

	// Initialize shortcodes
	Lone_Calculator_Shortcode::init();
}
add_action( 'plugins_loaded', 'lone_calculator_init' );

/**
 * Register plugin assets
 */
function lone_calculator_register_assets() {
	// Register styles
	wp_register_style(
		'lone-calculator-style',
		LONE_CALCULATOR_PLUGIN_URL . 'assets/css/calculator.css',
		array(),
		LONE_CALCULATOR_VERSION
	);

	// Register scripts
	wp_register_script(
		'lone-calculator-script',
		LONE_CALCULATOR_PLUGIN_URL . 'assets/js/calculator.js',
		array( 'jquery' ),
		LONE_CALCULATOR_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'lone_calculator_register_assets' );

/**
 * Enqueue assets only on pages with calculator shortcode
 */
function lone_calculator_enqueue_assets() {
	wp_enqueue_style( 'lone-calculator-style' );
	wp_enqueue_script( 'lone-calculator-script' );

	// Localize script with AJAX URL and nonce
	wp_localize_script(
		'lone-calculator-script',
		'loneCalculator',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'lone_calculator_nonce' ),
		)
	);
}

// Plugin activation hook
register_activation_hook( __FILE__, function() {
	// Any activation tasks can go here
});

// Plugin deactivation hook
register_deactivation_hook( __FILE__, function() {
	// Any deactivation cleanup can go here
});
