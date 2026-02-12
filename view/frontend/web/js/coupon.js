define(['jquery'], function ($) {
    'use strict';

    return function (config) {

        const delay = config.delay || 2000;

        const popup = $('#offer-popup');
        const countdownEl = $('#countdown');

        function startCountdown() {
            const expiration = parseInt(countdownEl.data('expiration'), 10);
            let time = expiration || 10;

            if (countdownEl.data('timer')) {
                clearInterval(countdownEl.data('timer'));
            }

            const timer = setInterval(function () {
                const minutes = Math.floor(time / 60);
                const seconds = time % 60;

                countdownEl.text(
                    (minutes < 10 ? '0' : '') + minutes + ':' +
                    (seconds < 10 ? '0' : '') + seconds
                );

                time--;

                if (time < 0) {
                    clearInterval(timer);
                    countdownEl.text('¡Expired!');
                }
            }, 1000);

            countdownEl.data('timer', timer);
        }

        function init() {

            setTimeout(function () {
                popup.addClass('show');
                startCountdown();
            }, delay);

            $('#close-popup, #accept-popup').on('click', function () {
                popup.removeClass('show');
            });

            popup.on('click', function (e) {
                if (e.target.id === 'offer-popup') {
                    popup.removeClass('show');
                }
            });
        }

        $(document).ready(init);
    };
});