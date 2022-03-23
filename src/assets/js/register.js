$(function() {
    $('.ui.form').form({
        fields: {
            email    : 'email',
            password : ['minLength[8]', 'empty'],
            planet   : ['minLength[3]', 'empty'],
        }
    });
});
