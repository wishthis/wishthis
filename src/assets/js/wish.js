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

        var formWish     = $('.form.wish');
        var imagePreview = $('img.preview');

        var inputTitle       = $('[name="wish_title"]');
        var inputDescription = $('[name="wish_description"]');
        var inputImage       = $('[name="wish_image"]');
        var inputURL         = $('[name="wish_url"]');

        modalAutoFill
        .modal({
            autoShow : true,
            onApprove: function() {
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
                     * Image
                     */
                    if (info.image) {
                        inputImage.val(info.image);
                        imagePreview.attr('src', info.image);
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
                            autoShow: true,
                            onApprove: function (buttonFetch) {
                                var formData = new URLSearchParams();
                                formData.append('wish_url_current', inputURL.val());
                                formData.append('wish_url_proposed', info.url);

                                buttonFetch.addClass('loading');

                                fetch('/src/api/wishes.php', {
                                    method: 'PUT',
                                    body  : formData
                                })
                                .then(response => response.json())
                                .then(response => {
                                    if (response.success) {
                                        inputURL.val(info.url);

                                        elementModalFetch.modal('hide');

                                        $('body').toast({ message: text.toast_wish_update });
                                    }

                                    buttonFetch.removeClass('loading');
                                });

                                return false;
                            },
                            onHide: function() {
                                formWish.removeClass('loading');
                            }
                        });
                    } else {
                        $('body').toast({
                            class  :   'primary',
                            message: text.toast_wish_save
                        });

                        formWish.removeClass('loading');
                    }
                })
                .catch(handleFetchCatch);
            }
        });

    });

    /**
     * Image
     */
    $(document).on('click', '.image.preview', function() {
        var modalImage = $('.modal.preview');

        modalImage
        .modal({
            autoShow:  true,
            onApprove: function() {
                var newImageURL = modalImage.find('[name="wish_image"]').val();

                $('img.preview').attr('src', newImageURL);
                $('.form.wish [name="wish_image"]').val(newImageURL);

                $('body').toast({
                    class  : 'primary',
                    message: text.toast_wish_save
                });
            }
        });
    });

    /**
     * Priority
     */
    $('.dropdown.priority').dropdown();

});
