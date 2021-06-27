/* global woodmart_settings */
(function($) {
	woodmartThemeModule.headerBuilder = function() {
		var $header         = $('.whb-header'),
		    $headerBanner   = $('.header-banner'),
		    $stickyElements = $('.whb-sticky-row'),
		    $firstSticky    = '',
		    $window         = woodmartThemeModule.$window,
		    isSticked       = false,
		    stickAfter      = 300,
		    cloneHTML       = '',
		    previousScroll,
		    isHideOnScroll  = $header.hasClass('whb-hide-on-scroll');

		$stickyElements.each(function() {
			var $this = $(this);

			if ($this[0].offsetHeight > 10) {
				$firstSticky = $this;
				return false;
			}
		});

		// Real header sticky option
		if ($header.hasClass('whb-sticky-real') || $header.hasClass('whb-scroll-slide')) {
			var $adminBar = $('#wpadminbar');
			var headerHeight = $header.find('.whb-main-header')[0].offsetHeight;
			var adminBarHeight = $adminBar.length > 0 ? $adminBar[0].offsetHeight : 0;

			if ($header.hasClass('whb-sticky-real')) {
				// if no sticky rows
				if ($firstSticky.length === 0 || $firstSticky[0].offsetHeight < 10) {
					return;
				}

				$header.addClass('whb-sticky-prepared').css({
					paddingTop: headerHeight
				});

				stickAfter = $firstSticky.offset().top - adminBarHeight;
			}

			if ($header.hasClass('whb-scroll-slide')) {
				stickAfter = headerHeight + adminBarHeight;
			}
		}

		if ($header.hasClass('whb-sticky-clone')) {
			var data = [];
			data['cloneClass'] = $header.find('.whb-general-header').attr('class');

			if (isHideOnScroll) {
				data['wrapperClasses'] = 'whb-hide-on-scroll';
			}

			cloneHTML = woodmart_settings.whb_header_clone;

			cloneHTML = cloneHTML.replace(/<%([^%>]+)?%>/g, function(replacement) {
				var selector = replacement.slice(2, -2);

				return $header.find(selector).length
					? $('<div>')
						.append($header.find(selector).first().clone())
						.html()
					: (data[selector] !== undefined) ? data[selector] : '';
			});

			cloneHTML = cloneHTML.replace(/<link[^>]*>/g,"");

			$header.after(cloneHTML);
			$header = $header.parent().find('.whb-clone');

			$header.find('.whb-row').removeClass('whb-flex-equal-sides').addClass('whb-flex-flex-middle');
		}

		$window.on('scroll', function() {
			var after = stickAfter;
			var currentScroll = woodmartThemeModule.$window.scrollTop();
			var windowHeight = woodmartThemeModule.$window.height();
			var documentHeight = woodmartThemeModule.$document.height();

			if ($headerBanner.length > 0 && woodmartThemeModule.$body.hasClass('header-banner-display')) {
				after += $headerBanner[0].offsetHeight;
			}

			if (!$('.close-header-banner').length && $header.hasClass('whb-scroll-stick')) {
				after = stickAfter;
			}

			if (currentScroll > after) {
				stickHeader();
			} else {
				unstickHeader();
			}

			var startAfter = 100;

			if ($header.hasClass('whb-scroll-stick')) {
				startAfter = 500;
			}

			if (isHideOnScroll) {
				if (previousScroll - currentScroll > 0 && currentScroll > after) {
					$header.addClass('whb-scroll-up');
					$header.removeClass('whb-scroll-down');
				} else if (currentScroll - previousScroll > 0 && currentScroll + windowHeight !== documentHeight && currentScroll > (after + startAfter)) {
					$header.addClass('whb-scroll-down');
					$header.removeClass('whb-scroll-up');
				} else if (currentScroll <= after) {
					$header.removeClass('whb-scroll-down');
					$header.removeClass('whb-scroll-up');
				} else if (currentScroll + windowHeight >= documentHeight - 5) {
					$header.addClass('whb-scroll-up');
					$header.removeClass('whb-scroll-down');
				}
			}

			previousScroll = currentScroll;
		});

		function stickHeader() {
			if (isSticked) {
				return;
			}

			isSticked = true;
			$header.addClass('whb-sticked');
		}

		function unstickHeader() {
			if (!isSticked) {
				return;
			}

			isSticked = false;
			$header.removeClass('whb-sticked');
		}

		woodmartThemeModule.$document.trigger('wdHeaderBuilderInited');
	};

	woodmartThemeModule.$window.on('wdEventStarted', function() {
		woodmartThemeModule.headerBuilder();
	});
})(jQuery);
