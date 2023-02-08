const wish_button_mark_as_fulfilled = '.ui.button.wish-fulfilled';
const wish_button_fulfil_wish       = '.ui.button.wish-fulfil';
const wish_button_visit             = '.ui.button.wish-visit';
const wish_button_options           = '.ui.button.wish-options';
const wish_button_options_edit      = wish_button_options + ' .item.wish-edit';

var wish;

function wish_set_to(wish_data) {
    wish = wish_data;

    $(wish_button_mark_as_fulfilled).removeClass('disabled');
    $(wish_button_fulfil_wish).removeClass('disabled');

    if (wish.url) {
        $(wish_button_visit)
        .attr('href', wish.url)
        .removeClass('disabled');
    } else {
        $(wish_button_visit).remove();
    }

    /**
     * Options
     */
    $(wish_button_options)
    .dropdown()
    .removeClass('disabled');

    /** Edit */
    $(wish_button_options_edit).removeClass('disabled');
}

function wish_unset() {
    wish = undefined;
}

$(function () {

    /**
     * Details
     */
    $(document).on('click', '.ui.button.wish-details', function() {
        var wish_details_template = $('template#wish-details').clone(true, true);
        var wish_details          = wish_details_template.contents().filter(function() { return this.nodeType !== 3; });
        var wish_details_size     = window.matchMedia('(max-width: 768px)').matches ? 'overlay fullscreen' : 'large';

        /** Show modal */
        wish_details
        .modal({
            'onShow'    : function() {
                var user_is_current = wishlist && wishlist.user === parseInt($('[name="user-id"]').val());

                if (user_is_current) {
                    $('.ui.button.wish-fulfil').remove();
                } else {
                    $('.ui.button.wish-fulfilled').remove();
                    $('.ui.dropdown.wish-options').remove();
                }
            },
            'onVisible' : function() {
                /**
                 * Dirty hack to change the default `display: block;` to
                 * `display: flex;` (using CSS).
                 */
                setTimeout(() => { $(this).css('display', ''); }, 0);
            },
            'onHide' : function(modal) {
                wish_unset();
            },
        })
        .modal('show')
        .addClass(wish_details_size);

        /** Get Wish */
        var card    = $(this).closest('.ui.card.wish');
        var wish_id = card.attr('data-id');

        var get_wish = new URLSearchParams(
            {
                'module' : 'wishes',
                'page'   : 'api',

                'wish_id' : wish_id
            }
        );

        fetch('/?' + get_wish, { method: 'GET' })
        .then(handleFetchError)
        .then(handleFetchResponse)
        .then(function(response) {
            wish_set_to(response.info);

            /**
             * Wish
             */
            /** Title */
            $('.wish-title').html(wish.title);

            /** Image */
            var wish_image       = wish.image ? wish.image : wishthis.wish.no_image;
            var wish_image_ext   = wish_image.split('.').pop();

            if ('svg' === wish_image_ext) {
                fetch(wish_image, { method: 'GET' })
                .then(handleFetchError)
                .then(handleFetchResponse)
                .then(function(response) {
                    var svg = $(response).filter(function() { return 'svg' === this.nodeName; });

                    $('.wish-image').html(svg);
                })
                .catch(function(response) {
                    $('.wish-image').html('<img src="' + wish_image + '" />');
                });
            } else {
                $('.wish-image').html('<img src="' + wish_image + '" />');
            }

            /** Description */
            $('.wish-description').html(wish.description);
        })
        .catch(handleFetchCatch);
    });
    /** */

    /**
     * Mark as fulfilled
     */
    $(document).on('click', wish_button_mark_as_fulfilled, function() {
        const modal_wish_details = $(this).closest('.ui.modal');
        const mark_as_fulfilled  = {
            'method' : 'PUT',
            'body'   : new URLSearchParams({
                'wish_id'     : wish.id,
                'wish_status' : wishthis.wish.status.fulfilled,
            }),
        }

        $(this).addClass('disabled loading');

        fetch('/api/wishes', mark_as_fulfilled)
        .then(handleFetchError)
        .then(handleFetchResponse)
        .then(function(response) {
            modal_wish_details.modal('hide');

            $('.ui.dropdown.filter.priority').api('query');
        })
        .catch(handleFetchCatch)
        .finally(function() {
            $(this).removeClass('disabled loading');
        });
    });
    /** */

    /**
     * Fulfill wish
     */
    $(document).on('click', wish_button_fulfil_wish, function() {
        const modal_wish_details = $(this).closest('.ui.modal');
        const mark_as_fulfilled  = {
            'method' : 'PUT',
            'body'   : new URLSearchParams({
                'wish_id'     : wish.id,
                'wish_status' : wishthis.wish.status.fulfilled,
            }),
        }

        $(this).addClass('disabled loading');

        fetch('/api/wishes', mark_as_fulfilled)
        .then(handleFetchError)
        .then(handleFetchResponse)
        .then(function(response) {
            modal_wish_details.modal('hide');

            $('.ui.dropdown.filter.priority').api('query');
        })
        .catch(handleFetchCatch)
        .finally(function() {
            $(this).removeClass('disabled loading');
        });
    });
    /** */

    /**
     * Options: Edit
     */
    $(document).on('click', wish_button_options_edit, function() {
        var wish_edit_template = $('template#wish-edit').clone(true, true);
        var wish_edit          = wish_edit_template.contents().filter(function() { return this.nodeType !== 3; });
        var wish_edit_size     = window.matchMedia('(max-width: 768px)').matches ? 'overlay fullscreen' : 'large';

        /**
         * Initialise
         */
        /** Checkbox */
        const checkbox_is_purchasable = wish_edit.find('.ui.checkbox.wish-is-purchasable');

        checkbox_is_purchasable
        .checkbox({
            onChecked   : function() {
                wish_edit.find('.item[data-tab="product"]').removeClass('disabled');
            },
            onUnchecked : function() {
                wish_edit.find('.item[data-tab="product"]').addClass('disabled');
            },
        });

        /**
         * Set values
         */
        /**
         * Save global-scope wish since it will be unset when the wish-details
         * modal is closed.
         */
        var wish_local = wish;

        wish_edit
        .modal({
            'onApprove' : wishSave,
        })
        .modal('show')
        .addClass(wish_edit_size);

        /** Initialise Tabs */
        wish_edit.find('.item[data-tab]')
        .tab();

        /** General */
        $('[name="wish_id"]').val(wish_local.id);
        $('[name="wishlist_id"]').val(wish_local.wishlist);
        $('[name="wish_title"]').val(wish_local.title);
        $('[name="wish_description"]').val(wish_local.description);
        $('[name="wish_image"]').val(wish_local.image);
        $('[name="wish_url"]').val(wish_local.url);
        $('.ui.selection.dropdown.priority').dropdown('set selected', wish_local.priority);

        if (wish_local.is_purchasable) {
            checkbox_is_purchasable.checkbox('check');
        } else {
            checkbox_is_purchasable.checkbox('uncheck');
        }

        /** Product */
        $('[name="wish_price"]').val(wish_local.price);

        /**
         * Save values
         */
        function wishSave() {
            const form_wish_edit = '.ui.form.wishlist-wish-edit';

            $(form_wish_edit).addClass('loading');

            var wish_data = new URLSearchParams(
                new FormData(
                    wish_edit.find(form_wish_edit)[0]
                )
            );

            fetch('/api/wishes', {
                'method' : 'POST',
                'body'   : wish_data,
            })
            .then(handleFetchError)
            .then(handleFetchResponse)
            .then(function(response) {
                wish_edit.modal('hide');

                $('body').toast({ message: wishthis.strings.toast.wish.update });

                $('.ui.dropdown.filter.priority').api('query');
            });

            return false;
        }
    });

    /**
     * Options: Delete
     */
    /** */

});
