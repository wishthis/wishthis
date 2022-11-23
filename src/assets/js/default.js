/**
 * Service Worker
 */
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
    navigator.serviceWorker
        .register('/service-worker.js')
        .then(res => console.log('wishthis service worker registered'))
        .catch(err => console.log('wishthis service worker not registered', err))
    })
}

const urlParams = new URLSearchParams(wishthis.$_GET);

$(function() {
    /**
     * Fomantic UI
     */
    /** API */
    $.fn.api.settings.api = {
        'get wishlists'             : '/?page=api&module=wishlists',
        'get wishlists by priority' : '/?page=api&module=wishlists&style={style}&wishlist_id={wishlistid}&priority={priority}',
        'delete wishlist'           : '/?page=api&module=wishlists',
        'update wish status'        : '/?page=api&module=wishes',
        'delete wish'               : '/?page=api&module=wishes',
    };

    /** Default callbacks */
    $.fn.api.settings.onResponse = function(response) {
        return response;
    }
    $.fn.api.settings.successTest = function(response) {
        return response.status == 'OK' || response.results || response.success || false;
    }
    $.fn.api.settings.onComplete = function(response, element, xhr) {
        element.removeClass('loading');
    }
    $.fn.api.settings.onSuccess = function(response, element, xhr) {
        element.dropdown({
            values : response.results
        })

        if (wishthis.$_GET.id) {
            element.dropdown('set selected', wishthis.$_GET.id);
        } else {
            if (response.results[0]) {
                element.dropdown('set selected', response.results[0].value);
            }
        }
    }
    $.fn.api.settings.onFailure = function(response, element, xhr) {
        var content = '';

        if ('string' === typeof response) {
            content = response.replace('<br />', '');
        }

        if ('' === response.warning) {
            content = text.modal_failure_content;
        }

        $('body')
        .modal({
            autoShow : true,
            title    : wishthis.strings.modal.failure.title,
            content  : content,
            class    : 'small',
            actions  : [
                {
                    text  : wishthis.strings.modal.failure.approve,
                    class : 'primary'
                }
            ]
        });
    }
    $.fn.api.settings.onError = function(response, element, xhr) {
        if ('string' === typeof response) {
            response = response.replace('<br />', '');
        }

        $('body')
        .modal({
            title   : wishthis.strings.modal.error.title,
            content : response,
            class   : 'small',
            actions : [
                {
                    text : wishthis.strings.modal.failure.approve,
                    class: 'primary'
                }
            ],
            autoShow: true
        });
    }
    /** */

    /** Toasts */
    $.fn.toast.settings.displayTime    = 'auto';
    $.fn.toast.settings.minDisplayTime = 3000;
    $.fn.toast.settings.showProgress   = 'bottom';
    $.fn.toast.settings.class          = 'success';
    $.fn.toast.settings.showIcon       = true;
    $.fn.toast.settings.title          = wishthis.strings.modal.success.title;

    /** Form Prompts */
    $.fn.form.settings.prompt = {
        empty                : wishthis.strings.form.prompt.empty,
        checked              : wishthis.strings.form.prompt.checked,
        email                : wishthis.strings.form.prompt.email,
        url                  : wishthis.strings.form.prompt.url,
        regExp               : wishthis.strings.form.prompt.regExp,
        integer              : wishthis.strings.form.prompt.integer,
        decimal              : wishthis.strings.form.prompt.decimal,
        number               : wishthis.strings.form.prompt.number,
        is                   : wishthis.strings.form.prompt.is,
        isExactly            : wishthis.strings.form.prompt.isExactly,
        not                  : wishthis.strings.form.prompt.not,
        notExactly           : wishthis.strings.form.prompt.notExactly,
        contain              : wishthis.strings.form.prompt.contain,
        containExactly       : wishthis.strings.form.prompt.containExactly,
        doesntContain        : wishthis.strings.form.prompt.doesntContain,
        doesntContainExactly : wishthis.strings.form.prompt.doesntContainExactly,
        minLength            : wishthis.strings.form.prompt.minLength,
        length               : wishthis.strings.form.prompt.length,
        exactLength          : wishthis.strings.form.prompt.exactLength,
        maxLength            : wishthis.strings.form.prompt.maxLength,
        match                : wishthis.strings.form.prompt.match,
        different            : wishthis.strings.form.prompt.different,
        creditCard           : wishthis.strings.form.prompt.creditCard,
        minCount             : wishthis.strings.form.prompt.minCount,
        exactCount           : wishthis.strings.form.prompt.exactCount,
        maxCount             : wishthis.strings.form.prompt.maxCount,
    };

    /** Calendar Text */
    var options_weekday = {
        timeZone : Intl.DateTimeFormat().resolvedOptions().timeZone,
        weekday  : 'short'
    };
    var options_months = {
        timeZone : Intl.DateTimeFormat().resolvedOptions().timeZone,
        month    : 'long'
    };
    var options_months_short = {
        timeZone : Intl.DateTimeFormat().resolvedOptions().timeZone,
        month    : 'short'
    };

    $.fn.calendar.settings.text.days = [
        new Date(2018, 00, 00).toLocaleString(wishthis.locale, options_weekday),
        new Date(2018, 00, 01).toLocaleString(wishthis.locale, options_weekday),
        new Date(2018, 00, 02).toLocaleString(wishthis.locale, options_weekday),
        new Date(2018, 00, 03).toLocaleString(wishthis.locale, options_weekday),
        new Date(2018, 00, 04).toLocaleString(wishthis.locale, options_weekday),
        new Date(2018, 00, 05).toLocaleString(wishthis.locale, options_weekday),
        new Date(2018, 00, 06).toLocaleString(wishthis.locale, options_weekday),
    ];
    $.fn.calendar.settings.text.months = [
        new Date(0000, 01, 00).toLocaleString(wishthis.locale, options_months),
        new Date(0000, 02, 00).toLocaleString(wishthis.locale, options_months),
        new Date(0000, 03, 00).toLocaleString(wishthis.locale, options_months),
        new Date(0000, 04, 00).toLocaleString(wishthis.locale, options_months),
        new Date(0000, 05, 00).toLocaleString(wishthis.locale, options_months),
        new Date(0000, 06, 00).toLocaleString(wishthis.locale, options_months),
        new Date(0000, 07, 00).toLocaleString(wishthis.locale, options_months),
        new Date(0000, 08, 00).toLocaleString(wishthis.locale, options_months),
        new Date(0000, 09, 00).toLocaleString(wishthis.locale, options_months),
        new Date(0000, 10, 00).toLocaleString(wishthis.locale, options_months),
        new Date(0000, 11, 00).toLocaleString(wishthis.locale, options_months),
        new Date(0000, 12, 00).toLocaleString(wishthis.locale, options_months),
    ];
    $.fn.calendar.settings.text.monthsShort = [
        new Date(0000, 01, 00).toLocaleString(wishthis.locale, options_months_short),
        new Date(0000, 02, 00).toLocaleString(wishthis.locale, options_months_short),
        new Date(0000, 03, 00).toLocaleString(wishthis.locale, options_months_short),
        new Date(0000, 04, 00).toLocaleString(wishthis.locale, options_months_short),
        new Date(0000, 05, 00).toLocaleString(wishthis.locale, options_months_short),
        new Date(0000, 06, 00).toLocaleString(wishthis.locale, options_months_short),
        new Date(0000, 07, 00).toLocaleString(wishthis.locale, options_months_short),
        new Date(0000, 08, 00).toLocaleString(wishthis.locale, options_months_short),
        new Date(0000, 09, 00).toLocaleString(wishthis.locale, options_months_short),
        new Date(0000, 10, 00).toLocaleString(wishthis.locale, options_months_short),
        new Date(0000, 11, 00).toLocaleString(wishthis.locale, options_months_short),
        new Date(0000, 12, 00).toLocaleString(wishthis.locale, options_months_short),
    ];
    $.fn.calendar.settings.text.today  = wishthis.strings.calendar.today;
    $.fn.calendar.settings.text.now    = wishthis.strings.calendar.now;
    $.fn.calendar.settings.text.am     = wishthis.strings.calendar.am;
    $.fn.calendar.settings.text.pm     = wishthis.strings.calendar.pm;
    $.fn.calendar.settings.text.weekNo = wishthis.strings.calendar.week_no;

    /** Dimmer */
    $.fn.dimmer.settings.closable = false;

    /**
     * Menu
     */
    $(document).on('click', '.menu.toggle', function() {
        $('.menu.sidebar').sidebar('show');
    });

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
        return response.text().then(function(text) {
            if (text.toLowerCase().includes('error') || text.toLowerCase().includes('exception')) {
                showError(text);
            }
        })
    } else if (isJSON) {
        return response.json().then(function(json) {
            if (json.warning) {
                showWarning(json.warning)
            }

            return json;
        });
    }
}

function handleFetchCatch(error) {
    console.log(error);

    return error;
}

function showError(error) {
    error = error.replace('<br />', '');

    $('body')
    .modal({
        title             : 'Error',
        content           : error,
        class             : 'small',
        actions           : [
            {
                text  : wishthis.strings.modal.failure.approve,
                class : 'primary'
            }
        ],
        autoShow      : true,
        allowMultiple : true
    });
}

function showWarning(warning) {
    warning = warning.replace('<br />', '');

    $('body')
    .modal({
        title         : 'Warning',
        content       : warning,
        class         : 'small',
        actions       : [
            {
                text  : wishthis.strings.modal.warning.approve,
                class : 'primary'
            }
        ],
        autoShow      : true,
        allowMultiple : true
    });
}
