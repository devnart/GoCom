(function ($) {

    $('#vc_ui-panel-edit-element').on('vcPanel.shown', function () {

        $('.woodmart-vc-button-set').each(function () {
            var $this = $(this);
            var currentValue = $this.find('.woodmart-vc-button-set-value').val();

            $this.find('[data-value="' + currentValue + '"]').addClass('checked');
        });

        $('.vc-button-set-item').on('click', function () {
            var $this = $(this);
            var value = $this.data('value');

            $this.addClass('checked');
            $this.siblings().removeClass('checked');
            $this.parents('.woodmart-vc-button-set').find('.woodmart-vc-button-set-value').val(value).trigger('change');
        });

    });

})(jQuery);
