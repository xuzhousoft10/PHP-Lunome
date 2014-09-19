$(document).ready(function() {
    $('.administrator-delete-item').click(function() {
        var target = $(this).attr('data-target');
        $(target).empty().remove();
        return false;
    });

    $('#administrator-nwe-add').click(function(){
        var name = $.trim($('#administrator-new-name').val());
        var password = $.trim($('#administrator-new-password').val());
        $('#administrator-new-name').val('');
        $('#administrator-new-password').val('');
        
        if ( 0 == name.length || 0 == password.length ) {
            return;
        }
        
        var row = $('<tr></tr>')
            .attr('id', 'administrator-item-'+name)
            .appendTo('#administrators-container')
            .append(
                $('<td></td>')
                .append(
                    $('<label></label>')
                    .attr('class', 'control-label')
                    .html(name)
                )
                .append(
                    $('<input/>')
                    .attr('type', 'hidden')
                    .attr('name', 'administrators['+name+'][password]')
                    .val(password)
                )
            )
            .append(
                $('<td></td>')
                .append(
                    $('<a></a>')
                    .attr('href', '#')
                    .attr('class', 'administrator-delete-item')
                    .attr('data-target', '#administrator-item-'+name)
                    .html('<span class="glyphicon glyphicon-remove"></span>')
                    .click(function() {
                        var target = $(this).attr('data-target');
                        $(target).empty().remove();
                        return false;
                    })
                )
            );
    });
});