<?php
/**
 * Plugin Name: Smart Dictionary Lookup
 * Description: A plugin that shows word definitions when double-clicked in post content using a public dictionary API.
 * Plugin URI:  https://wordpress.org/plugins/smart-dictionary-lookup
 * Version:     1.0.0
 * Author:      Sabbir Noyon
 * Author URI:  https://www.sabbir-noyon.com
 * Text Domain: smart-dictionary-lookup
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-sdl-core.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-sdl-admin.php';

final class Smart_Dictionary_Lookup {

	public function __construct() {
		$this->define_constants();
		$this->load_textdomain();

		add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
		register_activation_hook( __FILE__, [ $this, 'on_activation' ] );
		register_deactivation_hook( __FILE__, [ $this, 'on_deactivation' ] );
	}

	private function define_constants() {
		define( 'SDL_VERSION', '1.0.0' );
		define( 'SDL_FILE', __FILE__ );
		define( 'SDL_PATH', plugin_dir_path( __FILE__ ) );
		define( 'SDL_URL', plugin_dir_url( __FILE__ ) );
		define( 'SDL_ASSETS', plugin_dir_url( __FILE__ ) . 'assets/' );
	}

	public function load_textdomain() {
		load_plugin_textdomain( 'smart-dictionary-lookup', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	public function init_plugin() {
		if ( is_admin() ) {
			new SDL_Admin();
		}

		new SDL_Core();
	}

	public function on_activation() {
		// Activation logic (if needed)
	}

	public function on_deactivation() {
		// Deactivation logic (if needed)
	}
}

new Smart_Dictionary_Lookup();
