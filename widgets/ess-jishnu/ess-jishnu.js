/* JS file content */
(function ($) {
    var ess_jishnu = function ($scope, $) {
        /* Add the script here */
    }
    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/ess-jishnu.default", ess_jishnu);
    });
})(jQuery);