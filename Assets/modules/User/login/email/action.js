$(document).ready(function(){
    $('#login-submit-button').click(function(){
        $('#login-account').parent().removeClass('has-error');
        if ( 0 == $('#login-account').val().length ) {
            $('#login-account').focus().parent().addClass('has-error');
            return false;
        }
    });
});