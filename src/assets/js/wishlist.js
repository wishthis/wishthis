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
});
