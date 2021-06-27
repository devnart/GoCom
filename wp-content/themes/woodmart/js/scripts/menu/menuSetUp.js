/* global woodmart_settings */
(function($) {
	woodmartThemeModule.menuSetUp = function() {
		var hasChildClass = 'menu-item-has-children',
		    mainMenu      = $('.wd-nav'),
		    openedClass   = 'wd-opened';

		$('.mobile-nav').find('ul.wd-nav-mobile').find(' > li').has('.wd-dropdown-menu').addClass(hasChildClass);

		mainMenu.on('click', ' > .wd-event-click > a', function(e) {
			e.preventDefault();
			var $this = $(this);

			if (!$this.parent().hasClass(openedClass)) {
				$('.' + openedClass).removeClass(openedClass);
			}

			$this.parent().toggleClass(openedClass);
		});

		woodmartThemeModule.$document.on('click', function(e) {
			var target = e.target;

			if ($('.' + openedClass).length > 0 && !$(target).is('.wd-event-hover') && !$(target).parents().is('.wd-event-hover') && !$(target).parents().is('.' + openedClass + '')) {
				mainMenu.find('.' + openedClass + '').removeClass(openedClass);
			}
		});

		if ('yes' === woodmart_settings.menu_item_hover_to_click_on_responsive) {
			function menuIpadClick() {
				if (woodmartThemeModule.$window.width() <= 1024) {
					mainMenu.find(' > .menu-item-has-children.wd-event-hover').each(function() {
						$(this).data('original-event', 'hover').removeClass('wd-event-hover').addClass('wd-event-click');
					});
				} else {
					mainMenu.find(' > .wd-event-click').each(function() {
						var $this = $(this);

						if ($this.data('original-event') === 'hover') {
							$this.removeClass('wd-event-click').addClass('wd-event-hover');
						}
					});
				}
			}

			menuIpadClick();

			woodmartThemeModule.$window.on('resize', woodmartThemeModule.debounce(function() {
				menuIpadClick();
			}, 300));
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.menuSetUp();
	});
})(jQuery);
