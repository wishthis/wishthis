$(function () {

    /**
     * Filter wishes
     */
    $('.ui.dropdown.filter.priority')
    .dropdown({
        match          : 'text',
        fullTextSearch : true,
    })
    .dropdown('set selected', -1)
    .api({
        'action'  : 'get wishlists with priority',
        'urlData' : {
            'apitoken' : api.token,
        },
        beforeSend : function (settings) {
            settings.urlData.style      = $('input[name="style"]').val();
            settings.urlData.priority   = $('.ui.dropdown.filter.priority').dropdown('get value');

            /** Wishlist ID */
            var wistlist_cards_data = $('.wishlist-cards').attr('data-wishlist')
            var dropdown_wishlists  = $('.dropdown.wishlists');

            if (dropdown_wishlists.length) {
                var dropdown_wishlists_value = dropdown_wishlists.dropdown('get value');

                if (dropdown_wishlists_value) {
                    settings.urlData.wishlistid = dropdown_wishlists_value;
                }
            }

            if (typeof wistlist_cards_data !== 'undefined') {
                settings.urlData.wishlistid = wistlist_cards_data;
            }
            /** */

            return settings;
        },
        onSuccess  : function (response, dropdown_wishlists, xhr) {
            var html = response.results ? response.results : '';

            $('.wishlist-cards').html(html);
            $('.ui.dropdown.options').dropdown();
        }
    });


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
