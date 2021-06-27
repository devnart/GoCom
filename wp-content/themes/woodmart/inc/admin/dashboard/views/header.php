<div class="wrap">
	<div class="about-wrap">
		<h1>
			<?php if ( woodmart_get_opt( 'white_label' ) && woodmart_get_opt( 'white_label_dashboard_title' ) ) : ?>
				<?php echo esc_html( woodmart_get_opt( 'white_label_dashboard_title' ) ); ?>
			<?php else : ?>
				Welcome to Woodmart dashboard
			<?php endif; ?>
		</h1>

		<div class="about-text">
			<?php if ( woodmart_get_opt( 'white_label' ) && woodmart_get_opt( 'white_label_dashboard_text' ) ) : ?>
				<?php echo esc_html( woodmart_get_opt( 'white_label_dashboard_text' ) ); ?>
			<?php else : ?>
				Thank you for purchasing our premium eCommerce theme - Woodmart. Here you are able to start creating your awesome web store by importing our dummy content and theme options.
			<?php endif; ?>
		</div>

		<div class="woodmart-theme-badge">
			<?php
			$badge_url = WOODMART_ASSETS_IMAGES . '/woodmart-badge.png';

			if ( woodmart_get_opt( 'white_label_dashboard_logo' ) && woodmart_get_opt( 'white_label' ) ) {
				$image_data = woodmart_get_opt( 'white_label_dashboard_logo' );

				if ( isset( $image_data['url'] ) && $image_data['url'] ) {
					$badge_url = wp_get_attachment_image_url( $image_data['id'], 'medium' );
				}
			}
			?>
			<img src="<?php echo esc_url( $badge_url ); ?>">
			<span>
				<?php
				$theme_version = explode( '.', woodmart_get_theme_info( 'Version' ) );
				echo esc_html( $theme_version[0] . '.' . $theme_version[1] );
				?>
			</span>
		</div>

		<?php if ( apply_filters( 'woodmart_dashboard_links', true ) ) : ?>
			<p class="redux-actions">
				<a href="https://xtemos.com/documentation/woodmart/" target="_blank" class="btn-docs button">Docs</a>
				<a href="https://www.youtube.com/playlist?list=PLMw6W4rAaOgKKv0oexGHzpWBg1imvrval" target="_blank" class="btn-videos button">Video
					tutorials</a>
				<a href="https://themeforest.net/downloads" class="btn-rate button" target="_blank">Rate our theme</a>
				<a href="https://xtemos.com/forums/forum/woodmart-premium-template/" class="btn-support button button-primary" target="_blank">Support
					forum</a>
			</p>
		<?php endif; ?>
	</div>

	<div class="woodmart-wrap-content">
		<h2 class="nav-tab-wrapper">
			<?php foreach ( $this->get_tabs() as $tab => $title ) : ?>
				<a class="nav-tab 
				<?php
				if ( $this->get_current_tab() == $tab ) {
					echo 'nav-tab-active';
				}
				?>
				" href="<?php echo esc_url( $this->tab_url( $tab ) ); ?>"><?php echo esc_html( $title ); ?></a>
			<?php endforeach ?>
		</h2>
