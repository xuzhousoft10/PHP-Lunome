<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
require dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LayoutSetup.php';
require dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'NonFullWidthLayoutSetup.php';
?>
<?php require dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LayoutHeaderSetup.php'; ?>
<div id="content">
<?php foreach ( $this->particles as $name => $particle ) :?>
    <?php if (!isset($particle['option']['zone']) || 'content' === $particle['option']['zone']): ?>
        <?php echo $particle['content'];?>
    <?php endif; ?>
<?php endforeach; ?>
</div>
<?php require dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LayoutFooterSetup.php'; ?>