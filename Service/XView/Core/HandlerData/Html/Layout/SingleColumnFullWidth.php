<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
require dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LayoutSetup.php';
?>
<?php require dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LayoutHeaderSetup.php'; ?>
<div id="content">
<?php foreach ( $this->particles as $name => $particle ) :?>
    <?php echo $particle['content'];?>
<?php endforeach; ?>
</div>
<?php require dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LayoutFooterSetup.php'; ?>