/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdPortfolioPjaxComplete', function () {
		woodmartThemeModule.portfolioLoadMore();
	});

	$.each([
		'frontend/element_ready/wd_portfolio.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.portfolioLoadMore();
		});
	});

	woodmartThemeModule.portfolioLoadMore = function() {
		if (typeof $.fn.waypoint !== 'function') {
			return;
		}

		var waypoint = $('.wd-portfolio-load-more.load-on-scroll').waypoint(function() {
			    $('.wd-portfolio-load-more.load-on-scroll').trigger('click');
		    }, {offset: '100%'}),
		    process  = false;

		$('.wd-portfolio-load-more').on('click', function(e) {
			e.preventDefault();

			var $this = $(this);

			if (process || $this.hasClass('no-more-posts')) {
				return;
			}

			process = true;

			var holder   = $this.parent().parent().find('.wd-portfolio-holder'),
			    source   = holder.data('source'),
			    action   = 'woodmart_get_portfolio_' + source,
			    ajaxurl  = woodmart_settings.ajaxurl,
			    dataType = 'json',
			    method   = 'POST',
			    timeout,
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

									clearTimeout(timeout);

									timeout = setTimeout(function() {
										waypoint = $('.wd-portfolio-load-more.load-on-scroll').waypoint(function() {
											$('.wd-portfolio-load-more.load-on-scroll').trigger('click');
										}, {offset: '100%'});
									}, 1000);
								});
							} else {
								holder.append(items);
							}

							holder.data('paged', paged + 1);

							$this.attr('href', data.nextPage);

							if ('yes' === woodmart_settings.load_more_button_page_url){
								window.history.pushState('', '', data.currentPage);
							}
						}

						woodmartThemeModule.$document.trigger('wdPortfolioLoadMoreLoaded');

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
		woodmartThemeModule.portfolioLoadMore();
	});
})(jQuery);
