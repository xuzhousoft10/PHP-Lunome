<?php /* @var $this X\Service\XView\Core\Handler\Html */ ?>
<div data-role="page" id="pageone">
  <div data-role="header">
    <h1><?php echo $this->title; ?></h1>
  </div>

  <div data-role="content">
    <?php foreach ( $this->particles as $particle ) :?>
        <?php echo $particle['content'];?>
    <?php endforeach; ?>
  </div>
  
  <div data-role="footer" data-position="fixed">
      <div data-role="navbar">
          <ul>
              <li><a href="#" data-icon="bullets"></a></li>
              <li><a href="#" data-icon="bars"></a></li>
              <li><a href="#" data-icon="user"></a></li>
          </ul>
        </div>
    </div>
</div>