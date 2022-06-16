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
        return response.status == 'OK' || response.results || response.success || false;
    }
    $.fn.api.settings.onComplete = function(response, element, xhr) {
        element.removeClass('loading');
    }
    $.fn.api.settings.onSuccess = function(response, element, xhr) {
        element.dropdown({
            values     : response.results,
            placeholder: text.wishlist_no_selection
        })

        if ($_GET.id) {
            element.dropdown('set selected', $_GET.id);
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
            title   : text.modal_failure_title,
            content : content,
            class   : 'small',
            actions : [
                {
                    text  : text.modal_failure_approve,
                    class : 'primary'
                }
            ],
            autoShow: true
        });
    }
    $.fn.api.settings.onError = function(response, element, xhr) {
        if ('string' === typeof response) {
            response = response.replace('<br />', '');
        }

        $('body')
        .modal({
            title   : text.modal_error_title,
            content : response,
            class   : 'small',
            actions : [
                {
                    text : text.modal_failure_approve,
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
    $.fn.toast.settings.title          = text.modal_success_title;

    /** Form Prompts */
    $.fn.form.settings.prompt = {
        empty                : text.form_prompt_empty,
        checked              : text.form_prompt_checked,
        email                : text.form_prompt_email,
        url                  : text.form_prompt_url,
        regExp               : text.form_prompt_regExp,
        integer              : text.form_prompt_integer,
        decimal              : text.form_prompt_decimal,
        number               : text.form_prompt_number,
        is                   : text.form_prompt_is,
        isExactly            : text.form_prompt_isExactly,
        not                  : text.form_prompt_not,
        notExactly           : text.form_prompt_notExactly,
        contain              : text.form_prompt_contain,
        containExactly       : text.form_prompt_containExactly,
        doesntContain        : text.form_prompt_doesntContain,
        doesntContainExactly : text.form_prompt_doesntContainExactly,
        minLength            : text.form_prompt_minLength,
        length               : text.form_prompt_length,
        exactLength          : text.form_prompt_exactLength,
        maxLength            : text.form_prompt_maxLength,
        match                : text.form_prompt_match,
        different            : text.form_prompt_different,
        creditCard           : text.form_prompt_creditCard,
        minCount             : text.form_prompt_minCount,
        exactCount           : text.form_prompt_exactCount,
        maxCount             : text.form_prompt_maxCount,
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

    $.fn.calendar.settings.text = {
        days        : [
            new Date(2018, 00, 00).toLocaleString(locale, options_weekday),
            new Date(2018, 00, 01).toLocaleString(locale, options_weekday),
            new Date(2018, 00, 02).toLocaleString(locale, options_weekday),
            new Date(2018, 00, 03).toLocaleString(locale, options_weekday),
            new Date(2018, 00, 04).toLocaleString(locale, options_weekday),
            new Date(2018, 00, 05).toLocaleString(locale, options_weekday),
            new Date(2018, 00, 06).toLocaleString(locale, options_weekday),
        ],
        months      : [
            new Date(0000, 01, 00).toLocaleString(locale, options_months),
            new Date(0000, 02, 00).toLocaleString(locale, options_months),
            new Date(0000, 03, 00).toLocaleString(locale, options_months),
            new Date(0000, 04, 00).toLocaleString(locale, options_months),
            new Date(0000, 05, 00).toLocaleString(locale, options_months),
            new Date(0000, 06, 00).toLocaleString(locale, options_months),
            new Date(0000, 07, 00).toLocaleString(locale, options_months),
            new Date(0000, 08, 00).toLocaleString(locale, options_months),
            new Date(0000, 09, 00).toLocaleString(locale, options_months),
            new Date(0000, 10, 00).toLocaleString(locale, options_months),
            new Date(0000, 11, 00).toLocaleString(locale, options_months),
            new Date(0000, 12, 00).toLocaleString(locale, options_months),
        ],
        monthsShort : [
            new Date(0000, 01, 00).toLocaleString(locale, options_months_short),
            new Date(0000, 02, 00).toLocaleString(locale, options_months_short),
            new Date(0000, 03, 00).toLocaleString(locale, options_months_short),
            new Date(0000, 04, 00).toLocaleString(locale, options_months_short),
            new Date(0000, 05, 00).toLocaleString(locale, options_months_short),
            new Date(0000, 06, 00).toLocaleString(locale, options_months_short),
            new Date(0000, 07, 00).toLocaleString(locale, options_months_short),
            new Date(0000, 08, 00).toLocaleString(locale, options_months_short),
            new Date(0000, 09, 00).toLocaleString(locale, options_months_short),
            new Date(0000, 10, 00).toLocaleString(locale, options_months_short),
            new Date(0000, 11, 00).toLocaleString(locale, options_months_short),
            new Date(0000, 12, 00).toLocaleString(locale, options_months_short),
        ],
        today       : text.calendar_today,
        now         : text.calendar_now,
        am          : text.calendar_am,
        pm          : text.calendar_pm,
        weekNo      : text.calendar_week_no,
    };

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
                text  : text.modal_failure_approve,
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
                text  : text.modal_warning_approve,
                class : 'primary'
            }
        ],
        autoShow      : true,
        allowMultiple : true
    });
}
