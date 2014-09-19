$(document).ready(function(){
    $('#reset-button').click(function(){
        $('#reset-new-password').parent().removeClass('has-error');
        $('#reset-confirm-new-password').parent().removeClass('has-error');
        if ( 0 < $('#password-reset-area-confirm-error').length ) {
            $('#password-reset-area-confirm-error').remove();
        }
        
        if ( 0 == $('#reset-new-password').val().length ) {
            $('#reset-new-password').parent().addClass('has-error');
            $('#reset-new-password').focus();
            return false;
        }
        if ( 0 == $('#reset-confirm-new-password').val().length ) {
            $('#reset-confirm-new-password').parent().addClass('has-error');
            $('#reset-confirm-new-password').focus();
            return false;
        }
        if ( $('#reset-new-password').val() != $('#reset-confirm-new-password').val() ) {
            $('#reset-confirm-new-password').parent().addClass('has-error');
            $('#reset-confirm-new-password').focus();
            $('#password-reset-area').prepend(
                '<div id="password-reset-area-confirm-error" class="alert alert-danger alert-dismissable">' +
                    '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                    '<strong>Error!</strong> Password Confirm failed !' +
                '</div>');
            return false;
        }
        return true;
    });
    
    if ( 1 == $('#web-data-status').val()*1 ) {
        setTimeout(function(){
            window.location=$('#web-data-login-url').val();
        },3000);
    }
});