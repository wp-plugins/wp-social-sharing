<?php
if( ! defined( "SS_VERSION" ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

class SS_Public {
	
	public function __construct() {
		add_filter( 'the_content', array( $this, 'add_links_after_content' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ), 99 );
		add_shortcode( 'wp_social_sharing',array($this,'social_sharing'));
	}
	
	public function add_links_after_content( $content )
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

	public function social_sharing( $atts=array() ) {
		extract(shortcode_atts(array(
				'social_options' => 'twitter, facebook, googleplus',
				'twitter_username' => '',
				'twitter_text' => __( 'Share on Twitter ', 'social-sharing' ),
				'facebook_text' => __( 'Share on Facebook', 'social-sharing' ),
				'googleplus_text' => __( 'Share on Google+', 'social-sharing' ),
				'linkedin_text' => __('Share on Linkedin', 'social-sharing' ),
				'icon_order'=>'f,t,g,l',
				'show_icons'=>'0'	
		),$atts));

		if(!is_array($social_options))
			$social_options = array_filter( array_map( 'trim', explode( ',',$social_options ) ) );
		
		$title = urlencode( get_the_title() );
		$url = urlencode( get_permalink() );
	
		$loadjs='';
		
		$opts=ss_get_options();
		foreach ($opts['load_static'] as $static){
		    if($static == 'load_js'){
		       $loadjs='onclick="return ss_plugin_loadpopup_js(this);"';
		    }
		}
		
		$ssbutton_facebook='button-facebook';
		$ssbutton_twitter='button-twitter';
		$ssbutton_googleplus='button-googleplus';
		$ssbutton_linkedin='button-linkedin';
		$sssocial_sharing='';
		if($show_icons){
			$sssocial_sharing='ss-social-sharing';
			$ssbutton_facebook='ss-button-facebook';
			$ssbutton_twitter='ss-button-twitter';
			$ssbutton_googleplus='ss-button-googleplus';
			$ssbutton_linkedin='ss-button-linkedin';	
		}
		$icon_order=explode(',',$icon_order);
		ob_start();
		?>
		<div class="social-sharing <?php echo $sssocial_sharing;?>">
	        <?php 
	        foreach($icon_order as $o) {
	        	switch($o) {
	        		case 'f':
	        			if(in_array('facebook', $social_options)){
	        			?><a <?php echo $loadjs;?> rel="external nofollow" class="<?php echo $ssbutton_facebook;?>" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" ><?php echo $facebook_text; ?></a><?php
	        			}
	        		break;
	        		case 't':
	        			if(in_array('twitter', $social_options)){
	        			?><a <?php echo $loadjs;?> rel="external nofollow" class="<?php echo $ssbutton_twitter;?>" href="http://twitter.com/intent/tweet/?text=<?php echo $title; ?>&url=<?php echo $url; ?><?php if(!empty($twitter_username)) {  echo '&via=' . $twitter_username; } ?>" target="_blank"><?php echo $twitter_text; ?></a><?php
	        			}
	        		break;
	        		case 'g':
	        			if(in_array('googleplus', $social_options)){
	        			?><a <?php echo $loadjs;?> rel="external nofollow" class="<?php echo $ssbutton_googleplus;?>" href="https://plus.google.com/share?url=<?php echo $url; ?>" target="_blank" ><?php echo $googleplus_text; ?></a><?php
	        			}
	        		break;
					case 'l':
						if(in_array('linkedin', $social_options)){
							?><a <?php echo $loadjs;?> rel="external nofollow" class="<?php echo $ssbutton_linkedin;?>" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo substr($url,0,1024);?>&title=<?php echo substr($title,0,200);?>" target="_blank" ><?php echo $linkedin_text; ?></a><?php
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