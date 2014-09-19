<?php
/**
 * The settting view of xsession service.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @since   0.0.0
 * @since   Version 0.0.0
 */

/**
 * The vars of the view.
 */
$vars = get_defined_vars();
$handler = $vars['config']['handler'];
?>
<h4>XSession Configuration</h4>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
    <div>
        <hr/>
        Handler:
        <table>
            <tr>
                <td width="150px" >type : </td>
                <td width="450px">
                    <select name="config[handler][type]" id="xsession-config-handler-type">
                        <option value="none">Default
                        <option value="database">Database
                    </select>
                    <select id="xsession-config-handler-database-type" style="display: none">
                        <option value="mysql">Mysql
                        <option value="sqlite">Sqlite
                    </select>
                    <button id="xsession-config-handler-database-type-setting" type="button" style="display: none">Setting</button>
                </td>
            </tr>
        </table>
        <div id="xsession-config-handler"></div>
        <hr/>
        <button type="submit">Save</button>
    </div>
</form>
<script type="text/javascript">
function setupSessionHandler( config ) {
    $('#xsession-config-handler').empty();
    switch( config.type ) {
    case 'none':
        break;
    case 'database':
        var table = $('<table></table>');
        for( var key in config ) {
            if ( 'type' == key ) {
                continue;
            }
            
            var row = $('<tr></tr>');
            row.append('<td width="150px">'+key+' : </td>');
            var editor = $('<input type="text" style="width: 100%"/>');
            editor.attr('name', 'config[handler]['+key+']');
            editor.val(config[key]);
            row.append($('<td width="450px"></td>').append(editor));
            row.appendTo(table);
        }
        table.appendTo('#xsession-config-handler');
        break;
    } 
}
$(document).ready(function() {
    setupSessionHandler(<?php echo json_encode($handler); ?>);
    $('#xsession-config-handler-type').change(function(){
        $('#xsession-config-handler-database-type').hide();
        $('#xsession-config-handler-database-type-setting').hide();
        switch( $('#xsession-config-handler-type').val() ) {
        case 'none':
            setupSessionHandler({type:'none'});
            break;
        case 'database':
            $('#xsession-config-handler-database-type').show();
            $('#xsession-config-handler-database-type-setting').show();
            break;
        }
    });

    $('#xsession-config-handler-database-type-setting').click(function(){
        switch($('#xsession-config-handler-database-type').val()) {
        case 'mysql' :
            setupSessionHandler({
                type:'database',
                dsn:'mysql:'+'host='+prompt('Input database host:')+';dbname='+prompt('Input database name:'),
                user:prompt('Input username to database:'),
                password:prompt('Input password for user:'),
                table:prompt('Input table name.')});
            break;
        case 'sqlite' :
            setupSessionHandler({type:'database',dsn:'sqlite:'+prompt('Input the path of target sqlite database file.')});
            break;
        default :
            return;
        }
    });

    $('#xsession-config-handler-type').val('<?php echo $handler['type'];?>').trigger('change');
});
</script>
