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

        /**
         * Generate cache
         */
        var timerInterval = 1200;
        var timerCache    = setTimeout(
            function generateCache() {
                var cards = $('.ui.card[data-cache="false"]');

                cards.each(function(index, card) {
                    card = $(card);

                    var href       = card.find('.content [href]').prop('href');
                    var product_id = card.data('id');

                    card.addClass('loading');
                    card.attr('data-cache', true);

                    fetch('/src/api/cache.php?product_id=' + product_id + '&url=' + href, {
                        method: 'GET'
                    })
                    .then(response => response.json())
                    .then(response => {
                        if (response.success) {
                            var info = response.data;

                            /**
                             * Elements
                             */
                            var elementImage   = card.children('.image');
                            var elementContent = card.children('.content').first();
                            var elementDetails = card.children('.extra.content.details');
                            var elementButtons = card.children('.extra.content.buttons');

                            /**
                             * Image
                             */
                            if (info.image) {
                                if (!elementImage.length) {
                                    card.prepend(
                                        '<div class="image">' +
                                            '<img src="' + info.image + '" loading="lazy">' +
                                        '</div>'
                                    );
                                }
                            }

                            /**
                             * Header
                             */
                            var elementContentHeader      = elementContent.children('.header');
                            var elementContentHeaderTitle = elementContentHeader.children('a');

                            /** Favicon */
                            if (info.favicon) {
                                elementContentHeader.prepend(
                                    '<img src="' + info.favicon + '" loading="lazy">'
                                );
                            }

                            /** Title */
                            if (info.title) {
                                elementContentHeaderTitle.text(info.title);
                            }

                            /**
                             * Meta
                             */
                            var elementContentMeta = elementContent.children('.meta');

                            if (info.keywords.length) {
                                if (!elementContentMeta.length) {
                                    elementContent.append(
                                        '<div class="meta">' + info.keywords.join(', ') + '</div>'
                                    );
                                }
                            }

                            /**
                             * Description
                             */
                            var elementContentDescription = elementContent.children('.description');

                            if (info.description) {
                                if (!elementContentDescription.length) {
                                    elementContent.append(
                                        '<div class="description">' + info.description + '</div>'
                                    );
                                }
                            }

                            /**
                             * Details
                             */
                            if (info.publishedTime || info.providerName) {
                                if (!elementDetails.length) {
                                    elementButtons.before().append(
                                        '<div class="extra content details"></div>'
                                    );

                                    if (info.publishedTime) {
                                        elementContent.children('.extra.content.details').append(
                                            '<span class="right floated">' + info.publishedTime + '</span>'
                                        );
                                    }

                                    if (info.providerName) {
                                        elementContent.children('.extra.content.details').append(
                                            info.providerName
                                        );
                                    }
                                }
                            }

                            /**
                             * Finish
                             */
                            card.removeClass('loading');
                        }
                    });

                    if (index >= 0) {
                        return false;
                    }
                });

                if (cards.length > 0) {
                    setTimeout(generateCache, timerInterval);
                }
            },
            0
        );
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
