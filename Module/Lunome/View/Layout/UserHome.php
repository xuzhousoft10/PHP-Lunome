<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
$this->addStyle('body', array('padding-top'=>'70px'));
$vars = get_defined_vars();
?>
<div class="container">
    <div class="row">
        <?php foreach ( $this->particles as $particle ) : ?>
            <?php echo $particle['content'];?>
        <?php endforeach; ?>
    </div>
</div>