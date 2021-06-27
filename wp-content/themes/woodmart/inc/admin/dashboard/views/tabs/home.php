<div class="woodmart-main-import-area woodmart-row woodmart-two-columns <?php echo esc_attr( WOODMART_Registry()->import->get_imported_versions_css_classes() ); ?>">
	<div class="woodmart-column import-base">
		<div class="woodmart-column-inner">
			<div class="woodmart-box woodmart-box-shadow">
				<div class="woodmart-box-header">
					<h2>Basic or full import</h2>
					<span class="woodmart-box-label woodmart-label-error"><?php esc_html_e('Required', 'woodmart'); ?></span>
				</div>
				<div class="woodmart-box-content">
					<div class="woodmart-success base-imported-alert">
						Base dummy content is successfully imported and installed. Now you can choose any version to apply its settings for your website or leave a default one.
						You are be able to switch to default version settings any time.
					</div>
					<?php WOODMART_Registry()->import->imported_versions(); ?>
					<?php WOODMART_Registry()->import->base_import_screen(); ?>
				</div>
				<div class="woodmart-box-footer">
					<p>
						<strong>Basic import</strong> includes default version from our demo and a few products, blog posts and portfolio projects.
						It is a required minimum to see how our theme built and to be able to import additional
						versions or pages.
					</p>
				</div>
			</div>
		</div>
	</div>
	<div class="woodmart-column import-versions">
		<div class="woodmart-column-inner">
			<div class="woodmart-box woodmart-box-shadow">
				<div class="woodmart-box-header">
					<h2>Demo versions</h2>
					<span class="woodmart-box-label woodmart-label-warning"><?php esc_html_e('Recommended', 'woodmart'); ?></span>
				</div>
				<div class="woodmart-box-content">
					<div class="woodmart-warning choose-version-warning">Now you can select a version, apply its settings and set it as a home page.
						<br>
						Or just leave this and continue using default version. You will be able to activate any version later.
					</div>
					<?php WOODMART_Registry()->import->versions_import_screen(); ?>
				</div>
				<div class="woodmart-box-footer">
					<p>
						<strong>Demo version</strong> includes page content, slider and settings for one
						of our versions. Import will also change your home page
						and may add some widgets.<br>
						<strong>WARNING</strong>: it may change your Theme Settings.
					</p>
				</div>
			</div>
		</div>
	</div>
</div>