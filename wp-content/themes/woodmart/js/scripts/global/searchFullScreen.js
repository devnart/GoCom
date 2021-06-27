/* global woodmart_settings */
(function($) {
	woodmartThemeModule.searchFullScreen = function() {
		var searchWrapper = $('.wd-search-full-screen');

		woodmartThemeModule.$body.on('click', '.wd-header-search:not(.wd-header-search-mobile) > a', function(e) {

			e.preventDefault();

			if ($(this).parent().find('.wd-search-dropdown').length > 0) {
				return;
			}

			if (woodmartThemeModule.$body.hasClass('global-search-dropdown') || woodmartThemeModule.$window.width() < 1024) {
				return;
			}

			if (isOpened()) {
				closeWidget();
			} else {
				setTimeout(function() {
					openWidget();
				}, 10);
			}
		});

		woodmartThemeModule.$body.on('click', '.wd-close-search a, .main-page-wrapper, .header-banner', function(event) {

			if (!$(event.target).is('.wd-close-search a') && $(event.target).closest('.wd-search-full-screen').length) {
				return;
			}

			if (isOpened()) {
				closeWidget();
			}
		});

		var closeByEsc = function(e) {
			if (e.keyCode === 27) {
				closeWidget();
				woodmartThemeModule.$body.unbind('keyup', closeByEsc);
			}
		};

		var closeWidget = function() {
			woodmartThemeModule.$body.removeClass('wd-search-opened');
			searchWrapper.removeClass('wd-opened');
		};

		var openWidget = function() {
			var $bar = $('#wpadminbar');
			var barHeight = $bar.length > 0 ? $bar.outerHeight() : 0;
			var $sticked = $('.whb-sticked');
			var $mainHeader = $('.whb-main-header');
			var offset;

			if ($sticked.length > 0) {
				if ($('.whb-clone').length > 0) {
					offset = $sticked.outerHeight() + barHeight;
				} else {
					offset = $mainHeader.outerHeight() + barHeight;
				}
			} else {
				offset = $mainHeader.outerHeight() + barHeight;
				if (woodmartThemeModule.$body.hasClass('header-banner-display')) {
					offset += $('.header-banner').outerHeight();
				}
			}

			searchWrapper.css('top', offset);

			// Close by esc
			woodmartThemeModule.$body.on('keyup', closeByEsc);
			woodmartThemeModule.$body.addClass('wd-search-opened');

			searchWrapper.addClass('wd-opened');

			setTimeout(function() {
				searchWrapper.find('input[type="text"]').focus();

				woodmartThemeModule.$window.one('scroll', function() {
					if (isOpened()) {
						closeWidget();
					}
				});
			}, 300);
		};

		var isOpened = function() {
			return woodmartThemeModule.$body.hasClass('wd-search-opened');
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.searchFullScreen();
	});
})(jQuery);
