$(function () {
    /**
     * Progress
     */
    var progress  = $('.ui.progress');

    progress.progress({
         onSuccess : function() {
             $(this).slideUp();
         }
     });

    /**
     * Get Wishlists
     */
    var wishlists     = [];
    var wishlists_api = {
        'action'  : 'get wishlists',
        onSuccess : function(response, dropdown_wishlists, xhr) {
            /** Save response for later use */
            wishlists = response.results;

            /** Setup and populate dropdown */
            var dropdown_values = {
                'values' : wishlists,
            };

            dropdown_wishlists.dropdown('setup menu', dropdown_values);

            /** Select a dropdown item */
            if (!dropdown_wishlists.dropdown('get value')) {
                if (wishthis.$_GET.id) {
                    dropdown_wishlists.dropdown('set selected', wishthis.$_GET.id);
                } else {
                    if (Object.keys(wishlists).length >= 1) {
                        var first_wishlist_id = Object.keys(wishlists)[0];

                        dropdown_wishlists.dropdown('set selected', first_wishlist_id);
                    }
                }
            }

            /** Open add wish modal */
            if (wishthis.$_GET.wish_add) {
                $('.button.wishlist-wish-add').trigger('click');
            }

            /** Set wishlist style */
            const orientationIsPortrait = window.matchMedia('(orientation: portrait)');

            if (orientationIsPortrait.matches) {
                $('.buttons.view .button[value="list"]').trigger('click');
            } else {
                $('.buttons.view .button[value="grid"]').trigger('click');
            }
        },
    };

    $('.ui.dropdown.wishlists')
    .dropdown({
        onChange : function(wishlist_id, text, $choice) {
            wishthis.$_GET.id = wishlist_id;

            if (wishlist_id) {
                /** Get wishlist */
                const get_wishlist = new URLSearchParams(
                    {
                        'module' : 'wishlists',
                        'page'   : 'api',

                        'wishlist_id' : wishlist_id,
                    }
                );
                fetch('/?' + get_wishlist, { method: 'GET' })
                .then(handleFetchError)
                .then(handleFetchResponse)
                .then(function(response) {
                    var wishlist;

                    /** Set current wishlist */
                    wishlist = response.results;

                    /** Set share link */
                    $('.wishlist-share').attr('href', '/?page=wishlist&hash=' + wishlist.hash);

                    /** Enable wishlist options buttons */
                    $('.button.wishlist-wish-add').removeClass('disabled');
                    $('.button.wishlist-share').removeClass('disabled');
                    $('.button.wishlist-options').removeClass('disabled').dropdown();
                    $('.wishlist-rename').removeClass('disabled');
                    $('.wishlist-delete').removeClass('disabled');

                    /** Update URL */
                    urlParams.set('id', wishlist_id);

                    const params_url = new URLSearchParams(
                        {
                            'module' : 'url',
                            'page'   : 'api',

                            'url' : window.btoa(urlParams.toString()),
                        }
                    );
                    fetch('/?' + params_url, {
                        method: 'GET'
                    })
                    .then(handleFetchError)
                    .then(handleFetchResponse)
                    .then(function(response) {
                        window.history.pushState(null, document.title, response.data.url_pretty);
                    });

                    /** Get wishlist cards/wishes */

                    /**
                     * Trigger priorities dropdown
                     */
                    // $('.ui.dropdown.filter.priority').api('query');
                    /** */
                    /*
                    const get_wishes = new URLSearchParams(
                        {
                            'module' : 'wishes',
                            'page'   : 'api',

                            'wishlist_id'    : wishlist.id,
                            'wishlist_style' : $('[name="style"]').val(),
                            'wish_priority'  : -1,
                        }
                    );
                    fetch('/?' + get_wishes, { method: 'GET' })
                    .then(handleFetchError)
                    .then(handleFetchResponse)
                    .then(function(response) {
                        /** Cards *//*
                        var wishes         = response.results;
                        var wishlist_cards = $('.wishlist-cards');

                        wishlist_cards.html('');

                        switch (get_wishes.wishlist_style) {
                            case 'list':
                                wishlist_cards.append('<div class="ui one column doubling stackable grid wishlist"></div>');
                                break;

                            default:
                                wishlist_cards.append('<div class="ui three column doubling stackable grid wishlist"></div>');
                                break;
                        }

                        if (wishes.length > 0) {
                            wishes.forEach(wish => {
                                $('.wishlist-cards > .wishlist.grid').append('<div class="column">' + wish.card + '</div>');
                            });
                        } else {
                            $('.wishlist-cards > .wishlist.grid').append(
                                '<div class="sixteen wide column">' +
                                    '<div class="ui info icon message">' +
                                        '<i class="info circle icon"></i> ' +
                                        '<div class="content">' +
                                            '<div class="header">' + wishthis.strings.message.wishlist.empty.header + '</div>' +
                                            '<p>' + wishthis.strings.message.wishlist.empty.content + '</p>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>'
                            );
                        }

                        $('.ui.dropdown.wish-options').removeClass('disabled').dropdown();
                    });
                    */
                });
            } else {
                /** Disable wishlist options buttons */
                $('.button.wishlist-wish-add').removeClass('disabled');
                $('.button.wishlist-share').removeClass('disabled');
                $('.button.wishlist-options').removeClass('disabled');
                $('.wishlist-rename').removeClass('disabled');
                $('.wishlist-delete').removeClass('disabled');
            }

            /**
             * Generate cache
             *//*
            var cards = $('.ui.card[data-cache="true"]');

            if (cards.length > 0) {
                progress.slideDown();
                progress.progress('reset');
                progress.progress('set total', cards.length);
            }

            var timerInterval = 1200;
            var timerCache    = setTimeout(
                function generateCacheCards() {
                    var cards = $('.ui.card[data-cache="true"]');

                    if (cards.length > 0) {
                        cards.each(function (index, card) {
                            generateCacheCard($(card));

                            if (index >= 0) {
                                return false;
                            }
                        });

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
            */
        },
    })
    .api(wishlists_api)
    .api('query');

    /*
    function generateCacheCard(card) {
        var href = card.find('.content [href]').prop('href');

        if (!href) {
            return;
        }

        var wishlist_id = $('.ui.dropdown.wishlists').dropdown('get value') - 1;
        var wishlist_user = wishlists[wishlist_id].user;

        card.addClass('loading');
        card.attr('data-cache', 'false');

        const params_cache = new URLSearchParams(
            {
                'module' : 'wishes',
                'page'   : 'api',

                'wish_id'       : card.attr('data-id'),
                'wishlist_user' : wishlist_user,
            }
        );

        fetch('/?' + params_cache, {
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

            progress.progress('increment', 1);

            if (progress.progress('get percent') >= 100) {
                progress.slideUp();
            }

            $('.ui.dropdown.options').dropdown();
        });
    }
    */

    /**
     * Share Wishlist
     */
    $(document).on('click', '.button.wishlist-share', function(event) {
        event.preventDefault();

        var wishlist_href = window.location.origin + $(event.currentTarget).attr('href');

        navigator.clipboard
        .writeText(wishlist_href)
        .then(function() {
            $('body').toast({
                'message' : wishthis.strings.toast.clipboard.success.content,
            });
        }, function() {
            $('body').toast({
                'class'   : 'error',
                'title'   : wishthis.strings.toast.clipboard.error.title,
                'message' : wishthis.strings.toast.clipboard.error.content,
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
        formData.append('wishlist_id', wishthis.$_GET.id);

        fetch('/?page=api&module=wishlists', {
            method : 'PUT',
            body   : formData,
        })
        .then(handleFetchError)
        .then(handleFetchResponse)
        .then(function(response) {
            $('.ui.dropdown.wishlists').api('query');

            modalRename.modal('hide');

            $('body').toast({ message: wishthis.strings.toast.wishlist.rename });
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

    $(document).on('click', '.wishlist-options .wishlist-rename', function() {
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
        var wishlist_id = $('.ui.dropdown.wishlists').dropdown('get value');

        if (wishlist_id) {
            var modalDefault = $('.ui.modal.default');

            modalDefault
            .modal({
                title    : wishthis.strings.wishlist.delete.title,
                class    : 'tiny',
                content  : wishthis.strings.wishlist.delete.content.replace('WISHLIST_NAME', $('.ui.dropdown.wishlists').dropdown('get text')),
                actions  : [
                    {
                        text : wishthis.strings.wishlist.delete.approve,
                        class: 'approve red'
                    },
                    {
                        text : wishthis.strings.wishlist.delete.deny,
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
                            'wishlistID' : wishlist_id
                        },
                        on: 'now',
                        onSuccess: function (response, wishlists) {
                            $('.wishlist-cards .column').fadeOut(800);

                            wishlists.dropdown('clear');

                            urlParams.delete('id');

                            $('body').toast({ message : wishthis.strings.toast.wishlist.delete });

                            modalDefault.modal('hide');

                            setTimeout(() => {
                                $('.ui.dropdown.wishlists').api('query');
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
                'wish_id'     : card.attr('data-id'),
                'wish_status' : wishthis.strings.wish.status.fulfilled,
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

        var wishFormData = new URLSearchParams(
            {
                'module' : 'wishes',
                'page'   : 'api',

                'wish_id' : wishID
            }
        );

        fetch('/?' + wishFormData, {
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
            $('[name="wish_image"]').val(wish.image);
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
            title    : wishthis.strings.wish.delete.title,
            content  : wishthis.strings.wish.delete.content,
            class    : 'tiny',
            actions  : [
                {
                    text : wishthis.strings.wish.delete.approve,
                    class: 'approve primary'
                },
                {
                    text: wishthis.strings.wish.delete.deny
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
                        'wish_id' : card.attr('data-id'),
                    },
                    on        : 'now',
                    onSuccess : function () {
                        column.fadeOut(800);

                        $('body').toast({ message: wishthis.strings.toast.wish.delete });

                        modalDefault.modal('hide');

                        setTimeout(() => {
                            $('.ui.dropdown.wishlists').api('query');
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
                if (urlParams.has('wish_add')) {
                    delete(wishthis.$_GET.wish_add);
                    urlParams.delete('wish_add');

                    window.history.pushState(null, document.title, '?' + urlParams.toString());
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
                formWishlistCreate.addClass('loading');
                buttonCreate.addClass('loading');

                var formData = new URLSearchParams(new FormData(formWishlistCreate[0]));

                fetch('/?page=api&module=wishlists', {
                    method : 'POST',
                    body   : formData
                })
                .then(handleFetchError)
                .then(handleFetchResponse)
                .then(function(response) {
                    modalWishlistCreate.modal('hide');

                    urlParams.set('id', response.data.lastInsertId);

                    $('body').toast({ message: wishthis.strings.toast.wish.create });

                    $('.ui.dropdown.wishlists').api('query');
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
            const params_url = new URLSearchParams(
                {
                    'module' : 'wishes',
                    'page'   : 'api',

                    'wish_url' : wishURLCurrent
                }
            );

            fetch('/?=' + params_url, {
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

                            buttonUpdate.addClass('loading');

                            const formData = new URLSearchParams(
                                {
                                    'wish_url_current'  : modalValidate.find('input.current').val(),
                                    'wish_url_proposed' : modalValidate.find('input.proposed').val(),
                                }
                            );

                            fetch('/?page=api&module=wishes', {
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
                    formData.append('wishlist_id', wishthis.$_GET.id);

                    fetch('/?page=api&module=wishes', {
                        method : 'POST',
                        body   : formData
                    })
                    .then(handleFetchError)
                    .then(handleFetchResponse)
                    .then(function(response) {
                        if (!response.lastInsertId) {
                            return;
                        }

                        $('body').toast({ message: wishthis.strings.toast.wish.update });

                        $('.ui.dropdown.wishlists').api('query');

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
            .catch(handleFetchCatch);
        } else {
            /** Save form edit fields */
            /** This code block is a duplicate, please refactor */
            var formData = new URLSearchParams(new FormData(formAddOrEdit[0]));
            formData.append('wishlist_id', wishthis.$_GET.id);

            fetch('/?page=api&module=wishes', {
                method : 'POST',
                body   : formData
            })
            .then(handleFetchError)
            .then(handleFetchResponse)
            .then(function(response) {
                if (!response.lastInsertId) {
                    return;
                }

                $('body').toast({ message: wishthis.strings.toast.wish.update });

                $('.ui.dropdown.wishlists').api('query');

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
