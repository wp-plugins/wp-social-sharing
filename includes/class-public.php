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
	
		// add buttons to content
		return $content . $this->social_sharing();
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
		);
	
		// create final arguments array
		$args = wp_parse_args( $opts, $defaults );
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
		ob_start();
		?>
		<div class="social-sharing">
	        <?php foreach($social_options as $o) {
	        	switch($o) {
	        		case 'facebook':
	        			?><a <?php echo $loadjs;?> rel="external nofollow" class="button-facebook ss-sbutton" href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]=<?php echo $url; ?>&p[title]=<?php echo $title; ?>" target="_blank" ><?php echo $facebook_text; ?></a><?php
	        		break;
	        		case 'twitter':
	        			?><a <?php echo $loadjs;?> rel="external nofollow" class="button-twitter ss-sbutton" href="http://twitter.com/intent/tweet/?text=<?php echo $title; ?>&url=<?php echo $url; ?><?php if(!empty($twitter_username)) {  echo '&via=' . $twitter_username; } ?>" target="_blank"><?php echo $twitter_text; ?></a><?php
	        		break;
	        		case 'googleplus':
	        			?><a <?php echo $loadjs;?> rel="external nofollow" class="button-googleplus ss-sbutton" href="https://plus.google.com/share?url=<?php echo $url; ?>" target="_blank" ><?php echo $googleplus_text; ?></a><?php
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