/* global woodmart_settings */
(function($) {
	woodmartThemeModule.loginTabs = function() {
		var tabs               = $('.wd-register-tabs'),
		    btn                = tabs.find('.wd-switch-to-register'),
		    title              = $('.col-register-text h2'),
		    loginText          = tabs.find('.login-info'),
		    classOpened        = 'active-register',
		    loginLabel         = btn.data('login'),
		    registerLabel      = btn.data('register'),
		    loginTitleLabel    = btn.data('login-title'),
		    registerTitleLabel = btn.data('reg-title');

		btn.on('click', function(e) {
			e.preventDefault();

			if (isShown()) {
				hideRegister();
			} else {
				showRegister();
			}

			if (woodmartThemeModule.$window.width() < 768) {
				$('html, body').stop().animate({
					scrollTop: tabs.offset().top - 90
				}, 400);
			}
		});

		var showRegister = function() {
			tabs.addClass(classOpened);
			btn.text(loginLabel);

			if (loginText.length > 0) {
				title.text(loginTitleLabel);
			}
		};

		var hideRegister = function() {
			tabs.removeClass(classOpened);
			btn.text(registerLabel);

			if (loginText.length > 0) {
				title.text(registerTitleLabel);
			}
		};

		var isShown = function() {
			return tabs.hasClass(classOpened);
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.loginTabs();
	});
})(jQuery);
