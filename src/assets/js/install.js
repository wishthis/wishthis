$(function() {

    /**
     * Step 1
     */
    $(document).on('click', '#database-test', function(event) {
        var form         = $(this).closest('form');
        var formDatabase = new URLSearchParams(new FormData(form[0]));

        form.addClass('loading');

        fetch('/api/database-test', {
            method : 'POST',
            body   : formDatabase
        })
        .then(handleFetchError)
        .then(handleFetchResponse)
        .then(function(response) {
            if (response.dbTestSuccess) {
                $('input[type="submit"]').removeClass('disabled');

                form.form('remove errors');

                $('body').toast({ message : 'Database connection succeeded.' });
            } else {
                $('input[type="submit"]').addClass('disabled');

                form.form('add errors', ['Database connection failed.']);
            }
        })
        .finally(function() {
            form.removeClass('loading');
        });
    });

});
