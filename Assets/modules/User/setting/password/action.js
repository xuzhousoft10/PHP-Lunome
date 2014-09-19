$(document).ready(function() {
    var status = $('#web-data-status').val()*1;
    if ( 1 != status ) {
        $('#password-reset-button').click(function() {
            $('#password-old').parent().removeClass('has-error');
            $('#password-new').parent().removeClass('has-error');
            $('#password-new-confirm').parent().removeClass('has-error');
            
            if ( 0 == $('#password-old').val().length ) {
                $('#password-old').focus().parent().addClass('has-error');
                return false;
            }
                        
            if ( 0 == $('#password-new').val().length ) {
                $('#password-new').focus().parent().addClass('has-error');
                return false;
            }
            
            if ( 0 == $('#password-new-confirm').val().length ) {
                $('#password-new-confirm').focus().parent().addClass('has-error');
                return false;
            }

            if ( $('#password-new-confirm').val() != $('#password-new').val() ) {
                $('#password-new').parent().addClass('has-error');
                $('#password-new-confirm').val('').focus().parent().addClass('has-error');
                return false;
            }

            return true;
        });
    } else {
        setTimeout(function(){
            window.location=$('#web-data-logout-url').val();
        }, 2000);
    }
});