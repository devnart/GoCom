/* global woodmart_settings */
(function($) {
	woodmartThemeModule.fullScreenMenu = function() {
		$('.wd-header-fs-nav > a').on('click', function(e) {
			e.preventDefault();

			$('.wd-fs-menu').addClass('wd-opened');
		});

		woodmartThemeModule.$document.on('keyup', function(e) {
			if (e.keyCode === 27) {
				$('.wd-fs-close').click();
			}
		});

		$('.wd-fs-close').on('click', function() {
			$('.wd-fs-menu').removeClass('wd-opened');

			setTimeout(function() {
				$('.wd-nav-fs .menu-item-has-children').removeClass('sub-menu-open');
				$('.wd-nav-fs .menu-item-has-children .wd-nav-opener').removeClass('wd-active');
			}, 200);
		});

		$('.wd-nav-fs > .menu-item-has-children > a, .wd-nav-fs .wd-dropdown-fs-menu.wd-design-default .menu-item-has-children > a').append('<span class="wd-nav-opener"></span>');

		$('.wd-nav-fs').on('click', '.wd-nav-opener', function(e) {
			e.preventDefault();
			var $icon       = $(this),
			    $parentItem = $icon.parent().parent();

			if ($parentItem.hasClass('sub-menu-open')) {
				$parentItem.removeClass('sub-menu-open');
				$icon.removeClass('wd-active');
			} else {
				$parentItem.siblings('.sub-menu-open').find('.wd-nav-opener').removeClass('wd-active');
				$parentItem.siblings('.sub-menu-open').removeClass('sub-menu-open');
				$parentItem.addClass('sub-menu-open');
				$icon.addClass('wd-active');
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.fullScreenMenu();
	});
})(jQuery);
