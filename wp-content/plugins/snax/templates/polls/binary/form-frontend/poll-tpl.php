<?php
/**
 * Questions form
 *
 * @package snax 1.11
 * @subpackage Forms
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$snax_tab_index             = (int) filter_input( INPUT_COOKIE, 'snax_poll_' . get_the_ID() . '_active_tab' );
$snax_settings_tab_active   = apply_filters( 'snax_poll_settings_tab_active', true );
$snax_answers_set           = snax_get_poll_setting( 'answers_set' );
?>

<div id="quizzard" class="quizzard-poll quizzard-poll-binary">

	<h3><?php esc_html_e( 'Questions', 'snax' ); ?></h3>

	<div class="quizzard-questions quizzard-tab-content<?php echo 0 === $snax_tab_index ? ' quizzard-tab-content-active' : ''; ?>">
		<div class="quizzard-questions-header">
			<h2><?php esc_html_e( 'Questions', 'snax' );?></h2>

			<div class="quizzard-questions-answers-set">
				<?php esc_html_e( 'Answers', 'snax' ); ?>
				<select id="snax_answers_set" name="snax_answers_set">
					<option value="yes-no"<?php selected( $snax_answers_set, 'yes-no' ); ?>><?php esc_html_e( 'Yes or No', 'snax' ); ?></option>
					<option value="hot-not"<?php selected( $snax_answers_set, 'hot-not' ); ?>><?php esc_html_e( 'Hot or Not', 'snax' ); ?></option>
				</select>
			</div>

			<a href="#" class="button button-secondary button-small button-disabled quizzard-questions-collapse-all"><?php esc_html_e( 'Collapse all', 'snax' ); ?></a>
			<a href="#" class="button button-secondary button-small quizzard-questions-expand-all"><?php esc_html_e( 'Expand all', 'snax' ); ?></a>
		</div>

		<ul class="quizzard-q-items" id="quizzard-q-items">
			<li class="quizzard-q-item quizzard-next-q-item">
				<?php snax_get_template_part( 'polls/binary/form-frontend/question-next-tpl' ); ?>
			</li>
		</ul><!-- .quizzards-q-items -->
	</div>

	<?php if ( $snax_settings_tab_active ) : ?>
	<div class="quizzard-settings quizzard-tab-content<?php echo 2 === $snax_tab_index ? ' quizzard-tab-content-active' : ''; ?>">
		<?php snax_get_template_part( 'polls/binary/form-frontend/settings' ); ?>
	</div>
	<?php endif; ?>

</div><!-- #quizzard -->


<input type="hidden" id="quizzard-poll-nonce" value="<?php echo esc_attr( wp_create_nonce( 'quizzard-poll' ) ); ?>" />
<input type="hidden" name="snax_poll" value="binary" />

<script type="text/template" id="quizzard-question-tpl">
	<?php snax_get_template_part( 'polls/binary/form-frontend/question-tpl' ); ?>
</script>

<script type="text/template" id="quizzard-answer-tpl">
	<?php snax_get_template_part( 'polls/binary/form-frontend/answer-tpl' ); ?>
</script>

<?php $snax_poll_id = get_the_ID(); ?>
<script>
	(function (ctx) {
		if ( typeof ctx.snax_polls === 'undefined' ) {
			ctx.snax_polls = {};
		}

		ctx.snax_polls.poll = {
			id: 		<?php echo absint( $snax_poll_id ); ?>,
			type: 		'<?php echo esc_html( snax_get_binary_poll_type() ); ?>',
			questions:	<?php echo wp_json_encode( snax_get_poll_questions( $snax_poll_id ) ); ?>,
			ajax_url:   '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>'
		};
	})(window);
</script>
