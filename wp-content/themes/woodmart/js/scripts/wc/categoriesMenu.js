/* global woodmart_settings */
(function($) {
	woodmartThemeModule.categoriesMenu = function() {
		if (woodmartThemeModule.windowWidth > 1024) {
			return;
		}

		var categories = $('.wd-nav-product-cat'),
		    time       = 200;

		woodmartThemeModule.$body.on('click', '.wd-nav-opener', function(e) {
			var $this = $(this);
			e.preventDefault();

			if ($this.closest('.has-sub').find('> ul').hasClass('child-open')) {
				$this.removeClass('wd-active').closest('.has-sub').find('> ul').slideUp(time).removeClass('child-open');
			} else {
				$this.addClass('wd-active').closest('.has-sub').find('> ul').slideDown(time).addClass('child-open');
			}
		});

		woodmartThemeModule.$body.on('click', '.wd-btn-show-cat > a', function(e) {
			e.preventDefault();

			if (isOpened()) {
				closeCats();
			} else {
				openCats();
			}
		});

		woodmartThemeModule.$body.on('click', '.wd-nav-product-cat a', function(e) {
			if (!$(e.target).hasClass('wd-nav-opener')) {
				closeCats();
				categories.stop().attr('style', '');
			}
		});

		var isOpened = function() {
			return $('.wd-nav-product-cat').hasClass('categories-opened');
		};

		var openCats = function() {
			$('.wd-nav-product-cat').addClass('categories-opened').stop().slideDown(time);
			$('.wd-btn-show-cat').addClass('button-open');
		};

		var closeCats = function() {
			$('.wd-nav-product-cat').removeClass('categories-opened').stop().slideUp(time);
			$('.wd-btn-show-cat').removeClass('button-open');
		};
	};

	woodmartThemeModule.categoriesMenuBtns = function() {
		if (woodmartThemeModule.windowWidth > 1024) {
			return;
		}

		var categories    = $('.wd-nav-product-cat'),
		    subCategories = categories.find('li > ul'),
		    iconDropdown  = '<span class="wd-nav-opener"></span>';

		subCategories.parent().addClass('has-sub').append(iconDropdown);
	};

	$(document).ready(function() {
		woodmartThemeModule.categoriesMenu();
		woodmartThemeModule.categoriesMenuBtns();
	});
})(jQuery);
