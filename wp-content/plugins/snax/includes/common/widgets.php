<?php
/**
 * Snax Widgets
 *
 * @package snax
 * @subpackage Widgets
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Init widgets
 */
function snax_widgets_init() {
	register_widget( 'Snax_Widget_Lists' );
	register_widget( 'Snax_Widget_CTA' );
	register_widget( 'Snax_Widget_Teaser' );
}

/**
 * Class Snax_Widget_List
 */
class Snax_Widget_Lists extends WP_Widget {

	/**
	 * The total number of displayed widgets
	 *
	 * @var int
	 */
	static $counter = 1;

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		$widget_options = apply_filters( 'snax_widget_lists_options', array(
			'classname'   => 'snax snax-widget-lists',
			'description' => esc_html__( 'Recently created, updated, closed lists', 'snax' ),
		) );

		parent::__construct( 'snax_widget_lists', esc_html__( 'Snax Lists', 'snax' ), $widget_options );
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->get_default_args() );

		$title = apply_filters( 'widget_title', $instance['title'] );

		// HTML id.
		if ( empty( $instance['id'] ) ) {
			$instance['id'] = 'snax-widget-lists-' . self::$counter++;
		}

		// HTML class.
		$classes   = explode( ' ', $instance['class'] );
		$classes[] = 'snax-widget-lists';

		echo wp_kses_post( $args['before_widget'] );

		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}

		$query = new WP_Query( $this->get_query_args( $instance ) );

		set_query_var( 'snax_list_id', $instance['id'] );
		set_query_var( 'snax_list_classes', $classes );
		set_query_var( 'snax_list_query', $query );

		snax_get_template_part( 'widget-list' );

		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->get_default_args() );

		?>
		<div class="snax-widget-lists">
			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget title', 'snax' ); ?>
					:</label>
				<input class="widefat"
				       id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
				       value="<?php echo esc_attr( $instance['title'] ); ?>">
			</p>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'max' ) ); ?>"><?php esc_html_e( 'The max. number of entries to show', 'snax' ); ?>
					:</label>
				<input size="5"
				       type="text"
				       name="<?php echo esc_attr( $this->get_field_name( 'max' ) ); ?>"
				       id="<?php echo esc_attr( $this->get_field_id( 'max' ) ); ?>"
				       value="<?php echo esc_attr( $instance['max'] ) ?>"/>
			</p>

			<?php $types = $this->get_types(); ?>
			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php esc_html_e( 'Type', 'snax' ); ?>
					:</label>
				<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>"
				        id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>">
					<?php foreach ( $types as $type_id => $type_label ) : ?>
						<option
							value="<?php echo esc_attr( $type_id ); ?>"<?php selected( $type_id, $instance['type'] ); ?>><?php echo esc_html( $type_label ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"><?php esc_html_e( 'HTML id attribute (optional)', 'snax' ); ?>
					:</label>
				<input class="widefat"
				       type="text"
				       name="<?php echo esc_attr( $this->get_field_name( 'id' ) ); ?>"
				       id="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"
				       value="<?php echo esc_attr( $instance['id'] ) ?>"/>
			</p>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>"><?php esc_html_e( 'HTML class(es) attribute (optional)', 'snax' ); ?>
					:</label>
				<input class="widefat"
				       type="text"
				       name="<?php echo esc_attr( $this->get_field_name( 'class' ) ); ?>"
				       id="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>"
				       value="<?php echo esc_attr( $instance['class'] ) ?>"/>
			</p>
		</div>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$types = $this->get_types();

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['max']   = absint( $new_instance['max'] );
		$instance['type']  = key_exists( $new_instance['type'], $types ) ? $new_instance['type'] : 'recently_created';
		$instance['id']    = sanitize_html_class( $new_instance['id'] );
		$instance['class'] = implode( ' ', array_map( 'sanitize_html_class', explode( ' ', $new_instance['class'] ) ) );

		return $instance;
	}

	/**
	 * Get default arguments
	 *
	 * @return array
	 */
	public function get_default_args() {
		return apply_filters( 'snax_widget_lists_defaults', array(
			'title' => esc_html__( 'Recently created lists', 'snax' ),
			'max'   => 5,
			'type'  => 'recently_created',
			'id'    => '',
			'class' => '',
		) );
	}

	/**
	 * Return widget types
	 *
	 * @return array
	 */
	public function get_types() {
		return apply_filters( 'snax_widget_lists_types', array(
			'recently_created'               => esc_html__( 'Recently added', 'snax' ),
			'recently_updated'               => esc_html__( 'Recently modified (eg. when new item was added)', 'snax' ),
			'recently_opened_for_submission' => esc_html__( 'Open for submission (recently opened first)', 'snax' ),
			'recently_closed_for_submission' => esc_html__( 'Closed for submission (recently closed first)', 'snax' ),
			'recently_opened_for_voting'     => esc_html__( 'Open for voting (recently opened first)', 'snax' ),
			'recently_closed_for_voting'     => esc_html__( 'Closed for voting (recently closed first)', 'snax' ),
		) );
	}

	/**
	 * Return WP Query based on instance config data
	 *
	 * @param array $instance           Widget instanca config.
	 *
	 * @return array
	 */
	protected function get_query_args( $instance ) {
		// Default config, reflects the recently_created type.
		$query_args = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'meta_query'          => array(
				array(
					'key'     => '_snax_format',
					'value'   => 'list',
					'compare' => '=',
				),
			),
			'orderby'             => 'date',
			'order'               => 'DESC',
			'posts_per_page'      => $instance['max'],
			'ignore_sticky_posts' => true,
		);

		switch ( $instance['type'] ) {
			case 'recently_updated':
				$query_args['meta_query'][] = array(
					'key'     => '_snax_post_modified_date',
					'value'   => current_time( 'mysql' ),
					'compare' => '<=',
					'type'    => 'DATE',
				);
				$query_args['orderby']      = 'meta_value';
				$query_args['meta_key']     = '_snax_post_modified_date';
				break;

			case 'recently_opened_for_submission':
				$query_args['meta_query'][] = array(
					'key'     => '_snax_post_submission_start_date',
					'value'   => current_time( 'mysql' ),
					'compare' => '<=',
					'type'    => 'DATE',
				);
				$query_args['orderby']      = 'meta_value';
				$query_args['meta_key']     = '_snax_post_submission_start_date';
				break;

			case 'recently_closed_for_submission':
				$query_args['meta_query'][] = array(
					'key'     => '_snax_post_submission_end_date',
					'value'   => current_time( 'mysql' ),
					'compare' => '<=',
					'type'    => 'DATE',
				);
				$query_args['orderby']      = 'meta_value';
				$query_args['meta_key']     = '_snax_post_submission_end_date';
				break;

			case 'recently_opened_for_voting':
				$query_args['meta_query'][] = array(
					'key'     => '_snax_post_voting_start_date',
					'value'   => current_time( 'mysql' ),
					'compare' => '<=',
					'type'    => 'DATE',
				);
				$query_args['orderby']      = 'meta_value';
				$query_args['meta_key']     = '_snax_post_voting_start_date';
				break;

			case 'recently_closed_for_voting':
				$query_args['meta_query'][] = array(
					'key'     => '_snax_post_voting_end_date',
					'value'   => current_time( 'mysql' ),
					'compare' => '<=',
					'type'    => 'DATE',
				);
				$query_args['orderby']      = 'meta_value';
				$query_args['meta_key']     = '_snax_post_voting_end_date';
				break;
		}

		return apply_filters( 'snax_widget_lists_query_args', $query_args );
	}
}

/**
 * Class Snax_Widget_CTA
 */
class Snax_Widget_CTA extends WP_Widget {

	/**
	 * The total number of displayed widgets
	 *
	 * @var int
	 */
	static $counter = 1;

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		$widget_options = apply_filters( 'snax_widget_cta_options', array(
			'classname'   => 'snax snax-widget-cta',
			'description' => esc_html__( 'Box with link to the Frontend Submission page', 'snax' ),
		) );

		parent::__construct( 'snax_widget_cta', esc_html__( 'Snax Call To Action', 'snax' ), $widget_options );
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->get_default_args() );

		$title = apply_filters( 'widget_title', $instance['title'] );

		// HTML id.
		if ( empty( $instance['id'] ) ) {
			$instance['id'] = 'snax-widget-cta-' . self::$counter++;
		}

		// HTML class.
		$classes = explode( ' ', $instance['class'] );

		echo wp_kses_post( $args['before_widget'] );

		do_action( 'snax_before_widget_cta_title' );

		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}

		$text_before 	= apply_filters( 'wpml_translate_single_string', $instance['text_before'], 'Widgets', 'Snax Call to Action - text field' );
		$button_label 	= apply_filters( 'wpml_translate_single_string', $instance['button_label'], 'Widgets', 'Snax Call to Action - button label field' );

		$snax_cta_type = $instance['type'];
		$snax_cta_list_type = '';
		if ( 'ranked_list' === $snax_cta_type ) {
			$snax_cta_type = 'list';
			$snax_cta_list_type = 'ranked';
		}
		if ( 'list' === $snax_cta_type ) {
			$snax_cta_type = 'list';
		}
		if ( 'classic_list' === $snax_cta_type ) { 
			$snax_cta_type = 'list';
			$snax_cta_list_type = 'classic';
		}

		set_query_var( 'snax_cta_list_type', $snax_cta_list_type );
		set_query_var( 'snax_cta_type', $snax_cta_type );
		set_query_var( 'snax_cta_text_before', $text_before );
		set_query_var( 'snax_cta_button_label', $button_label );
		snax_get_template_part( 'widget-call-to-action' );

		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->get_default_args() );

		?>
		<div class="snax-widget-cta">
			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget title', 'snax' ); ?>
					:</label>
				<input class="widefat"
				       id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
				       value="<?php echo esc_attr( $instance['title'] ); ?>">
			</p>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'text_before' ) ); ?>"><?php esc_html_e( 'Call To Action text', 'snax' ); ?>
					:</label>
				<input class="widefat"
					   size="50"
				       type="text"
				       name="<?php echo esc_attr( $this->get_field_name( 'text_before' ) ); ?>"
				       id="<?php echo esc_attr( $this->get_field_id( 'text_before' ) ); ?>"
				       value="<?php echo esc_attr( $instance['text_before'] ) ?>"/>
			</p>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'button_label' ) ); ?>"><?php esc_html_e( 'Button label', 'snax' ); ?>
					:</label>
				<input class="widefat"
					   size="20"
				       type="text"
				       name="<?php echo esc_attr( $this->get_field_name( 'button_label' ) ); ?>"
				       id="<?php echo esc_attr( $this->get_field_id( 'button_label' ) ); ?>"
				       value="<?php echo esc_attr( $instance['button_label'] ) ?>"/>
			</p>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"><?php esc_html_e( 'HTML id attribute (optional)', 'snax' ); ?>
					:</label>
				<input class="widefat"
				       type="text"
				       name="<?php echo esc_attr( $this->get_field_name( 'id' ) ); ?>"
				       id="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"
				       value="<?php echo esc_attr( $instance['id'] ) ?>"/>
			</p>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>"><?php esc_html_e( 'HTML class(es) attribute (optional)', 'snax' ); ?>
					:</label>
				<input class="widefat"
				       type="text"
				       name="<?php echo esc_attr( $this->get_field_name( 'class' ) ); ?>"
				       id="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>"
				       value="<?php echo esc_attr( $instance['class'] ) ?>"/>
			</p>
			<?php
			$type = $instance['type'];
			$all_formats = snax_get_formats();
			?>
			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php esc_html_e( 'Link to:', 'snax' ); ?>
					:</label>
				<select class="widefat"
				       type="text"
				       name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>"
					   id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>">
					   <option value="all" value=""<?php selected( $type, 'all' ); ?>>All formats</option>
					<?php
					foreach ( $all_formats as $key => $format ) {?>
						<option value="<?php echo esc_attr( $key );?>" value=""<?php selected( $type, $key ); ?>><?php echo wp_kses_post( $format['labels']['name'] );?></option>
						<?php
					}
					?>
					   </select>
			</p>
		</div>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title']        = wp_strip_all_tags( $new_instance['title'] );
		$instance['text_before']  = wp_strip_all_tags( $new_instance['text_before'] );
		$instance['button_label'] = wp_strip_all_tags( $new_instance['button_label'] );
		$instance['id']           = sanitize_html_class( $new_instance['id'] );
		$instance['class']        = implode( ' ', array_map( 'sanitize_html_class', explode( ' ', $new_instance['class'] ) ) );
		$instance['type'] 		  = wp_strip_all_tags( $new_instance['type'] );

		do_action( 'wpml_register_single_string', 'Widgets', 'Snax Call to Action - text field', $instance['text_before'] );
		do_action( 'wpml_register_single_string', 'Widgets', 'Snax Call to Action - button label field', $instance['button_label'] );

		return $instance;
	}

	/**
	 * Get default arguments
	 *
	 * @return array
	 */
	public function get_default_args() {
		return apply_filters( 'snax_widget_lists_defaults', array(
			'title'        => '',
			'text_before'  => esc_html__( 'Unleash your creativity and share you story with us!', 'snax' ),
			'button_label' => esc_html__( 'Create', 'snax' ),
			'id'           => '',
			'class'        => '',
			'type'         => 'all',
		) );
	}
}

/**
 * Class Snax_Widget_Teaser
 */
class Snax_Widget_Teaser extends WP_Widget {

	/**
	 * The total number of displayed widgets
	 *
	 * @var int
	 */
	static $counter = 1;

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		$widget_options = apply_filters( 'snax_widget_lists_options', array(
			'classname'   => 'snax snax-teaser',
			'description' => esc_html__( 'A post teaser', 'snax' ),
		) );

		parent::__construct( 'snax_widget_teaser', esc_html__( 'Snax Teaser', 'snax' ), $widget_options );
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->get_default_args() );
		echo wp_kses_post( $args['before_widget'] );

		do_action( 'snax_before_widget_teaser_title' );
		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}

		$post_id = $instance['post_id'];
		if ( $post_id ) {
			$template = '/widget-teaser/' . $instance['group'];
			set_query_var( 'snax_widget_teaser_post_id', $post_id );
			snax_get_template_part( $template );
		}

		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->get_default_args() );
		$post_groups = $this->get_post_groups();
		?>
		<div class="snax-widget-lists">
			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget title', 'snax' ); ?>
					:</label>
				<input class="widefat"
				       id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
				       value="<?php echo esc_attr( $instance['title'] ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'post_id' ) ); ?>"><?php esc_html_e( 'Post', 'snax' ); ?></label>
				<select class="widefat" id="<?php echo esc_attr( $this -> get_field_id( 'post_id' ) ); ?>" name="<?php echo  esc_attr( $this -> get_field_name( 'post_id' ) ); ?>" >
				<?php foreach ( $post_groups as $group ) {
					echo '<optgroup label="' . $group['label'] . '">';
					foreach ( $group['posts'] as $post ) {
						$id = $post->ID . '__' . $group['slug'];
						$old_id = $instance['post_id'] . '__' . $instance['group'];
						$label = $post->post_title;
						echo '<option value="' . esc_attr( $id ) . '"' . selected( $id === $old_id , true, false ) . '>' . esc_html( $label ) . '</option>';
					}
					echo '</optgroup>';
				}?>
				</select>
			</p>
		</div>
		<?php
	}
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$id = explode( '__', filter_var( $new_instance['post_id'], FILTER_SANITIZE_STRING ) );
		$instance['post_id'] = $id[0];
		$instance['group'] = $id[1];
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}

	/**
	 * Get default arguments
	 *
	 * @return array
	 */
	public function get_default_args() {
		return apply_filters( 'snax_widget_teaser_defaults', array(
			'post_id' => esc_html__( 'Recently created lists', 'snax' ),
			'title'        => '',
		) );
	}

	/**
	 * Get groupped posts to select from
	 *
	 * @return array
	 */
	private function get_post_groups() {
		$groups = array();

		$posts = get_posts( array(
			'post_type'         => snax_get_poll_post_type(),
			'posts_per_page'    => -1,
			'meta_query'		=> array(
				array(
					'key'          => '_snax_poll_type',
					'value'        => 'binary',
				),
			),
		) );
		if ( $posts ) {
			$groups[] = array(
				'label'	=> 'Binary Polls',
				'slug'	=> 'poll-binary',
				'posts' => $posts,
			);
		}

		$posts = get_posts( array(
			'post_type'         => snax_get_poll_post_type(),
			'posts_per_page'    => -1,
			'meta_query'		=> array(
				array(
					'key'          => '_snax_poll_type',
					'value'        => 'versus',
				),
			),
		) );
		if ( $posts ) {
			$groups[] = array(
				'label'	=> 'Versus Polls',
				'slug'	=> 'poll-versus',
				'posts' => $posts,
			);
		}

		return $groups;
	}

}
