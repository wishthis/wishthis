/**
 * The currently selected wishlist.
 */
var wishlist;
var wishlists = [];

$(function () {

    /**
     * Get Wishlists
     */
    var wishlistsItems = [];
    var wishlistsApi   = {
        'action'    : 'get wishlists',
        'onSuccess' : function(response, dropdownWishlists, xhr) {
            /** Save response for later use */
            wishlists      = response.wishlists;
            wishlistsItems = response.wishlistsItems;

            /** Setup and populate dropdown */
            var dropdownValues = {
                'values' : wishlistsItems,
            };

            dropdownWishlists.dropdown('setup menu', dropdownValues);

            /** Select a dropdown item */
            setDropdownWishlistsSelection();

            /** Open add wish modal */
            if (wishthis.$_GET.wish_add) {
                $('.button.wishlist-wish-add').trigger('click');
            }

            /** Set wishlist style */
            if ($('.buttons.view .active.button[value]').length === 0) {
                const orientationIsPortrait = window.matchMedia('(orientation: portrait)');

                if (orientationIsPortrait.matches) {
                    $('.buttons.view .button[value="list"]').trigger('click');
                } else {
                    $('.buttons.view .button[value="grid"]').trigger('click');
                }
            }
        },
    };

    $('.ui.dropdown.wishlists')
    .dropdown({
        'preserveHTML' : false,
        'onChange'     : function(wishlist_id, text, choice) {
            wishthis.$_GET.id = wishlist_id;

            if (wishlist_id) {
                /** Set currently selected wishlist */
                wishlists.forEach(wishlistI => {
                    if (wishlistI.id === parseInt(wishlist_id)) {
                        wishlist = wishlistI;
                    }
                });

                /** Set share link */
                $('.wishlist-share').attr('href', '/wishlist/' + $(wishlist).prop('hash'));

                /** Enable wishlist options buttons */
                $('.button.wishlist-wish-add').removeClass('disabled');
                $('.button.wishlist-share').removeClass('disabled');
                $('.button.wishlist-options')
                .removeClass('disabled')
                .dropdown({
                    'action' : 'select'
                });
                $('.wishlist-rename').removeClass('disabled');
                $('.wishlist-delete').removeClass('disabled');

                /** Update URL */
                urlParams.set('id', wishlist_id);

                updateURL();
                /** */

                /**
                 * Very dirty hack to ensure the wishes are going to be
                 * displayed after the page has laoded.
                 */
                setTimeout(function dropdown_wishlists_api() {
                    var api_is_complete = $('.ui.dropdown.filter.priority').api('was complete');

                    if ($('.ui.column.wishlist > .column').length > 0 && api_is_complete) {
                        $('.ui.dropdown.filter.priority').api('query');

                        setTimeout(dropdown_wishlists_api, 1);
                    }
                }, 1);
            } else {
                /** Disable wishlist options buttons */
                $('.button.wishlist-wish-add').removeClass('disabled');
                $('.button.wishlist-share').removeClass('disabled');
                $('.button.wishlist-options').removeClass('disabled');
                $('.wishlist-rename').removeClass('disabled');
                $('.wishlist-delete').removeClass('disabled');
            }
        },
    })
    .api(wishlistsApi)
    .api('query');

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

        fetch('/index.php?page=api&module=wishlists', {
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
    $(document).on('click', '.wishlist-options .wishlist-delete', function () {
        var wishlist_id = wishthis.$_GET.id;

        if (wishlist_id) {
            var modalDefault = $('.ui.modal.default');

            modalDefault
            .modal({
                'title'    : wishthis.strings.modal.wishlist.delete.title,
                'class'    : 'tiny',
                'content'  : wishthis.strings.modal.wishlist.delete.content.replace('WISHLIST_NAME', $('.ui.dropdown.wishlists').dropdown('get text')),
                'actions'  : [
                    {
                        text : wishthis.strings.modal.wishlist.delete.approve,
                        class: 'approve red'
                    },
                    {
                        text : wishthis.strings.modal.wishlist.delete.deny,
                        class: 'deny'
                    },
                ],
                'autoShow' : true,
                'onApprove': function (buttonApprove) {
                    buttonApprove.addClass('loading');

                    var delete_wishlist = new URLSearchParams(
                        {
                            'wishlist_id' : wishlist_id,
                        }
                    );
                    fetch('/index.php?page=api&module=wishlists', {
                        'method' : 'DELETE',
                        'body'   : delete_wishlist,
                    })
                    .then(handleFetchError)
                    .then(handleFetchResponse)
                    .then(function(response) {
                        $('.wishlist-cards .column').fadeOut(800);

                        urlParams.delete('id');
                        wishthis.$_GET.id = null;
                        updateURL();
                        setDropdownWishlistsSelection();

                        $('.ui.dropdown.wishlists').api('query');

                        modalDefault.modal('hide');

                        $('body').toast({ message : wishthis.strings.toast.wishlist.delete });
                    })
                    .catch(handleFetchCatch)
                    .finally(function() {
                        buttonApprove.removeClass('loading');
                    });

                    return false;
                }
            });
        }
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

                fetch('/index.php?page=api&module=wishlists', {
                    method : 'POST',
                    body   : formData
                })
                .then(handleFetchError)
                .then(handleFetchResponse)
                .then(function(response) {
                    modalWishlistCreate.modal('hide');

                    urlParams.set('id', response.data.lastInsertId);

                    $('body').toast({ message: wishthis.strings.toast.wish.create });

                    $('.ui.dropdown.wishlists')
                    .api('query')
                    .dropdown('set value', response.data.lastInsertId);
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

            fetch('/index.php?' + params_url, {
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

                            fetch('/index.php?page=api&module=wishes', {
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
                    insertWish(formAddOrEdit, modalAddOrEdit, buttonAddOrSave);
                }
            })
            .catch(handleFetchCatch);
        } else {
            insertWish(formAddOrEdit, modalAddOrEdit, buttonAddOrSave);
        }
    }

    /**
     * Insert wish
     */
    function insertWish(formAddOrEdit, modalAddOrEdit, buttonAddOrSave) {
        var formData = new URLSearchParams(new FormData(formAddOrEdit[0]));
        formData.append('wishlist_id', wishthis.$_GET.id);

        fetch('/index.php?page=api&module=wishes', {
            'method' : 'POST',
            'body'   : formData,
        })
        .then(handleFetchError)
        .then(handleFetchResponse)
        .then(function(response) {
            if (!response.lastInsertId) {
                return;
            }

            $('body').toast({ message: wishthis.strings.toast.wish.update });

            $('.ui.dropdown.filter.priority').api('query');

            modalAddOrEdit.modal('hide');
        })
        .catch(handleFetchCatch)
        .finally(function() {
            formAddOrEdit.removeClass('loading');
            buttonAddOrSave.removeClass('disabled');
        });
    }

    /**
     * Update URL
     */
    function updateURL() {
        fetch('/index.php?page=api&module=url&url=' + window.btoa('/?' + urlParams.toString()), { method: 'GET' })
        .then(handleFetchError)
        .then(handleFetchResponse)
        .then(function(response) {
            window.history.pushState(null, document.title, response.data.url_pretty);
        });
    }

    /**
     * Set dropdown wishlists seelction
     */
    function setDropdownWishlistsSelection() {
        var dropdown_wishlists = $('.ui.dropdown.wishlists');
        var wishlist_id;

        if (!dropdown_wishlists.dropdown('get value')) {
            if (wishthis.$_GET.id) {
                wishlist_id = wishthis.$_GET.id;
            } else {
                if (wishlists.length >= 1) {
                    wishlist = $(wishlists).first();

                    var first_wishlist_id = wishlist.prop('id');

                    wishlist_id = first_wishlist_id;
                }
            }

            dropdown_wishlists.dropdown('set selected', wishlist_id);
        }
    }

});
