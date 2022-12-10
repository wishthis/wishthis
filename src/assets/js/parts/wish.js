$(function () {

    $(document).on('click', '.ui.button.details', function() {
        console.log($('.ui.modal.details'));
        $('.ui.modal.details').modal('show');
    });

});
