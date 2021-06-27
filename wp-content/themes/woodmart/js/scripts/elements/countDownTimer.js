/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdProductsTabsLoaded wdUpdateWishlist wdShopPageInit', function () {
		woodmartThemeModule.countDownTimer();
	});

	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default',
		'frontend/element_ready/wd_countdown_timer.default',
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.countDownTimer();
		});
	});

	woodmartThemeModule.countDownTimer = function() {
		$('.wd-timer').each(function() {
			var $this = $(this);
			dayjs.extend(window.dayjs_plugin_utc);
			dayjs.extend(window.dayjs_plugin_timezone);
			var time = dayjs.tz($this.data('end-date'), $this.data('timezone'));
			$this.countdown(time.toDate(), function(event) {
				$this.html(event.strftime(''
					+ '<span class="countdown-days">%-D <span>' + woodmart_settings.countdown_days + '</span></span> '
					+ '<span class="countdown-hours">%H <span>' + woodmart_settings.countdown_hours + '</span></span> '
					+ '<span class="countdown-min">%M <span>' + woodmart_settings.countdown_mins + '</span></span> '
					+ '<span class="countdown-sec">%S <span>' + woodmart_settings.countdown_sec + '</span></span>'));
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.countDownTimer();
	});
})(jQuery);
