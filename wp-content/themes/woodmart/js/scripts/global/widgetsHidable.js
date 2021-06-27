/* global woodmart_settings */
(function($) {
	woodmartThemeModule.widgetsHidable = function() {
		woodmartThemeModule.$document.on('click', '.widget-hidable .widget-title', function() {
			var $this = $(this);
			var $content = $this.siblings('ul, div, form, label, select');

			$this.parent().toggleClass('widget-hidden');
			$content.stop().slideToggle(200);
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.widgetsHidable();
	});
})(jQuery);
