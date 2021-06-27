<div class="woodmart-main-import-area woodmart-row woodmart-two-columns <?php echo esc_attr( WOODMART_Registry()->import->get_imported_versions_css_classes() ); ?>">
	<div class="woodmart-column import-pages">
		<div class="woodmart-column-inner">
			<div class="woodmart-box woodmart-box-shadow">
				<div class="woodmart-box-header">
					<h2>Additional pages</h2>
					<span class="woodmart-box-label woodmart-label-success"><?php esc_html_e('Optional', 'woodmart'); ?></span>
				</div>
				<div class="woodmart-box-content">
					<?php WOODMART_Registry()->import->pages_import_screen(); ?>
				</div>
				<div class="woodmart-box-footer">
					<p>Import additional pages that may be useful for your website like Contacts, About us, FaQs, Services etc.</p>
				</div>
			</div>
		</div>
	</div>
	<div class="woodmart-column import-elements">
		<div class="woodmart-column-inner">
			<div class="woodmart-box woodmart-box-shadow">
				<div class="woodmart-box-header">
					<h2>Additional elements</h2>
					<span class="woodmart-box-label woodmart-label-success"><?php esc_html_e('Optional', 'woodmart'); ?></span>
				</div>
				<div class="woodmart-box-content">
					<?php WOODMART_Registry()->import->elements_import_screen(); ?>
				</div>
				<div class="woodmart-box-footer">
					<p>Elements pages demonstrate abilities of custom WPBakery Page Builder elements that come with our theme.</p>
				</div>
			</div>
		</div>
	</div>
</div>