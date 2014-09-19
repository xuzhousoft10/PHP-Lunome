<?php
/**
 * The setting view of xurl service
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */

/**
 * Vars of the view.
 */
$vars = get_defined_vars();
$config = $vars['config'];
?>
<h4>XRequest Configuration</h4>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
    <div>
        <hr/>
        Request Recorder:
        <table>
            <tr>
                <td>Status:</td>
                <td>
                    <input type="hidden" name="config[RecordRequest][status]" value="off">
                    <input 
                        id="config-RecordRequest-status"
                        type="checkbox" 
                        name="config[RecordRequest][status]" 
                        value="on" 
                        <?php if ('on' == $config['RecordRequest']['status']) : ?>
                        checked 
                        <?php endif; ?>
                    />
                </td>
            </tr>
            <tr id="config-RecordRequest-handler-select">
                <td width="150px" >type : </td>
                <td width="450px">
                    <select name="config[RecordRequest][handler]" id="config-RecordRequest-handler">
                        <option value="none">
                        <option value="Database">Database
                    </select>
                    <select id="config-RecordRequest-handler-database-type" style="display: none">
                        <option value="mysql">Mysql
                    </select>
                    <button id="config-RecordRequest-handler-database-type-setting" type="button" style="display: none">Setting</button>
                </td>
            </tr>
        </table>
        <div id="config-RecordRequest-handler-config"></div>
        <hr/>
        <div>
            On No Rule Matched: <input type="text" value="<?php echo $config['Error']['unmatched']; ?>" name="config[Error][unmatched]"/>
        </div>
        <hr/>
        <button type="submit">Save</button>
    </div>
</form>
<script type="text/javascript">
function setupSessionHandler( config ) {
    $('#config-RecordRequest-handler-config').empty();
    switch( config.RecordRequest.handler ) {
    case 'none':
        break;
    case 'Database':
        var table = $('<table></table>');
        for( var key in config.RecordRequest ) {
            if ( 'status' == key || 'handler' == key ) {
                continue;
            }
            
            var row = $('<tr></tr>');
            row.append('<td width="150px">'+key+' : </td>');
            var editor = $('<input type="text" style="width: 100%"/>');
            editor.attr('name', 'config[RecordRequest]['+key+']');
            editor.val(config.RecordRequest[key]);
            row.append($('<td width="450px"></td>').append(editor));
            row.appendTo(table);
        }
        table.appendTo('#config-RecordRequest-handler-config');
        break;
    } 
}

function setupErrorUnmatched( config ) {
    $('#config_Error_unmatched_extra').empty();
    $('#config_Error_unmatched').val(config.unmatched);
    switch(config.unmatched) {
    case 'exception':
        break;
    case '404':
        $('#config_Error_unmatched_extra').append('Unmatched Handler:');
        var editor = $('<input />')
            .val(config.handler)
            .attr('name', '');
        $('#config_Error_unmatched_extra').append(editor);
    }
}

$(document).ready(function() {
    $('#config-RecordRequest-status').change(function(){
        if ( $('#config-RecordRequest-status').is(':checked') ) {
            $('#config-RecordRequest-handler-select').show();
        } else {
            $('#config-RecordRequest-handler-select').hide();
            $('#config-RecordRequest-handler-config').empty();
        }
    });
    $('#config-RecordRequest-status').trigger('change');

    <?php if ('on' == $config['RecordRequest']['status']): ?>
    setupSessionHandler(<?php echo json_encode($config); ?>);
    <?php endif; ?>
    $('#config-RecordRequest-handler').change(function(){
        $('#config-RecordRequest-handler-database-type').hide();
        $('#config-RecordRequest-handler-database-type-setting').hide();
        switch( $('#config-RecordRequest-handler').val() ) {
        case 'Database':
            $('#config-RecordRequest-handler-database-type').show();
            $('#config-RecordRequest-handler-database-type-setting').show();
            break;
        }
    });
    <?php if ('on' == $config['RecordRequest']['status']): ?>
    $('#config-RecordRequest-handler').val('<?php echo $config['RecordRequest']['handler'];?>').trigger('change');
    <?php endif; ?>
    
    $('#config-RecordRequest-handler-database-type-setting').click(function(){
        switch($('#config-RecordRequest-handler-database-type').val()) {
        case 'mysql' :
            setupSessionHandler({RecordRequest:{
                handler:'Database',
                dsn:'mysql:'+'host='+prompt('Input database host:')+';dbname='+prompt('Input database name:'),
                username:prompt('Input username to database:'),
                password:prompt('Input password for user:'),
                table:prompt('Input table name.')}});
            break;
        default :
            return;
        }
    });

    setupErrorUnmatched(<?php echo json_encode($config['Error']); ?>);
    $('#config_Error_unmatched').change(function() {
        switch($('#config_Error_unmatched').val()) {
        case 'exception':
            break;
        case '404':
            var handler = prompt('Input 404 handler(string, file path or url) :');
            alert(handler);
        }
    });
});
</script>
