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

            const urlParams = new URLSearchParams(window.location.search);

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
    $('.ui.dropdown.wishlists').on('change', function() {
        var wishlistValue = $('.ui.dropdown.wishlists').dropdown('get value');
        var wishlistIndex = $('.ui.dropdown.wishlists select').prop('selectedIndex') - 1;

        $('[name="wishlist_delete_id"]').val(wishlistValue);

        if (wishlistValue) {
            $('.wishlist-view').removeClass('disabled');

            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('wishlist', wishlistValue);
            window.history.pushState({}, '', '/?' + urlParams.toString());

            $('.wishlist-share').attr('href', '/?wishlist=' + wishlists[wishlistIndex].hash);

            $('.wishlist-share').removeClass('disabled');
            $('.wishlist-delete button').removeClass('disabled');
        } else {
            $('.wishlist-view').addClass('disabled');

            $('.wishlist-share').addClass('disabled');
            $('.wishlist-delete button').addClass('disabled');
        }
    });

    /**
     * Delete Wishlist
     */
    $('.wishlist-delete').on('submit', function(event) {
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
                        data: {
                            wishlistID: wishlistValue
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
                            wishlistRefresh();
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
        }

        event.preventDefault();
    });
});
