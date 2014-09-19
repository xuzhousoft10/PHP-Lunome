$(document).ready(function() {
        $('#new-patch-file-selector').change(function() {
            $('#new-patch-upload-form').submit();
        });
        
        $('.upload-patch-btn').click(function() {
            var name = $(this).attr('data-name');
            $('#new-patch-upload-form').attr('action', '/admin?action=module/patch&name='+name);
            $('#new-patch-file-selector').trigger('click');
            return false;
        });
    });