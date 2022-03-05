$(function () {

    /**
     * Auto fill
     */
    if ($('[name="wish_url"]').val()) {
        $('.button.auto-fill').removeClass('disabled');
    }

    $(document).on('click', '.button.auto-fill', function () {
        var modalAutoFill = $('.modal.auto-fill');
        var modalValidate = $('.modal.validate');

        var formWish = $('.form.wish');

        var inputTitle       = $('[name="wish_title"]');
        var inputDescription = $('[name="wish_description"]');
        var inputURL         = $('[name="wish_url"]');

        modalAutoFill
        .modal({
            onApprove: function(buttonApprove) {
                formWish.addClass('loading');

                fetch('/src/api/wishes.php?wish_url=' + inputURL.val(), {
                    method: 'GET'
                })
                .then(handleFetchError)
                .then(handleFetchResponse)
                .then(function(response) {
                    var info = response.info;

                    /**
                     * Prodiver name
                     */
                    if (info.providerName) {
                        modalValidate.find('.providerName').text(info.providerName);
                    } else {
                        modalValidate.find('.provider').remove();
                    }

                    /**
                     * Title
                     */
                    if (info.title) {
                        inputTitle.val(info.title);
                    }

                    /**
                     * Description
                     */
                     if (info.description) {
                        inputDescription.val(info.description);
                    }

                    /**
                     * URL
                     */
                    if (info.url && info.url !== inputURL.val()) {
                        var elementModalFetch = $('.modal.validate');

                        elementModalFetch.find('input.current').val(inputURL.val());
                        elementModalFetch.find('input.proposed').val(info.url);

                        elementModalFetch
                        .modal({
                            onApprove: function (buttonFetch) {
                                var formData = new URLSearchParams();
                                formData.append('wish_url_current', inputURL.val());
                                formData.append('wish_url_proposed', info.url);

                                buttonFetch.addClass('loading');

                                fetch('/src/api/wishes.php', {
                                    method: 'PUT',
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(response => {
                                    if (response.success) {
                                        inputURL.val(info.url);

                                        elementModalFetch.modal('hide');

                                        $('body').toast({ message: 'Wish information updated.' });
                                    }

                                    buttonFetch.removeClass('loading');
                                });

                                return false;
                            },
                            onHide: function() {
                                formWish.removeClass('loading');
                            }
                        })
                        .modal('show');
                    } else {
                        $('body').toast({ message: 'Wish information updated.' });

                        formWish.removeClass('loading');
                    }
                })
                .catch(handleFetchCatch);
            }
        })
        .modal('show');

    });

    /**
     * Image
     */
    $(document).on('click', '.image.preview', function() {
        var modalImage = $('.modal.preview');

        modalImage
        .modal({
            onApprove: function(buttonApprove) {
                var formImage = modalImage.find('form.preview');
                var formData  = new URLSearchParams(new FormData(formImage[0]));

                formImage.addClass('loading');

                fetch('/src/api/wishes.php', {
                    method: 'PUT',
                    body:   formData
                })
                .then(handleFetchError)
                .then(handleFetchResponse)
                .then(function(response) {
                    var elementImage = $('.form.wish img.preview');
                    elementImage.attr('src', response.wish_url);

                    formImage.removeClass('loading');
                    modalImage.modal('hide');

                    $('body').toast({ message: 'Wish image successfully updated.' });
                });

                return false;
            }
        })
        .modal('show');
    });

});
