$(function() {
    $('.ui.dropdown').dropdown();

    /**
     * User Warning
     */
    if ($('.wishlist-own').length) {
        $('body')
        .modal({
            title   : $('.wishlist-own .header').text(),
            content : $('.wishlist-own .text').html(),
            class   : '',
            blurring: true,
            closable: false,
            actions : [
                {
                    text : text.modal_wishlist_warning_approve,
                    class: 'approve primary'
                },
                {
                    text : text.modal_wishlist_warning_deny,
                    class: 'deny'
                }
            ],
            autoShow: true,
            onApprove: function() {
                window.close();
            },
            onDeny: function() {
                $('.wishlist-own').slideUp();
            }
        });
    }

    /**
     * Fulfil wish
     */
    $(document).on('click', '.ui.button.fulfil', function() {
        var button = $(this);
        var card   = button.closest('.ui.card');
        var column = card.closest('.column');

        $('body')
        .modal({
            title   : text.modal_wish_fulfil_title,
            content : text.modal_wish_fulfil,
            class   : 'tiny',
            actions : [
                {
                    text : text.modal_wish_fulfil_approve,
                    class: 'approve primary'
                },
                {
                    text : text.modal_wish_fulfil_deny,
                    class: ''
                }
            ],
            autoShow : true,
            onApprove: function() {
                /**
                 * Update wish status
                 */
                button.api({
                    action    : 'update wish status',
                    method    : 'PUT',
                    data      : {
                        wish_id     : card.attr('data-id'),
                        wish_status : 'unavailable'
                    },
                    on        : 'now',
                    onSuccess : function(response, element, xhr) {
                        column.fadeOut();
                    },
                });
            }
        });
    });
});
