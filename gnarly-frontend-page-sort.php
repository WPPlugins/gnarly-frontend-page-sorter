<?php
/*
Plugin Name: Gnarly Frontend Page Sort
Version: 1.0
Description: Drag & Drop sorting of pages on the front end of your wordpress site. Great for sites that use get_pages to list sub pages of a parent page.
Author: Gnarly Apps
Author URI: http://www.gnarlyapps.com
Plugin URI: http://www.gnarlyapps.com
Text Domain: gnarly-sort
Domain Path: /languages
*/

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


if( !class_exists( 'Gnarly_sort' ) ) :

	class Gnarly_sort {				

		var $is_gnarly_admin;
		
		protected static $_instance = null;

		function __construct(){
			
			// Define the plugin absolute and http paths
			define('GNARLY_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
			define('GNARLY_HTTP', trailingslashit( plugins_url( '/', __FILE__ ) ) );

			// Sets the $is_gnarly_admin variable to true if the current user has the Administrator role
			add_action( 'init', array( $this, 'check_gnarly_admin' ) );			

			// Enqueue the awesome javascripts that makes this baby work
			add_action( 'wp_enqueue_scripts', array( $this, 'gnarly_enqueue' ) );

			// ajax handling
			add_action( "wp_ajax_gnarly_sort", array( $this, 'gnarly_ajax_sort' ) );
			add_action( "wp_ajax_nopriv_gnarly_sort", array( $this, 'gnarly_ajax_sort' ) );		

		}		


		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		// checks if current user is administrator. If they are then let the plugin do its thang!
		public function check_gnarly_admin(){
			
			$is_admin = $this->get_user_role();

			if( $is_admin ) :				
				$this->is_gnarly_admin = true;
			endif;

		}

		// generic checking if user is administrator
		public function get_user_role() {
			
			global $current_user;
			$user_roles = $current_user->roles;			
			$user_role = array_shift($user_roles);
			
			if( $user_role == 'administrator' ) :
				return true;
			endif;

		}

		// the function that sets the final sorting order for the pages using menu_order
		public function gnarly_ajax_sort(){			

			$result = $_REQUEST;		

			$post_ids = $result['posts'];

			if ( isset( $result['gnarly_nonce'] ) || !wp_verify_nonce( $result['gnarly_nonce'], 'do_gnarly_sort' ) ) :
				
				foreach( $post_ids as $post_id ) :
					
					$order 	= $post_id['order'];
					$id 	= $post_id['id'];

					global $wpdb;
					$response['update'] = 					
						$wpdb->update(
							$wpdb->posts,
								array( 	'menu_order' => $order ),
								array( 	'ID'	=>	$id ),
									array(	'%d'),
									array( 	'%d' )

						);

				endforeach;
			
				// If update was successful return true to ajax call
				if( $response['update'] )
					$response['gnarly'] = true;				

			endif;

			echo json_encode( $response );

			exit();

		}

		// load the scripts only if the Admin is gnarly enough
		public function gnarly_enqueue() { 
			
			if( $this->is_gnarly_admin ) :				

			    wp_enqueue_script('jquery');  
			    wp_enqueue_script('jquery-ui-core');
				wp_enqueue_script('jquery-ui-sortable');

				wp_register_script( 'gnarly-frontend-sort', GNARLY_HTTP . 'assets/js/gnarly.frontend.sort.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable' ), '', true ); 
				wp_enqueue_script( 'gnarly-frontend-sort' );  				
				wp_localize_script( 'gnarly-frontend-sort', 'gnarly', 
					array( 
						'ajaxurl' => wp_nonce_url( admin_url( 'admin-ajax.php' ), 'do_gnarly_sort', 'gnarly_nonce' ) 
					) 
				);
				wp_localize_script( 'gnarly-frontend-sort', 'gnarly_elements', 
					array( 
						'container' 			=> 	'[data-gnarly-sort=true]',						
						'container_children'	=>	'[data-gnarly-id]'
						
					) 
				);

			endif;		
			
		}

	}

endif; // eof: class exists

function gnarly_sort() {
	return Gnarly_sort::instance();
}

// Global for backwards compatibility.
$GLOBALS['gnarly_sort'] = gnarly_sort();

?>