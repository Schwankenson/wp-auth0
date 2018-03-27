/* globals jQuery, console, alert, Cookies, wpAuth0LockGlobal, Auth0Lock, Auth0LockPasswordless */

jQuery(document).ready(function ($) {
    var lockOpts = wpAuth0LockGlobal.lock;

    // Missing critical Auth0 settings
    if ( ! lockOpts.ready ) {
        $( '#form-signin-wrapper' ).hide();
        $( '#loginform' ).show();
        $( '#login' ).find( 'h1' ).show();
        return;
    }

    var loginFormId = 'auth0-login-form';
    var loginForm = $( '#' + loginFormId );

    // Nowhere to put the form
    if ( ! loginForm.length ) {
        console.log( 'Auth0 cannot find node with id ' + loginFormId );
        return;
    }

    // Set state cookie to verify during callback
    Cookies.set( lockOpts.stateCookieName, lockOpts.settings.auth.params.state );

    var Lock = lockOpts.usePasswordless
        ? new Auth0LockPasswordless( lockOpts.clientId, lockOpts.domain, lockOpts.settings )
        : new Auth0Lock( lockOpts.clientId, lockOpts.domain, lockOpts.settings );

    if ( lockOpts.showAsModal ) {
        $( '<button>' )
            .text( lockOpts.modalButtonText )
            .attr( 'id', 'a0LoginButton' )
            .click( Lock.show  )
            .insertAfter( $( '#auth0-login-form' ) );
    } else {
        Lock.show();
    }
});

