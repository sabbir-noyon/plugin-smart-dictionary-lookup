<?php
/**
 * Frontend functionality for Smart Dictionary Lookup plugin.
 *
 * @package Smart_Dictionary_Lookup
 */

namespace sabbirnoyon\smartdictionarylookup;

defined( 'ABSPATH' ) || exit;

/**
 * Class SmartDIC_Core.
 *
 * Handles frontend assets, AJAX, and popup.
 */
class SmartDIC_Core {

	/**
	 * Constructor to initialize hooks.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_public_assets' ] );
		add_action( 'wp_footer', [ $this, 'add_popup_container' ] );

		add_action( 'wp_ajax_smartdic_fetch_data', [ $this, 'handle_fetch_data_request' ] );
		add_action( 'wp_ajax_nopriv_smartdic_fetch_data', [ $this, 'handle_fetch_data_request' ] );
	}

	/**
	 * Enqueue frontend styles and scripts.
	 */
	public function enqueue_public_assets() {
		wp_enqueue_style(
			'smartdic-style',
			SMARTDIC_URL . 'public/css/smartdic-style.css',
			[],
			SMARTDIC_VERSION
		);

		wp_enqueue_script(
			'smartdic-script',
			SMARTDIC_URL . 'public/js/smartdic-script.js',
			[ 'jquery' ],
			SMARTDIC_VERSION,
			true
		);

		// Add nonce for AJAX security
		wp_localize_script(
			'smartdic-script',
			'smartdic_vars',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'smartdic_nonce' ),
				'enable_popup'   => get_option( 'smartdic_enable_popup', 1 ),
				'popup_theme'    => get_option( 'smartdic_popup_theme', 'light' ),
				'popup_position' => get_option( 'smartdic_popup_position', 'bottom-right' ),
			]
		);
	}

	/**
	 * Handle AJAX request to fetch word definition from dictionary API.
	 */
	public function handle_fetch_data_request() {
		// Verify nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'smartdic_nonce' ) ) {
			wp_send_json_error( [ 'message' => 'Invalid request (nonce failed).' ] );
		}

		// Check permission (for logged-in users only)
		if ( is_user_logged_in() && ! current_user_can( 'read' ) ) {
			wp_send_json_error( [ 'message' => 'Permission denied.' ] );
		}

		$word = isset( $_POST['word'] ) ? sanitize_text_field( $_POST['word'] ) : '';

		if ( empty( $word ) ) {
			wp_send_json_error( [ 'message' => 'No word provided.' ] );
		}

		$api_base = get_option( 'smartdic_api_url', 'https://api.dictionaryapi.dev/api/v2/entries/en/' );
		$response = wp_remote_get( esc_url_raw( $api_base . $word ) );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( [ 'message' => 'API request failed.' ] );
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( isset( $data[0]['meanings'][0]['definitions'][0]['definition'] ) ) {
			$definition = sanitize_text_field( $data[0]['meanings'][0]['definitions'][0]['definition'] );
			wp_send_json_success( [ 'definition' => $definition ] );
		} else {
			wp_send_json_error( [ 'message' => 'No definition found.' ] );
		}
	}

	/**
	 * Output popup container markup in footer.
	 */
	public function add_popup_container() {
		?>
		<div id="smartdic-popup" style="display: none;">
			<div class="smartdic-selected-word"></div>
			<div class="smartdic-definition-text"></div>
			<button class="smartdic-close" aria-label="<?php echo esc_attr__( 'Close', 'smart-dictionary-lookup' ); ?>">&times;</button>
		</div>
		<?php
	}
}
