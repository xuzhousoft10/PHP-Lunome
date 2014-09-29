<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
$this->addStyle('html,body,#content', array('height'=>'100%'));
?>
<div class="row lnm-height-100">
  <div class="col-md-3 col-md-offset-8" style="top:15%">
    <form action="/?module=lunome&action=index" method="post">
      <div class="form-group">
        <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
      </div>
      <div class="btn-group btn-group-justified">
        <div class="btn-group">
          <a class="btn btn-default" href="/?module=lunome&action=user/login">Login</a>
        </div>
        <div class="btn-group">
          <button type="submit" class="btn btn-primary">Send</button>
        </div>
      </div>
    </form>
  </div>
</div>