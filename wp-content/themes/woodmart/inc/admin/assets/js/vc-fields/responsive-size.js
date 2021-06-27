(function ($) {

    var $panel = $('#vc_ui-panel-edit-element');

    $panel.on('vcPanel.shown', function () {
        //Transfer old options
        transferCustomSizeOptions({
            oldSizes: {
                desktop: $('.desktop_text_size'),
                tablet: $('.tablet_text_size'),
                mobile: $('.mobile_text_size'),
            },
            newOptSelector: $('.title_font_size'),
        });

        transferCustomSizeOptions({
            oldSizes: {
                desktop: $('.desktop_text_size'),
                tablet: $('.tablet_text_size'),
                mobile: $('.mobile_text_size'),
            },
            newOptSelector: $('.text_font_size'),
        });

        transferCustomSizeOptions({
            oldSizes: {
                desktop: $('.title_desktop_text_size'),
                tablet: $('.title_tablet_text_size'),
                mobile: $('.title_mobile_text_size'),
            },
            newOptSelector: $('.custom_title_size'),
        });

        transferCustomSizeOptions({
            oldSizes: {
                desktop: $('.subtitle_desktop_text_size'),
                tablet: $('.subtitle_tablet_text_size'),
                mobile: $('.subtitle_mobile_text_size'),
            },
            newOptSelector: $('.custom_subtitle_size'),
        });

        function transferCustomSizeOptions(args) {
            if (args.newOptSelector.length == 0) return;

            $.each(args.oldSizes, function (key, value) {
                if (!value.val()) return;
                args.newOptSelector.find('input[data-id="' + key + '"]').val(value.val());
                args.newOptSelector.find('.woodmart-rs-item').removeClass('hide');
                args.newOptSelector.find('.woodmart-rs-value').val('');
                value.val('');
            });
        }

        //Size options
        $('.woodmart-rs-wrapper').each(function () {
            var $this = $(this);
            setInputsValue($this);
            setMainValue($this);
        });

        $('.woodmart-rs-input').on('change', function () {
            var $wrapper = $(this).parents('.woodmart-rs-wrapper');
            setMainValue($wrapper);
        });

        $('.woodmart-rs-trigger').on('click', function () {
            var $wrapper = $(this).parents('.woodmart-rs-wrapper');
            $wrapper.find('.woodmart-rs-item.tablet,.woodmart-rs-item.mobile').toggleClass('hide');
        });

        function setMainValue($this) {
            var $mainInput = $this.find('.woodmart-rs-value');
            var results = {
                param_type: 'woodmart_responsive_size',
                css_args: $mainInput.data('css_args'),
                selector_id: $('.woodmart-css-id').val(),
                data: {}
            };

            $this.find('.woodmart-rs-input').each(function (index, elm) {
                var value = $(elm).val();
                var responsive = $(elm).data('id');
                if (value) {
                    results.data[responsive] = value + 'px';
                }
            });

            if ($.isEmptyObject(results.data)) {
                results = '';
            } else {
                results = window.btoa(JSON.stringify(results));
            }

            $mainInput.val(results).trigger('change');
        }

        function setInputsValue($this) {
            var $mainInput = $this.find('.woodmart-rs-value');
            var mainInputVal = $mainInput.val();
            var toggle = {};

            if (mainInputVal) {
                var parseVal = JSON.parse(window.atob(mainInputVal));

                $.each(parseVal.data, function (key, value) {
                    $this.find('.woodmart-rs-input').each(function (index, element) {
                        var dataid = $(element).data('id');

                        if (dataid == key) {
                            $(element).val(value.replace('px', ''));
                            //Toggle
                            toggle[dataid] = value;
                        }
                    });
                });
            }

            //Toggle
            function size(obj) {
                var size = 0, key;
                for (key in obj) {
                    if (obj.hasOwnProperty(key)) size++;
                }
                return size;
            };

            var size = size(toggle);

            if (size >= 2) {
                $this.find('.woodmart-rs-item').removeClass('hide');
            }
        }

    });

})(jQuery);
