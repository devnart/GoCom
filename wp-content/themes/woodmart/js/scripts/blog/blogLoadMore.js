/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_blog.default',
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.blogLoadMore();
		});
	});

	woodmartThemeModule.blogLoadMore = function() {
		var btnClass = '.wd-blog-load-more.load-on-scroll',
		    process  = false;

		woodmartThemeModule.clickOnScrollButton(btnClass, false, false);

		$('.wd-blog-load-more').on('click', function(e) {
			e.preventDefault();

			var $this = $(this);

			if (process || $this.hasClass('no-more-posts')) {
				return;
			}

			process = true;

			var holder   = $this.parent().siblings('.wd-blog-holder'),
			    source   = holder.data('source'),
			    action   = 'woodmart_get_blog_' + source,
			    ajaxurl  = woodmart_settings.ajaxurl,
			    dataType = 'json',
			    method   = 'POST',
			    atts     = holder.data('atts'),
			    paged    = holder.data('paged');

			$this.addClass('loading');

			var data = {
				atts  : atts,
				paged : paged,
				action: action
			};

			if (source === 'main_loop') {
				ajaxurl = $this.attr('href');
				method = 'GET';
				data = {};
			}

			$.ajax({
				url     : ajaxurl,
				data    : data,
				dataType: dataType,
				method  : method,
				success : function(data) {
					woodmartThemeModule.removeDuplicatedStylesFromHTML(data.items, function(html) {
						var items = $(html);

						if (items) {
							if (holder.hasClass('masonry-container')) {
								holder.append(items).isotope('appended', items);
								holder.imagesLoaded().progress(function() {
									holder.isotope('layout');
									woodmartThemeModule.clickOnScrollButton(btnClass, true, false);
								});
							} else {
								holder.append(items);
								holder.imagesLoaded().progress(function() {
									woodmartThemeModule.clickOnScrollButton(btnClass, true, false);
								});
							}

							if ('yes' === woodmart_settings.load_more_button_page_url){
								window.history.pushState('', '', data.currentPage);
							}
							holder.data('paged', paged + 1);

							if (source === 'main_loop') {
								$this.attr('href', data.nextPage);
								if (data.status === 'no-more-posts') {
									$this.hide().remove();
								}
							}
						}

						if (data.status === 'no-more-posts') {
							$this.addClass('no-more-posts');
							$this.hide();
						}
					});
				},
				error   : function() {
					console.log('ajax error');
				},
				complete: function() {
					$this.removeClass('loading');
					process = false;
				}
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.blogLoadMore();
	});
})(jQuery);
