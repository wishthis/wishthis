$(function() {
    $('.ui.form').form({
        on: 'blur',
        fields: {
            email: 'email',
            password: ['minLength[6]', 'empty'],
        }
    });
});
