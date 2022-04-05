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
        showStatistic(
            $('#wishes .value'),
            response.data.wishes.count,
            0
        );
        showStatistic(
            $('#wishlists .value'),
            response.data.wishlists.count,
            0
        );
        showStatistic(
            $('#users .value'),
            response.data.users.count,
            0
        );
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
