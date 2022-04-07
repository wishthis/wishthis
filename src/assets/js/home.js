$(function() {
    /**
     * Statistics
     */
    fetch('/src/api/statistics.php?table=all', {
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
                    showStatistic($('#wishes .value'), response.data.wishes.count, 0);
                }
            });
        };
        var observerCallbackWishlists = (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    showStatistic($('#wishlists .value'), response.data.wishlists.count, 0);
                }
            });
        };
        var observerCallbackUsers = (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    showStatistic($('#users .value'), response.data.users.count, 0);
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
});

function showStatistic(elementStatistic, amount, timeout) {
    var interval    = 20;
    var intervalEnd = 750;

    setTimeout(
        function count() {
            var value = $.isNumeric(elementStatistic.text())
                      ? parseInt(elementStatistic.text())
                      : -1;

            if (value < amount) {
                elementStatistic.text(value + 1);

                var remainingSlowDown    = 6;
                var remainingInterations = amount - value;

                if (remainingInterations < remainingSlowDown) {
                    interval = (remainingSlowDown - remainingInterations) * (intervalEnd / remainingSlowDown);
                }

                setTimeout(count, interval);
            }
        },
        timeout
    );
}
