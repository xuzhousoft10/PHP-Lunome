$(document).ready(function() {
    $('#register-button').click(function() {
        $('#register-email').parent().removeClass('has-error');
        $('#register-password').parent().removeClass('has-error');
        $('#register-password-confirm').parent().removeClass('has-error');
        
        if ( 0 == $('#register-email').val().length ) {
            $('#register-email').focus().parent().addClass('has-error');
            return false;
        }
        
        if ( 0 == $('#register-password').val().length ) {
            $('#register-password').focus().parent().addClass('has-error');
            return false;
        }
        
        if ( 0 == $('#register-password-confirm').val().length ) {
            $('#register-password-confirm').focus().parent().addClass('has-error');
            return false;
        }

        if ( $('#register-password').val() != $('#register-password-confirm').val() ) {
            $('#register-password').parent().addClass('has-error');
            $('#register-password-confirm').val('').focus().parent().addClass('has-error');
            return false;
        }

        return true;
    });
});