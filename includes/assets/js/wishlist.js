$(function() {
    /**
     * User Warning
     */
    if ($('.wishlist-own').length) {
        $('body')
        .modal({
            title:    $('.wishlist-own .header').text(),
            content:  $('.wishlist-own .text').html(),
            class:    '',
            blurring: true,
            closable: false,
            actions:  [
                {
                    text: 'Show wishlist anyway',
                    class: 'approve primary'
                }
            ],
            onApprove: function() {
                $('.wishlist-own').slideUp();
            }
        })
        .modal('show');
    }

    /**
     * Commit
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
});
