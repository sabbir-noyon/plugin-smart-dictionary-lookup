<?php
/**
 * Admin Settings for Smart Dictionary Lookup Plugin.
 *
 * @package Smart_Dictionary_Lookup
 */

namespace sabbirnoyon\smartdictionarylookup;

defined( 'ABSPATH' ) || exit;

final class SmartDIC_Admin {

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'smartdic_add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );

	}

	/**
	 * Summary of enqueue_admin_assets
	 */
	public function enqueue_admin_assets() {
    	wp_enqueue_style( 'smartdic-admin-style', 
		SMARTDIC_URL . 'admin/css/smartdic-admin-style.css', [], SMARTDIC_VERSION );
	}
	
	/**
	 * Add plugin settings page to admin menu.
	 */
	public function smartdic_add_admin_menu() {
		add_options_page(
			__( 'Smart Dictionary Lookup Settings', 'smart-dictionary-lookup' ),
			__( 'Smart Dictionary Lookup', 'smart-dictionary-lookup' ),
			'manage_options',
			'smartdic-settings',
			[ $this, 'settings_page_content' ]
		);
	}

	/**
	 * Register settings and fields.
	 */
	public function register_settings() {
		register_setting( 'smartdic_settings_group', 'smartdic_api_url', [
			'type'              => 'string',
			'sanitize_callback' => 'esc_url_raw',
			'default'           => 'https://api.dictionaryapi.dev/api/v2/entries/en/',
		] );

		register_setting( 'smartdic_settings_group', 'smartdic_enable_popup', [
			'type'              => 'boolean',
			'sanitize_callback' => 'absint',
			'default'           => 1,
		] );

		register_setting( 'smartdic_settings_group', 'smartdic_popup_theme', [
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => 'light',
		] );

		register_setting( 'smartdic_settings_group', 'smartdic_popup_position', [
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => 'bottom-right',
		] );
	}

	/**
	 * Display settings page HTML.
	 */
	public function settings_page_content() {
		$api_url        = esc_url( get_option( 'smartdic_api_url', 'https://api.dictionaryapi.dev/api/v2/entries/en/' ) );
		$enable_popup   = absint( get_option( 'smartdic_enable_popup', 1 ) );
		$popup_theme    = sanitize_text_field( get_option( 'smartdic_popup_theme', 'light' ) );
		$popup_position = sanitize_text_field( get_option( 'smartdic_popup_position', 'bottom-right' ) );
		?>
		<div class="wrap">
			<h1 class="smartdic-admin-title-wrapper">
  				<div class="smartdic-admin-title-inner">
    				<img src="<?php echo esc_url( SMARTDIC_URL . 'admin/images/main.png' ); ?>" alt="<?php esc_attr_e( 'Smart Dictionary Lookup', 'smart-dictionary-lookup' ); ?>">
    				<span class="smartdic-admin-title-text"><?php esc_html_e( 'Smart Dictionary Lookup Settings', 'smart-dictionary-lookup' ); ?></span>
  				</div>
			</h1>
			
			<form action="options.php" method="post">
				<?php settings_fields( 'smartdic_settings_group' ); ?>
				<?php do_settings_sections( 'smartdic-settings' ); ?>

				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="smartdic_api_url"><?php esc_html_e( 'API URL', 'smart-dictionary-lookup' ); ?></label></th>
						<td>
							<input type="url" name="smartdic_api_url" id="smartdic_api_url" class="regular-text" value="<?php echo esc_attr( $api_url ); ?>">
							<p class="description"><?php esc_html_e( 'Enter the API base URL.', 'smart-dictionary-lookup' ); ?></p>
						</td>
					</tr>

					<tr>
						<th scope="row"><?php esc_html_e( 'Enable Popup', 'smart-dictionary-lookup' ); ?></th>
						<td>
							<input type="hidden" name="smartdic_enable_popup" value="0">
							<input type="checkbox" name="smartdic_enable_popup" value="1" <?php checked( $enable_popup, 1 ); ?>>
							<label><?php esc_html_e( 'Show the Dictionary popup', 'smart-dictionary-lookup' ); ?></label>
						</td>
					</tr>

					<tr>
						<th scope="row"><?php esc_html_e( 'Popup Theme', 'smart-dictionary-lookup' ); ?></th>
						<td>
							<select name="smartdic_popup_theme">
								<option value="light" <?php selected( $popup_theme, 'light' ); ?>><?php esc_html_e( 'Light', 'smart-dictionary-lookup' ); ?></option>
								<option value="dark" <?php selected( $popup_theme, 'dark' ); ?>><?php esc_html_e( 'Dark', 'smart-dictionary-lookup' ); ?></option>
							</select>
						</td>
					</tr>

					<tr>
						<th scope="row"><?php esc_html_e( 'Popup Position', 'smart-dictionary-lookup' ); ?></th>
						<td>
							<select name="smartdic_popup_position">
								<option value="bottom-right" <?php selected( $popup_position, 'bottom-right' ); ?>><?php esc_html_e( 'Bottom Right', 'smart-dictionary-lookup' ); ?></option>
								<option value="bottom-left" <?php selected( $popup_position, 'bottom-left' ); ?>><?php esc_html_e( 'Bottom Left', 'smart-dictionary-lookup' ); ?></option>
								<option value="top-right" <?php selected( $popup_position, 'top-right' ); ?>><?php esc_html_e( 'Top Right', 'smart-dictionary-lookup' ); ?></option>
								<option value="top-left" <?php selected( $popup_position, 'top-left' ); ?>><?php esc_html_e( 'Top Left', 'smart-dictionary-lookup' ); ?></option>
							</select>
						</td>
					</tr>
				</table>

				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}
