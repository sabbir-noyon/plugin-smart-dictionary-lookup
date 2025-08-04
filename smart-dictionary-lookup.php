<?php
/*
Plugin Name: Smart Dictionary Lookup
Plugin URI: https://wordpress.org/plugins/smart-dictionary-lookup
Description: Smart, instant word definitions with a beautiful popup when users double-click text. Fully customizable, lightweight, and WordPress-friendly.
Version: 1.0.0
Author: Sabbir Noyon
Author URI: https://profiles.wordpress.org/sabbirnoyon/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: smart-dictionary-lookup
*/

namespace sabbirnoyon\smartdictionarylookup;

defined( 'ABSPATH' ) || exit;

require_once plugin_dir_path( __FILE__ ) . 'includes/class-smartdic-core.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-smartdic-admin.php';

/**
 * Main plugin class.
 */
final class Smart_Dictionary_Lookup {
		public function __construct() {
		$this->define_constants();
		add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );

		register_activation_hook( __FILE__, [ $this, 'on_activation' ] );
		register_deactivation_hook( __FILE__, [ $this, 'on_deactivation' ] );
	}

	private function define_constants() {
		define( 'SMARTDIC_VERSION', '1.0.0' );
		define( 'SMARTDIC_FILE', __FILE__ );
		define( 'SMARTDIC_PATH', plugin_dir_path( __FILE__ ) );
		define( 'SMARTDIC_URL', plugin_dir_url( __FILE__ ) );
		define( 'SMARTDIC_ASSETS', plugin_dir_url( __FILE__ ) . 'assets/' );
	}

	public function init_plugin() {
		if ( is_admin() ) {
			new \sabbirnoyon\smartdictionarylookup\SmartDIC_Admin();
		}

		new \sabbirnoyon\smartdictionarylookup\SmartDIC_Core();
	}

	public function on_activation() {
		// Future activation logic
	}

	public function on_deactivation() {
		// Future deactivation logic
	}
}

new Smart_Dictionary_Lookup();
