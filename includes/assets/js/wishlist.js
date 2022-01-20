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
});
