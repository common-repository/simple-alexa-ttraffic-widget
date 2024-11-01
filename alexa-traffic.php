<?php
/*
Plugin Name: Simple Alexa Traffic Widget
Plugin URI: http://egyfirst.com/alexa-traffic-widget/
Description: An easy-to-use widget to show the alexa traffic. 
Author: EgyFirst Software
Author URI: http://egyfirst.com
Version: 1.0
*/

class Simple_Alexa_Traffic_Widget extends WP_Widget {

	/**
	 * Default widget values.
	 *
	 * @var array
	 */
	protected $defaults;


	/**
	 * Constructor method.
	 *
	 * Set some global values and create widget.
	 */
	function __construct() {

		/**
		 * Default widget option values.
		 */
		$this->defaults = array(
			'url'                  => '',
			'type'             => a,
		);

		$widget_ops = array(
			'classname'   => 'alexa-traffic',
			'description' => __( 'Displays alexa traffic stats.', 'at' ),
		);

		$control_ops = array(
			'id_base' => 'alexa-traffic',
		);

		$this->WP_Widget( 'alexa-traffic', __( 'Alexa traffic', 'at' ), $widget_ops, $control_ops );

	}

	/**
	 * Widget Form.
	 *
	 * Outputs the widget form that allows users to control the output of the widget.
	 *
	 */
	function form( $instance ) {

		/** Merge with defaults */
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		?>

		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'Url:' ); ?></label></p>
		<p><input type="text" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" value="<?php echo esc_attr( $instance['url'] ); ?>" class="widefat" /></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Type', 'at' ); ?>:</label>
			<select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">
				<option value="a" <?php selected( 'a', $instance['type'] ) ?>><?php _e( 'Small Button', 'at' ); ?></option>
				<option value="b" <?php selected( 'b', $instance['type'] ) ?>><?php _e( 'Regular Button', 'at' ); ?></option>
				<option value="c" <?php selected( 'c', $instance['type'] ) ?>><?php _e( 'Banner', 'at' ); ?></option>
			</select>
		</p>
		
		
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52612b3e70c1955c"></script>
<!-- AddThis Button END -->


<?php


	}

	/**
	 * Form validation and sanitization.
	 *
	 * Runs when you save the widget form. Allows you to validate or sanitize widget options before they are saved.
	 *
	 */
	function update( $newinstance, $oldinstance ) {

		foreach ( $newinstance as $key => $value ) {

			/** Sanitize Profile URIs */
			if ( array_key_exists( $key, (array) $this->profiles ) ) {
				$newinstance[$key] = esc_url( $newinstance[$key] );
			}

		}

		return $newinstance;

	}

	/**
	 * Widget Output.
	 *
	 * Outputs the actual widget on the front-end based on the widget options the user selected.
	 *
	 */
	function widget( $args, $instance ) {

		extract( $args );

		/** Merge with defaults */
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $before_widget;

			if ( ! empty( $instance['title'] ) )
				echo $before_title . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $after_title;

			printf( '<a href="http://www.alexa.com/siteinfo/%s"><script type="text/javascript" src="http://xslt.alexa.com/site_stats/js/s/%s?url=%s"></script></a>', $instance['url'],$instance['type'],$instance['url']);

		echo $after_widget;

	}

}

add_action( 'widgets_init', 'at_load_widget' );
/**
 * Widget Registration.
 *
 * Register Social Media Links widget.
 *
 */
function at_load_widget() {

	register_widget( 'Simple_Alexa_Traffic_Widget' );

}

function alexa_traffic_func( $atts ) {
	extract( shortcode_atts( array(
		'url' => '',
		'type' => 'a',
	), $atts ) );

	return "<a href='http://www.alexa.com/siteinfo/{$url}'><script type='text/javascript' src='http://xslt.alexa.com/site_stats/js/s/{$type}?url={$url}'></script></a>";
}
add_shortcode( 'alexa_traffic', 'alexa_traffic_func' );