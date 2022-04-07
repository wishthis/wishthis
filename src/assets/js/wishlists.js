$(function () {
    /**
     * Get Wishlists
     */
    var wishlists = [];

    function wishlistsRefresh() {
        $('.ui.dropdown.wishlists').api({
            action   : 'get wishlists',
            method   : 'GET',
            on       : 'now',
            onSuccess: function (response, element, xhr) {
                wishlists = response.results;

                element.dropdown({
                    values      : wishlists,
                    placeholder : text.wishlist_no_selection
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

        if (wishlistValue) {
            $_GET.wishlist = wishlistValue;

            $('.wishlist-share').attr('href', '/?wishlist=' + wishlists[wishlistIndex].hash);

            $('.button.wishlist-wish-add').removeClass('disabled');
            $('.button.wishlist-share').removeClass('disabled');
            $('.wishlist-rename').removeClass('disabled');
            $('.wishlist-delete').removeClass('disabled');

            /** Update URL */
            urlParams.set('wishlist', wishlistValue);

            fetch('/src/api/url.php?url=' + btoa(urlParams.toString()), {
                method: 'GET'
            })
            .then(handleFetchError)
            .then(handleFetchResponse)
            .then(function(response) {
                window.history.pushState({}, '', response.data.url);

                $('.ui.dropdown.filter.priority')
                .dropdown('restore default value')
                .dropdown('restore default text');
            });
        } else {
            $('.button.wishlist-wish-add').addClass('disabled');
            $('.button.wishlist-share').addClass('disabled');
            $('.wishlist-rename').addClass('disabled');
            $('.wishlist-delete').addClass('disabled');
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
        var cards = $('.ui.card[data-cache="true"]');

        if (cards.length > 0) {
            progress.slideDown();
            progress.removeClass('indeterminate');
            progress.progress({
                total: cards.length,
                onSuccess: function() {
                    $(this).slideUp();
                }
            });
        } else {
            progress.slideUp();
        }

        var timerInterval = 1200;
        var timerCache    = setTimeout(
            function generateCacheCards() {
                var cards = $('.ui.card[data-cache="true"]');

                cards.each(function (index, card) {
                    generateCacheCard($(card));

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

        $('.ui.dropdown.options').dropdown({
            onChange: function(value, text, choice) {
                var dropdownOptions = $(this);

                setTimeout(function() {
                    dropdownOptions.dropdown('restore defaults', true);
                }, 0);
            }
        });
    });

    function generateCacheCard(card) {
        var href = card.find('.content [href]').prop('href');

        if (!href) {
            return;
        }

        card.addClass('loading');
        card.attr('data-cache', false);

        var wishlistIndex = $('.ui.dropdown.wishlists select').prop('selectedIndex') - 1;
        var wishlist_user = wishlists[wishlistIndex].user;

        fetch('/src/api/wishes.php?wish_id=' + card.attr('data-id') + '&wishlist_user=' + wishlist_user, {
            method: 'GET'
        })
        .then(handleFetchError)
        .then(handleFetchResponse)
        .then(function(response) {
            card.replaceWith(response.html.replace('data-cache="true"', 'data-cache="false"'));
        })
        .catch(handleFetchCatch)
        .finally(function() {
            card.removeClass('loading');

            progress.progress('increment');

            $('.ui.dropdown.options').dropdown();
        });
    }

    /**
     * Share Wishlist
     */
    $(document).on('click', '.button.wishlist-share', function(event) {
        event.preventDefault();

        var wishlist_href = window.location.origin + $(event.currentTarget).attr('href');


        navigator.clipboard.writeText(wishlist_href).then(function() {
            $('body').toast({ message: text.toast_clipboard_success });
        }, function() {
            $('body').toast({
                class   : 'error',
                title   : text.toast_clipboard_error_title,
                message : text.toast_clipboard_error
            });
        });

    });

    /**
     * Rename Wishlist
     */
    $(document).on('click', '.options .wishlist-rename', function() {
        var modalRename   = $('.modal.wishlist-rename');
        var formRename    = modalRename.find('.form.wishlist-rename');
        var inputID       = modalRename.find('[name="wishlist_id"]');
        var inputTitle    = modalRename.find('[name="wishlist_title"]');

        var wishlistID    = $('.ui.dropdown.wishlists').dropdown('get value');
        var wishlistTitle = $('.ui.dropdown.wishlists').dropdown('get text');

        inputID.val(wishlistID);

        inputTitle.val(wishlistTitle);
        inputTitle.attr('placeholder', wishlistTitle);

        modalRename
        .modal({
            autoShow:  true,
            onApprove: function(buttonApprove) {
                buttonApprove.addClass('loading disabled');

                var formData = new URLSearchParams(new FormData(formRename[0]));

                fetch('/src/api/wishlists.php', {
                    method: 'PUT',
                    body:   formData
                })
                .then(handleFetchError)
                .then(handleFetchResponse)
                .then(function(response) {
                    wishlistsRefresh();

                    modalRename.modal('hide');

                    $('body').toast({ message: text.toast_wishlist_rename });
                })
                .catch(handleFetchCatch)
                .finally(function() {
                    buttonApprove.removeClass('loading disabled');
                });

                return false;
            }
        });
    });

    /**
     * Delete Wishlist
     */
    $(document).on('click', '.options .wishlist-delete', function () {
        var wishlistValue = $('.ui.dropdown.wishlists').dropdown('get value');

        if (wishlistValue) {
            var modalDefault = $('.ui.modal.default');

            modalDefault
            .modal({
                title    : text.modal_wishlist_delete_title,
                class    : 'tiny',
                content  : text.modal_wishlist_delete.replace('WISHLIST_NAME', $('.ui.dropdown.wishlists').dropdown('get text')),
                actions  : [
                    {
                        text : text.modal_wishlist_delete_approve,
                        class: 'approve red'
                    },
                    {
                        text : text.modal_wishlist_delete_deny,
                        class: 'deny'
                    },
                ],
                autoShow : true,
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

                            $('body').toast({ message:text.toast_wishlist_delete });

                            modalDefault.modal('hide');

                            setTimeout(() => {
                                wishlistsRefresh();
                            }, 200);
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
            });
        }
    });

    /**
     * Delete Wish
     */
    $(document).on('click', '.wish-delete', function () {
        var buttonDelete = $(this);
        var card         = buttonDelete.closest('.ui.card');
        var column       = card.closest('.column');
        var modalDefault = $('.ui.modal.default');

        modalDefault
        .modal({
            title    : text.modal_wish_delete_title,
            content  : text.modal_wish_delete,
            class    : 'tiny',
            actions  : [
                {
                    text : text.modal_wish_delete_approve,
                    class: 'approve primary'
                },
                {
                    text: text.modal_wish_delete_deny
                }
            ],
            autoShow : true,
            onApprove: function (buttonApprove) {
                buttonApprove.addClass('loading');

                /**
                 * Delete wish
                 */
                buttonDelete.api({
                    action    : 'delete wish',
                    method    : 'DELETE',
                    data      : {
                        wish_id: card.attr('data-id'),
                    },
                    on        : 'now',
                    onSuccess : function () {
                        column.fadeOut();

                        $('body').toast({ message: text.toast_wish_delete });

                        modalDefault.modal('hide');

                        setTimeout(() => {
                            wishlistsRefresh();
                        }, 200);
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
        });
    });

    /**
     * Add wish
     */
    $(document).on('click', '.button.wishlist-wish-add', function () {
        var modalWishlistWishAdd = $('.ui.modal.wishlist-wish-add');

        modalWishlistWishAdd.find('[name="wishlist_id"]').val($('.ui.dropdown.wishlists').dropdown('get value'));
        modalWishlistWishAdd
        .modal({
            autoShow : true,
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
                    $('body').toast({ message: text.toast_wish_add });

                    wishlistsRefresh();

                    modalWishlistWishAdd.modal('hide');

                    buttonAdd.removeClass('loading');
                })
                .catch(handleFetchCatch);

                return false;
            }
        });
    });

    /**
     * Create wishlist
     */
     $(document).on('click', '.button.wishlist-create', function () {
        var modalWishlistCreate = $('.ui.modal.wishlist-create');
        var formWishlistCreate  = modalWishlistCreate.find('.ui.form');
        var inputWishlistName   = formWishlistCreate.find('[name="wishlist-name"]');

        inputWishlistName.attr('placeholder', inputWishlistName.attr('data-default'));
        inputWishlistName.val(inputWishlistName.attr('data-default'));

        modalWishlistCreate
        .modal({
            autoShow: true,
            onApprove: function (buttonCreate) {
                const formData = new URLSearchParams(new FormData(formWishlistCreate[0]));

                formWishlistCreate.addClass('loading');
                buttonCreate.addClass('loading');

                fetch('/src/api/wishlists.php', {
                    method: 'POST',
                    body:   formData
                })
                .then(handleFetchError)
                .then(handleFetchResponse)
                .then(function(response) {
                    modalWishlistCreate.modal('hide');

                    urlParams.set('wishlist', response.data.lastInsertId);

                    $('body').toast({ message: text.toast_wish_create });

                    wishlistsRefresh();
                })
                .finally(() => {
                    formWishlistCreate.removeClass('loading');
                    buttonCreate.removeClass('loading');
                });

                return false;
            }
        });
    });

});
