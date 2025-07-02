<?php
/** 
 * Admin Settings for Smart Dictionary Lookup Plugin.
 * 
 * @package Smart_Dictionary_Lookup
 */

if ( !defined( 'ABSPATH' )) {
    exit;
}
final class SDL_Admin {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'sdl_add_admin_menu' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );


    }

    /**
	 * Settings page for under the Settings menu.
	 */
    function sdl_add_admin_menu(){

        add_options_page( 
            'Smart Dictionary Lookup Settings', 
            'Smart Dictionary Lookup',
            'manage_options',
            'sdl-settings',
            [ $this, 'settings_page_content' ]
        );

    }

    function register_settings() {

        register_setting( 'sdl_settings_group', 'sdl_api_url', [
            'type' => 'string',
            'sanitize_callback' => 'esc_url_raw',
            'default' => 'https://api.dictionaryapi.dev/api/v2/entries/en/',
         ] );

          register_setting( 'sdl_settings_group', 'sdl_enable_popup', [
            'type' => 'boolean',
            'sanitize_callback' => 'absint',
            'default' => 1,
         ] );
         
          register_setting( 'sdl_settings_group', 'sdl_popup_theme', [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'light',
         ] );

         register_setting( 'sdl_settings_group', 'sdl_popup_position', [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'bottom-right'
         ] );

    }

    /**
	 * Outputs the settings page HTML
	 */
    function settings_page_content() {

        $api_url = esc_url( get_option( 'sdl_api_url', 'https://api.dictionaryapi.dev/api/v2/entries/en/' ) ) ;
        $enable_popup = get_option( 'sdl_enable_popup', 1 );
        $popup_theme = sanitize_text_field( get_option( 'sdl_enable_theme', 'light' ) );
        $popup_position = sanitize_text_field( get_option( 'sdl_popup_position', 'bottom-right' ) );
       
       ?>
        <div class="wrap">
            <h1>
                <img src="<?php echo SDL_URL . 'admin/images/main.png'; ?>" alt="Smart Dictionary Lookup" style="height: 70px; vertical-align: middle; margin-right: 10px; margin-top:-5px;">
                Smart Dictionary Lookup Settings
            </h1>
            <form action="options.php" method="post" >
                <?php settings_fields( 'sdl_settings_group' ) ?>
                <?php do_settings_sections( 'sdl_settings_group' ) ?>

                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="sdl_api_url">API URL</label></th>
                        <td>
                            <input type="url" name="sdl_api_url" id="sdl_api_url" class="regular-text" value="<?php echo $api_url; ?>">
                            <p class="description" style="margin-left:4px;">Enter the API base URL</p>
                            
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Enable Popup</th>
                            <td>
                                <input type="checkbox" name="sdl_enable_popup" value="1" <?php checked( $enable_popup, 1 ); ?>>
                                <label>Show the Dictionary popup</label>
                            </td>
                    </tr>

                    <tr>
						<th scope="row">Popup Theme</th>
						<td>
							<select name="sdl_popup_theme">
								<option value="light" <?php selected( $popup_theme, 'light' ); ?>>Light</option>
								<option value="dark" <?php selected( $popup_theme, 'dark' ); ?>>Dark</option>
							</select>
						</td>
					</tr>

                    <tr>
						<th scope="row">Popup Position</th>
						<td>
							<select name="sdl_popup_position">
								<option value="bottom-right" <?php selected( $popup_position, 'bottom-right' ); ?>>Bottom Right</option>
								<option value="bottom-left" <?php selected( $popup_position, 'bottom-left' ); ?>>Bottom Left</option>
								<option value="top-right" <?php selected( $popup_position, 'top-right' ); ?>>Top Right</option>
								<option value="top-left" <?php selected( $popup_position, 'top-left' ); ?>>Top Left</option>
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