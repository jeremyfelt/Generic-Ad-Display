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

	public $query_args = array();

	public function __construct() {
		add_action( 'init', array( $this, 'capture_ad_request' ) );
	}

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

	public function display_ad_request() {
		header('Content-Type:text/javascript');
		?>
		document.write('');
		document.write('<div style="width:<?php echo absint( $this->query_args['width'] ); ?>px;height:<?php echo absint( $this->query_args['height'] ); ?>px;background: #888;color:#fefefe;">Ad Content</div>');
		<?
		die();
	}

}
new Generic_Ad_Display_Plugin();