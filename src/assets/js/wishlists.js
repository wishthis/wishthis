$(function () {
    /**
     * Get Wishlists
     */
    var wishlists = [];

    $('.ui.dropdown.wishlists').api({
        action: 'get wishlists',
        method: 'GET',
        on: 'now',
        onSuccess: function (response, element, xhr) {
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
    $(document).on('change', '.ui.dropdown.wishlists', function () {
        var wishlistValue = $('.ui.dropdown.wishlists').dropdown('get value');
        var wishlistIndex = $('.ui.dropdown.wishlists select').prop('selectedIndex') - 1;

        $('[name="wishlist_delete_id"]').val(wishlistValue);

        if (wishlistValue) {
            urlParams.set('wishlist', wishlistValue);
            window.history.pushState({}, '', '/?' + urlParams.toString());

            $('.wishlist-share').attr('href', '/?wishlist=' + wishlists[wishlistIndex].hash);

            $('.wishlist-product-add').removeClass('disabled');
            $('.wishlist-share').removeClass('disabled');
            $('.wishlist-delete button').removeClass('disabled');
        } else {
            $('.wishlist-product-add').addClass('disabled');
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
        var timerCache = setTimeout(
            function generateCacheCards() {
                var cards = $('.ui.card[data-cache="false"]');

                cards.each(function (index, card) {
                    generateCacheCard(card);

                    if (index >= 0) {
                        return false;
                    }
                });

                if (cards.length > 0) {
                    setTimeout(generateCacheCards, timerInterval);
                }
            },
            0
        );
    });

    function generateCacheCard(card) {
        card = $(card);

        var href = card.find('.content [href]').prop('href');
        var product_id = card.data('id');
        var refresh = card.find('button.refresh');

        card.addClass('loading');
        card.attr('data-cache', true);

        fetch('/src/api/cache.php?product_id=' + product_id + '&product_url=' + href, {
            method: 'GET'
        })
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                var info = response.data;

                /**
                 * Elements
                 */
                var elementImage = card.children('.image');
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
                            '<img class="preview" src="' + info.image + '" loading="lazy">' +
                            '</div>'
                        );
                    } else {
                        elementImage.children('img').attr('src', info.image);
                    }
                }

                /** Favicon */
                if (info.favicon) {
                    var elementFavicon = elementImage.children('img.favicon');

                    if (!elementFavicon.length) {
                        elementImage.children().first().after(
                            '<img class="favicon" src="' + info.favicon + '" loading="lazy">'
                        );
                    } else {
                        elementFavicon.attr('src', info.favicon);
                    }
                }

                /** Provider name */
                if (info.providerName) {
                    var elementProviderName = elementImage.children('span.provider');

                    if (!elementProviderName.length) {
                        $('<span class="provider">' + info.providerName + '</span>').insertBefore(elementImage.children().last());
                    } else {
                        elementProviderName.text(info.providerName);
                    }
                }

                /**
                 * Header
                 */
                var elementContentHeader = elementContent.children('.header');
                var elementContentHeaderTitle = elementContentHeader.children('a');

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
                 * Finish
                 */
                card.removeClass('loading');
            }

            refresh.removeClass('working');
        });
    }

    /**
     * Refresh
     */
    $(document).on('click', '.ui.button.refresh', function (event) {
        var button = $(event.currentTarget);
        var card = button.closest('.ui.card');

        button.addClass('working');

        generateCacheCard(card);
    });

    /**
     * Delete Wishlist
     */
    $(document).on('submit', '.wishlist-delete', function (event) {
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
                onApprove: function () {
                    $('.ui.dropdown.wishlists').api({
                        action: 'delete wishlist',
                        method: 'DELETE',
                        data: {
                            wishlistID: wishlistValue
                        },
                        on: 'now',
                        onSuccess: function (response, wishlists) {
                            $('.wishlist-cards .column').fadeOut();

                            wishlists.dropdown('clear');

                            urlParams.delete('wishlist');
                            window.history.pushState({}, '', '/?' + urlParams.toString());

                            $('.ui.dropdown.wishlists').api({
                                action: 'get wishlists',
                                method: 'GET',
                                on: 'now'
                            });
                        }
                    });
                }
            })
            .modal('show');
        }

        event.preventDefault();
    });

    /**
     * Delete Product
     */
    $(document).on('click', '.ui.button.delete', function () {
        var button = $(this);
        var card = button.closest('.ui.card');
        var column = card.closest('.column');

        $('body')
        .modal({
            title: 'Really delete?',
            content: 'Would you really like to delete to this product? It will be gone forever.',
            class: 'tiny',
            actions: [
                {
                    text: 'Yes, delete',
                    class: 'approve primary'
                },
                {
                    text: 'Cancel'
                }
            ],
            onApprove: function () {
                /**
                 * Delete product
                 */
                button.api({
                    action: 'delete product',
                    method: 'DELETE',
                    data: {
                        productID: card.data('id'),
                    },
                    on: 'now',
                    onSuccess: function () {
                        column.fadeOut();

                        location.reload();
                    },
                });
            }
        })
        .modal('show');
    });

    /**
     * Add product
     */
    $(document).on('click', '.wishlist-product-add', function () {
        var modal = $('.ui.modal.wishlist-product-add');

        modal.find('[name="wishlist_id"]').val($('.ui.dropdown.wishlists').dropdown('get value'));
        modal
        .modal({
            onApprove: function (button) {
                button.addClass('loading');

                var form = $('.ui.form.wishlist-product-fetch');
                var formData = new URLSearchParams();
                formData.append('wishlist_id', form.find('input[name="wishlist_id"]').val());
                formData.append('product_url', form.find('input[name="product_url"]').val());

                fetch('/src/api/products.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        modal.modal('hide');
                    }

                    button.removeClass('loading');

                    location.reload();
                });

                return false;
            }
        })
        .modal('show');
    });

    /** Fetch */
    $(document).on('submit', '.wishlist-product-fetch', function (event) {
        event.preventDefault();

        var form = $(event.currentTarget);
        var href = form.find('[name="product_url"]').val();

        var elementModalAdd = $('.ui.modal.wishlist-product-add');
        var elementButtons = elementModalAdd.find('.actions .button');
        var elementImage = elementModalAdd.find('.image img');

        form.addClass('loading');
        elementButtons.addClass('disabled');

        fetch('/src/api/cache.php?product_url=' + href, {
            method: 'GET'
        })
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                var info = response.data;

                /**
                 * Image
                 */
                if (info.image && elementImage.length) {
                    elementImage.attr('src', info.image);
                }

                /**
                 * URL
                 */
                if (info.url && info.url !== href) {
                    var elementModalFetch = $('.ui.modal.wishlist-product-fetch');

                    elementModalFetch.find('input.current').val(href);
                    elementModalFetch.find('input.proposed').val(info.url);

                    elementButtons.addClass('disabled');

                    elementModalFetch
                    .modal({
                        allowMultiple: true,
                        closable: false,
                        onApprove: function (buttonFetch) {
                            var formData = new URLSearchParams();
                            formData.append('product_url_current', href);
                            formData.append('product_url_proposed', info.url);

                            buttonFetch.addClass('loading');

                            fetch('/src/api/products.php', {
                                method: 'PUT',
                                body: formData
                            })
                                .then(response => response.json())
                                .then(response => {
                                    if (response.success) {
                                        form.find('input[type="url"]').val(info.url);

                                        elementModalFetch.modal('hide');
                                    }

                                    buttonFetch.removeClass('loading');
                                });

                            return false;
                        },
                        onHide: function() {
                            form.removeClass('loading');
                            elementButtons.removeClass('disabled');
                        }
                    })
                    .modal('show');
                } else {
                    form.removeClass('loading');
                    elementButtons.removeClass('disabled');
                }
            }
        });
    });

});
