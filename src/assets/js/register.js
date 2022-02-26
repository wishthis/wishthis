$(function() {
    $('.ui.form').form({
        on: 'blur',
        fields: {
            email: 'email',
            password: ['minLength[6]', 'empty'],
            planet: ['minLength[3]', 'empty'],
        }
    });
});
