<div class="woodmart-row woodmart-one-column">
	<div class="woodmart-column woodmart-stretch-column">
		<div class="woodmart-column-inner">
			<div class="woodmart-box woodmart-box-shadow">
				<div class="woodmart-box-header">
					<h2>Activate your theme license</h2>
					<span class="woodmart-box-label <?php if ( woodmart_is_license_activated() ): ?>woodmart-label-success<?php else: ?>woodmart-label-warning<?php endif; ?>">
						<?php if ( woodmart_is_license_activated() ): ?>
							<?php esc_html_e('Activated', 'woodmart'); ?>
						<?php else: ?>
							<?php esc_html_e('Optional', 'woodmart'); ?>
						<?php endif ?>
					</span>
				</div>
				<div class="woodmart-box-content">
					<?php WOODMART_Registry()->license->form(); ?>
				</div>
				<?php if ( ! woodmart_get_opt( 'white_label' ) ) : ?>
					<div class="woodmart-box-footer">
						<p>
							<strong>Note:</strong> you are allowed to use our theme only on one domain if you have purchased a regular license. <br/>
							But we give you an ability to activate our theme to turn on auto updates on two domains: for the development website and for your production (live) website.<br>
							If you need to check all your active domains or you want to remove some of them you should visit <a href="https://xtemos.com/" target="_blank">our website</a> and check the activation list in your account.
						</p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>