$(function() {
    /**
     * Fomantic UI
     */
    $.fn.api.settings.api = {
        'get wishlists'        : '/src/api/wishlists.php',
        'delete wishlist'      : '/src/api/wishlists.php',
        'update product status': '/src/api/products.php',
        'delete product'       : '/src/api/products.php',
    };

    /** Default callbacks */
    $.fn.api.settings.onResponse = function(response) {
        return response;
    }
    $.fn.api.settings.successTest = function(response) {
        return response.status == 'OK' || response.success || false;
    }
    $.fn.api.settings.onComplete = function(response, element, xhr) {
        element.removeClass('loading');
    }
    $.fn.api.settings.onSuccess = function(response, element, xhr) {
        element.dropdown({
            values: response.results,
            placeholder: 'No wishlist selected.'
        })

        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.has('wishlist')) {
            element.dropdown('set selected', urlParams.get('wishlist'));
        } else {
            if (response.results[0]) {
                element.dropdown('set selected', response.results[0].value);
            }
        }
    }
    $.fn.api.settings.onFailure = function(response, element, xhr) {
        console.log(response);
        console.log(element);
        console.log(xhr);

        $('body')
        .modal({
            title:    'Failure',
            content:  'The process has failed.',
            class:    '',
            actions:  [
                {
                    text: 'Oh well',
                    class: 'primary'
                }
            ]
        })
        .modal('show');
    }
    $.fn.api.settings.onError = function(response, element, xhr) {
        console.log(response);
        console.log(element);
        console.log(xhr);

        $('body')
        .modal({
            title:    'Error',
            content:  'There has been an error.',
            class:    '',
            actions:  [
                {
                    text: 'Oh well',
                    class: 'primary'
                }
            ]
        })
        .modal('show');
    }
    $.fn.api.settings.onAbort = function(response, element, xhr) {
        console.log(response);
        console.log(element);
        console.log(xhr);

        $('body')
        .modal({
            title:    'Interrupted',
            content:  'The process was interrupted.',
            class:    '',
            actions:  [
                {
                    text: 'Oh well',
                    class: 'primary'
                }
            ]
        })
        .modal('show');
    }

    $('.ui.dropdown.wishlists').dropdown({
        filterRemoteData: true
    });

    /**
     * Refresh Wishlist
     */
    // wishlistRefresh();

    /**
     * Commit to Product
     */
     $('.ui.button.commit').on('click', function() {
        var button = $(this);
        var card   = button.closest('.ui.card');
        var column = card.closest('.column');

        $('body')
        .modal({
            title:    'Really commit?',
            content:  'Would you really like to commit to this purchase? It will no longer appear in the wishlist anymore.',
            class:    'tiny',
            actions:  [
                {
                    text: 'Yes, commit',
                    class: 'approve primary'
                },
                {
                    text: 'Cancel',
                    class: ''
                }
            ],
            onApprove: function() {
                /**
                 * Update product status
                 */
                button.api({
                    action: 'update product status',
                    method: 'PUT',
                    data: {
                        productID: card.data('id'),
                        productStatus: 'unavailable'
                    },
                    on: 'now',
                    onResponse: function(response) {
                        return response;
                    },
                    successTest: function(response) {
                        return response.success || false;
                    },
                    onComplete: function(response, element, xhr) {

                    },
                    onSuccess: function(response, element, xhr) {
                        column.fadeOut();
                    },
                    onFailure: function(response, element, xhr) {

                    },
                    onError: function(errorMessage, element, xhr) {

                    },
                    onAbort: function(errorMessage, element, xhr) {

                    }
                });
            }
        })
        .modal('show');
    });

    /**
     * Delete Product
     */
     $('.ui.button.delete').on('click', function() {
        var button = $(this);
        var card   = button.closest('.ui.card');
        var column = card.closest('.column');

        $('body')
        .modal({
            title:    'Really delete?',
            content:  'Would you really like to delete to this product? It will be gone forever.',
            class:    'tiny',
            actions:  [
                {
                    text: 'Yes, delete',
                    class: 'approve primary'
                },
                {
                    text: 'Cancel',
                    class: ''
                }
            ],
            onApprove: function() {
                /**
                 * Delete product
                 */
                button.api({
                    action: 'delete product',
                    method: 'DELETE',
                    data: {
                        productID: card.data('id'),
                    },
                    on: 'now',
                    onResponse: function(response) {
                        return response;
                    },
                    successTest: function(response) {
                        return response.success || false;
                    },
                    onComplete: function(response, element, xhr) {

                    },
                    onSuccess: function(response, element, xhr) {
                        column.fadeOut();
                    },
                    onFailure: function(response, element, xhr) {

                    },
                    onError: function(errorMessage, element, xhr) {

                    },
                    onAbort: function(errorMessage, element, xhr) {

                    }
                });
            }
        })
        .modal('show');
    });
});

function wishlistRefresh() {
    /**
     * URL Parameter
     */
    const urlParams = new URLSearchParams(window.location.search);

    $('.ui.dropdown.wishlists').api({
        action: 'get wishlists',
        method: 'GET',
        on:     'now'
    });
}
