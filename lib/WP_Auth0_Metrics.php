<?php

class WP_Auth0_Metrics {

	protected $a0_options;

	public function __construct( WP_Auth0_Options $a0_options ) {
		$this->a0_options = $a0_options;
	}

	public function init() {
		add_action( 'admin_footer', array( $this, 'render' ) );
	}

	public function render() {

		if ( ! WP_Auth0_Admin::is_auth0_admin_page() ) {
			return;
		}

		if ( $this->a0_options->get( 'metrics' ) == 1 ) {
?>
      <script src="//cdn.auth0.com/js/m/metrics-1.min.js"></script>
      <script>
        var a0metricsLib = new Auth0Metrics("auth0-for-wordpress", "https://dwh-tracking.it.auth0.com/dwh-metrics", "wp-plugin");
        function metricsTrack(event, trackData, callback) {
          if (typeof(a0metricsLib) === 'undefined') {
            return;
          }

          if (typeof(trackData) === 'function') {
            callback = trackData;
            trackData = null;
          }

          var params = {
            tenant:"<?php echo $this->a0_options->get_tenant_name(); ?>"
          };

          if (trackData) {
            params.trackData = trackData;
          }

          a0metricsLib.track(event, params, callback);
        }
      </script>
    <?php
		}
		else {
?>
      <script>
        function metricsTrack() {
          // Metrics are disabled
        }
      </script>
    <?php
		}
	}

}
