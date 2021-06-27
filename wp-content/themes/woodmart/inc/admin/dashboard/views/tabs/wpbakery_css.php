<div class="woodmart-row woodmart-one-column">
	<div class="woodmart-column woodmart-stretch-column">
		<div class="woodmart-column-inner">
			<div class="woodmart-box woodmart-box-shadow">
				<div class="woodmart-box-header">
					<h2>Generate style.css file for your website</h2>
					<span class="woodmart-box-label woodmart-label-warning">
						<?php esc_html_e('Advanced', 'woodmart'); ?>
					</span>
				</div>
				<div class="woodmart-box-content">
					<?php WOODMART_Registry()->wpbcssgenerator->form(); ?>
				</div>
				<div class="woodmart-box-footer">
					<p>
						This section allows you to generate a custom CSS file based on our core theme styles. 
						It means that you can reduce your CSS file size if you are not using some part of the functionality. 
						Useful for performance and loading time optimization. 
					</p>
				</div>
			</div>
		</div>
	</div>
</div>