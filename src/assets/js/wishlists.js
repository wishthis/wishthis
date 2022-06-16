$(function () {
    var wishlist = {
        'id' : $_GET.id
    };

    /**
     * Get Wishlists
     */
    var wishlists = [];

    function wishlistsRefresh() {
        var selectedValue = $('.ui.dropdown.wishlists').dropdown('get value');

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

                if (wishlist.id) {
                    if (wishlist.id === selectedValue) {
                        element.dropdown('set selected', wishlist.id, null, true);
                    } else {
                        element.dropdown('set selected', wishlist.id);
                    }
                } else {
                    if (wishlists[0]) {
                        element.dropdown('set selected', wishlists[0].value);
                    }
                }

                /** Open add wish modal */
                if ($_GET.wish_add) {
                    $('.button.wishlist-wish-add').trigger('click');
                }
            }
        });
    }

    wishlistsRefresh();

    /**
     * Selection
     */
    var progress = $('.ui.progress');
    progress.progress({
        /**
         * Only fires once
         *
         * @see https://github.com/fomantic/Fomantic-UI/issues/2177
         */
        onSuccess : function() {
            wishlistsRefresh();

            progress.slideUp();
        }
    });

    $(document).on('change', '.ui.dropdown.wishlists', function () {
        var wishlistValue = $('.ui.dropdown.wishlists').dropdown('get value');
        var wishlistIndex = $('.ui.dropdown.wishlists select').prop('selectedIndex') - 1;

        progress.progress('reset');
        progress.addClass('indeterminate');

        if (wishlistValue) {
            wishlist.id = wishlistValue;

            $('.wishlist-share').attr('href', '/?page=wishlist&hash=' + wishlists[wishlistIndex].hash);

            $('.button.wishlist-wish-add').removeClass('disabled');
            $('.button.wishlist-share').removeClass('disabled');
            $('.wishlist-rename').removeClass('disabled');
            $('.wishlist-delete').removeClass('disabled');

            /** Update URL */
            urlParams.set('id', wishlistValue);

            fetch('/src/api/url.php?url=' + window.btoa(urlParams.toString()), {
                method: 'GET'
            })
            .then(handleFetchError)
            .then(handleFetchResponse)
            .then(function(response) {
                window.history.pushState(null, document.title, response.data.url);

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
            progress.progress('set total', cards.length);
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
    function wishlistRenameApprove(buttonApprove) {
        buttonApprove.addClass('loading disabled');

        var modalRename = $('.modal.wishlist-rename');

        var formRename = modalRename.find('.form.wishlist-rename');
        var formData   = new URLSearchParams(new FormData(formRename[0]));

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

    $(document).on('submit', '.form.wishlist-rename', function(event) {
        event.preventDefault();

        var buttonApprove = $('.modal.wishlist-rename .button.approve');

        wishlistRenameApprove(buttonApprove);
    });

    $(document).on('click', '.options .wishlist-rename', function() {
        var modalRename   = $('.modal.wishlist-rename');
        var inputID       = modalRename.find('[name="wishlist_id"]');
        var inputTitle    = modalRename.find('[name="wishlist_title"]');

        var wishlistID    = $('.ui.dropdown.wishlists').dropdown('get value');
        var wishlistTitle = $('.ui.dropdown.wishlists').dropdown('get text');

        inputID.val(wishlistID);

        inputTitle.val(wishlistTitle);
        inputTitle.attr('placeholder', wishlistTitle);

        modalRename
        .modal({
            autoShow  :  true,
            onApprove : wishlistRenameApprove
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
                            $('.wishlist-cards .column').fadeOut(800);

                            wishlists.dropdown('clear');

                            urlParams.delete('id');

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
     * Mark as Fulfilled
     */
    $(document).on('click', '.wish-fulfilled', function() {
        var button = $(this);
        var card   = button.closest('.card');

        button.api({
            action    : 'update wish status',
            method    : 'PUT',
            data      : {
                wish_id     : card.attr('data-id'),
                wish_status : wish_status_fulfilled,
            },
            on        : 'now',
            onSuccess : function(response, element, xhr) {
                card.closest('.column').fadeOut(800);
            },
        });
    });

    /**
     * Edit Wish
     */
    $(document).on('click', '.wish-edit', function (event) {
        validateURL = true;

        /** Form */
        var formEdit = $('.form.wishlist-wish-edit');
        formEdit.addClass('loading');
        formEdit.trigger('reset');
        formEdit.find('.dropdown').dropdown('restore defaults');
        formEdit.find('.item').tab('change tab', 'general');

        /** Checkbox */
        formEdit
        .find('.checkbox')
        .checkbox({
            onChecked   : function() {
                formEdit.find('.item[data-tab="product"]').removeClass('disabled');
            },
            onUnchecked : function() {
                formEdit.find('.item[data-tab="product"]').addClass('disabled');
            },
        })
        .checkbox('uncheck');

        /** Get Wish */
        var wishID = $(this).attr('data-id');

        var wishFormData = new URLSearchParams({
            'wish_id' : wishID
        });

        fetch('/src/api/wishes.php?' + wishFormData, {
            method: 'GET'
        })
        .then(handleFetchError)
        .then(handleFetchResponse)
        .then(function(response) {
            var wish = response.info;

            /** General */
            $('[name="wish_id"]').val(wish.id);
            $('[name="wish_title"]').val(wish.title);
            $('[name="wish_description"]').val(wish.description);
            $('[name="wish_url"]').val(wish.url);
            $('.ui.selection.dropdown.priority').dropdown('set selected', wish.priority);

            if (wish.is_purchasable) {
                formEdit.find('.checkbox').checkbox('check');
            } else {
                formEdit.find('.checkbox').checkbox('uncheck');
            }

            /** Product */
            $('[name="wish_price"]').val(wish.price);
        })
        .catch(handleFetchCatch)
        .finally(function() {
            formEdit.removeClass('loading');
        });

        /** Save wish */
        var modalWishlistWishEdit = $('.ui.modal.wishlist-wish-edit');

        modalWishlistWishEdit.find('[name="wishlist_id"]').val($('.ui.dropdown.wishlists').dropdown('get value'));
        modalWishlistWishEdit
        .modal({
            autoShow  : true,
            onApprove : function (buttonSave) {
                validateWishURL(formEdit, buttonSave, modalWishlistWishEdit, validateURL);

                return false;
            }
        });

        event.preventDefault();
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
                        column.fadeOut(800);

                        $('body').toast({ message: text.toast_wish_delete });

                        modalDefault.modal('hide');

                        setTimeout(() => {
                            wishlistsRefresh();
                        }, 800);
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
        validateURL = true;

        /** Form */
        var formAdd = $('.form.wishlist-wish-add');
        formAdd.trigger('reset');
        formAdd.find('.dropdown').dropdown('restore defaults');
        formAdd.find('.item').tab('change tab', 'general');

        /** Checkbox */
        formAdd.find('.checkbox').checkbox({
            onChecked   : function() {
                formAdd.find('.item[data-tab="product"]').removeClass('disabled');
            },
            onUnchecked : function() {
                formAdd.find('.item[data-tab="product"]').addClass('disabled');
            },
        })
        .checkbox('uncheck');

        /** Modal */
        var modalWishlistWishAdd = $('.ui.modal.wishlist-wish-add');
        modalWishlistWishAdd.find('[name="wishlist_id"]').val($('.ui.dropdown.wishlists').dropdown('get value'));
        modalWishlistWishAdd
        .modal({
            autoShow  : true,
            onApprove : function (buttonAdd) {
                validateWishURL(formAdd, buttonAdd, modalWishlistWishAdd);

                return false;
            },
            onHide    : function() {
                /** Ugly URL */
                var paramString = location.search.split('?')[1];
                var queryString = new URLSearchParams(paramString);

                if (queryString.has('wish_add')) {
                    queryString.delete('wish_add');

                    window.history.pushState(null, document.title, '?' + queryString.toString());
                }

                /** Pretty URL */
                var path      = location.pathname;
                var pathParts = path.split('/')
                var pathAdd   = pathParts[pathParts.length - 1];
                var pathNew   = path.substring(0, path.length - pathAdd.length - 1);

                if (pathAdd.toLowerCase() === 'add') {
                    window.history.pushState(null, document.title, pathNew);
                }
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

                    urlParams.set('id', response.data.lastInsertId);

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

    var validateURL = true;

    function validateWishURL(formAddOrEdit, buttonAddOrSave, modalAddOrEdit) {
        /**
         * Validate Form
         */
        formAddOrEdit
        .form({
            fields: {
                wish_price : ['number'],
            }
        })
        .form('validate form');

        if (!formAddOrEdit.form('is valid')) {
            return;
        }

        /**
         * Validate URL
         */
        var inputURL       = modalAddOrEdit.find('[name="wish_url"]');
        var wishURLCurrent = inputURL.val();

        formAddOrEdit.addClass('loading');
        buttonAddOrSave.addClass('disabled');

        if (wishURLCurrent) {
            fetch('/src/api/wishes.php?wish_url=' + wishURLCurrent, {
                method: 'GET'
            })
            .then(handleFetchError)
            .then(handleFetchResponse)
            .then(function(response) {
                var wishInfoProposed = response.info;

                var modalValidate = $('.modal.validate');

                /** Prodiver name */
                if (wishInfoProposed.providerName) {
                    modalValidate.find('.providerName').text(wishInfoProposed.providerName);
                } else {
                    modalValidate.find('.provider').remove();
                }

                /** URL */
                if (wishURLCurrent && wishInfoProposed.url && wishURLCurrent !== wishInfoProposed.url && validateURL) {
                    modalValidate.find('input.current').val(wishURLCurrent);
                    modalValidate.find('input.proposed').val(wishInfoProposed.url);
                    modalValidate
                    .modal({
                        autoShow      : true,
                        allowMultiple : true,
                        onApprove     : function (buttonUpdate) {
                            inputURL.val(modalValidate.find('input.proposed').val());

                            var formData = new URLSearchParams({
                                'wish_url_current'  : modalValidate.find('input.current').val(),
                                'wish_url_proposed' : modalValidate.find('input.proposed').val()
                            });

                            buttonUpdate.addClass('loading');

                            fetch('/src/api/wishes.php', {
                                method : 'PUT',
                                body   : formData
                            })
                            .then(handleFetchError)
                            .then(handleFetchResponse)
                            .then(function(response) {
                                buttonUpdate.removeClass('loading');
                                modalValidate.modal('hide');
                            });
                        },
                        onDeny        : function() {
                            validateURL = false;
                        },
                        onHide        : function() {
                            formAddOrEdit.removeClass('loading');
                            buttonAddOrSave.removeClass('disabled');
                        }
                    });
                } else {
                    /** Save form edit fields */
                    /** This code block is a duplicate, please refactor */
                    var formData = new URLSearchParams(new FormData(formAddOrEdit[0]));

                    fetch('/src/api/wishes.php', {
                        method : 'POST',
                        body   : formData
                    })
                    .then(handleFetchError)
                    .then(handleFetchResponse)
                    .then(function(response) {
                        if (!response.lastInsertId) {
                            return;
                        }

                        $('body').toast({ message: text.toast_wish_update });

                        wishlistsRefresh();

                        modalAddOrEdit.modal('hide');
                    })
                    .catch(handleFetchCatch)
                    .finally(function() {
                        formAddOrEdit.removeClass('loading');
                        buttonAddOrSave.removeClass('disabled');
                    });
                    /** */
                }
            })
            .catch(handleFetchCatch)
            .finally(function() {
                formAddOrEdit.removeClass('loading');
                buttonAddOrSave.removeClass('disabled');
            });
        } else {
            /** Save form edit fields */
            /** This code block is a duplicate, please refactor */
            var formData = new URLSearchParams(new FormData(formAddOrEdit[0]));

            fetch('/src/api/wishes.php', {
                method : 'POST',
                body   : formData
            })
            .then(handleFetchError)
            .then(handleFetchResponse)
            .then(function(response) {
                if (!response.lastInsertId) {
                    return;
                }

                $('body').toast({ message: text.toast_wish_update });

                wishlistsRefresh();

                modalAddOrEdit.modal('hide');
            })
            .catch(handleFetchCatch)
            .finally(function() {
                formAddOrEdit.removeClass('loading');
                buttonAddOrSave.removeClass('disabled');
            });
            /** */
        }
    }


});
