jQuery(window).on('elementor:init', function() {
	var postSearch = elementor.modules.controls.BaseData.extend({
		isSearch: false,

		resultsRender: function() {
			var self = this;
			var ids = this.getControlValue();

			if (!ids) {
				return;
			}

			if (!_.isArray(ids)) {
				ids = [ids];
			}

			self.addControlSpinner();

			jQuery.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: self.model.get('render'),
					post_type: self.model.get('post_type'),
					taxonomy: self.model.get('taxonomy'),
					id: ids,
				},

				success: function(results) {
					self.isSearch = true;
					self.model.set('options', results);
					self.render();
				},
			});
		},

		addControlSpinner: function() {
			this.ui.select.prop('disabled', true);
			this.$el.find('.elementor-control-title').after('<span class="elementor-control-spinner">&nbsp;<i class="fa fa-spinner fa-spin"></i>&nbsp;</span>');
		},

		onReady: function() {
			var self = this;

			this.ui.select.select2({
				placeholder: 'Search',
				allowClear: true,
				minimumInputLength: 2,
				ajax: {
					url: ajaxurl,
					dataType: 'json',
					method: 'post',
					delay: 250,
					data: function(params) {
						return {
							q: params.term, // search term
							action: self.model.get('search'),
							post_type: self.model.get('post_type'),
							taxonomy: self.model.get('taxonomy'),
						};
					},
					processResults: function(data) {
						return {
							results: data,
						};
					},
					cache: true,
				},
			});

			if (!this.isSearch) {
				this.resultsRender();
			}
		},

		onBeforeDestroy: function() {
			if (this.ui.select.data('select2')) {
				this.ui.select.select2('destroy');
			}

			this.$el.remove();
		},
	});
	elementor.addControlView('wd_autocomplete', postSearch);
});
