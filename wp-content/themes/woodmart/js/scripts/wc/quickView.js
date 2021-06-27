/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.quickViewInit();
		});
	});

	woodmartThemeModule.quickViewInit = function() {
		woodmartThemeModule.$document.on('click', '.open-quick-view', function(e) {
			e.preventDefault();

			if ($('.open-quick-view').hasClass('loading')) {
				return true;
			}

			var $this     = $(this),
			    productId = $this.data('id'),
			    loopName  = $this.data('loop-name'),
			    loop      = $this.data('loop'),
			    prev      = '',
			    next      = '',
			    loopBtns  = $('.quick-view').find('[data-loop-name="' + loopName + '"]');

			$this.addClass('loading');

			if (typeof loopBtns[loop - 1] != 'undefined') {
				prev = loopBtns.eq(loop - 1).addClass('quick-view-prev');
				prev = $('<div>').append(prev.clone()).html();
			}

			if (typeof loopBtns[loop + 1] != 'undefined') {
				next = loopBtns.eq(loop + 1).addClass('quick-view-next');
				next = $('<div>').append(next.clone()).html();
			}

			woodmartThemeModule.quickViewLoad(productId, $this, prev, next);
		});
	};

	woodmartThemeModule.quickViewCarousel = function() {
		if ('undefined' === typeof $.fn.owlCarousel) {
			return;
		}

		var $quickViewCarousel = $('.product-quick-view .woocommerce-product-gallery__wrapper');

		$quickViewCarousel.trigger('destroy.owl.carousel');
		$quickViewCarousel.addClass('owl-carousel').owlCarousel({
			rtl    : woodmartThemeModule.$body.hasClass('rtl'),
			items  : 1,
			dots   : false,
			nav    : true,
			navText: false,
			navClass : ['owl-prev wd-btn-arrow', 'owl-next wd-btn-arrow'],
		});
	};

	woodmartThemeModule.quickViewLoad = function(id, btn) {
		var data = {
			id    : id,
			action: 'woodmart_quick_view'
		};

		var initPopup = function(data) {
			var items = $(data);

			$.magnificPopup.open({
				items       : {
					src : items,
					type: 'inline'
				},
				tClose      : woodmart_settings.close,
				tLoading    : woodmart_settings.loading,
				removalDelay: 500,
				callbacks   : {
					beforeOpen: function() {
						this.st.mainClass = 'mfp-move-horizontal quick-view-wrapper';
					},
					open      : function() {
						var $form = $('.variations_form');

						$form.each(function() {
							$(this).wc_variation_form().find('.variations select:eq(0)').change();
						});

						$form.trigger('wc_variation_form');
						woodmartThemeModule.$body.trigger('woodmart-quick-view-displayed');
						woodmartThemeModule.$document.trigger('wdQuickViewOpen');
						setTimeout(function() {
							woodmartThemeModule.$document.trigger('wdQuickViewOpen300');
						}, 300);
						woodmartThemeModule.quickViewCarousel();
					}
				}
			});
		};

		$.ajax({
			url     : woodmart_settings.ajaxurl,
			data    : data,
			method  : 'get',
			success : function(data) {
				woodmartThemeModule.removeDuplicatedStylesFromHTML(data, function(data){
					if (woodmart_settings.quickview_in_popup_fix) {
						$.magnificPopup.close();
						setTimeout(function() {
							initPopup(data);
						}, 500);
					} else {
						initPopup(data);
					}
				});
			},
			complete: function() {
				btn.removeClass('loading');
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.quickViewInit();
		woodmartThemeModule.quickViewCarousel();
	});
})(jQuery);
