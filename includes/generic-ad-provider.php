<?php

class Generic_Ad_ACM_Provider extends ACM_Provider {
	function __construct() {
		// Default output HTML
		$this->output_html = '<script type="text/javascript" src="%url%"></script>';

		/**
		 * The Ad tag IDs available to us on the front end. These are ultimately called
		 * with do_action( 'acm_tag', $tag_id ) wherever you want the ad to display
		 */
		$this->ad_tag_ids = array(
			array(
				'tag' => '728x90_atf',
				'url_vars' => array(
					'width' => 728,
					'height' => 90,
				),
			),
			array(
				'tag' => '300x250_rail',
				'url_vars' => array(
					'width' => 300,
					'height' => 250,
				),
			),
			array(
				'tag' => '180x180_rail',
				'url_vars' => array(
					'width' => 180,
					'height' => 180,
				)
			)
		);

		/**
		 * The only URL that should be allowed is ourselves, since this is a demo
		 */
		$this->whitelisted_script_urls = array( $_SERVER['HTTP_HOST'] );

		/**
		 * The things that should appear as arguments for your ad call.
		 */
		$this->ad_code_args = array(
			array(
				'key'   => 'background_color',
				'label' => 'Background Color',
				'editable' => true,
				'required' => true,
			),
			array(
				'key'   => 'describer',
				'label' => 'Describer',
				'editable'  => true,
				'required'  => true,
			),
		);

		parent::__construct();
	}

}

class Generic_Ad_ACM_WP_List_Table extends ACM_WP_List_Table {

	function __construct() {
		parent::__construct( array(
		                          'singular'=> 'generic_ad_acm_wp_list_table',
		                          'plural' => 'generic_ad_acm_wp_list_table',
		                          'ajax'	=> true,
		                     ));
	}

	function get_columns() {
		$columns = array(
			'id' => 'ID',
			'background_color' => 'Background Color',
			'describer' => 'Describer',
			'priority' => 'Priority',
			'conditionals' => 'Conditionals',
		);
		return apply_filters( 'acm_list_table_columns', $columns );
	}

	function column_background_color( $item ) {
		$output = esc_html( $item['url_vars']['background_color'] );
		$output .= $this->row_actions_output( $item );
		return $output;
	}

	function column_describer( $item ) {
		return esc_html( $item['url_vars']['describer'] );
	}
}