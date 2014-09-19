/**
 * Verify the login form for password login.
 * 
 * @author Michael Luthor <michaelluthor@163.com>
 */
$(document).ready(function(){
    $('#login-submit-button').click(function(){
        $('#login-account').parent().removeClass('has-error');
        $('#login-password-inputer').parent().removeClass('has-error');
        if ( 0 == $('#login-account').val().length ) {
            $('#login-account').focus().parent().addClass('has-error');
            return false;
        }
        if ( 0 == $('#login-password-inputer').val().length ) {
            $('#login-password-inputer').focus().parent().addClass('has-error');
            return false;
        }
        
        var token = $('#web-data-login-token').val();
        var username = $('#login-account').val();
        var password = $('#login-password-inputer').val();
        var encodedPassword = token+':'+username+':'+password;
        $('#login-password-container').val($.md5(encodedPassword));
        return true;
    });
    
    $('#login-verify-code-refresh').click(function() {
        $('#login-verify-code-img').attr('src', $('#web-data-verify-code-url').val()+Math.random());
        return false;
    });
});