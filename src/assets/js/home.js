$(function() {
    /**
     * Statistics
     */
    fetch('/api/statistics/all', {
        method: 'GET'
    })
    .then(handleFetchError)
    .then(handleFetchResponse)
    .then(function(response) {
        /**
         * Intersection observer
         *
         * @link https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API
         */
        var observerOptions = {
            rootMargin : '0px',
            threshold  : 0
        };
        var observerCallbackWishes = (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    showStatistic($('#wishes .value'), response.data.wishes.count, 0, response.data.modified);

                    observerWishes.unobserve(document.querySelector('#wishes'));
                }
            });
        };
        var observerCallbackWishlists = (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    showStatistic($('#wishlists .value'), response.data.wishlists.count, 0, response.data.modified);

                    observerWishlists.unobserve(document.querySelector('#wishlists'));
                }
            });
        };
        var observerCallbackUsers = (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    showStatistic($('#users .value'), response.data.users.count, 0, response.data.modified);

                    observerUsers.unobserve(document.querySelector('#users'));
                }
            });
        };
        var observerWishes    = new IntersectionObserver(observerCallbackWishes, observerOptions);
        var observerWishlists = new IntersectionObserver(observerCallbackWishlists, observerOptions);
        var observerUsers     = new IntersectionObserver(observerCallbackUsers, observerOptions);

        observerWishes.observe(document.querySelector('#wishes'));
        observerWishlists.observe(document.querySelector('#wishlists'));
        observerUsers.observe(document.querySelector('#users'));
    });

    /**
     * News
     */
    fetch('/api/blog', {
        method: 'GET'
    })
    .then(handleFetchError)
    .then(handleFetchResponse)
    .then(function(response) {
        $('.ui.list.news').html(response.html);
    });
});

function showStatistic(elementStatistic, amount, timeout, modified) {
    const duration        = 2000;
    const intervalInitial = 42;

    var interval  = intervalInitial;
    var value     = 0;
    var percent   = 0;
    var increment = 1;

    setTimeout(
        function count() {
            increment = amount / duration * intervalInitial;

            if (value + increment < amount) {
                value = value + increment;

                elementStatistic.text(Math.round(value));

                setTimeout(count, interval);
            } else {
                elementStatistic.text(amount);
            }

            percent = value / amount * 100;

            if (percent >= 80) {
                interval = interval * 1.4;
            }
        },
        timeout
    );

    /** Set Popup */
    $(elementStatistic).popup({
        'title'     : wishthis.strings.popup.last_modified,
        'content'   : modified,
        'position'  : 'right center',
        'variation' : isDarkMode ? '' : 'inverted',
        'hoverable' : true,
    });
}
