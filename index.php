<?php
/*
Plugin Name: WP Social Sharing
Version: 1.3
Plugin URI: http://wordpress.org/plugins/wp-social-sharing/
Description: Adds very attractive responsive social sharing buttons of Facebook, Twitter, Linkedin and Google+ to wordpress posts, pages or media. 
Author: Arjun Jain
Author URI: http://www.arjunjain.info/
Text Domain: wp-social-sharing
License: GPL v3
*/

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

define( "SS_VERSION", "1.3" );
define( "SS_PLUGIN_DIR", plugin_dir_path( __FILE__ ) ); 
define( "SS_PLUGIN_URL", plugins_url( '/' , __FILE__ ) );

require_once SS_PLUGIN_DIR . 'includes/plugin.php';

if( ! is_admin() ) {
	require_once SS_PLUGIN_DIR . 'includes/class-public.php';
	new SS_Public();
} elseif( ! defined("DOING_AJAX") || ! DOING_AJAX ) {
	require SS_PLUGIN_DIR . 'includes/class-admin.php';
	new SS_Admin();
}

register_activation_hook(__FILE__, array('SS_Admin','wss_plugin_activation_action'));

add_action( 'plugins_loaded', 'wss_update_db_check_while_plugin_upgrade' );

function wss_update_db_check_while_plugin_upgrade(){
	$current_version=get_option('wss_plugin_version');
	if($current_version === FALSE)
		$current_version='1.0';
	
	// change for linkedin button
	if($current_version != '1.3'){
		update_option('wss_wp_social_sharing','f,t,g,l');
		$default=get_option('wp_social_sharing');
		$default['linkedin_text']='Share on Linkedin';
		update_option('wp_social_sharing',$default);
		update_option('wss_plugin_version','1.3');
	}
}