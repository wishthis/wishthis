$(function() {
    $.fn.api.settings.api = {
        'get wishlists' : '/includes/api/wishlists.php'
    };

    $('.ui.dropdown.wishlists').dropdown({
        filterRemoteData: true
    });

    $('.ui.dropdown.wishlists').api({
        action: 'get wishlists',
        method: 'GET',
        data : {
            userid: 1
        },
        on: 'now',
        onResponse: function(response) {
            console.log('onResponse');
            // make some adjustments to response
            return response;
        },
        successTest: function(response) {
            console.log('successTest');
            // test whether a JSON response is valid
            return response.success || false;
        },
        onComplete: function(response, element, xhr) {
            $('.ui.dropdown.wishlists').removeClass('loading');
        },
        onSuccess: function(response, element, xhr) {
            $('.ui.dropdown.wishlists')
            .dropdown({
                values: response.results
            })
            .dropdown('set selected', response.results[0].value);
        },
        onFailure: function(response, element, xhr) {
            console.log('onFailure');
            // request failed, or valid response but response.success = false
        },
        onError: function(errorMessage, element, xhr) {
            console.log('onError');
            // invalid response
        },
        onAbort: function(errorMessage, element, xhr) {
            console.log('onAbort');
            // navigated to a new page, CORS issue, or user canceled request
        }
    });
});
