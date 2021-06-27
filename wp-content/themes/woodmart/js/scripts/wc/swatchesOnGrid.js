/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.swatchesOnGrid();
		});
	});

	woodmartThemeModule.swatchesOnGrid = function() {
		woodmartThemeModule.$body.on('click', '.swatch-on-grid', function() {
			var src, srcset, image_sizes;

			var $this       = $(this),
			    imageSrc    = $this.data('image-src'),
			    imageSrcset = $this.data('image-srcset'),
			    imageSizes  = $this.data('image-sizes');

			if (typeof imageSrc == 'undefined' || '' === imageSrc) {
				return;
			}

			var product    = $this.parents('.product-grid-item'),
			    image      = product.find('.product-image-link img'),
			    source     = product.find('.product-image-link picture source'),
			    srcOrig    = image.data('original-src'),
			    srcsetOrig = image.data('original-srcset'),
			    sizesOrig  = image.data('original-sizes');

			if (typeof srcOrig == 'undefined') {
				image.data('original-src', image.attr('src'));
			}

			if (typeof srcsetOrig == 'undefined') {
				image.data('original-srcset', image.attr('srcset'));
			}

			if (typeof sizesOrig == 'undefined') {
				image.data('original-sizes', image.attr('sizes'));
			}

			if ($this.hasClass('active-swatch')) {
				src = srcOrig;
				srcset = srcsetOrig;
				image_sizes = sizesOrig;
				$this.removeClass('active-swatch');
				product.removeClass('product-swatched');
			} else {
				$this.parent().find('.active-swatch').removeClass('active-swatch');
				$this.addClass('active-swatch');
				product.addClass('product-swatched');
				src = imageSrc;
				srcset = imageSrcset;
				image_sizes = imageSizes;
			}

			if (image.attr('src') === src) {
				return;
			}

			product.addClass('wd-loading-image');

			image.attr('src', src).attr('srcset', srcset).attr('image_sizes', image_sizes).one('load', function() {
				product.removeClass('wd-loading-image');
			});

			if (source.length > 0) {
				source.attr('srcset', srcset).attr('image_sizes', image_sizes);
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.swatchesOnGrid();
	});
})(jQuery);
