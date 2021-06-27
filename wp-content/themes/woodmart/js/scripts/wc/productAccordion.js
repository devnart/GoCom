/* global woodmart_settings */
(function($) {
	woodmartThemeModule.productAccordion = function() {
		var $accordion = $('.wc-tabs-wrapper');
		var time = 300;
		var hash = window.location.hash;
		var url = window.location.href;

		if (hash.toLowerCase().indexOf('comment-') >= 0 || hash === '#reviews' || hash === '#tab-reviews') {
			$accordion.find('.tab-title-reviews').addClass('active');
		} else if (url.indexOf('comment-page-') > 0 || url.indexOf('cpage=') > 0) {
			$accordion.find('.tab-title-reviews').addClass('active');
		} else {
			$accordion.find('.wd-accordion-title').first().addClass('active');
		}

		$('.woocommerce-review-link').on('click', function() {
			$('.wd-accordion-title.tab-title-reviews').click();
		});

		$accordion.on('click', '.wd-accordion-title', function(e) {
			e.preventDefault();

			var $this  = $(this),
			    $panel = $this.siblings('.woocommerce-Tabs-panel');

			var currentIndex = $this.parent().index();
			var oldIndex = $this.parent().siblings().find('.active').parent('.wd-tab-wrapper').index();

			if ($this.hasClass('active')) {
				oldIndex = currentIndex;
				$this.removeClass('active');
				$panel.stop().slideUp(time);
			} else {
				$accordion.find('.wd-accordion-title').removeClass('active');
				$accordion.find('.woocommerce-Tabs-panel').slideUp();
				$this.addClass('active');
				$panel.stop().slideDown(time);
			}

			if (currentIndex === -1) {
				oldIndex = currentIndex;
			}

			woodmartThemeModule.$window.trigger('resize');

			setTimeout(function() {
				woodmartThemeModule.$window.trigger('resize');

				if (woodmartThemeModule.$window.width() < 1024 && currentIndex > oldIndex) {
					var $header = $('.sticky-header');
					var headerHeight = $header.length > 0 ? $header.outerHeight() : 0;
					$('html, body').animate({
						scrollTop: $this.offset().top - $this.outerHeight() - headerHeight - 50
					}, 500);
				}
			}, time);

			woodmartThemeModule.$document.trigger('wood-images-loaded');
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.productAccordion();
	});
})(jQuery);
