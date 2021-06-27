(function ($) {

    var $panel = $('#vc_ui-panel-edit-element');

    $panel.on('vcPanel.shown', function () {
        var $sliders = $('.woodmart-vc-slider');
        $sliders.each(function () {
            var $this = $(this);
            var $value = $this.find('.woodmart-slider-field-value');
            var $slider = $this.find('.woodmart-slider-field');
            var $text = $this.find('.woodmart-slider-field-value-text');
            var sliderData = $value.data();

            $slider.slider({
                range: 'min',
                value: sliderData.start,
                min: sliderData.min,
                max: sliderData.max,
                step: sliderData.step,
                slide: function (event, ui) {
                    $value.val(ui.value).trigger('change');
                    $text.text(ui.value);
                }
            });

            // Initiate the display
            $value.val($slider.slider('value')).trigger('change');
            $text.text($slider.slider('value'));
        });
    });

})(jQuery);
