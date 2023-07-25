$(function() {
    $('.menu.profile .item').tab();

    /**
     * Personal
     */
    $('.ui.calendar').calendar({
        type           : 'date',
        firstDayOfWeek : 1,
        startMode      : 'year'
    });

    $('[data-tab="personal"] .ui.form').form({
        fields: {
            'user-email' : 'email',
        }
    });

    /**
     * Password
     */
    $('[data-tab="password"] .ui.form').form({
        fields: {
            'user-password'        : ['minLength[8]', 'empty'],
            'user-password-repeat' : ['minLength[8]', 'empty'],
            match                  : {
                identifier : 'user-password-repeat',
                depends    : 'user-password',
                rules      : [
                    {
                        type   : 'match[user-password]',
                        prompt : wishthis.strings.form.profile.password
                    }
                ]
            }
        }
    });

    $(document).on('change', '[name="user-password"]', function() {
        var password = $(this).val();

        if (password.length > 8) {
            $('.step.long')
            .removeClass('disabled')
            .addClass('completed');
        } else {
            $('.step.long')
            .removeClass('completed')
            .addClass('disabled');
        }

        var hasSpecialCharacter = false;

        password.split('').forEach(character => {
            if (character.match(/\W/)) {
                hasSpecialCharacter = true;
                return;
            }
        });

        if (hasSpecialCharacter) {
            $('.step.special')
            .addClass('completed')
            .removeClass('disabled');
        } else {
            $('.step.special')
            .removeClass('completed')
            .addClass('disabled');
        }
    });

    /**
     * Preferences
     */
    $('.ui.progress').progress();

    /** Locale */
    var dropdown_locale_settings = {
        'sortSelect'     : 'natural',
        'fullTextSearch' : true,
    };

    $('.ui.dropdown.language').dropdown(dropdown_locale_settings);
    $('.ui.dropdown.currency').dropdown(dropdown_locale_settings);

    /** Channel */
    $('.ui.dropdown.channel').dropdown();

    var isPWA = navigator.standalone || window.matchMedia('(display-mode: standalone)').matches;

    if (isPWA) {
        $('.ui.dropdown.channel').dropdown('set selected', 'stable');
        $('.ui.dropdown.channel').addClass('disabled');
        $('.ui.dropdown.channel').find('select').removeAttr('name');

        if ('undefined' !== typeof CHANNELS) {
            CHANNELS.forEach(channel => {
                if (channel.host === location.host) {
                    $('.ui.dropdown.channel').dropdown('set selected', channel.branch);
                }
            });
        }
    }

    /** Advertisements */
    $('.ui.checkbox.advertisements').checkbox();

    /**
     * Account
     */
    $('.ui.checkbox.account-delete').checkbox();

});
