$(function() {
    $('.ui.calendar').calendar({
        type           : 'date',
        firstDayOfWeek : 1,
        startMode      : 'year'
    });

    $('.ui.form').form({
        fields: {
            'user-email'    : 'email',
            'user-password' : ['minLength[8]'],
            match           : {
                identifier : 'user-password-repeat',
                depends    : 'user-password',
                rules      : [
                    {
                        type   : 'match[user-password]',
                        prompt : text.form_profile_password
                    }
                ]
            },
        }
    });
});
