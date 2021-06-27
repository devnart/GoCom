/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdPjaxStart', function () {
		woodmartThemeModule.hideShopSidebar();
	});

	woodmartThemeModule.$document.on('wdShopPageInit', function () {
		woodmartThemeModule.hiddenSidebar();
	});

	woodmartThemeModule.hiddenSidebar = function() {
		var position = woodmartThemeModule.$body.hasClass('rtl') ? 'right' : 'left';

		if (woodmartThemeModule.$body.hasClass('offcanvas-sidebar-desktop') && woodmartThemeModule.windowWidth > 1024 || woodmartThemeModule.$body.hasClass('offcanvas-sidebar-tablet') && woodmartThemeModule.windowWidth <= 1024) {
			$('.area-sidebar-shop').addClass('wd-side-hidden wd-' + position + ' wd-inited wd-scroll');
			$('.area-sidebar-shop .widget-area').addClass('wd-scroll-content');
		}

		if (woodmartThemeModule.$body.hasClass('offcanvas-sidebar-mobile') && woodmartThemeModule.windowWidth <= 768) {
			$('.sidebar-container').addClass('wd-side-hidden wd-' + position + ' wd-inited wd-scroll');
			$('.sidebar-container .widget-area').addClass('wd-scroll-content');
		}

		woodmartThemeModule.$body.off('click', '.wd-show-sidebar-btn, .wd-sidebar-opener').on('click', '.wd-show-sidebar-btn, .wd-sidebar-opener', function(e) {
			e.preventDefault();

			if ($('.sidebar-container').hasClass('wd-opened')) {
				woodmartThemeModule.hideShopSidebar();
			} else {
				showSidebar();
			}
		});

		woodmartThemeModule.$body.on('click touchstart', '.wd-close-side, .close-side-widget', function() {
			woodmartThemeModule.hideShopSidebar();
		});

		var showSidebar = function() {
			$('.sidebar-container').addClass('wd-opened');
			$('.wd-close-side').addClass('wd-close-side-opened');
		};

		woodmartThemeModule.$document.trigger('wdHiddenSidebarsInited');
	};

	woodmartThemeModule.hideShopSidebar = function() {
		$('.sidebar-container').removeClass('wd-opened');
		$('.wd-close-side').removeClass('wd-close-side-opened');
	};

	$(document).ready(function() {
		woodmartThemeModule.hiddenSidebar();
	});
})(jQuery);
