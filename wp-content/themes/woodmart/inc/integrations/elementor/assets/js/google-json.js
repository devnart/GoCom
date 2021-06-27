jQuery(window).on('elementor:init', function() {
    var googleJson = elementor.modules.controls.BaseData.extend({
        onReady: function() {
            var val = this.ui.input.val();

            try {
                this.ui.textarea.val(atob(val));
            } catch(e) {
                this.ui.textarea.val(val);
            }
        },

        onBeforeDestroy: function() {
             this.setValue(btoa(this.ui.textarea.val()));
        },

        onBaseInputChange: function() {
            this.setValue(btoa(this.ui.textarea.val()));
        },
    });
    elementor.addControlView('wd_google_json', googleJson);
});
