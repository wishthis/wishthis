$(function() {
    /**
     * Get Wishlists
     */
    var wishlists = [];

    $('.ui.dropdown.wishlists').api({
        action:    'get wishlists',
        method:    'GET',
        on:        'now',
        onSuccess: function(response, element, xhr) {
            wishlists = response.results;

            element.dropdown({
                values: wishlists,
                placeholder: 'No wishlist selected.'
            })

            if (urlParams.has('wishlist')) {
                element.dropdown('set selected', urlParams.get('wishlist'));
            } else {
                if (wishlists[0]) {
                    element.dropdown('set selected', wishlists[0].value);
                }
            }
        }
    });

    /**
     * Selection
     */
    $(document).on('change', '.ui.dropdown.wishlists', function() {
        var wishlistValue = $('.ui.dropdown.wishlists').dropdown('get value');
        var wishlistIndex = $('.ui.dropdown.wishlists select').prop('selectedIndex') - 1;

        $('[name="wishlist_delete_id"]').val(wishlistValue);

        if (wishlistValue) {
            urlParams.set('wishlist', wishlistValue);
            window.history.pushState({}, '', '/?' + urlParams.toString());

            $('.wishlist-share').attr('href', '/?wishlist=' + wishlists[wishlistIndex].hash);

            $('.wishlist-share').removeClass('disabled');
            $('.wishlist-delete button').removeClass('disabled');
        } else {
            $('.wishlist-share').addClass('disabled');
            $('.wishlist-delete button').addClass('disabled');
        }

        /**
         * Cards
         */
        $('.wishlist-cards').html(wishlists[wishlistIndex].cards);
    });

    /**
     * Delete Wishlist
     */
    $(document).on('submit', '.wishlist-delete', function(event) {
        var wishlistValue = $('.ui.dropdown.wishlists').dropdown('get value');

        if (wishlistValue) {
            $('body')
            .modal({
                title: 'Really delete?',
                class: 'tiny',
                content: 'Do you really want to delete the wishlist <strong>' + $('.ui.dropdown.wishlists').dropdown('get text') + '</strong>?',
                actions: [
                    {
                        text: 'Yes, delete',
                        class: 'approve red'
                    },
                    {
                        text: 'No, keep',
                        class: 'deny'
                    },
                ],
                onApprove: function() {
                    $('.ui.dropdown.wishlists').api({
                        action: 'delete wishlist',
                        method: 'DELETE',
                        data:   {
                            wishlistID: wishlistValue
                        },
                        on:     'now',
                        onSuccess: function(response, wishlists) {
                            $('.wishlist-cards .column').fadeOut();

                            wishlists.dropdown('clear');

                            urlParams.delete('wishlist');
                            window.history.pushState({}, '', '/?' + urlParams.toString());

                            $('.ui.dropdown.wishlists').api({
                                action: 'get wishlists',
                                method: 'GET',
                                on:     'now'
                            });
                        }
                    });
                }
            })
            .modal('show');
        }

        event.preventDefault();
    });
});
