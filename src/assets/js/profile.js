$(function() {
    $('.menu.profile .item').tab();

    $('.ui.calendar').calendar({
        type           : 'date',
        firstDayOfWeek : 1,
        startMode      : 'year'
    });

    $('.ui.form').form({
        fields: {
            'user-email'           : 'email',
            'user-password'        : ['minLength[8]', 'empty'],
            'user-password-repeat' : ['minLength[8]', 'empty'],
            match                  : {
                identifier : 'user-password-repeat',
                depends    : 'user-password',
                rules      : [
                    {
                        type   : 'match[user-password]',
                        prompt : text.form_profile_password
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

    $('.ui.progress').progress();

    /**
     * Preferences
     */

    /** Language */
    $('.ui.dropdown.locale').dropdown({
        sortSelect     : 'natural',
        fullTextSearch : true,
    });

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
});
