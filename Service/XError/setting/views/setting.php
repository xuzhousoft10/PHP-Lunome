<?php 
/**
 * The setting view of XError
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
$vars = get_defined_vars();
$config = $vars['config'];
?>
<hr/>
<form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post">
<table>
    <tr>
        <td>Handler</td>
        <td>
            <select name="config[ErrorReport][handler]" id="config-ErrorReport-handler">
                <option value="Default">Default
                <option value="Tracker">Tracker
            </select>
        </td>
    </tr>
</table>
<hr/>
<table>
    <tr>
        <td>Email Error</td>
        <td></td>
    </tr>
    <tr>
        <td>Status</td>
        <td>
            <input type="hidden" name="config[EmailError][status]" value="off">
            <?php if ( 'on' == $config['EmailError']['status'] ) : ?>
            <input type="checkbox" name="config[EmailError][status]" value="on" checked>
            <?php else : ?>
            <input type="checkbox" name="config[EmailError][status]" value="on">
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>Recipients</td>
        <td>
            <textarea name="config[EmailError][recipients]"><?php echo $config['EmailError']['recipients'];?></textarea>
        </td>
    </tr>
    <tr>
        <td>Server</td>
        <td><input type="text" name="config[EmailError][server]" value="<?php echo $config['EmailError']['server'];?>"></td>
    </tr>
    <tr>
        <td>Port</td>
        <td><input type="text" name="config[EmailError][port]" value="<?php echo $config['EmailError']['port'];?>"></td>
    </tr>
    <tr>
        <td>From</td>
        <td><input type="text" name="config[EmailError][from]" value="<?php echo $config['EmailError']['from'];?>"></td>
    </tr>
    <tr>
        <td>Name</td>
        <td><input type="text" name="config[EmailError][name]" value="<?php echo $config['EmailError']['name'];?>"></td>
    </tr>
    <tr>
        <td>Username</td>
        <td><input type="text" name="config[EmailError][username]" value="<?php echo $config['EmailError']['username'];?>"></td>
    </tr>
    <tr>
        <td>Password</td>
        <td><input type="text" name="config[EmailError][password]" value="<?php echo $config['EmailError']['password'];?>"></td>
    </tr>
</table>
<input type="hidden" name="config[EmailError][handler]" value="smtp" />

<hr/>
<button type="submit">Save</button>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $('#config-ErrorReport-handler').val('<?php echo $config['ErrorReport']['handler'];?>');
});
</script>