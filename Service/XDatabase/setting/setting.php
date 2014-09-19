<?php 
$vars = get_defined_vars();
$dbConfig = $vars['dbConfig'];
?>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
    <div id="xdb-setting-db-list"></div>
    
    <br/>
    <div style="border-style:double;width:650px;padding-left: 20px; text-align:right">
        <select id="xdb-setting-add-db-type-sector">
            <option value="mysql">Mysql
            <option value="sqlite">SQLite
        </select>
        <button id="xdb-setting-add-db-button" type="button">Add DB</button>
        <script type="text/javascript">
        $(document).ready(function(){
            $('#xdb-setting-add-db-button').click(function(){
                var name = prompt('Input name for db instance');
                if ( null == name || 0==name.length ) {
                    return;
                }
                switch( $('#xdb-setting-add-db-type-sector').val() ) {
                case 'mysql' :
                    addDBItem({
                        name:name,
                        dsn:'mysql:'+'host='+prompt('Input database host:')+';dbname='+prompt('Input database name:'),
                        user:prompt('Input username to database:'),
                        password:prompt('Input password for user:')});
                    break;
                case 'sqlite' :
                    addDBItem({name:name,dsn:'sqlite:'+prompt('Input the path of target sqlite database file.')});
                    break;
                default :
                    return;
                }
            });
        });
        </script>
    </div>
    
    <br/>
    <div style="border-style:double;width:650px;padding-left: 20px; text-align:right">
        <button type="submit">Submit</button>
    </div>
</form>
<script type="text/javascript">
var DBCounter = 0;
function addDBItem(config) {
    var container = $('<div style="border-style:double;width:650px;padding-left: 20px;"></div>');
    var table = $('<table></table>');
    var mark = 'DB'+DBCounter;
    DBCounter++;
    for( var key in config ) {
        var row = $('<tr></tr>');
        row.append('<td width="150px">'+key+' : </td>');
        var editor = $('<input type="text" style="width: 100%"/>');
        editor.attr('name', 'config[DB]['+mark+']['+key+']');
        editor.val(config[key]);
        row.append($('<td width="450px"></td>').append(editor));
        row.appendTo(table);
    }
    
    var row = $('<tr></tr>');
    row.append('<td></td>');
    row.append('<td></td>').append($('<button type="button">Delete</button>').click(function(){
        $(this).parent().parent().parent().parent().empty().remove();
    }));
    row.appendTo(table);
    table.appendTo(container);
    container.appendTo('#xdb-setting-db-list');
}

$(document).ready(function() {
    <?php foreach ( $dbConfig as $name => $config ) : ?>
    <?php $config = array_merge(array('name'=>$name), $config); ?>
    addDBItem(<?php echo json_encode($config)?>);
    <?php endforeach; ?>
});
</script>