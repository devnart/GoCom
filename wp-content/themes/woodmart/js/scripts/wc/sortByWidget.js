/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit', function() {
		woodmartThemeModule.sortByWidget();
	});

	woodmartThemeModule.sortByWidget = function() {
		if (!woodmartThemeModule.$body.hasClass('woodmart-ajax-shop-on') || typeof ($.fn.pjax) == 'undefined') {
			return;
		}

		var $wcOrdering = $('.woocommerce-ordering');

		$wcOrdering.on('change', 'select.orderby', function() {
			var $form = $(this).closest('form');

			$form.find('[name="_pjax"]').remove();

			$.pjax({
				container: '.main-page-wrapper',
				timeout  : woodmart_settings.pjax_timeout,
				url      : '?' + $form.serialize(),
				scrollTo : false,
				renderCallback: function(context, html, afterRender) {
					woodmartThemeModule.removeDuplicatedStylesFromHTML(html, function(html) {
						context.html(html);
						afterRender();
						woodmartThemeModule.$document.trigger('wdShopPageInit');
						woodmartThemeModule.$document.trigger('wood-images-loaded');
					});
				}
			});
		});

		$wcOrdering.on('submit', function(e) {
			e.preventDefault(e);
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.sortByWidget();
	});
})(jQuery);
