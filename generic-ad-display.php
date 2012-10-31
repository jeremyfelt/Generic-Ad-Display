<?php
/*
Plugin Name: Generic Ad Display
Plugin URI:
Description: Serves ads directly from WordPress for testing.
Version: 0.1
Author: Jeremy Felt
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

class Generic_Ad_Display_Plugin {

	public function __construct() {
		add_action( 'init', array( $this, 'capture_ad_request' ) );
	}

	public function capture_ad_request() {

		if ( ! is_user_logged_in() )
			return;

		if ( strstr( trailingslashit( $_SERVER['REQUEST_URI'] ), '/generic_ad/' ) ) {
			$url_results = parse_url( $_SERVER['REQUEST_URI'] );
			if ( isset( $url_results['query'] ) )
				$query_args = wp_parse_args( $url_results['query'] );
			else
				return;
		} else {
			return;
		}

		// Now we have a full set of query args that we can use to display an ad unit

	}

}
new Generic_Ad_Display_Plugin();