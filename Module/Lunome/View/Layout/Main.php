<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
$this->addStyle('body', array('padding-top'=>'70px'));
?>
<div class="container">
    <div class="row">
      <div class="col-md-2">
        <div class="list-group">
          <a href="#" class="list-group-item active">
            Cras justo odio
          </a>
          <a href="#" class="list-group-item">Dapibus ac facilisis in</a>
          <a href="#" class="list-group-item">Morbi leo risus</a>
          <a href="#" class="list-group-item">Porta ac consectetur ac</a>
          <a href="#" class="list-group-item">Vestibulum at eros</a>
        </div>
      </div>
      <div class="col-md-10">
        <?php foreach ( $this->particles as $particle ) : ?>
            <?php echo $particle['content'];?>
        <?php endforeach; ?>
      </div>
    </div>
</div>