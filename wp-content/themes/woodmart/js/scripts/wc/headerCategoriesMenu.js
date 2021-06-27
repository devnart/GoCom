/* global woodmart_settings */
(function($) {
	woodmartThemeModule.headerCategoriesMenu = function() {
		if (woodmartThemeModule.windowWidth > 1024) {
			return;
		}

		var categories    = $('.wd-header-cats'),
		    catsUl        = categories.find('.categories-menu-dropdown'),
		    subCategories = categories.find('.menu-item-has-children'),
		    button        = categories.find('.menu-opener'),
		    time          = 200,
		    iconDropdown  = '<span class="drop-category"></span>';

		subCategories.find('> a').before(iconDropdown);

		catsUl.on('click', '.drop-category', function() {
			var $this = $(this);
			var sublist = $this.parent().find('> .wd-dropdown-menu, >.sub-sub-menu');

			if (sublist.hasClass('child-open')) {
				$this.removeClass('act-icon');
				sublist.slideUp(time).removeClass('child-open');
			} else {
				$this.addClass('act-icon');
				sublist.slideDown(time).addClass('child-open');
			}
		});

		categories.on('click', '.menu-opener', function(e) {
			e.preventDefault();

			if (isOpened()) {
				closeCats();
			} else {
				openCats();
			}
		});

		catsUl.on('click', 'a', function() {
			closeCats();
			catsUl.stop().attr('style', '');
		});

		var isOpened = function() {
			return catsUl.hasClass('categories-opened');
		};

		var openCats = function() {
			catsUl.addClass('categories-opened').stop().slideDown(time);
			button.addClass('button-open');

		};

		var closeCats = function() {
			catsUl.removeClass('categories-opened').stop().slideUp(time);
			button.removeClass('button-open');
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.headerCategoriesMenu();
	});
})(jQuery);
