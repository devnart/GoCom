/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_3d_view.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.view3d();
		});
	});

	woodmartThemeModule.view3d = function() {
		$('.wd-threed-view:not(.wd-product-threed)').each(function() {
			init($(this));
		});

		$('.product-360-button a').on('click', function(e) {
			e.preventDefault();
			init($('.wd-threed-view.wd-product-threed'));
		});

		function init($this) {
			var data = $this.data('args');

			if (!data || $this.hasClass('wd-threed-view-inited')) {
				return false;
			}

			$this.ThreeSixty({
				totalFrames : data.frames_count,
				endFrame    : data.frames_count,
				currentFrame: 1,
				imgList     : '.threed-view-images',
				progress    : '.spinner',
				imgArray    : data.images,
				height      : data.height,
				width       : data.width,
				responsive  : true,
				navigation  : true,
				framerate   : woodmart_settings.three_sixty_framerate,
			});

			$this.addClass('wd-threed-view-inited');
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.view3d();
	});
})(jQuery);
