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

    $('.ui.dropdown.channel').dropdown();
    $('.ui.dropdown.locale').dropdown({
        sortSelect : 'natural',
    });
    $('.ui.progress').progress();

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
