<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
require dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LayoutSetup.php';
require dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'MultiColumnLayoutSetup.php';
require dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'NonFullWidthLayoutSetup.php';
?>
<?php require dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LayoutHeaderSetup.php'; ?>
<div id="content">
    <div id="content-left">
        <?php foreach ( $this->particles as $name => $particle ) : ?>
            <?php if (isset($particle['option']['zone']) && 'left' === $particle['option']['zone']) : ?>
                <?php echo $particle['content']; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div id="content-right">
        <?php foreach ( $this->particles as $name => $particle ) : ?>
            <?php if (!isset($particle['option']['zone']) || 'right' === $particle['option']['zone'] || 'content' === $particle['option']['zone'] ) : ?>
                <?php echo $particle['content']; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
<?php require dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LayoutFooterSetup.php'; ?>