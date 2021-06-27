/* global woodmart_settings */
(function($) {
	woodmartThemeModule.ajaxPortfolio = function() {
		if ('no' === woodmart_settings.ajax_portfolio || 'undefined' === typeof ($.fn.pjax)) {
			return;
		}

		var ajaxLinks = '.wd-type-links .wd-nav-portfolio a, .tax-project-cat .wd-pagination a, .post-type-archive-portfolio .wd-pagination a';

		woodmartThemeModule.$body.on('click', '.tax-project-cat .wd-pagination a, .post-type-archive-portfolio .wd-pagination a', function() {
			scrollToTop(true);
		});

		woodmartThemeModule.$document.pjax(ajaxLinks, '.main-page-wrapper', {
			timeout : woodmart_settings.pjax_timeout,
			scrollTo: false,
			renderCallback: function(context, html, afterRender) {
				woodmartThemeModule.removeDuplicatedStylesFromHTML(html, function(html) {
					context.html(html);
					afterRender();
				});
			}
		});

		woodmartThemeModule.$document.on('pjax:start', function() {
			var $siteContent = $('.site-content');

			$siteContent.removeClass('ajax-loaded');
			$siteContent.addClass('ajax-loading');

			woodmartThemeModule.$document.trigger('wdPortfolioPjaxStart');
			woodmartThemeModule.$window.trigger('scroll.loaderVerticalPosition');
		});

		woodmartThemeModule.$document.on('pjax:end', function() {
			$('.site-content').removeClass('ajax-loading');
		});

		woodmartThemeModule.$document.on('pjax:complete', function() {
			if (!woodmartThemeModule.$body.hasClass('tax-project-cat') && !woodmartThemeModule.$body.hasClass('post-type-archive-portfolio')) {
				return;
			}

			woodmartThemeModule.$document.trigger('wdPortfolioPjaxComplete');
			woodmartThemeModule.$document.trigger('wood-images-loaded');

			scrollToTop(false);

			$('.wd-ajax-content').removeClass('wd-loading');
		});

		var scrollToTop = function(type) {
			if (woodmart_settings.ajax_scroll === 'no' && type === false) {
				return false;
			}

			var $scrollTo = $(woodmart_settings.ajax_scroll_class),
			    scrollTo  = $scrollTo.offset().top - woodmart_settings.ajax_scroll_offset;

			$('html, body').stop().animate({
				scrollTop: scrollTo
			}, 400);
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.ajaxPortfolio();
	});
})(jQuery);
