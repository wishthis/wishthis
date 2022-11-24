"use strict";

window.addEventListener("load", (event) => {
    /**
     * Generate page preview
     */
    html2canvas(
        document.querySelector('body'),
        {
            'logging' : false,
            'x'       : window.scrollX,
            'y'       : window.scrollY,
            'width'   : window.innerWidth,
            'height'  : window.innerHeight,
        }
    )
    .then(canvas => {
        var data = new URLSearchParams();
        data.append('preview', canvas.toDataURL());
        data.append('page', wishthis.$_GET.page);

        /** Save page preview */
        fetch('/api/save-preview', {
            method : 'POST',
            body   : data
        })
        .then(handleFetchError)
        .then(handleFetchResponse)
        .then(function(response) {})
        .finally(function() {});
    });
});
