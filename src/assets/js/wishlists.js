$(function () {
    /**
     * Get Wishlists
     */
    var wishlists = [];

    function wishlistsRefresh() {
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

                if ($_GET.wishlist) {
                    element.dropdown('set selected', $_GET.wishlist);
                } else {
                    if (wishlists[0]) {
                        element.dropdown('set selected', wishlists[0].value);
                    }
                }
            }
        });
    }

    wishlistsRefresh();

    /**
     * Selection
     */
    var progress = $('.ui.progress');

    $(document).on('change', '.ui.dropdown.wishlists', function () {
        var wishlistValue = $('.ui.dropdown.wishlists').dropdown('get value');
        var wishlistIndex = $('.ui.dropdown.wishlists select').prop('selectedIndex') - 1;

        progress.progress('reset');
        progress.addClass('indeterminate');

        $('[name="wishlist_delete_id"]').val(wishlistValue);

        if (wishlistValue) {
            $_GET.wishlist = wishlistValue;

            $('.wishlist-share').attr('href', '/?wishlist=' + wishlists[wishlistIndex].hash);

            $('.button.wishlist-wish-add').removeClass('disabled');
            $('.button.wishlist-share').removeClass('disabled');
            $('.wishlist-delete button').removeClass('disabled');

            /** Update URL */
            urlParams.set('wishlist', wishlistValue);

            fetch('/src/api/url.php?url=' + btoa(urlParams.toString()), {
                method: 'GET'
            })
            .then(response => response.json())
            .then(response => {
                if (response.success) {
                    window.history.pushState({}, '', response.data.url);
                }
            });
        } else {
            $('.button.wishlist-wish-add').addClass('disabled');
            $('.button.wishlist-share').addClass('disabled');
            $('.wishlist-delete button').addClass('disabled');
        }

        /**
         * Cards
         */
        if (wishlistIndex >= 0) {
            $('.wishlist-cards').html(wishlists[wishlistIndex].cards);
        } else {
            $('.wishlist-cards').html('');
        }

        /**
         * Generate cache
         */
        var cards = $('.ui.card[data-cache="false"]');

        if (cards.length > 0) {
            progress.slideDown();
            progress.removeClass('indeterminate');
            progress.progress({
                total: cards.length
            });
        } else {
            progress.slideUp();
        }

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

        var href    = card.find('.content [href]').prop('href');
        var wish_id = card.data('id');
        var refresh = card.find('button.refresh');

        card.addClass('loading');
        card.attr('data-cache', true);

        fetch('/src/api/cache.php?wish_id=' + wish_id + '&wish_url=' + href, {
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
                            '<div class="description">' + info.description + '</div>' +
                            '<div class="description-fade"></div>'
                        );
                    }
                }

                /**
                 * Finish
                 */
                card.removeClass('loading');
                progress.progress('increment');
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
        event.preventDefault();

        var wishlistValue = $('.ui.dropdown.wishlists').dropdown('get value');

        if (wishlistValue) {
            var modalDefault = $('.ui.modal.default');

            modalDefault
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
                onApprove: function (buttonApprove) {
                    buttonApprove.addClass('loading');

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

                            $('body').toast({
                                class:    'success',
                                showIcon: 'check',
                                message:  'Wishlist successfully deleted.'
                            });

                            wishlistsRefresh();

                            modalDefault.modal('hide');
                        }
                    });

                    /**
                     * Return false is currently not working.
                     *
                     * @version 2.8.8
                     * @see     https://github.com/fomantic/Fomantic-UI/issues/2105
                     */
                    return false;
                }
            })
            .modal('show');
        }
    });

    /**
     * Delete Wish
     */
    $(document).on('click', '.ui.button.delete', function () {
        var button       = $(this);
        var card         = button.closest('.ui.card');
        var column       = card.closest('.column');
        var modalDefault = $('.ui.modal.default');

        modalDefault
        .modal({
            title: 'Really delete?',
            content: '<p>Would you really like to delete to this wish? It will be gone forever.</p>',
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
            onApprove: function (buttonApprove) {
                buttonApprove.addClass('loading');

                /**
                 * Delete wish
                 */
                button.api({
                    action: 'delete wish',
                    method: 'DELETE',
                    data: {
                        wish_id: card.data('id'),
                    },
                    on: 'now',
                    onSuccess: function () {
                        column.fadeOut();

                        $('body').toast({
                            class:   'success',
                            showIcon: 'check',
                            message:  'Wish successfully deleted.'
                        });

                        wishlistsRefresh();

                        modalDefault.modal('hide');
                    },
                });

                /**
                 * Return false is currently not working.
                 *
                 * @version 2.8.8
                 * @see     https://github.com/fomantic/Fomantic-UI/issues/2105
                 */
                return false;
            }
        })
        .modal('show');
    });

    /**
     * Add wish
     */
    $(document).on('click', '.button.wishlist-wish-add', function () {
        var modalWishlistWishAdd = $('.ui.modal.wishlist-wish-add');

        modalWishlistWishAdd.find('[name="wishlist_id"]').val($('.ui.dropdown.wishlists').dropdown('get value'));
        modalWishlistWishAdd
        .modal({
            onApprove: function (buttonAdd) {
                buttonAdd.addClass('loading');

                var form     = $('.form.wishlist-wish-add');
                var formData = new URLSearchParams(new FormData(form[0]));

                fetch('/src/api/wishes.php', {
                    method: 'POST',
                    body:   formData
                })
                .then(handleFetchError)
                .then(handleFetchResponse)
                .then(function(response) {
                    if (response.success) {
                        $('body').toast({
                            class:    'success',
                            showIcon: 'check',
                            message:  'Wish successfully added.'
                        });

                        wishlistsRefresh();

                        modalWishlistWishAdd.modal('hide');
                    }

                    buttonAdd.removeClass('loading');
                })
                .catch(function(error) {
                    console.log(error);
                });

                return false;
            }
        })
        .modal('show');
    });

    /** Fetch */
    $(document).on('click', '#wishlist-wish-add-url-validate', function () {
        var buttonValidate    = $(this);
        var inputWishURL      = buttonValidate.prev();
        var inputURLContainer = buttonValidate.parent();

        var elementModalAdd = $('.ui.modal.wishlist-wish-add');
        var elementButtons  = elementModalAdd.find('.actions .button');
        var elementName     = elementModalAdd.find('input[name="wish_name"]');

        buttonValidate.addClass('disabled loading');
        elementButtons.addClass('disabled');

        fetch('/src/api/cache.php?wish_url=' + inputWishURL.val(), {
            method: 'GET'
        })
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                var info = response.data;

                /**
                 * Name
                 */
                if (info.title && elementName.length) {
                    elementName.val(info.title);
                }

                /**
                 * URL
                 */
                if (info.url && info.url !== inputWishURL.val()) {
                    var elementModalFetch = $('.ui.modal.wishlist-wish-fetch');

                    elementModalFetch.find('input.current').val(inputWishURL.val());
                    elementModalFetch.find('input.proposed').val(info.url);

                    elementButtons.addClass('disabled');

                    elementModalFetch
                    .modal({
                        allowMultiple: true,
                        closable: false,
                        onApprove: function (buttonFetch) {
                            var formData = new URLSearchParams();
                            formData.append('wish_url_current', inputWishURL.val());
                            formData.append('wish_url_proposed', info.url);

                            buttonFetch.addClass('loading');

                            fetch('/src/api/wishes.php', {
                                method: 'PUT',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(response => {
                                if (response.success) {
                                    inputWishURL.val(info.url);

                                    elementModalFetch.modal('hide');
                                }

                                buttonFetch.removeClass('loading');
                            });

                            return false;
                        },
                        onHide: function() {
                            buttonValidate.removeClass('disabled loading');
                            elementButtons.removeClass('disabled');
                        }
                    })
                    .modal('show');
                } else {
                    buttonValidate.removeClass('disabled loading');
                    elementButtons.removeClass('disabled');
                }

                inputURLContainer.attr('data-validated', 'true');
            }
        });
    });

    /**
     * Create wishlist
     */
     $(document).on('click', '.button.wishlist-create', function () {
        var modalWishlistCreate = $('.ui.modal.wishlist-create');
        var formWishlistCreate  = modalWishlistCreate.find('.ui.form');

        modalWishlistCreate
        .modal({
            onApprove: function (buttonCreate) {
                const formData = new URLSearchParams(new FormData(formWishlistCreate[0]));

                formWishlistCreate.addClass('loading');
                buttonCreate.addClass('loading');

                fetch('/src/api/wishlists.php', {
                    method: 'POST',
                    body:   formData
                })
                .then(response => response.json())
                .then(response => {
                    if(response.success) {
                        modalWishlistCreate.modal('hide');

                        urlParams.set('wishlist', response.data.lastInsertId);

                        $('body').toast({
                            class: 'success',
                            showIcon: 'check',
                            message: 'Wishlist successfully created.'
                        });

                        wishlistsRefresh();
                    }
                })
                .finally(() => {
                    formWishlistCreate.removeClass('loading');
                    buttonCreate.removeClass('loading');
                });

                return false;
            }
        })
        .modal('show');
    });

});
