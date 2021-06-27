/* global woodmart_settings */
(function($) {
	woodmartThemeModule.menuOffsets = function() {
		var setOffset = function(li) {
			var $dropdown = li.find(' > .wd-dropdown-menu');
			var dropdownWidth = $dropdown.outerWidth();
			var dropdownOffset = $dropdown.offset();
			var toRight;
			var viewportWidth;
			var dropdownOffsetRight;

			$dropdown.attr('style', '');

			if (!dropdownWidth || !dropdownOffset) {
				return;
			}

			if ($dropdown.hasClass('wd-design-full-width')) {
				viewportWidth = woodmartThemeModule.$window.width();

				if (woodmartThemeModule.$body.hasClass('rtl')) {
					dropdownOffsetRight = viewportWidth - dropdownOffset.left - dropdownWidth;

					if (dropdownOffsetRight + dropdownWidth >= viewportWidth) {
						toRight = dropdownOffsetRight + dropdownWidth - viewportWidth;

						$dropdown.css({
							right: -toRight
						});
					}
				} else {
					if (dropdownOffset.left + dropdownWidth >= viewportWidth) {
						toRight = dropdownOffset.left + dropdownWidth - viewportWidth;

						$dropdown.css({
							left: -toRight
						});
					}
				}
			} else if ($dropdown.hasClass('wd-design-sized') || $dropdown.hasClass('wd-design-default')) {
				viewportWidth = woodmart_settings.site_width;

				if (woodmartThemeModule.$window.width() < viewportWidth || ! viewportWidth) {
					viewportWidth = woodmartThemeModule.$window.width();
				}

				dropdownOffsetRight = viewportWidth - dropdownOffset.left - dropdownWidth;

				var extraSpace = 15;
				var containerOffset = (woodmartThemeModule.$window.width() - viewportWidth) / 2;
				var dropdownOffsetLeft;

				if (woodmartThemeModule.$body.hasClass('rtl')) {
					dropdownOffsetLeft = containerOffset + dropdownOffsetRight;

					if (dropdownOffsetLeft + dropdownWidth >= viewportWidth) {
						toRight = dropdownOffsetLeft + dropdownWidth - viewportWidth;

						$dropdown.css({
							right: -toRight - extraSpace
						});
					}
				} else {
					dropdownOffsetLeft = dropdownOffset.left - containerOffset;

					if (dropdownOffsetLeft + dropdownWidth >= viewportWidth) {
						toRight = dropdownOffsetLeft + dropdownWidth - viewportWidth;

						$dropdown.css({
							left: -toRight - extraSpace
						});
					}
				}
			}
		};

		$('.wd-header-main-nav ul.menu > li, .wd-header-secondary-nav ul.menu > li').each(function() {
			var $menu = $(this);

			if ($menu.hasClass('menu-item')) {
				$menu = $(this).parent();
			}

			$menu.on('mouseenter mousemove', function() {
				if ($menu.hasClass('wd-offsets-calculated')) {
					return;
				}

				$menu.find(' > .menu-item-has-children').each(function() {
					setOffset($(this));
				});

				woodmartThemeModule.$document.trigger('resize.vcRowBehaviour');

				$menu.addClass('wd-offsets-calculated');
			});

			if ('yes' === woodmart_settings.clear_menu_offsets_on_resize) {
				setTimeout(function() {
					woodmartThemeModule.$window.on('resize', woodmartThemeModule.debounce(function() {
						$menu.removeClass('wd-offsets-calculated');
						$menu.find(' > .menu-item-has-children > .wd-dropdown-menu').attr('style', '');
					}, 300));
				}, 2000);
			}
		});
	};

	woodmartThemeModule.$window.on('wdEventStarted', function() {
		woodmartThemeModule.menuOffsets();
	});
})(jQuery);
