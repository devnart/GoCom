/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_counter.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.visibleElements();
		});
	});

	woodmartThemeModule.counterShortcode = function(counter) {
		if (counter.attr('data-state') === 'done' || parseInt(counter.text()) !== counter.data('final')) {
			return;
		}

		counter.prop('Counter', 0).animate({
			Counter: counter.text()
		}, {
			duration: 3000,
			easing  : 'swing',
			step    : function(now) {
				if (now >= counter.data('final')) {
					counter.attr('data-state', 'done');
				}

				counter.text(Math.ceil(now));
			}
		});
	};

	woodmartThemeModule.visibleElements = function() {
		$('.woodmart-counter .counter-value').each(function() {
			var $this = $(this);

			$this.waypoint(function() {
				woodmartThemeModule.counterShortcode($this);
			}, {offset: '100%'});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.visibleElements();
	});
})(jQuery);
