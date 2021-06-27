(function ($) {

    //Frontend live save
    $('[data-vc-ui-element="button-save"]').on('click', function () {
        if (!$('body').hasClass('vc_editor')) return;

        var $vcIframe = $('#vc_inline-frame');
        var cssId = $('.woodmart-css-id').val();
        var $styleTag = $vcIframe.contents().find('#' + cssId);
        var results = '';
        var sortedCssData = {};
        var css = {
            desktop: '',
            tablet: '',
            mobile: '',
        };

        $('.woodmart-rs-wrapper, .woodmart-vc-colorpicker').each(function () {
            dataSorting($(this));
        });

        $.each(sortedCssData, function (size, selectors) {
            $.each(selectors, function (selector, cssData) {
                css[size] += selector + '{';
                $.each(cssData, function (cssProp, cssValue) {
                    css[size] += cssProp + ':' + cssValue + ';';
                });
                css[size] += '}';
            });
        });

        $.each(css, function (size, cssValue) {
            if (size == 'desktop' && cssValue) {
                results += cssValue;
            } else if (size == 'tablet' && cssValue) {
                results += '@media (max-width: 1024px) {' + cssValue + '}';
            } else if (size == 'mobile' && cssValue) {
                results += '@media (max-width: 767px) {' + cssValue + '}';
            }
        });

        if ($styleTag.length == 0) {
            $vcIframe.contents().find('body').prepend('<style id="' + cssId + '" data-type="woodmart_shortcodes-custom-css">' + results + '</style>');
        } else if ($styleTag.length > 0) {
            $styleTag.html(results);
        }

        function dataSorting($this) {
            if ($this.parents('.vc_shortcode-param').hasClass('vc_dependent-hidden')) return;
            var data = $this.find('.wpb_vc_param_value').val();

            if (data) {
                var parseData = JSON.parse(window.atob(data));

                $.each(parseData.data, function (size, cssValue) {
                    if (typeof sortedCssData[size] != 'object') {
                        sortedCssData[size] = {};
                    }

                    $.each(parseData.css_args, function (cssProp, classesArray) {
                        $.each(classesArray, function (index, cssClass) {
                            selector = '#wd-' + parseData.selector_id + cssClass;

                            if (typeof sortedCssData[size][selector] != 'object') {
                                sortedCssData[size][selector] = {};
                            }

                            if (typeof sortedCssData[size][selector][cssProp] != 'object') {
                                sortedCssData[size][selector][cssProp] = {};
                            }

                            if (cssProp == 'font-size') {
                                sortedCssData[size][selector]['line-height'] = parseInt( cssValue.replace('px', '') ) + 10 + 'px';
                            }
                            if (cssProp == 'line-height') {
                                delete sortedCssData[size][selector]['line-height'];
                            }
                            sortedCssData[size][selector][cssProp] = cssValue;
                        });
                    });
                });
            }
        }
    });

    //JS init on frontend editor
    if (typeof window.InlineShortcodeView != 'undefined') {
        window.InlineShortcodeView_woodmart_brands = window.InlineShortcodeView.extend({
            rendered: function () {
                $(document).trigger('FrontendEditorCarouselInit', [this.$el.find('.brands-items-wrapper')]);
                window.InlineShortcodeView_woodmart_brands.__super__.rendered.call(this);
            }
        });

        window.InlineShortcodeView_woodmart_categories = window.InlineShortcodeView.extend({
            rendered: function () {
                $(document).trigger('FrontendEditorCarouselInit', [this.$el.find('.categories-style-carousel')]);
                window.InlineShortcodeView_woodmart_categories.__super__.rendered.call(this);
            }
        });

        window.InlineShortcodeView_woodmart_blog = window.InlineShortcodeView.extend({
            rendered: function () {
                $(document).trigger('FrontendEditorCarouselInit', [this.$el.find('.slider-type-post')]);
                window.InlineShortcodeView_woodmart_blog.__super__.rendered.call(this);
            }
        });

        window.InlineShortcodeView_woodmart_gallery = window.InlineShortcodeView.extend({
            rendered: function () {
                $(document).trigger('FrontendEditorCarouselInit', [this.$el.find('.wd-images-gallery')]);
                window.InlineShortcodeView_woodmart_gallery.__super__.rendered.call(this);
            }
        });

        window.InlineShortcodeView_woodmart_instagram = window.InlineShortcodeView.extend({
            rendered: function () {
                $(document).trigger('FrontendEditorCarouselInit', [this.$el.find('.instagram-pics')]);
                window.InlineShortcodeView_woodmart_instagram.__super__.rendered.call(this);
            }
        });

        window.InlineShortcodeView_woodmart_products = window.InlineShortcodeView.extend({
            rendered: function () {
                $(document).trigger('FrontendEditorCarouselInit', [this.$el.find('.slider-type-product')]);
                window.InlineShortcodeView_woodmart_products.__super__.rendered.call(this);
            }
        });
    }

})(jQuery);
