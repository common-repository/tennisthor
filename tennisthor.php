<?php
/*
Plugin Name: Tennis booking system - TennisThor
Plugin URI: http://wordpress.org/plugins/tennisthor/
Description: Tennis court bookings for tennis courts & other sports such as table tennis, football etc..
Version: 1.2.1
Author: Vladimir Bosev
Author URI: https://tennisthor.com
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

add_action('init', 'tennisthor_session', 1);
if(!function_exists('tennisthor_session'))
{
function tennisthor_session() {
    if(!session_id()) {
        session_start();
    }
}
}

require_once 'include/functions.php';
require_once 'include/login.php';
require_once 'include/register.php';
require_once 'include/tournaments.php';
require_once 'include/tournament-detail.php';
require_once 'include/reservation-timeline.php';
require_once 'include/rating.php';
require_once 'include/thor_power_rating.php';




define('TENNISTHOR_DOMAIN', 'https://www.tennisthor.com');
define('TENNISTHOR_ADMIN_URL', admin_url());

$tennis_club_id = get_option('tennisthor_frontend_club_id');
$tennis_token = get_option('tennisthor_admin_api_token');
$enc_tennis_club_id = tennisthor_encrypt_decrypt('encrypt',$tennis_club_id);

if(!function_exists('custom_tennisthor_install'))
{
function custom_tennisthor_install()
{
	global $wpdb;	

    $page_option = get_option('tennisthor_tournaments_page');
	if($page_option == '')
	{	
	    $post = array(
	          'comment_status' => 'closed',
	          'ping_status' =>  'closed' ,
	          'post_author' => 1,
	          'post_content'		=> '[tennisthor_tournaments]',
	          'post_date' => date('Y-m-d H:i:s'),
	          'post_name' => 'Tournaments',
	          'post_status' => 'publish' ,
	          'post_title' => 'Tournaments',
	          'post_type' => 'page',
	    );  
	    $page_id = wp_insert_post( $post, false );
	    update_option( 'tennisthor_tournaments_page', $page_id );	
    }


    $page_option = get_option('tennisthor_tournament_detail_page');
	if($page_option == '')
	{	
	    $post = array(
	          'comment_status' => 'closed',
	          'ping_status' =>  'closed' ,
	          'post_author' => 1,
	          'post_content'		=> '[tennisthor_tournament_detail]',
	          'post_date' => date('Y-m-d H:i:s'),
	          'post_name' => 'Tournament Detail',
	          'post_status' => 'publish' ,
	          'post_title' => 'Tournament Detail',
	          'post_type' => 'page',
	    );  
	    $page_id = wp_insert_post( $post, false );
	    update_option( 'tennisthor_tournament_detail_page', $page_id );	
    } 

    $page_option = get_option('tennisthor_reservation_page');
	if($page_option == '')
	{	
	    $post = array(
	          'comment_status' => 'closed',
	          'ping_status' =>  'closed' ,
	          'post_author' => 1,
	          'post_content'		=> '[tennisthor_reservation_timeline]',
	          'post_date' => date('Y-m-d H:i:s'),
	          'post_name' => 'Reservation',
	          'post_status' => 'publish' ,
	          'post_title' => 'Reservation',
	          'post_type' => 'page',
	    );  
	    $page_id = wp_insert_post( $post, false );
	    update_option( 'tennisthor_reservation_page', $page_id );	
    }

    $page_option = get_option('tennisthor_rating_page');
	if($page_option == '')
	{	
	    $post = array(
	          'comment_status' => 'closed',
	          'ping_status' =>  'closed' ,
	          'post_author' => 1,
	          'post_content'		=> '[tennisthor_rating]',
	          'post_date' => date('Y-m-d H:i:s'),
	          'post_name' => 'Rating',
	          'post_status' => 'publish' ,
	          'post_title' => 'Rating',
	          'post_type' => 'page',
	    );  
	    $page_id = wp_insert_post( $post, false );
	    update_option( 'tennisthor_rating_page', $page_id );	
    }  

    $page_option = get_option('tennisthor_thor_power_rating_page');
	if($page_option == '')
	{	
	    $post = array(
	          'comment_status' => 'closed',
	          'ping_status' =>  'closed' ,
	          'post_author' => 1,
	          'post_content'		=> '[tennisthor_thor_power_rating]',
	          'post_date' => date('Y-m-d H:i:s'),
	          'post_name' => 'Thor Power Rating',
	          'post_status' => 'publish' ,
	          'post_title' => 'Thor Power Rating',
	          'post_type' => 'page',
	    );  
	    $page_id = wp_insert_post( $post, false );
	    update_option( 'tennisthor_thor_power_rating_page', $page_id );	
    } 


    $page_option = get_option('tennisthor_login_page');
	if($page_option == '')
	{	
	    $post = array(
	          'comment_status' => 'closed',
	          'ping_status' =>  'closed' ,
	          'post_author' => 1,
	          'post_content'		=> '[tennisthor_login]',
	          'post_date' => date('Y-m-d H:i:s'),
	          'post_name' => 'TennisThor Login',
	          'post_status' => 'publish' ,
	          'post_title' => 'TennisThor Login',
	          'post_type' => 'page',
	    );  
	    $page_id = wp_insert_post( $post, false );
	    update_option( 'tennisthor_login_page', $page_id );	
    }

    $page_option = get_option('tennisthor_register_page');
	if($page_option == '')
	{	
	    $post = array(
	          'comment_status' => 'closed',
	          'ping_status' =>  'closed' ,
	          'post_author' => 1,
	          'post_content'		=> '[tennisthor_register]',
	          'post_date' => date('Y-m-d H:i:s'),
	          'post_name' => 'TennisThor Signup',
	          'post_status' => 'publish' ,
	          'post_title' => 'TennisThor Signup',
	          'post_type' => 'page',
	    );  
	    $page_id = wp_insert_post( $post, false );
	    update_option( 'tennisthor_register_page', $page_id );	
    }      

               	


}
}
register_activation_hook(__FILE__,'custom_tennisthor_install');

if(!function_exists('custom_tennisthor_deactivation'))
{
function custom_tennisthor_deactivation()
{
	global $wpdb;	

	$page_options = ['tennisthor_tournaments_page', 'tennisthor_tournament_detail_page', 'tennisthor_reservation_page', 'tennisthor_rating_page', 'tennisthor_thor_power_rating_page', 'tennisthor_login_page','tennisthor_register_page'];

	foreach($page_options as $page_option)
	{
		$page_id = get_option($page_option);
		if($page_id!='')
		{
			wp_delete_post( $page_id ,true);	
			delete_option($page_option);
		}			
	}
}
}
register_deactivation_hook(__FILE__,'custom_tennisthor_deactivation');

if ( is_admin() ) {
	if(!function_exists('tennisthor_general_admin_notice'))
	{	
	function tennisthor_general_admin_notice(){
		$token = get_option('tennisthor_admin_api_token');
		if(trim($token) == "")		
		{			
			echo '<div class="notice notice-warning is-dismissible">
				<p>Set Tennis Thor API key to configure Tennis Thor plugin content in <a href="'.admin_url('admin.php?').'page=tennisthor-setting">setting</a> page.</p>
			</div>';			
		}
	}
	}
	add_action('admin_notices', 'tennisthor_general_admin_notice');			
	
	require_once 'admin/functions.php';
	require_once 'admin/index.php';
}
else
{
	if( !is_admin())
	{
		add_action( 'wp_head', 'tennisthor_header_scripts' );
		if(!function_exists('tennisthor_header_scripts'))
		{		
		function tennisthor_header_scripts(){
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script('jquery-ui-core');
		}
		}

		if ( $GLOBALS['pagenow'] != 'wp-login.php' ) {
			@wp_enqueue_style('tennis-thor', plugins_url('assets/css/bootstrap-social.css',__FILE__));
			@wp_enqueue_style('tennis-thor1', plugins_url('assets/css/style.css',__FILE__));		
		}
	}
}