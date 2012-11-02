<?php
/*
Plugin Name: Generic Ad Display
Plugin URI:
Description: Mimics an ad server in WordPress for testing.
Version: 0.1
Author: Jeremy Felt, 10up
Author URI: http://jeremyfelt.com
License: GPL2
*/

/*  Copyright 2012 Jeremy Felt (email: jeremy.felt@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include __DIR__ . '/includes/generic-ad-provider.php';

class Generic_Ad_Display_Plugin {

	/**
	 * @var array container for all of the query args that we'll receive from the ad unit request
	 */
	public $query_args = array();

	/**
	 * Build it.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'capture_ad_request' ) );

		add_filter( 'acm_register_provider_slug', array( $this, 'register_provider_slug' ) );
		add_filter( 'acm_provider_slug', array( $this, 'set_provider_slug' ) );
		add_filter( 'acm_default_url', array( $this, 'set_default_url' ) );
	}

	/**
	 * Look at incoming requests and capture anything that matches a generic_ad load. We'll provide
	 * an output for these to replace whatever WordPress had in mind for it.
	 */
	public function capture_ad_request() {

		if ( ! is_user_logged_in() )
			return;

		if ( strstr( trailingslashit( $_SERVER['REQUEST_URI'] ), '/generic_ad/' ) ) {
			$url_results = parse_url( $_SERVER['REQUEST_URI'] );
			if ( isset( $url_results['query'] ) )
				$this->query_args = wp_parse_args( $url_results['query'] );
			else
				return;
		} else {
			return;
		}

		// It's my party and our ads need a width and height to work.
		if ( empty( $this->query_args['width'] ) || empty( $this->query_args['height'] ) )
			return;

		// Now we have a full set of query args that we can use to display an ad unit
		$this->display_ad_request();
	}

	/**
	 * Provide a Javascript file that writes information about the requested ad unit to the document
	 * that has made the request.
	 */
	public function display_ad_request() {
		// The request was requested from a script tag, so the browser is expecting a JS file.
		header('Content-Type:text/javascript');
		?>
		document.write('');
		document.write('<div style="width:<?php echo absint( $this->query_args['width'] ); ?>px;height:<?php echo absint( $this->query_args['height'] ); ?>px;background: #<?php echo esc_js( $this->query_args['background_color'] ); ?>;color:#fefefe;">');
		<?php

		// Go through all of the arguments from the ad call and put them in our document.written box
		foreach ( $this->query_args as $the_arg => $the_val ) :
			?>
			document.write('<?php echo esc_js( $the_arg ); ?> = <?php echo esc_js( $the_val ); ?> | ');
			<?php
		endforeach;
		?>document.write('</div>');<?php

		die(); // Wouldn't want WordPress to continue loading, would we?
	}

	function register_provider_slug( $current_providers ) {
		$current_providers->generic_ad = array(
			'provider' => 'Generic_Ad_ACM_Provider',
			'table' => 'Generic_Ad_ACM_WP_List_Table',
		);
		return $current_providers;
	}

	function set_provider_slug() {
		return 'generic_ad';
	}

	function set_default_url() {
		return home_url() . '/generic_ad/?width=%width%&height=%height%&background_color=%background_color%&describer=%describer%';
	}
}
new Generic_Ad_Display_Plugin();