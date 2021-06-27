/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit', function () {
		woodmartThemeModule.stickySidebarBtn();
	});

	woodmartThemeModule.stickySidebarBtn = function() {
		var $trigger = $('.wd-show-sidebar-btn');
		var $stickyBtn = $('.wd-sidebar-opener.wd-on-shop:not(.toolbar)');

		if ($stickyBtn.length <= 0 || $trigger.length <= 0 || woodmartThemeModule.$window.width() >= 1024) {
			return;
		}

		var stickySidebarBtnToggle = function() {
			var btnOffset = $trigger.offset().top + $trigger.outerHeight();
			var windowScroll = woodmartThemeModule.$window.scrollTop();

			if (btnOffset < windowScroll) {
				$stickyBtn.addClass('wd-sticky');
			} else {
				$stickyBtn.removeClass('wd-sticky');
			}
		};

		stickySidebarBtnToggle();

		woodmartThemeModule.$window.on('scroll', stickySidebarBtnToggle);
		woodmartThemeModule.$window.on('resize', stickySidebarBtnToggle);
	};

	$(document).ready(function() {
		woodmartThemeModule.stickySidebarBtn();
	});
})(jQuery);
