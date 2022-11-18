$(function() {
    /**
     * Tabs
     */
    var current_version_tab = version.replaceAll('.', '-');

    $('.menu .item').tab();
    $('.menu .item').tab('change tab', current_version_tab);
});
