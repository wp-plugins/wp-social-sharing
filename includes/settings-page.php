<?php 
if( ! defined("SS_VERSION") ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}
?>
<div id="ss" class="wrap">
	<div class="ss-container">
		<div class="ss-column ss-primary">
			<h2>WP Social Sharing</h2>
			<form id="ss_settings" method="post" action="options.php">
			<?php settings_fields( 'wp_social_sharing' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label><?php _e('Social share button','wp-social-sharing');?></label>
					</th>
					<td>
						<input type="checkbox" id="facebook_share" name="wp_social_sharing[social_options][]" value="facebook" <?php checked( in_array( 'facebook', $opts['social_options'] ), true ); ?>><label for="facebook_share"><?php echo _e('Facebook','wp-social-sharing')?></label>
						<input type="checkbox" id="twitter_share" name="wp_social_sharing[social_options][]" value="twitter" <?php checked( in_array( 'twitter', $opts['social_options'] ), true ); ?>><label for="twitter_share"><?php echo _e('Twitter','wp-social-sharing')?></label>
						<input type="checkbox" id="googleplus_share" name="wp_social_sharing[social_options][]" value="googleplus" <?php checked( in_array( 'googleplus', $opts['social_options'] ), true ); ?>><label for="googleplus_share"><?php echo _e('Google Plus','wp-social-sharing')?></label>
					</td>
				</tr>
				<tr valign="top">
					<th><label for="facebook_text"><?php _e('Facebook Share button text','wp-social-sharing');?></label></th>
					<td>
						<input type="text" class="widefat" name="wp_social_sharing[facebook_text]" id="facebook_text" value="<?php echo esc_attr($opts['facebook_text']); ?>"> 
					</td>
				</tr>
				<tr valign="top">
					<th><label for="twitter_text"><?php _e('Twitter Share button text','wp-social-sharing');?></label></th>
					<td>
						<input type="text" class="widefat" name="wp_social_sharing[twitter_text]" id="twitter_text" value="<?php echo esc_attr($opts['twitter_text']); ?>"> 
					</td>
				</tr>
				<tr valign="top">
					<th><label for="googleplus_text"><?php _e('Google plus share button text','wp-social-sharing');?></label></th>
					<td>
						<input type="text" name="wp_social_sharing[googleplus_text]" id="googleplus_text" class="widefat" value="<?php echo esc_attr($opts['googleplus_text']); ?>"> 
					</td>
				</tr>
				<tr>
					<th><label><?php _e('Load plugin scripts','wp-social-sharing');?></label></th>
					<td>
						<input type="checkbox" name="wp_social_sharing[load_static][]" id="load_icon_css" value="load_css" <?php checked( in_array( 'load_css', $opts['load_static'] ), true ); ?>><label for="load_icon_css"><?php _e('Load Share button CSS','wp-social-sharing');?></label>
						<input type="checkbox" name="wp_social_sharing[load_static][]" id="load_popup_js" value="load_js"  <?php checked( in_array( 'load_js', $opts['load_static'] ), true ); ?>><label for="load_popup_js"><?php _e('Load Share button JS','wp-social-sharing') ?></label>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row">
						<label><?php _e('Automatically add sharing links?', 'wp-social-sharing'); ?></label>
					</th>
					<td>
						<ul>
						<?php foreach( $post_types as $post_type_id => $post_type ) { ?>
							<li>
								<label>
									<input type="checkbox" name="wp_social_sharing[auto_add_post_types][]" value="<?php echo esc_attr( $post_type_id ); ?>" <?php checked( in_array( $post_type_id, $opts['auto_add_post_types'] ), true ); ?>> <?php printf( __(' Auto display to %s', 'wp-social-sharing' ), $post_type->labels->name ); ?>
								</label>
							</li>
						<?php } ?>
						</ul>
						<small><?php _e('Automatically adds the sharing links to the end of the selected post types.', 'wp-social-sharing'); ?></small>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="ss_twitter_username"><?php _e('Twitter Username', 'wp-social-sharing'); ?></label>
					</th>
					<td>
						<input type="text" name="wp_social_sharing[twitter_username]" id="ss_twitter_username" class="widefat" placeholder="arjun077" value="<?php echo esc_attr($opts['twitter_username']); ?>">
						<small><?php _e('Set this if you want to append "via @yourTwitterUsername" to tweets.', 'wp-social-sharing'); ?></small>
					</td>
				</tr>
			</table>
			<?php
				submit_button();
			?>
		</form>
	</div>

	<div class="ss-column ss-secondary">

		<div class="ss-box">
			<h3 class="ss-title"><?php _e( 'Donate $10, $20 or $50', 'wp-social-sharing' ); ?></h3>
			<p><?php _e( 'If you like this plugin, consider supporting it by donating a token of your appreciation.', 'wp-social-sharing' ); ?></p>
			<div class="ss-donate">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
					<button name="submit" class="button-primary"><?php _e( 'Donate with PayPal', 'wp-social-sharing' ); ?></button>
				</form>
			</div>
			<p><?php _e( 'Some other ways to support this plugin', 'wp-social-sharing' ); ?></p>
			<ul class="ul-square">
				<li><a href="http://wordpress.org/support/view/plugin-reviews/wp-social-sharing?rate=5#postform" target="_blank"><?php printf( __( 'Leave a %s review on WordPress.org', 'wp-social-sharing' ), '&#9733;&#9733;&#9733;&#9733;&#9733;' ); ?></a></li>
				<li><a href="http://twitter.com/intent/tweet/?text=<?php echo urlencode('I am using Wordpress "Social Sharing" plugin to show social sharing buttons on my WordPress site.'); ?>&via=arjun077&url=<?php echo urlencode('http://www.arjunjain.info'); ?>" target="_blank"><?php _e('Tweet about this plugin','wp-social-sharing');?></a></li>
				<li><a href="http://wordpress.org/plugins/wp-social-sharing/#compatibility"><?php _e( 'Vote "works" on the WordPress.org plugin page', 'wp-social-sharing' ); ?></a></li>
			</ul>
		</div>
		<div class="ss-box">
			<h3 class="ss-title"><?php _e( 'Looking for support?', 'wp-social-sharing' ); ?></h3>
			<p><?php printf( __( 'Please use the %splugin support forums%s on WordPress.org.', 'wp-social-sharing' ), '<a href="#">', '</a>' ); ?></p>
		</div>
		<br style="clear:both; " />
	</div>
</div>
</div>