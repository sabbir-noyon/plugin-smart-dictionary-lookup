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
		add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
	}

	public function init_plugin() {
		if ( is_admin() ) {
			new SDL_Admin();
		}

		new SDL_Core();
	}
}

new Smart_Dictionary_Lookup();
