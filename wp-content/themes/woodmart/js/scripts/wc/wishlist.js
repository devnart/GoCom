/* global woodmart_settings */
(function($) {
	woodmartThemeModule.wishlist = function() {
		var cookiesName = 'woodmart_wishlist_count';

		if (woodmartThemeModule.$body.hasClass('logged-in')) {
			cookiesName += '_logged';
		}

		if (woodmart_settings.is_multisite) {
			cookiesName += '_' + woodmart_settings.current_blog_id;
		}

		var $widget = $('.wd-header-wishlist');
		var cookie = Cookies.get(cookiesName);

		if ($widget.length > 0 && 'undefined' !== typeof cookie) {
			try {
				var count = JSON.parse(cookie);
				$widget.find('.wd-tools-count').text(count);
			}
			catch (e) {
				console.log('cant parse cookies json');
			}
		}

		// Add to wishlist action
		woodmartThemeModule.$body.on('click', '.wd-wishlist-btn a', function(e) {
			var $this = $(this);

			if (!$this.hasClass('added')) {
				e.preventDefault();
			}

			var productId = $this.data('product-id');
			var addedText = $this.data('added-text');
			var key = $this.data('key');

			if ($this.hasClass('added')) {
				return true;
			}

			$this.addClass('loading');

			$.ajax({
				url     : woodmart_settings.ajaxurl,
				data    : {
					action    : 'woodmart_add_to_wishlist',
					product_id: productId,
					key       : key
				},
				dataType: 'json',
				method  : 'GET',
				success : function(response) {
					if (response) {
						$this.addClass('added');
						woodmartThemeModule.$document.trigger('added_to_wishlist');

						if (response.wishlist_content) {
							updateWishlist(response);
						}

						if ($this.find('span').length > 0) {
							$this.find('span').text(addedText);
						} else {
							$this.text(addedText);
						}
					} else {
						console.log('something wrong loading wishlist data ', response);
					}
				},
				error   : function() {
					console.log(
						'We cant add to wishlist. Something wrong with AJAX response. Probably some PHP conflict.');
				},
				complete: function() {
					$this.removeClass('loading');
				}
			});
		});

		woodmartThemeModule.$body.on('click', '.wd-wishlist-remove', function(e) {
			e.preventDefault();
			var $this = $(this);
			var productId = $this.data('product-id');
			var key = $this.data('key');

			if ($this.hasClass('added')) {
				return true;
			}

			$this.addClass('loading');

			$.ajax({
				url     : woodmart_settings.ajaxurl,
				data    : {
					action    : 'woodmart_remove_from_wishlist',
					product_id: productId,
					key       : key
				},
				dataType: 'json',
				method  : 'GET',
				success : function(response) {
					if (response.wishlist_content) {
						updateWishlist(response);
					} else {
						console.log('something wrong loading wishlist data ', response);
					}
				},
				error   : function() {
					console.log('We cant remove from wishlist. Something wrong with AJAX response. Probably some PHP conflict.');
				},
				complete: function() {
					$this.removeClass('loading');
				}
			});
		});

		// Elements update after ajax
		function updateWishlist(data) {
			var $wishlistContent = $('.wd-wishlist-content');
			var $widget = $('.wd-header-wishlist');

			if ($widget.length > 0) {
				$widget.find('.wd-tools-count').text(data.count);
			}

			if ($wishlistContent.length > 0 && !$wishlistContent.hasClass('wd-wishlist-preview')) {
				woodmartThemeModule.removeDuplicatedStylesFromHTML(data.wishlist_content, function(html) {
					$wishlistContent.replaceWith(html);
				});
			}

			woodmartThemeModule.$document.trigger('wdUpdateWishlist');
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.wishlist();
	});
})(jQuery);
