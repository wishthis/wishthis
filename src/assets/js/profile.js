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

    $('.ui.dropdown').dropdown();
    $('.ui.dropdown.locale').dropdown({
        sortSelect : 'natural',
    });
});
