/* global woodmart_settings */
(function($) {
	woodmartThemeModule.mobileNavigation = function() {
		var body        = woodmartThemeModule.$body,
		    mobileNav   = $('.mobile-nav'),
		    dropDownCat = $('.mobile-nav .wd-nav-mobile .menu-item-has-children'),
		    elementIcon = '<span class="wd-nav-opener"></span>';

		var closeSide = $('.wd-close-side');

		dropDownCat.append(elementIcon);

		mobileNav.on('click', '.wd-nav-opener', function(e) {
			e.preventDefault();
			var $this = $(this);
			var $parent = $this.parent();

			if ($parent.hasClass('opener-page')) {
				$parent.removeClass('opener-page').find('> ul').slideUp(200);
				$parent.removeClass('opener-page').find('.wd-dropdown-menu .container > ul, .wd-dropdown-menu > ul').slideUp(200);
				$parent.find('> .wd-nav-opener').removeClass('wd-active');
			} else {
				$parent.addClass('opener-page').find('> ul').slideDown(200);
				$parent.addClass('opener-page').find('.wd-dropdown-menu .container > ul, .wd-dropdown-menu > ul').slideDown(200);
				$parent.find('> .wd-nav-opener').addClass('wd-active');
			}
		});

		mobileNav.on('click', '.wd-nav-mob-tab li', function(e) {
			e.preventDefault();
			var $this = $(this);
			var menuName = $this.data('menu');

			if ($this.hasClass('wd-active')) {
				return;
			}

			$this.parent().find('.wd-active').removeClass('wd-active');
			$this.addClass('wd-active');
			$('.wd-nav-mobile').removeClass('wd-active');
			$('.mobile-' + menuName + '-menu').addClass('wd-active');
		});

		body.on('click', '.wd-header-mobile-nav > a', function(e) {
			e.preventDefault();

			if (mobileNav.hasClass('wd-opened')) {
				closeMenu();
			} else {
				openMenu();
			}
		});

		body.on('click touchstart', '.wd-close-side', function() {
			closeMenu();
		});

		body.on('click', '.mobile-nav .login-side-opener', function() {
			closeMenu();
		});

		function openMenu() {
			mobileNav.addClass('wd-opened');
			closeSide.addClass('wd-close-side-opened');
		}

		function closeMenu() {
			mobileNav.removeClass('wd-opened');
			closeSide.removeClass('wd-close-side-opened');
			$('.mobile-nav .searchform input[type=text]').blur();
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.mobileNavigation();
	});
})(jQuery);
