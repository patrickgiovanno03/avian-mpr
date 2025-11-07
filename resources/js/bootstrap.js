window._ = require("lodash");

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require("popper.js").default;
    window.$ = window.jQuery = require("jquery");

    require("bootstrap");
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require("axios");

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.baseURL = document.head.querySelector(
    'meta[name="base-url"]'
).content;

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

try {
    window.moment = require("moment");
    window.Highcharts = require("highcharts");

    require("highcharts/modules/exporting")(window.Highcharts);
} catch (e) {}

try {
    window.Promise = require("bluebird");
} catch (e) {}

try {
    window.AutoNumeric = require("autonumeric");
} catch (error) {
    console.log("Unable to load AutoNumeric " + error);
}

try {
    window.toastr = require("toastr");
} catch (error) {
    console.error("Unable to load toastr", error);
}

if ($) {
    $.fn.blockMessage = function (message = "Please wait...", color = "#FFF") {
        this.block({
            message:
                '<span class="text-semibold"><i class="icon-spinner4 spinner position-left"></i>&nbsp;' +
                message +
                "</span>",
            overlayCSS: {
                backgroundColor: color,
                opacity: 0.8,
                cursor: "wait",
            },
            css: {
                border: 0,
                padding: "10px 15px",
                color: "#fff",
                width: "auto",
                "-webkit-border-radius": 2,
                "-moz-border-radius": 2,
                backgroundColor: "#333",
            },
        });

        return this;
    };

    $.fn.unblockMessage = function () {
        this.unblock();

        return this;
    };

    $.fn.processExcel = function (data) {
        var workbook = XLSX.read(data, {
            type: "binary",
        });

        var firstSheet = workbook.SheetNames[0];
        var data = to_json(workbook);
        return data;
    };

    $.fn.toIdrFormat = function (angka, prefix = "IDR", isDecimal = 1) {
        var FixedNumber = 2;
        if (isDecimal == "0") {
            FixedNumber = 0;
        }
        // console.log(isDecimal);
        var minus = false;
        if (angka < 0) {
            minus = true;
        }
        angka = parseFloat(angka);
        var strAngka = angka
            .toFixed(FixedNumber)
            .toString()
            // Keep only digits and decimal points:
            .replace(/[^\d.]/g, "")
            // Remove duplicated decimal point, if one exists:
            .replace(/^(\d*\.)(.*)\.(.*)$/, "$1$2$3")
            // Keep only two digits past the decimal point:
            .replace(/\.(\d{2})\d+/, ".$1")
            // Add thousands separators:
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        if (minus) {
            strAngka = "-" + strAngka;
        }

        return strAngka;
    };
}
