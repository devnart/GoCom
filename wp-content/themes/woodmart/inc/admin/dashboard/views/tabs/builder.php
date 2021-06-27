<div class="woodmart-main-import-area woodmart-row woodmart-one-column">
	<div class="woodmart-column woodmart-stretch-column">
		<div class="woodmart-column-inner">
			<div class="woodmart-box woodmart-box-shadow">
				<div class="woodmart-box-header">
					<?php if ( woodmart_get_opt( 'white_label' ) ) : ?>
						<h2>WoodMart header builder</h2>
					<?php else : ?>
						<h2>Header builder</h2>
					<?php endif; ?>
				</div>
				<div class="woodmart-box-content">
					<div id="whb-header-builder">
						<div class="xts-notice xts-info">
							<?php esc_attr_e( 'The header builder cannot be loaded correctly. Probably, there is some JS conflict with some of the installed plugins or some issue with the header builder script. Check your JS console to debug this. Try to disable all external plugins and be sure that you have the latest version of the theme installed and then check again.', 'woodmart' ); ?>
						</div>
					</div>
				</div>
				<?php if ( apply_filters( 'woodmart_header_builder_admin_footer_links', true ) ) : ?>
					<div class="woodmart-box-footer">
						<p>
							<a href="https://xtemos.com/docs/woodmart/header-builder/" target="_blank" class="whb-docs-link">
								<span class="dashicons dashicons-media-document"></span>
								<span class="link-title"><?php esc_html_e( 'Documentation', 'woodmart' ); ?></span>
							</a>

							<a href="https://www.youtube.com/playlist?list=PLMw6W4rAaOgI5rk5lf0JqYXwPEkOAsAxt" target="_blank" class="whb-videos-link">
								<span class="dashicons dashicons-video-alt3"></span>
								<span class="link-title"><?php esc_html_e( 'Video tutorials', 'woodmart' ); ?></span>
							</a>
							<a href="https://xtemos.com/forums/forum/woodmart-premium-template/" target="_blank" class="whb-support-link">
								<span class="dashicons dashicons-sos"></span>
								<span class="link-title"><?php esc_html_e( 'Support forum', 'woodmart' ); ?></span>
							</a>
						</p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
