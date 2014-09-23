<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
$this->addStyle('html,body,#content', array('height'=>'100%'));
?>
<div class="row lnm-height-100">
  <div class="col-md-3 col-md-offset-8" style="top:15%">
    <form action="/?module=lunome&action=user/login" method="post">
      <div class="form-group">
        <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
      </div>
      <div class="form-group">
        <div class="input-group">
          <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
          <span class="input-group-btn">
            <button class="btn btn-default" type="button">
              <span class="glyphicon glyphicon-eye-open"></span>
            </button>
          </span>
        </div>
      </div>
      <div class="btn-group btn-group-justified">
        <div class="btn-group">
          <a href="/?module=lunome&action=user/login" class="btn btn-default">Login</a>
        </div>
        <div class="btn-group">
          <button type="submit" class="btn btn-primary">Register</button>
        </div>
      </div>
    </form>
  </div>
</div>