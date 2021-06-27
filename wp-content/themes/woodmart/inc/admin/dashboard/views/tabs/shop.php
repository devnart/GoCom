<div class="woodmart-main-import-area woodmart-row woodmart-two-columns <?php echo esc_attr( WOODMART_Registry()->import->get_imported_versions_css_classes() ); ?>">
	<div class="woodmart-column import-shop-options">
		<div class="woodmart-column-inner">
			<div class="woodmart-box woodmart-box-shadow">
				<div class="woodmart-box-header">
					<h2>Shop page options</h2>
					<span class="woodmart-box-label woodmart-label-success"><?php esc_html_e('Optional', 'woodmart'); ?></span>
				</div>
				<div class="woodmart-box-content">
					<?php WOODMART_Registry()->import->shops_import_screen(); ?>
				</div>
				<div class="woodmart-box-footer">
					<p>Set up <strong>shop page</strong> settings examples from our demo. It may replace some of your theme settings.</p>
				</div>
			</div>
		</div>
	</div>
	<div class="woodmart-column import-single-products">
		<div class="woodmart-column-inner">
			<div class="woodmart-box woodmart-box-shadow">
				<div class="woodmart-box-header">
					<h2>Single product page layouts</h2>
					<span class="woodmart-box-label woodmart-label-success"><?php esc_html_e('Optional', 'woodmart'); ?></span>
				</div>
				<div class="woodmart-box-content">
					<?php WOODMART_Registry()->import->products_import_screen(); ?>
				</div>
				<div class="woodmart-box-footer">
					<p>Set up <strong>product page</strong> settings examples from our demo. It may replace some of your theme settings.</p>
				</div>
			</div>
		</div>
	</div>
</div>