<?php
/**
 * Snax Post Voting Box Template Part
 *
 * @package snax
 * @subpackage Theme
 */

?>

<div class="snax-voting-container">
	<h2 class="snax-voting-container-title g1-beta g1-beta-2nd"><?php esc_html_e( 'Did you like this list?', 'bunchy' ); ?></h2>
	<?php snax_render_voting_box( null, 0, 'snax-voting-large' ); ?>
</div>
