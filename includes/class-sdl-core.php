<?php
/**
 * Frontend functionality for Smart Dictionary Lookup plugin.
 *
 * @package Smart_Dictionary_Lookup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SDL_Core
 *
 * Manages frontend scripts, styles, AJAX, and the popup markup.
 */
class SDL_Core {

	/**
	 * Constructor to initialize hooks.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_public_assets' ] );
		add_action( 'wp_footer', [ $this, 'add_popup_container' ] );

		add_action( 'wp_ajax_sdl_fetch_data', [ $this, 'handle_fetch_data_request' ] );
		add_action( 'wp_ajax_nopriv_sdl_fetch_data', [ $this, 'handle_fetch_data_request' ] );
	}

	/**
	 * Enqueue frontend styles and scripts.
	 */
	public function enqueue_public_assets() {
		wp_enqueue_style(
			'sdl-style',
			SDL_URL . 'public/css/sdl-style.css',
			[],
			SDL_VERSION
		);

		wp_enqueue_script(
			'sdl-script',
			SDL_URL . 'public/js/sdl-script.js',
			[ 'jquery' ],
			SDL_VERSION,
			true
		);

		// Pass AJAX URL to JavaScript.
		wp_localize_script(
			'sdl-script',
			'sdl_vars',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			]
		);
	}

	/**
	 * Handle AJAX request to fetch word definition from dictionary API.
	 */
	public function handle_fetch_data_request() {
		$word = isset( $_POST['word'] ) ? sanitize_text_field( $_POST['word'] ) : '';

		if ( empty( $word ) ) {
			wp_send_json_error( [ 'message' => 'No word provided.' ] );
		}

		// Call dictionary API.
		$response = wp_remote_get( "https://api.dictionaryapi.dev/api/v2/entries/en/{$word}" );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( [ 'message' => 'API request failed.' ] );
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if (
			isset( $data[0]['meanings'][0]['definitions'][0]['definition'] )
		) {
			$definition = $data[0]['meanings'][0]['definitions'][0]['definition'];
			wp_send_json_success( [ 'definition' => $definition ] );
		} else {
			wp_send_json_error( [ 'message' => 'No definition found.' ] );
		}
	}

	/**
	 * Output a hidden popup container in the footer.
	 */
	public function add_popup_container() {
		echo '
		
		<div id="sdl-popup" area-live="polite" role="alert" style="display:none;">
			<span id="sdl-popup-close" title="'. esc_attr__( 'Close', 'smart-dictionary-lookup' ) .' " > Close </span>
			<div id="sdl-popup-content"> Loading... </div>
		</div>
		
		';
	}
}
