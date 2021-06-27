<?php
/**
 * Twitter map.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Elementor widget that inserts an embeddable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Twitter extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_twitter';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Twitter', 'woodmart' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'wd-icon-twitter';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'wd-elements' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		/**
		 * Content tab.
		 */

		/**
		 * General settings.
		 */
		$this->start_controls_section(
			'general_content_section',
			[
				'label' => esc_html__( 'General', 'woodmart' ),
			]
		);

		$this->add_control(
			'consumer_key',
			[
				'label' => esc_html__( 'Consumer Key', 'woodmart' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'consumer_secret',
			[
				'label' => esc_html__( 'Consumer Secret', 'woodmart' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'access_token',
			[
				'label' => esc_html__( 'Access Token', 'woodmart' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'accesstoken_secret',
			[
				'label' => esc_html__( 'Access Token Secret', 'woodmart' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'name',
			[
				'label'   => esc_html__( 'Twitter Name (without @ symbol)', 'woodmart' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Twitter',
			]
		);

		$this->add_control(
			'num_tweets',
			[
				'label'   => esc_html__( 'Number of Tweets', 'woodmart' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 5,
			]
		);

		$this->add_control(
			'avatar_size',
			[
				'label'       => esc_html__( 'Size of Avatar', 'woodmart' ),
				'description' => esc_html__( 'Default: 48px', 'woodmart' ),
				'type'        => Controls_Manager::NUMBER,
			]
		);

		$this->add_control(
			'show_avatar',
			[
				'label'        => esc_html__( 'Show your avatar image', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'exclude_replies',
			[
				'label'        => esc_html__( 'Exclude Replies', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$default_settings = [
			'name'               => 'Twitter',
			'num_tweets'         => 5,
			'cache_time'         => 5,
			'consumer_key'       => '',
			'consumer_secret'    => '',
			'access_token'       => '',
			'accesstoken_secret' => '',
			'show_avatar'        => 0,
			'avatar_size'        => '',
			'exclude_replies'    => false,
		];

		$settings = wp_parse_args( $this->get_settings_for_display(), $default_settings );
		
		if ( ! $settings['name'] || ! $settings['consumer_key'] || ! $settings['consumer_secret'] || ! $settings['access_token'] || ! $settings['accesstoken_secret'] ) {
			echo '<div class="wd-notice wd-info">' . esc_html__( 'You need to enter your Consumer key and secret to display your recent twitter feed.', 'woodmart' ) . '</div>';
			return;
		}

		woodmart_enqueue_inline_style( 'twitter' );

		?>
		<div class="wd-twitter-element wd-twitter-vc-element">
			<?php woodmart_get_twitts( $settings ); ?>
		</div>
		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Twitter() );
