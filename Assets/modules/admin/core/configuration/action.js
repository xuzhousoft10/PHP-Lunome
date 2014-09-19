$(document).ready(function() {
    $('.configuration-delete-item').click(function() {
        var target = $(this).attr('data-target');
        $(target).empty().remove();
        return false;
    });

    $('#configuratin-nwe-add').click(function() {
        var name = $('#configuration-new-name').val();
        var value = $('#configuration-new-value').val();

        if ( 0 == $.trim(name).length ) {
            return false;
        }
        
        var row = $('<tr></tr>').attr('id', 'configuration-item-'+name).appendTo('#configuration-container');
        $('<td><label class="control-label">'+name+'</label></td>').appendTo(row);
        $('<td></td>')
            .append(
                    $('<input />')
                        .val(value)
                        .attr('class', 'form-control input-sm')
                        .attr('name', 'configuration['+name+']')
                        .attr('type', 'text')
            )
            .appendTo(row);
        $('<td></td>')
            .append(
                $('<a></a>')
                    .attr('href','#')
                    .attr('class', 'configuration-delete-item')
                    .attr('data-target', '#configuration-item-'+name)
                    .html('<span class="glyphicon glyphicon-remove"></span>')
                    .click(function() {
                         var target = $(this).attr('data-target');
                         $(target).empty().remove();
                         return false;
                     })
         )
         .appendTo(row);

        $('#configuration-new-name').val('');
        $('#configuration-new-value').val('');
    });
});