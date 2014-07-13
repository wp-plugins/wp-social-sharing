<?php
if( ! defined( "SS_VERSION" ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

class SS_Public {
	
	public function __construct() {
		add_filter( 'the_content', array( $this, 'add_links' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ), 99 );
		add_shortcode( 'wp_social_sharing',array($this,'social_sharing'));
	}
	
	public function add_links( $content )
	{
		$opts = ss_get_options();
		$show_buttons = false;
	
		if( ! empty( $opts['auto_add_post_types'] ) && in_array( get_post_type(), $opts['auto_add_post_types'] ) && is_singular( $opts['auto_add_post_types'] ) ) {
			$show_buttons = true;
		}
		
		$show_buttons = apply_filters( 'ss_display', $show_buttons );
	
		if( ! $show_buttons ) {
			return $content;
		}
		$opts['icon_order']=get_option('wss_wp_social_sharing');
		return $content . $this->social_sharing($opts);
	}
	
	public function load_assets() 
	{
		$opts = ss_get_options();
		foreach ($opts['load_static'] as $static){
			if($static == 'load_css'){
				wp_enqueue_style( 'wp-social-sharing', SS_PLUGIN_URL . 'static/socialshare.css', array(), SS_VERSION );
			}	
			if($static == 'load_js'){
				wp_enqueue_script( 'wp-social-sharing', SS_PLUGIN_URL . 'static/socialshare.js', array(), SS_VERSION, true );				
			}		
		}
	}

	function social_sharing( $args = array() ) {
	
		$opts = ss_get_options();
		$defaults = array(
				'social_options' => 'twitter, facebook, googleplus',
				'twitter_username' => $opts['twitter_username'],
				'twitter_text' => __( 'Twitter', 'social-sharing' ),
				'facebook_text' => __( 'Facebook', 'social-sharing' ),
				'googleplus_text' => __( 'Google+', 'social-sharing' ),
				'icon_order'=>'f,t,g'
		);
	
		$opts['icon_order']=get_option('wss_wp_social_sharing');

		if(!is_array($args['social_options']))
			$args['social_options'] = array_filter( array_map( 'trim', explode( ',', $args['social_options'] ) ) );
		extract( $args );
	
		$title = urlencode( get_the_title() );
		$url = urlencode( get_permalink() );
		
		$loadjs='';
		foreach ($opts['load_static'] as $static){
			if($static == 'load_js'){
				$loadjs='onclick="return ss_plugin_loadpopup_js(this);"';				
			}
		}
		$icon_order=explode(',',$icon_order);
		ob_start();
		?>
		<div class="social-sharing">
	        <?php 
	        foreach($icon_order as $o) {
	        	switch($o) {
	        		case 'f':
	        			if(in_array('facebook', $social_options)){
	        			?><a <?php echo $loadjs;?> rel="external nofollow" class="button-facebook ss-sbutton" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" ><?php echo $facebook_text; ?></a><?php
	        			}
	        		break;
	        		case 't':
	        			if(in_array('twitter', $social_options)){
	        			?><a <?php echo $loadjs;?> rel="external nofollow" class="button-twitter ss-sbutton" href="http://twitter.com/intent/tweet/?text=<?php echo $title; ?>&url=<?php echo $url; ?><?php if(!empty($twitter_username)) {  echo '&via=' . $twitter_username; } ?>" target="_blank"><?php echo $twitter_text; ?></a><?php
	        			}
	        		break;
	        		case 'g':
	        			if(in_array('googleplus', $social_options)){
	        			?><a <?php echo $loadjs;?> rel="external nofollow" class="button-googleplus ss-sbutton" href="https://plus.google.com/share?url=<?php echo $url; ?>" target="_blank" ><?php echo $googleplus_text; ?></a><?php
	        			}
	        		break;
	        	}
	        } ?>
	    </div>
	    <?php
	  	$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
}