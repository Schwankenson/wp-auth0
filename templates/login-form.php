<?php
function renderAuth0Form( $canShowLegacyLogin = true, $specialSettings = array() ) {
  if ( is_user_logged_in() ) {
    return;
  }

  if ( ! $canShowLegacyLogin || ! isset( $_GET['wle'] ) ) {
    $options = WP_Auth0_Options::Instance();
    $lock_options = new WP_Auth0_Lock10_Options( $specialSettings );

    $use_passwordless = $options->get('passwordless_enabled', FALSE );
    $lock_cdn_url = $options->get( $use_passwordless ? 'passwordless_cdn_url' : 'cdn_url' );
    wp_enqueue_script( 'wpa0_lock', $lock_cdn_url, array( 'jquery' ), FALSE, TRUE );

    wp_enqueue_script( 'js-cookie', WPA0_PLUGIN_LIB_URL . 'js.cookie.min.js', FALSE, '2.2.0', TRUE );

    wp_enqueue_script( 'wpa0_lock_init', WPA0_PLUGIN_JS_URL . 'lock-init.js', array( 'jquery' ), WPA0_VERSION, TRUE );
    wp_localize_script(
      'wpa0_lock_init',
      'wpAuth0LockGlobal',
      array(
        'i18n' => array(),
        'lock' => array(
          'settings' => $lock_options->get_lock_options(),
          'ready' => WP_Auth0::ready(),
          'domain' => $options->get( 'domain' ),
          'clientId' => $options->get( 'client_id' ),
          'stateCookieName' => WPA0_STATE_COOKIE_NAME,
          'usePasswordless' => $use_passwordless,
          'showAsModal' => ! empty( $specialSettings['show_as_modal'] ),
          'modalButtonText' => ! empty ( $specialSettings['modal_trigger_name'] )
            ? sanitize_text_field( $specialSettings['modal_trigger_name'] )
            : __( 'Login', 'wp-auth0' ),
        ),
      )
    );

    $login_tpl = apply_filters( 'auth0_login_form_tpl', 'auth0-login-form.php', $lock_options, $canShowLegacyLogin );
    require $login_tpl;

  } else {
    add_action( 'login_footer', array( 'WP_Auth0', 'render_back_to_auth0' ) );
    add_action( 'woocommerce_after_customer_login_form', array( 'WP_Auth0', 'render_back_to_auth0' ) );
  }
}