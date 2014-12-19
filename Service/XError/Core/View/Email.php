<?php
/**
* Error report email content template.
*
* @author Michael Luthor <michaelluthor@163.com>
* @version 0.0.0
* @since Version 0.0.0
*/
$vars = get_defined_vars();
$error = $vars['error'];
?>
( <?php echo $error['number'];?> ) <?php echo $error['message'];?><?php echo "\n";?>
===============================================================
<?php echo "\n";?>
#<?php echo $error['line']; ?> <?php echo $error['file']; ?> <?php echo "\n"; ?>
===============================================================
<?php echo "\n";?>
<?php print_r($error['context']);?><?php echo "\n"; ?>
===============================================================
<?php echo "\n";?>
<?php debug_print_backtrace(); ?>