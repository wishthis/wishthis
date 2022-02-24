$(function() {
    $('.ui.dropdown.wishlists').api({
        action: 'get wishlists',
        method: 'GET',
        on:     'now'
    });

    $(document).on('submit', '.ui.form', function(event) {
        event.preventDefault();

        var form = $(event.currentTarget);
        var card = $('.ui.card');

        var url = $('.ui.form [name="url"]').val();

        card.find('.content a').attr('href', url);

        fetch('/src/api/products.php', {
            method: 'PUT',
            body: form.serialize()
        })
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                card.attr('data-id', response.data.lastInsertId);
                card.attr('data-cache', 'false');
            }
        });
    });
});
