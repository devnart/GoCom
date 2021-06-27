/* global woodmart_settings */
(function($) {
	woodmartThemeModule.clickOnScrollButton = function(btnClass, destroy, offset) {
		if (typeof $.fn.waypoint != 'function') {
			return;
		}

		var $btn = $(btnClass);
		if ($btn.length === 0) {
			return;
		}

		$btn.trigger('wd-waypoint-destroy');

		if (!offset) {
			offset = 0;
		}

		var waypoint = new Waypoint({
			element: $btn[0],
			handler: function() {
				$btn.trigger('click');
			},
			offset : function() {
				return woodmartThemeModule.$window.outerHeight() + parseInt(offset);
			}
		});

		$btn.data('waypoint-inited', true).off('wd-waypoint-destroy').on('wd-waypoint-destroy', function() {
			if ($btn.data('waypoint-inited')) {
				waypoint.destroy();
				$btn.data('waypoint-inited', false);
			}
		});
	};
})(jQuery);
