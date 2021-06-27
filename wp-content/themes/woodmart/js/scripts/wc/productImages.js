/* global woodmart_settings */
(function($) {
	woodmartThemeModule.productImages = function() {
		var currentImage,
		    $productGallery   = $('.woocommerce-product-gallery'),
		    $mainImages       = $('.woocommerce-product-gallery__wrapper'),
		    $thumbs           = $productGallery.find('.thumbnails'),
		    PhotoSwipeTrigger = '.woodmart-show-product-gallery';

		$thumbs.addClass('thumbnails-ready');

		if ($productGallery.hasClass('image-action-popup')) {
			PhotoSwipeTrigger += ', .woocommerce-product-gallery__image a';
		}

		$productGallery.on('click', '.woocommerce-product-gallery__image a', function(e) {
			e.preventDefault();
		});

		$productGallery.on('click', PhotoSwipeTrigger, function(e) {
			e.preventDefault();

			currentImage = $(this).attr('href');

			var items = getProductItems();

			woodmartThemeModule.callPhotoSwipe(getCurrentGalleryIndex(e), items);
		});

		$thumbs.on('click', '.image-link', function(e) {
			e.preventDefault();
		});

		var getCurrentGalleryIndex = function(e) {
			if ($mainImages.hasClass('owl-carousel')) {
				return $mainImages.find('.owl-item.active').index();
			} else {
				return $(e.currentTarget).parent().parent().index();
			}
		};

		var getProductItems = function() {
			var items = [];

			$mainImages.find('figure a img').each(function() {
				var $this = $(this);
				var src     = $this.attr('data-large_image'),
				    width   = $this.attr('data-large_image_width'),
				    height  = $this.attr('data-large_image_height'),
				    caption = $this.data('caption');

				items.push({
					src  : src,
					w    : width,
					h    : height,
					title: (woodmart_settings.product_images_captions === 'yes') ? caption : false
				});

			});

			return items;
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.productImages();
	});
})(jQuery);
