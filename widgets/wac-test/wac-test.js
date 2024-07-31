/* JS file content */
(function ($) {
    var wac_test = function ($scope, $) {
        /* Add the script here */
    }
    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/wac-test.default", wac_test);
    });
})(jQuery);