<?php
/*
Plugin Name: WP Social Sharing
Version: 1.9
Plugin URI: http://wordpress.org/plugins/wp-social-sharing/
Description: Adds very attractive responsive social sharing buttons of Facebook, Twitter, Linkedin, Pinterest, Xing and Google+ to wordpress posts, pages or media. 
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

define( "SS_VERSION", "1.5" );  // db versioin
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
	$db_version=get_option('wss_plugin_version');
	if($db_version === FALSE)
		$db_version='1.0';

	if($db_version == '1.0' ){
		update_option('wss_wp_social_sharing','f,t,g,l,p,x');
		$default=get_option('wp_social_sharing');
		$default['linkedin_text']='Share on Linkedin';
		$default['pinterest_text']='Share on Pinterest';
        $default['xing_text']='Share on Xing';
		update_option('wp_social_sharing',$default);
		update_option('wss_plugin_version','1.5');
	}
	else if($db_version  == '1.3'){ // update db from version 1.3 to 1.4
		$current_wss_option=get_option('wss_wp_social_sharing');
		$current_wss_option = $current_wss_option.',p,x';
		update_option('wss_wp_social_sharing',$current_wss_option);
		$default=get_option('wp_social_sharing');
		$default['pinterest_text']='Share on Pinterest';
    	$default['xing_text']='Share on Xing';
		update_option('wp_social_sharing',$default);
		update_option('wss_plugin_version','1.5');
	}
    else if($db_version  == '1.4'){ // update db from version 1.4 to 1.5
    	$current_wss_option=get_option('wss_wp_social_sharing');
        $current_wss_option = $current_wss_option.',x';
        update_option('wss_wp_social_sharing',$current_wss_option);
        $default=get_option('wp_social_sharing');
        $default['xing_text']='Share on Xing';
        update_option('wp_social_sharing',$default);
        update_option('wss_plugin_version','1.5');
    }
}