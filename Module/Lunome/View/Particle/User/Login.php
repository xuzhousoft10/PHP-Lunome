<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
?>
<div class="row lnm-height-100">
  <div class="col-md-3 col-md-offset-8" style="top:15%">
    <form action="/?module=lunome&action=index" method="post">
      <div class="form-group">
        <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
      </div>
      <div class="form-group">
        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
      </div>
      <div class="form-group">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Verify code">
          <span class="input-group-addon"><img src="Assets/image/verify-code-pic.png" height="20" /></span>
          <span class="input-group-btn">
            <button class="btn btn-default" type="button">
              <span class="glyphicon glyphicon-refresh"></span>
            </button>
          </span>
        </div>
      </div>
      <div class="btn-group btn-group-justified">
        <div class="btn-group">
          <a class="btn btn-default" href="/?module=lunome&action=user/register">Register</a>
        </div>
        <div class="btn-group">
          <button type="submit" class="btn btn-primary">Login</button>
        </div>
      </div>
    </form>
  </div>
</div>