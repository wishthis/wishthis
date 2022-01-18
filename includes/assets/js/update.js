$(function() {
    const urlParams = new URLSearchParams(window.location.search);

    var retryIn = urlParams.has('interval') ? urlParams.get('interval') : 5;
    var value = 0;
    var total = retryIn * 1000;

    $('#retryIn').html(retryIn);

    setTimeout(function step() {
        $('.ui.progress').progress({
            total: total,
            value: value
        });

        if (value >= total) {
            setTimeout(function() {
                urlParams.set('interval', parseInt(retryIn) + 5);

                window.location.href = window.location.origin + '/?' + urlParams.toString();
            }, 1000);
        } else {
            value += 100;

            setTimeout(step, 100);
        }
    }, 100)
});
