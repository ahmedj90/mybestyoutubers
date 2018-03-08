<?php
/**
 * Facebook share link
 *
 * @package snax 1.11
 * @subpackage Share
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

global $snax_share_args;
?>
<script type="text/javascript">
	(function () {
		var triggerOnLoad = false;

		window.quizzardShareOnFB = function() {
			jQuery('body').trigger('snaxFbNotLoaded');
			triggerOnLoad = true;
		};

		var _fbAsyncInit = window.fbAsyncInit;

		window.fbAsyncInit = function() {
			FB.init({
				appId      : '<?php echo esc_attr( snax_get_facebook_app_id() ); ?>',
				xfbml      : true,
				version    : 'v2.5'
			});

			window.quizzardShareOnFB = function() {
				var shareObj        	= jQuery('.snax-share-object').data('quizzardShareObject');
				var shareTitle 		    = '<?php echo esc_html( $snax_share_args['title'] ); ?>';
				var shareDescription	= '<?php echo esc_html( $snax_share_args['description'] ); ?>';
				var shareImage	        = '<?php echo esc_html( $snax_share_args['thumb'] ); ?>';

				FB.login(function(response) {
					if (response.status === 'connected') {
						var objectToShare = {
							'og:url':           '<?php echo esc_url( $snax_share_args['url'] ); ?>', // Url to share.
							'og:title':         shareTitle,
							'og:description':   shareDescription
						};

						// Add image only if set. FB fails otherwise.
						if (shareImage) {
							objectToShare['og:image'] = shareImage;
						}

						FB.ui({
								method: 'share_open_graph',
								action_type: 'og.shares',
								action_properties: JSON.stringify({
									object : objectToShare
								})
							},
							// callback
							function(response) {
								var validShare = response.post_id ? true : false;

								shareObj.unlock(validShare);
							});
					}
				}, {
					// Beginning October 12, 2016, post_id requires your app to have publish_actions granted,
					// and for the user to share to their timeline or a group.
					scope: 'publish_actions'
				});
			};

			// Fire original callback.
			if (typeof _fbAsyncInit === 'function') {
				_fbAsyncInit();
			}

			// Open share popup as soon as possible, after loading FB SDK.
			if (triggerOnLoad) {
				setTimeout(function() {
					quizzardShareOnFB();
				}, 1000);
			}
		};

		// JS SDK loaded before we hook into it. Trigger callback now.
		if (typeof window.FB !== 'undefined') {
			window.fbAsyncInit();
		}
	})();
</script>

<a class="quizzard-share quizzard-share-facebook" onclick="quizzardShareOnFB(); return false;" href="#" title="<?php esc_attr_e( 'Share on Facebook', 'snax' ); ?>" target="_blank" rel="nofollow">
	<?php esc_html_e( 'Share on Facebook', 'snax' ); ?>
</a>
