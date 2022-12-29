const wish_button_mark_as_fulfilled = '.ui.button.wish-fulfilled';
const wish_button_visit             = '.ui.button.wish-visit';

var wish;

function wish_set_to(wish_data) {
    wish = wish_data;

    $(wish_button_mark_as_fulfilled).removeClass('disabled');

    if (wish.url) {
        $(wish_button_visit)
        .attr('href', wish.url)
        .removeClass('disabled');
    }
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

        /** Show modal */
        wish_details
        .modal({
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

});
