$(function() {
    $('.ui.dropdown.wishlists').on('change', function() {
        $('[name="wishlist_delete_id"]').val(
            $('.ui.dropdown.wishlists').dropdown('get value')
        );
    });
});
