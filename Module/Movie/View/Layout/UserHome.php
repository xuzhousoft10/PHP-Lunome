<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
$this->getStyleManager()->add('body', array('padding-top'=>'70px'));
$vars = get_defined_vars();
?>
<div class="container">
    <div class="row">
        <?php echo $this->getParticleViewManager()->toString();?>
    </div>
</div>