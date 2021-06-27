/* global woodmart_settings */
(function($) {
	woodmartThemeModule.footerWidgetsAccordion = function() {
		if (woodmartThemeModule.windowWidth >= 576) {
			return;
		}

		$('.footer-widget-collapse .widget-title').on('click', function() {
			var $title = $(this);
			var $widget = $title.parent();
			var $content = $widget.find('> *:not(.widget-title)');

			if ($widget.hasClass('footer-widget-opened')) {
				$widget.removeClass('footer-widget-opened');
				$content.stop().slideUp(200);
			} else {
				$widget.addClass('footer-widget-opened');
				$content.stop().slideDown(200);
				woodmartThemeModule.$document.trigger('wood-images-loaded');
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.footerWidgetsAccordion();
	});
})(jQuery);
