<?php
if( ! defined("SS_VERSION") ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

function ss_get_options()
{	
	static $options;

	if( ! $options ) {
		$defaults = array(
			'twitter_username' => "",
			'auto_add_post_types' => array( 'post' ),
			'social_options'=>array('facebook','twitter','googleplus'),
			'load_static'=>array('load_css','load_js'),
			'facebook_text'=>"Share on Facebook",
			'twitter_text'=>"Share on Twitter",
			'googleplus_text'=>"Share on Google+",
		);

		$db_option = get_option( 'wp_social_sharing', array() );
		if(!isset($db_option['load_static'])){
			$db_option['load_static']=array();
		}
		if(!isset($db_option['social_options'])){
			$db_option['social_options']=array();
		}
		if(!isset($db_option['auto_add_post_types'])){
			$db_option['auto_add_post_types']=array();
		}
		
		if( ! $db_option ) {
			update_option( 'wp_social_sharing', $defaults );
		}

		$options = wp_parse_args( $db_option, $defaults );
	}
	return $options;
}