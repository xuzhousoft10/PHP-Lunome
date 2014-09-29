<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
$this->addStyle('#userboard', array(
    'margin-right'  => 'auto',
    'margin-left'   => 'auto',
));
$this->addStyle('#userboard', array('width' => '720px'), '(min-width:768px)');
$this->addStyle('#userboard', array('width' => '940px'), '(min-width:992px)');
$this->addStyle('#userboard', array('width' => '1140px'), '(min-width:1200px)');
?>
<div id="userboard" class="jumbotron" style="background-image: url('/Assets/image/userboard.jpg');background-size: 100% 100%;height: 250px;">
  <div style="position: relative;top: 60px;">
    <div class="pull-left">
      <img src="http://www.gravatar.com/avatar/5b5854d237940c86212d59ff12590e6e?size=150" class="img-thumbnail">
    </div>
    <div class="pull-left" style="padding-left: 10px;position: relative;top: 70px;">
      <p><strong>Michael Luthor</strong></p>
      <div>
        <strong>
          Movie (1268) | TV (546) | Book (48913) | Footprint (5794) 
        </strong>
      </div>
    </div>
  </div>
</div>
