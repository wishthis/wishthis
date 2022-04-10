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

        /**
         * Update wish status
         */
        button.api({
            action    : 'update wish status',
            method    : 'PUT',
            data      : {
                wish_id     : card.attr('data-id'),
                wish_status : wish_status_temporary,
            },
            on        : 'now',
            onSuccess : function(response, element, xhr) {
                card.dimmer('show');
            },
        });
    });

    /** Confirm */
    $(document).on('click', '.card .button.confirm', function() {
        var button = $(this);
        var card   = button.closest('.card');

        button.api({
            action    : 'update wish status',
            method    : 'PUT',
            data      : {
                wish_id     : card.attr('data-id'),
                wish_status : wish_status_unavailable,
            },
            on        : 'now',
            onSuccess : function(response, element, xhr) {
                card.closest('.column').fadeOut();
            },
        });
    });

});
