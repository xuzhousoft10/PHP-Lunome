$(document).ready(function() {
    $('#profile-setting-button').click(function(){
        $('#profile-user-name').focus().parent().removeClass('has-error');
        if ( 0 == $('#profile-user-name').val().length ) {
            $('#profile-user-name').focus().parent().addClass('has-error');
            return false;
        }
        return true;
    });
});