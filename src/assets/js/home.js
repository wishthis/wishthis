$(function() {
    /**
     * Statistics
     */
    fetch('/src/api/statistics.php?table=all', {
        method: 'GET'
    })
    .then(response => response.json())
    .then(response => {
        console.log(response);

        if (response.success) {
            showStatistic(
                $('#products .value'),
                response.data.products.count,
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
        }
    });
});

function showStatistic(elementStatistic, amount, timeout) {
    var interval    = 20;
    var intervalEnd = 1000;

    setTimeout(
        function count() {
            var value = $.isNumeric(elementStatistic.text())
                      ? parseInt(elementStatistic.text())
                      : -1;

            if (value <= amount) {
                elementStatistic.text(value + 1);

                var remainingSlowDown    = 3;
                var remainingInterations = amount - value;

                if (remainingInterations < remainingSlowDown) {
                    interval = (remainingSlowDown - remainingInterations) * (intervalEnd / remainingSlowDown);
                } else {
                    interval += interval * 0.1;
                }

                console.log(interval);
                setTimeout(count, interval);
            }
        },
        timeout
    );
}
