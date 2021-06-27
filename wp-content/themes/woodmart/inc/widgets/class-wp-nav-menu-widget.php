<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * Custom Navigation Menu widget class
 *
 */

if( ! class_exists( 'WOODMART_WP_Nav_Menu_Widget' ) ) {
	class WOODMART_WP_Nav_Menu_Widget extends WP_Widget {

		public function __construct() {
			$widget_ops = array( 'description' => esc_html__('Add a custom mega menu to your sidebar.', 'woodmart') );
			parent::__construct( 'nav_mega_menu', esc_html__('WOODMART Sidebar Mega Menu', 'woodmart'), $widget_ops );
		}

		public function widget($args, $instance) {
			// Get menu
			$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

			if ( !$nav_menu )
				return;

			/** This filter is documented in wp-includes/default-widgets.php */
			$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			echo wp_kses_post( $args['before_widget'] );

			if ( !empty($instance['title']) )
				echo wp_kses_post( $args['before_title'] ) . $instance['title'] . wp_kses_post( $args['after_title'] );

			wp_nav_menu( array(
				'fallback_cb' => '',
				'container'  => '',
				'menu' => $nav_menu,
				'menu_class' => 'menu wd-nav wd-nav-vertical' . woodmart_get_old_classes( ' vertical-navigation' ),
				'walker' => new WOODMART_Mega_Menu_Walker()
			) );

			echo wp_kses_post( $args['after_widget'] );
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			if ( ! empty( $new_instance['title'] ) ) {
				$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
			}
			if ( ! empty( $new_instance['nav_menu'] ) ) {
				$instance['nav_menu'] = (int) $new_instance['nav_menu'];
			}
			return $instance;
		}

		public function form( $instance ) {
			$title = isset( $instance['title'] ) ? $instance['title'] : '';
			$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

			// Get menus
			$menus = wp_get_nav_menus();

			// If no menus exists, direct the user to go and create some.
			if ( !$menus ) {
				echo '<p>'. sprintf( 'No menus have been created yet. <a href="%s">Create some</a>.', admin_url('nav-menus.php') ) .'</p>';
				return;
			}
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e('Title:', 'woodmart') ?></label>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php echo sanitize_text_field( $title ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id('nav_menu') ); ?>"><?php esc_html_e('Select Menu:', 'woodmart'); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id('nav_menu') ); ?>" name="<?php echo esc_attr( $this->get_field_name('nav_menu') ); ?>">
					<option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'woodmart' ) ?></option>
			<?php
				foreach ( $menus as $menu ) {
					echo '<option value="' . $menu->term_id . '"'
						. selected( $nav_menu, $menu->term_id, false )
						. '>'. esc_html( $menu->name ) . '</option>';
				}
			?>
				</select>
			</p>
			<?php
		}
	}
}
