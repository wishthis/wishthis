$(function() {
    $('.ui.form').form({
        fields: {
            email: 'email',
            password: ['minLength[6]', 'empty'],
            planet: ['minLength[3]', 'empty'],
        }
    });
});
