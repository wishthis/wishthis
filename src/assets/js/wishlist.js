$(function() {
    $('.ui.dropdown').dropdown();

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
                    text: 'Close this tab',
                    class: 'approve primary'
                },
                {
                    text: 'Show wishlist anyway',
                    class: 'deny'
                }
            ],
            onApprove: function() {
                window.close();
            },
            onDeny: function() {
                $('.wishlist-own').slideUp();
            }
        })
        .modal('show');
    }

    /**
     * Commit to Wish
     */
    $(document).on('click', '.ui.button.commit', function() {
    var button = $(this);
    var card   = button.closest('.ui.card');
    var column = card.closest('.column');

    $('body')
    .modal({
        title:    'Really commit?',
        content:  'Would you really like to commit to this purchase? It will no longer appear in the wishlist for others anymore.',
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
             * Update wish status
             */
            button.api({
                action: 'update wish status',
                method: 'PUT',
                data: {
                    wish_id: card.data('id'),
                    wish_status: 'unavailable'
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
});
