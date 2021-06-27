/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdQuickShopSuccess wdQuickViewOpen wdUpdateWishlist', function () {
		woodmartThemeModule.swatchesVariations();
	});

	woodmartThemeModule.swatchesVariations = function() {
		var $variation_forms = $('.variations_form');
		var variationGalleryReplace = false;
		var swathesSelected = false;

		// Firefox mobile fix
		$('.variations_form .label').on('click', function(e) {
			if ($(this).siblings('.value').hasClass('with-swatches')) {
				e.preventDefault();
			}
		});

		$variation_forms.each(function() {
			var $variation_form = $(this);

			if ($variation_form.data('swatches')) {
				return;
			}

			$variation_form.data('swatches', true);

			if (!$variation_form.data('product_variations')) {
				$variation_form.find('.swatches-select').find('> div').addClass('swatch-enabled');
			}

			if ($('.swatches-select > div').hasClass('active-swatch')) {
				$variation_form.addClass('variation-swatch-selected');
			}

			$variation_form.on('click', '.swatches-select > div', function() {
					var $this = $(this);
					var value = $this.data('value');
					var id = $this.parent().data('id');
					var title = $this.data('title');

					resetSwatches($variation_form);

					if ($this.hasClass('active-swatch')) {
						return;
					}

					if ($this.hasClass('swatch-disabled')) {
						return;
					}

					$variation_form.find('select#' + id).val(value).trigger('change');
					$this.parent().find('.active-swatch').removeClass('active-swatch');
					$this.addClass('active-swatch');
					resetSwatches($variation_form);

					if ((woodmart_settings.swatches_labels_name === 'yes' && woodmartThemeModule.$window.width() >= 769) || woodmartThemeModule.$window.width() <= 768) {
						$this.parents('tr').find('.wd-attr-selected').html(title);
					}
				})
				.on('woocommerce_update_variation_values', function() {
					resetSwatches($variation_form);
				})
				.on('click', '.reset_variations', function() {
					$variation_form.find('.active-swatch').removeClass('active-swatch');

					if ((woodmart_settings.swatches_labels_name === 'yes' && woodmartThemeModule.$window.width() >= 769) || woodmartThemeModule.$window.width() <= 768) {
						$variation_form.find('.wd-attr-selected').html('');
					}
				})
				.on('reset_data', function() {
					var $this = $(this);
					var all_attributes_chosen = true;
					var some_attributes_chosen = false;

					$variation_form.find('.variations select').each(function() {
						var value = $this.val() || '';

						if (value.length === 0) {
							all_attributes_chosen = false;
						} else {
							some_attributes_chosen = true;
						}

					});

					if (all_attributes_chosen) {
						$this.parent().find('.active-swatch').removeClass('active-swatch');
					}

					$variation_form.removeClass('variation-swatch-selected');

					var $mainOwl = $('.woocommerce-product-gallery__wrapper.owl-carousel');

					resetSwatches($variation_form);

					replaceMainGallery('default', $variation_form);

					if (!$mainOwl.hasClass('owl-carousel')) {
						return;
					}

					if (woodmart_settings.product_slider_auto_height === 'yes') {
						if (!isQuickView() && isVariationGallery('default', $variation_form) && variationGalleryReplace) {
							$mainOwl.trigger('destroy.owl.carousel');
						}

						$('.product-images').imagesLoaded(function() {
							$mainOwl = $mainOwl.owlCarousel(woodmartThemeModule.mainCarouselArg);
							$mainOwl.trigger('refresh.owl.carousel');
						});
					} else {
						$mainOwl = $mainOwl.owlCarousel(woodmartThemeModule.mainCarouselArg);
						$mainOwl.trigger('refresh.owl.carousel');
					}

					var slide_go_to = woodmart_settings.product_gallery.thumbs_slider.position === 'centered' ? woodmart_settings.centered_gallery_start : 0;

					if (isQuickView()) {
						slide_go_to = 0;
					}

					$mainOwl.trigger('to.owl.carousel', slide_go_to);
				})
				.on('reset_image', function() {
					var $thumb = $('.thumbnails .product-image-thumbnail img').first();

					if (!isQuickView() && !isQuickShop($variation_form)) {
						$thumb.wc_reset_variation_attr('src');
					}
				})
				.on('show_variation', function(e, variation) {
					if (((woodmart_settings.swatches_labels_name === 'yes' && woodmartThemeModule.$window.width() >= 769) || woodmartThemeModule.$window.width() <= 768) && !swathesSelected) {
						$variation_form.find('.active-swatch').each(function() {
							var $this = $(this);
							var title = $this.data('title');
							$this.parents('tr').find('.wd-attr-selected').html(title);
						});

						swathesSelected = true;
					}

					if (!variation.image.src) {
						return;
					}

					var galleryHasImage = $variation_form.parents('.single-product-content').find('.thumbnails .product-image-thumbnail img[data-o_src="' + variation.image.thumb_src + '"]').length > 0;
					var $firstThumb = $variation_form.parents('.single-product-content').find('.thumbnails .product-image-thumbnail img').first();
					var originalImageUrl = $variation_form.parents('.single-product-content').find('.woocommerce-product-gallery .woocommerce-product-gallery__image > a').first().data('o_href');

					if (galleryHasImage) {
						$firstThumb.wc_reset_variation_attr('src');
					}

					if (!isQuickShop($variation_form) && !replaceMainGallery(variation.variation_id, $variation_form)) {
						if ($firstThumb.attr('src') !== variation.image.thumb_src) {
							$firstThumb.wc_set_variation_attr('src', variation.image.src);
						}

						woodmartThemeModule.$document.trigger('wdShowVariationNotQuickView');
					}

					$variation_form.addClass('variation-swatch-selected');

					if (!isQuickShop($variation_form) && !isQuickView() && originalImageUrl !== variation.image.full_src) {
						scrollToTop();
					}

					var $mainOwl = $('.woocommerce-product-gallery__wrapper.owl-carousel');

					if (!$mainOwl.hasClass('owl-carousel')) {
						return;
					}

					if (woodmart_settings.product_slider_auto_height === 'yes') {
						if (!isQuickView() && isVariationGallery(variation.variation_id, $variation_form) && variationGalleryReplace) {
							$mainOwl.trigger('destroy.owl.carousel');
						}

						$('.product-images').imagesLoaded(function() {
							$mainOwl = $mainOwl.owlCarousel(woodmartThemeModule.mainCarouselArg);
							$mainOwl.trigger('refresh.owl.carousel');
						});
					} else {
						$mainOwl = $mainOwl.owlCarousel(woodmartThemeModule.mainCarouselArg);
						$mainOwl.trigger('refresh.owl.carousel');
					}

					var $thumbs = $('.images .thumbnails');

					$mainOwl.trigger('to.owl.carousel', 0);

					if ($thumbs.hasClass('owl-carousel')) {
						$thumbs.owlCarousel().trigger('to.owl.carousel', 0);
						$thumbs.find('.active-thumb').removeClass('active-thumb');
						$thumbs.find('.product-image-thumbnail').eq(0).addClass('active-thumb');
					} else if ($thumbs.hasClass('slick-slider')) {
						$thumbs.slick('slickGoTo', 0);
						if (!$thumbs.find('.product-image-thumbnail').eq(0).hasClass('active-thumb')) {
							$thumbs.find('.active-thumb').removeClass('active-thumb');
							$thumbs.find('.product-image-thumbnail').eq(0).addClass('active-thumb');
						}
					}
				});
		});

		var resetSwatches = function($variation_form) {
			// If using AJAX
			if (!$variation_form.data('product_variations')) {
				return;
			}

			$variation_form.find('.variations select').each(function() {
				var select = $(this);
				var swatch = select.parent().find('.swatches-select');
				var options = select.html();
				options = $(options);

				swatch.find('> div').removeClass('swatch-enabled').addClass('swatch-disabled');

				options.each(function() {
					var value = $(this).val();

					if ($(this).hasClass('enabled')) {
						swatch.find('div[data-value="' + value + '"]').removeClass('swatch-disabled').addClass('swatch-enabled');
					} else {
						swatch.find('div[data-value="' + value + '"]').addClass('swatch-disabled').removeClass('swatch-enabled');
					}
				});
			});
		};

		var isQuickView = function() {
			return $('.single-product-content').hasClass('product-quick-view');
		};

		var isQuickShop = function($form) {
			return $form.parent().hasClass('quick-shop-form');
		};

		var isVariationGallery = function(key, $variationForm) {
			if ('old' === woodmart_settings.variation_gallery_storage_method) {
				return isVariationGalleryOld(key);
			} else {
				return isVariationGalleryNew(key, $variationForm);
			}
		};

		var isVariationGalleryOld = function(key) {
			if (typeof woodmart_variation_gallery_data === 'undefined' && typeof woodmart_qv_variation_gallery_data === 'undefined') {
				return;
			}

			var variation_gallery_data = isQuickView() ? woodmart_qv_variation_gallery_data : woodmart_variation_gallery_data;

			return typeof variation_gallery_data !== 'undefined' && variation_gallery_data && variation_gallery_data[key];
		};

		var isVariationGalleryNew = function(key, $variationForm) {
			var data = getAdditionalVariationsImagesData($variationForm);

			return typeof data !== 'undefined' && data && data[key] && data[key].length > 1;
		};

		var scrollToTop = function() {
			if ((woodmart_settings.swatches_scroll_top_desktop === 'yes' && woodmartThemeModule.$window.width() >= 1024) || (woodmart_settings.swatches_scroll_top_mobile === 'yes' && woodmartThemeModule.$window.width() <= 1024)) {
				var $page = $('html, body');

				$page.stop(true);

				woodmartThemeModule.$window.on('mousedown wheel DOMMouseScroll mousewheel keyup touchmove', function() {
					$page.stop(true);
				});

				$page.animate({
					scrollTop: $('.product-image-summary').offset().top - 150
				}, 800);

				$('.wd-swatch').tooltip('hide');
			}
		};

		var getAdditionalVariationsImagesData = function($variationForm) {
			var rawData = $variationForm.data('product_variations');
			var data = [];

			if (!rawData) {
				return data;
			}

			rawData.forEach(function(value) {
				data[value.variation_id] = value.additional_variation_images;
				data['default'] = value.additional_variation_images_default;
			});

			return data;
		};

		var replaceMainGallery = function(key, $variationForm) {
			if ('old' === woodmart_settings.variation_gallery_storage_method) {
				if (!isVariationGallery(key, $variationForm) || isQuickShop($variationForm) || ('default' === key && !variationGalleryReplace)) {
					return false;
				}

				replaceMainGalleryOld(key, $variationForm);
			} else {
				if (!isVariationGallery(key, $variationForm) || isQuickShop($variationForm) || ('default' === key && !variationGalleryReplace)) {
					return false;
				}

				var data = getAdditionalVariationsImagesData($variationForm);
				replaceMainGalleryNew(data[key], $variationForm);
			}

			woodmartThemeModule.quickViewCarousel();
			$('.woocommerce-product-gallery__image').trigger('zoom.destroy');
			woodmartThemeModule.$document.trigger('wdReplaceMainGallery');
			if (!isQuickView()) {
				woodmartThemeModule.$document.trigger('wdReplaceMainGalleryNotQuickView');
			}

			variationGalleryReplace = 'default' !== key;

			woodmartThemeModule.$window.trigger('resize');

			return true;
		};

		var replaceMainGalleryOld = function(key, $variationForm) {
			var variation_gallery_data = isQuickView() ? woodmart_qv_variation_gallery_data : woodmart_variation_gallery_data;

			var imagesData = variation_gallery_data[key];
			var $mainGallery = $variationForm.parents('.single-product-content').find('.woocommerce-product-gallery__wrapper');

			$mainGallery.empty();

			for (var index = 0; index < imagesData.length; index++) {
				var $html = '<div class="product-image-wrap"><figure data-thumb="' + imagesData[index].data_thumb + '" class="woocommerce-product-gallery__image">';

				if (!isQuickView()) {
					$html += '<a href="' + imagesData[index].href + '">';
				}

				$html += imagesData[index].image;

				if (!isQuickView()) {
					$html += '</a>';
				}

				$html += '</figure></div>';

				$mainGallery.append($html);
			}
		};

		var replaceMainGalleryNew = function(imagesData, $variationForm) {
			var $mainGallery = $variationForm.parents('.single-product-content').find('.woocommerce-product-gallery__wrapper');
			$mainGallery.empty();

			for (var key in imagesData) {
				if (imagesData.hasOwnProperty(key)) {
					var $html = '<div class="product-image-wrap"><figure class="woocommerce-product-gallery__image" data-thumb="' + imagesData[key].thumbnail_src + '">';

					if (!isQuickView()) {
						$html += '<a href="' + imagesData[key].full_src + '" data-elementor-open-lightbox="no">';
					}

					var srcset = imagesData[key].srcset ? 'srcset="' + imagesData[key].srcset + '"' : '';

					$html += '<img width="' + imagesData[key].width + '" height="' + imagesData[key].height + '" src="' + imagesData[key].src + '" class="' + imagesData[key].class + '" alt="' + imagesData[key].alt + '" title="' + imagesData[key].title + '" data-caption="' + imagesData[key].data_caption + '" data-src="' + imagesData[key].data_src + '"  data-large_image="' + imagesData[key].data_large_image + '" data-large_image_width="' + imagesData[key].data_large_image_width + '" data-large_image_height="' + imagesData[key].data_large_image_height + '" ' + srcset + ' sizes="' + imagesData[key].sizes + '" />';

					if (!isQuickView()) {
						$html += '</a>';
					}

					$html += '</figure></div>';

					$mainGallery.append($html);
				}
			}
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.swatchesVariations();
	});
})(jQuery);
