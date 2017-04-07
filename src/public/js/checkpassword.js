$('#change-password-submit').on('click', function(e) {
    var password = $('#password').val();
    var confirm = $('#confirm_password').val();
    var element = document.getElementById('confirm_password');

    if(password != confirm) {
        e.preventDefault();
        element.setCustomValidity('Passwords do not match.');
    } else {
        element.setCustomValidity('');
    }
});