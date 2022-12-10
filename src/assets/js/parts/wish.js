$(function () {

    /**
     * Details
     */
    $(document).on('click', '.ui.button.details', function() {
        var wish_details_template = $('template#wish-details').clone(true, true);
        var wish_details          = wish_details_template.contents().filter(function() { return this.nodeType !== 3; });

        /** Show modal */
        wish_details.modal('show');

        /** Get Wish */
        var card    = $(this).closest('.ui.card.wish');
        var wish_id = card.attr('data-id');

        var get_wish = new URLSearchParams(
            {
                'module' : 'wishes',
                'page'   : 'api',

                'wish_id' : wish_id,
            }
        );

        fetch('/?' + get_wish, { method: 'GET' })
        .then(handleFetchError)
        .then(handleFetchResponse)
        .then(function(response) {
            var wish = response.info;

            /** General */
            $('.wish-title').html(wish.title);
            $('.wish-image').prop('outerHTML', '<img class="image wish-image" src="' + wish.image + '" />');
            $('.wish-description').html(wish.description);
        })
        .catch(handleFetchCatch)
        .finally(function() {

        });
    });
    /** */

});
