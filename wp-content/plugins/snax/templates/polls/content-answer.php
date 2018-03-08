<?php
$snax_answer_class = array(
	'snax-poll-answer',
	'snax-poll-answer-' . get_the_ID(),
);
// gets the 'grandparent' of the answer.
$quiz_id = snax_get_poll_type( wp_get_post_parent_id( wp_get_post_parent_id( get_the_ID() ) ) );
if ( 'classic' === $quiz_id ) {
	$img_size = 'poll-answer-grid-1of2';
} else {
	$img_size = 'quizzard-answer-grid-1of2';
}
?>

<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $snax_answer_class ) ); ?>" data-quizzard-answer-id="<?php echo absint( get_the_ID() ); ?>">
	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="snax-poll-answer-media">
			<?php the_post_thumbnail( $img_size ); ?>
		</figure>
	<?php endif; ?>
	<div class="snax-poll-answer-label"><input type="radio" name="snax_question_1" /> <?php the_title(); ?></div>
</div>
