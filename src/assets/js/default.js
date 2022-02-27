/**
 * Service Worker
 */
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
    navigator.serviceWorker
        .register('/serviceWorker.js')
        .then(res => console.log('service worker registered'))
        .catch(err => console.log('service worker not registered', err))
    })
}

const urlParams = new URLSearchParams($_GET);

$(function() {
    /**
     * Fomantic UI
     */
    /** API */
    $.fn.api.settings.api = {
        'get wishlists'        : '/src/api/wishlists.php',
        'delete wishlist'      : '/src/api/wishlists.php',
        'update wish status'   : '/src/api/wishes.php',
        'delete wish'          : '/src/api/wishes.php',
    };

    /** Default callbacks */
    $.fn.api.settings.onResponse = function(response) {
        return response;
    }
    $.fn.api.settings.successTest = function(response) {
        return response.status == 'OK' || response.success || false;
    }
    $.fn.api.settings.onComplete = function(response, element, xhr) {
        element.removeClass('loading');
    }
    $.fn.api.settings.onSuccess = function(response, element, xhr) {
        element.dropdown({
            values: response.results,
            placeholder: 'No wishlist selected.'
        })

        if ($_GET.wishlist) {
            element.dropdown('set selected', $_GET.wishlist);
        } else {
            if (response.results[0]) {
                element.dropdown('set selected', response.results[0].value);
            }
        }
    }
    $.fn.api.settings.onFailure = function(response, element, xhr) {
        if ('string' === typeof response) {
            response = response.replace('<br />', '');
        }

        $('body')
        .modal({
            title:    'Failure',
            content:  response,
            class:    '',
            actions:  [
                {
                    text: 'Thanks for nothing',
                    class: 'primary'
                }
            ]
        })
        .modal('show');
    }
    $.fn.api.settings.onError = function(response, element, xhr) {
        if ('string' === typeof response) {
            response = response.replace('<br />', '');
        }

        $('body')
        .modal({
            title:    'Error',
            content:  response,
            class:    '',
            actions:  [
                {
                    text: 'Thanks for nothing',
                    class: 'primary'
                }
            ]
        })
        .modal('show');
    }
    /** */

    /** Toasts */
    $.fn.toast.settings.displayTime    = 'auto';
    $.fn.toast.settings.minDisplayTime = 3000;
    $.fn.toast.settings.showProgress   = true;
    $.fn.toast.settings.class          = 'success';
    $.fn.toast.settings.showIcon       = 'check';
    $.fn.toast.settings.title          = 'Success';

    /**
     * Menu
     */
    $(document).on('click', '.menu.toggle', function() {
        $('.menu.sidebar').sidebar('show');
    });

    $(window).resize(function(event) {
        if ($(window).width() > 768) {
            $('.menu.sidebar').removeClass('vertical sidebar');
        } else {
            $('.menu.sidebar').addClass('vertical sidebar');
        }
    });
    $(window).trigger('resize');
});

/**
 * Functions
 */
function handleFetchError(response) {
    if (!response.ok) {
        console.log('handleFetchError');
        console.log(response);

        showError(response.statusText);
        throw Error(response.statusText);
    }

    return response;
}

function handleFetchResponse(response) {
    var isText = response.headers.get('content-type')?.includes('text/html');
    var isJSON = response.headers.get('content-type')?.includes('application/json');

    if (isText) {
        return response.text()
        .then(function(text) {
            if (text.toLowerCase().includes('error') || text.toLowerCase().includes('exception')) {
                showError(text);
            }
        })
    } else if (isJSON) {
        return response.json();
    }
}

function showError(error) {
    error = error.replace('<br />', '');

    $('body')
        .modal({
            title:    'Error',
            content:  error,
            class:    '',
            actions:  [
                {
                    text: 'Thanks for nothing',
                    class: 'primary'
                }
            ]
        })
        .modal('show');
}
