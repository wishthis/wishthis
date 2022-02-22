$(function() {
    $('.ui.dropdown.wishlists').api({
        action: 'get wishlists',
        method: 'GET',
        on:     'now'
    });
});
