/* global woodmart_settings */
(function($) {
	woodmartThemeModule.loginDropdown = function() {
		if (woodmartThemeModule.windowWidth <= 1024) {
			return;
		}

		$('.wd-dropdown-register').each(function() {
			var $this    = $(this),
			    $content = $this.find('.login-dropdown-inner');

			$content.find('input[id="username"]').on('click', function() {
				$this.addClass('wd-active-login').removeClass('wd-active-link');
			});

			$content.find('input[id="username"]').on('input', function() {
				if ($this.hasClass('wd-active-login')) {
					$this.removeClass('wd-active-login').addClass('wd-active-link');
				}
			});

			$content.find('input').not('[id="username"]').on('click', function() {
				$this.removeClass('wd-active-login').removeClass('wd-active-link');
			});

			woodmartThemeModule.$document.click(function(a) {
				if ('undefined' != typeof (a.target.className.length) && a.target.className.indexOf('wd-dropdown-register') === -1 && a.target.className.indexOf('input-text') === -1) {
					$this.removeClass('wd-active-login').removeClass('wd-active-link');
				}
			});

			$('.wd-dropdown-register').on('mouseout', function() {
				if ($this.hasClass('wd-active-link')) {
					$this.removeClass('wd-active-link');
				}
			}).on('mouseleave', function() {
				if ($this.hasClass('wd-active-link')) {
					$this.removeClass('wd-active-link');
				}
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.loginDropdown();
	});
})(jQuery);
