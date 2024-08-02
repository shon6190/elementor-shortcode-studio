/* JS file content */
(function ($) {
    var ess_test = function ($scope, $) {
        /* Add the script here */
    }
    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/ess-test.default", ess_test);
    });
})(jQuery);