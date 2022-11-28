$(function () {

    /**
     * Filter wishes
     */
    $('.ui.dropdown.filter.priority')
    .dropdown({
        'match'          : 'text',
        'fullTextSearch' : true,
    })
    .api({
        'action'     : 'get wishlists by priority',
        'beforeSend' : function (settings) {
            var wishlist_id = $('.wishlist-cards[data-wishlist]').attr('data-wishlist');

            if (undefined === wishthis.$_GET.id && wishlist_id.length > 0) {
                wishthis.$_GET.id = wishlist_id;
            }

            settings.urlData.style      = $('input[name="style"]').val();
            settings.urlData.priority   = $('.ui.dropdown.filter.priority').dropdown('get value');
            settings.urlData.wishlistid = wishthis.$_GET.id;

            return settings;
        },
        'onSuccess'  : function (response, dropdown_wishlists, xhr) {
            var html = response.results ? response.results : '';

            $('.wishlist-cards').html(html);
            $('.ui.dropdown.wish-options')
            .removeClass('disabled')
            .dropdown({
                'action' : 'select'
            });
        }
    })
    .dropdown('set selected', -1);

    /**
     * Style
     */
    $('.buttons.view .button[value]').on('click', function() {
        $('input[name="style"]').val( $(this).val() );

        $('.buttons.view .button[value]').removeClass('active');
        $(this).addClass('active');

        $('.ui.dropdown.filter.priority').api('query');
    });

    if ($('.ui.dropdown.wishlists').length === 0) {
        const orientationIsPortrait = window.matchMedia('(orientation: portrait)');

        if (orientationIsPortrait.matches) {
            $('.buttons.view .button[value="list"]').trigger('click');
        } else {
            $('.buttons.view .button[value="grid"]').trigger('click');
        }
    }

});
