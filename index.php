<?php
/*
Plugin Name: WP Social Sharing
Version: 1.2
Plugin URI: http://wordpress.org/plugins/wp-social-sharing/
Description: Adds very attractive responsive social sharing buttons of Facebook, Twitter and Google+ to wordpress posts, pages or media. 
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

define( "SS_VERSION", "1.0" );
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