$(function() {
    $('.ui.calendar').calendar({
        type          : 'date',
        firstDayOfWeek: 1,
        initialDate   : '01.01.1990',
        startMode     : 'year'
    });

    $('.ui.form').form({
        fields: {
            'user-email': 'email',
            match: {
                identifier : 'user-password-repeat',
                depends    : 'user-password',
                rules      : [
                    {
                        type   : 'match[user-password]',
                        prompt : 'Password must match.'
                    }
                ]
            },
        }
    });
});
