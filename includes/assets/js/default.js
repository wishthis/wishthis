$(function() {
    /**
     * Fomantic UI
     */
    $.fn.api.settings.api = {
        'get wishlists'        : '/includes/api/wishlists.php',
        'delete wishlist'      : '/includes/api/wishlists.php',
        'update product status': '/includes/api/products.php',
    };

    $('.ui.dropdown.wishlists').dropdown({
        filterRemoteData: true
    });

    wishlistRefresh();
});

function wishlistRefresh() {
    /**
     * URL Parameter
     */
    const urlParams = new URLSearchParams(window.location.search);

    $('.ui.dropdown.wishlists').api({
        action: 'get wishlists',
        method: 'GET',
        on: 'now',
        onResponse: function(response) {
            return response;
        },
        successTest: function(response) {
            return response.success || false;
        },
        onComplete: function(response, element, xhr) {
            $('.ui.dropdown.wishlists').removeClass('loading');
        },
        onSuccess: function(response, element, xhr) {
            $('.ui.dropdown.wishlists').dropdown({
                values: response.results,
                placeholder: 'No wishlist selected.'
            })

            if (urlParams.has('wishlist')) {
                $('.ui.dropdown.wishlists').dropdown('set selected', urlParams.get('wishlist'));
            }
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
}
