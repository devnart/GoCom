/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdReplaceMainGallery', function () {
		woodmartThemeModule.productImagesGallery();
	});

	woodmartThemeModule.productImagesGallery = function() {
		var $mainGallery = $('.woocommerce-product-gallery__wrapper:not(.quick-view-gallery)');
		var $thumbs = $('.images .thumbnails');
		var $mainOwl = $('.woocommerce-product-gallery__wrapper');
		var thumbs_position = woodmart_settings.product_gallery.thumbs_slider.position;

		$thumbs.addClass('thumbnails-ready');

		if (woodmart_settings.product_gallery.images_slider) {
			if (woodmart_settings.product_slider_auto_height === 'yes') {
				$('.product-images').imagesLoaded(function() {
					initMainGallery();
				});
			} else {
				initMainGallery();
			}
		} else if (woodmartThemeModule.$window.width() <= 1024 && (thumbs_position === 'bottom_combined' || thumbs_position === 'bottom_column' || thumbs_position === 'bottom_grid')) {
			initMainGallery();
		}

		if (woodmart_settings.product_gallery.thumbs_slider.enabled && woodmart_settings.product_gallery.images_slider) {
			initThumbnailsMarkup();
			if (woodmart_settings.product_gallery.thumbs_slider.position === 'left' && woodmartThemeModule.$body.width() > 1024 && typeof ($.fn.slick) != 'undefined') {
				initThumbnailsVertical();
			} else {
				initThumbnailsHorizontal();
			}
		}

		function initMainGallery() {
			if ('undefined' === typeof $.fn.owlCarousel) {
				return;
			}

			$mainGallery.trigger('destroy.owl.carousel');
			$mainGallery.addClass('owl-carousel').owlCarousel(woodmartThemeModule.mainCarouselArg);
			woodmartThemeModule.$document.trigger('wood-images-loaded');
		}

		function initThumbnailsMarkup() {
			var markup = '';

			$mainGallery.find('.woocommerce-product-gallery__image').each(function() {
				var $this = $(this);
				var image = $this.data('thumb'),
				    alt   = $this.find('a img').attr('alt'),
				    title = $this.find('a img').attr('title');

				if ( ! title ) {
					title = $this.find('a picture').attr('title');
				}

				markup += '<div class="product-image-thumbnail"><img alt="' + alt + '" title="' + title + '" src="' + image + '" /></div>';
			});

			if ($thumbs.hasClass('slick-slider')) {
				$thumbs.slick('unslick');
			} else if ($thumbs.hasClass('owl-carousel')) {
				$thumbs.trigger('destroy.owl.carousel');
			}

			$thumbs.empty();
			$thumbs.append(markup);
		}

		function initThumbnailsVertical() {
			$thumbs.slick({
				slidesToShow   : woodmart_settings.product_gallery.thumbs_slider.items.vertical_items,
				slidesToScroll : woodmart_settings.product_gallery.thumbs_slider.items.vertical_items,
				vertical       : true,
				verticalSwiping: true,
				infinite       : false
			});

			$thumbs.on('click', '.product-image-thumbnail', function() {
				$mainOwl.trigger('to.owl.carousel', $(this).index());
			});

			$mainOwl.on('changed.owl.carousel', function(e) {
				var i = e.item.index;

				$thumbs.slick('slickGoTo', i);
				$thumbs.find('.active-thumb').removeClass('active-thumb');
				$thumbs.find('.product-image-thumbnail').eq(i).addClass('active-thumb');
			});

			$thumbs.find('.product-image-thumbnail').eq(0).addClass('active-thumb');

			$thumbs.imagesLoaded(function () {
				$thumbs.slick('setPosition');
			});
		}

		function initThumbnailsHorizontal() {
			if ('undefined' === typeof $.fn.owlCarousel) {
				return;
			}

			$thumbs.addClass('owl-carousel').owlCarousel({
				rtl       : woodmartThemeModule.$body.hasClass('rtl'),
				items     : woodmart_settings.product_gallery.thumbs_slider.items.desktop,
				responsive: {
					1025: {
						items: woodmart_settings.product_gallery.thumbs_slider.items.desktop
					},
					769 : {
						items: woodmart_settings.product_gallery.thumbs_slider.items.tablet_landscape
					},
					577 : {
						items: woodmart_settings.product_gallery.thumbs_slider.items.tablet
					},
					0   : {
						items: woodmart_settings.product_gallery.thumbs_slider.items.mobile
					}
				},
				dots      : false,
				nav       : true,
				navText   : false,
				navClass : ['owl-prev wd-btn-arrow', 'owl-next wd-btn-arrow'],
			});

			var $thumbsOwl = $thumbs.owlCarousel();

			$thumbs.on('mouseup', '.owl-item', function() {
				var i = $(this).index();

				$thumbsOwl.trigger('to.owl.carousel', i);
				$mainOwl.trigger('to.owl.carousel', i);
			});

			$mainOwl.on('changed.owl.carousel', function(e) {
				var i = e.item.index;

				$thumbsOwl.trigger('to.owl.carousel', i);
				$thumbs.find('.active-thumb').removeClass('active-thumb');
				$thumbs.find('.product-image-thumbnail').eq(i).addClass('active-thumb');
			});

			$thumbs.find('.product-image-thumbnail').eq(0).addClass('active-thumb');
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.productImagesGallery();
	});
})(jQuery);
