<?php
$snax_question_class = array(
	'snax-poll-question',
	'snax-poll-question-' . get_the_ID(),
	'snax-poll-question-hidden',
	'snax-poll-question-unanswered',
	'snax-poll-question-title-' . ( snax_get_poll_title_hide() ? 'hide' : 'show' ),
	'snax-poll-question-answer-title-' . ( snax_get_poll_answers_labels_hide() ? 'hide' : 'show' ),
);
?>

<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $snax_question_class ) ); ?>" data-quizzard-question-id="<?php echo absint( get_the_ID() ); ?>">
	<?php the_title( '<h2 class="snax-poll-question-title"><span class="snax-poll-question-counter"><span class="snax-poll-question-counter-value"></span></span>', '</h2>' ); ?>

	<figure class="snax-poll-question-media">
		<?php the_post_thumbnail(); ?>
	</figure>

	<?php snax_get_template_part('polls/loop-answers' ); ?>

</div><!-- .snax-poll-question -->
