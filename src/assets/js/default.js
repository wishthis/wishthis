const urlParams = new URLSearchParams(window.location.search);

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

        if ('string' === typeof response) {
            response = response.replace('<br />', '');
        }

        $('body')
        .modal({
            title:    'Failure',
            content:  response,
            class:    '',
            actions:  [
                {
                    text: 'Thanks for nothing',
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

        if ('string' === typeof response) {
            response = response.replace('<br />', '');
        }

        $('body')
        .modal({
            title:    'Error',
            content:  response,
            class:    '',
            actions:  [
                {
                    text: 'Thanks for nothing',
                    class: 'primary'
                }
            ]
        })
        .modal('show');
    }
    /** */

    $('.ui.dropdown.wishlists').dropdown({
        filterRemoteData: true
    });

    /**
     * Commit to Product
     */
     $(document).on('click', '.ui.button.commit', function() {
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
                    onSuccess: function(response, element, xhr) {
                        column.fadeOut();
                    },
                });
            }
        })
        .modal('show');
    });

    /**
     * Delete Product
     */
     $(document).on('click', '.ui.button.delete', function() {
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
                    text: 'Cancel'
                }
            ],
            onApprove: function() {
                /**
                 * Delete product
                 */
                button.api({
                    action:     'delete product',
                    method:     'DELETE',
                    data:       {
                        productID: card.data('id'),
                    },
                    on:        'now',
                    onSuccess: function() {
                        column.fadeOut();
                    },
                });
            }
        })
        .modal('show');
    });
});
