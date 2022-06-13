$(function () {

    /**
     * Priority
     */
    $('.modal .wishlist-wish-add .dropdown.priority').dropdown();

    /**
     * Properties
     */
    $('.checkbox').checkbox({
        onChecked   : function() {
            $('.menu.wish-add .item[data-tab="product"]').removeClass('disabled');
        },
        onUnchecked : function() {
            $('.menu.wish-add .item[data-tab="product"]').addClass('disabled');
        },
    });

});
