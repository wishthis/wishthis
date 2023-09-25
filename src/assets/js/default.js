const urlParams  = new URLSearchParams(wishthis.$_GET);
const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;

$(function() {
    /**
     * Fomantic UI
     */

    /** Default callbacks */
    $.fn.api.settings.onResponse = function(response) {
        return response;
    }
    $.fn.api.settings.successTest = function(response) {
        return 'OK' === response.status || response.success || false;
    }
    $.fn.api.settings.onComplete = function(response, element, xhr) {
        element.removeClass('loading');
    }
    $.fn.api.settings.onFailure = function(response, element, xhr) {
        var modal_failure_content = '';

        if ('string' === typeof response && response) {
            modal_failure_content = response.replace('<br />', '');
        } else {
            modal_failure_content = wishthis.strings.modal.failure.content;
        }

        showFailure(
            wishthis.strings.modal.failure.title,
            modal_failure_content,
        );
    }
    $.fn.api.settings.onError = function(response, element, xhr) {
        var modal_error_content = '';

        if ('string' === typeof response && response) {
            modal_error_content = response.replace('<br />', '');
        } else {
            modal_error_content = wishthis.strings.modal.error.content;
        }

        showError(
            wishthis.strings.modal.error.title,
            modal_error_content,
        );
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

    /**
     * Popups
     */
    popup_settings_default = {
        'position'  : 'top center',
        'variation' : isDarkMode ? '' : 'inverted',
        'hoverable' : true,
    };

    $('[data-content]').popup(popup_settings_default);
    $('[data-html]').popup(popup_settings_default);
});

/**
 * Functions
 */
function handleFetchError(response) {
    if (!response.ok) {
        console.log('handleFetchError');
        console.log(response);

        showError(
            wishthis.strings.modal.error.title,
            response.statusText
        );

        throw Error(response.statusText);
    }

    return response;
}

function handleFetchResponse(response) {
    var content_type = response.headers.get('content-type');
    var isText       = false;
    var isJSON       = false;

    if (content_type) {
        isText =    content_type.includes('text/html')
                 || content_type.includes('image/svg+xml');

        isJSON = content_type.includes('application/json');
    }

    if (isText) {
        return response
        .text()
        .then(function(text) {
            if (text.toLowerCase().includes('error') || text.toLowerCase().includes('exception')) {
                showError(
                    wishthis.strings.modal.error.title,
                    text
                );
            } else {
                return text;
            }
        })
    } else if (isJSON) {
        return response.json().then(function(json) {
            if (json.warning) {
                showFailure(
                    wishthis.strings.modal.failure.title,
                    json.warning
                );
            }

            return json;
        });
    }

    return response;
}

function handleFetchCatch(error) {
    console.log(error);

    return error;
}

function showError(title = '', content = '') {
    var modal_error = {
        'class'         : 'small',
        'autoShow'      : true,
        'allowMultiple' : true,
        'actions'       : [
            {
                'text'  : wishthis.strings.modal.error.approve,
                'class' : 'primary',
            }
        ],
    };

    if (title) {
        modal_error.title = title;
    } else {
        modal_error.title = wishthis.strings.modal.error.title;
    }

    if (Array.isArray(content)) {
        modal_error.content = '';

        content.forEach(paragraph => {
            modal_error.content += '<p>' + paragraph + '</p>';
        });
    } else {
        modal_error.content = content;
    }

    $('body').modal(modal_error);
}

function showFailure(title = '', content = '') {
    var modal_failure = {
        'class'         : 'small',
        'autoShow'      : true,
        'allowMultiple' : true,
        'actions'       : [
            {
                'text'  : wishthis.strings.modal.failure.approve,
                'class' : 'primary',
            }
        ],
    };

    if (title) {
        modal_failure.title = title;
    } else {
        modal_failure.title = wishthis.strings.modal.failure.title;
    }

    if (Array.isArray(content)) {
        modal_failure.content = '';

        content.forEach(paragraph => {
            modal_failure.content += '<p>' + paragraph + '</p>';
        });
    } else {
        modal_failure.content = content;
    }

    $('body').modal(modal_failure);
}
