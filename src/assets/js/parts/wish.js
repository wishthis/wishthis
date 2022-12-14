$(function () {

    /**
     * Details
     */
    $(document).on('click', '.ui.button.wish-details', function() {
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

                'wish_id' : wish_id
            }
        );

        fetch('/?' + get_wish, { method: 'GET' })
        .then(handleFetchError)
        .then(handleFetchResponse)
        .then(function(response) {
            var wish = response.info;

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
        .catch(handleFetchCatch)
        .finally(function() {

        });
    });
    /** */

});
