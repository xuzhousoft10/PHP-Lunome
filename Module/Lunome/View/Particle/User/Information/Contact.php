<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
$this->addStyle('#content-right', array(
    'width' => '80%',
    'float' => 'left',
));
$this->addScriptFile('bootstrap-switch', '/Assets/library/bootstrap-switch/js/bootstrap-switch.js');
$this->addCssLink('bootstrap-switch', '/Assets/library/bootstrap-switch/css/bootstrap-switch.css');
$this->addScriptString('user-info-init-switch', '$(document).ready(function(){$(".user-info-switch").bootstrapSwitch({onText:"Public", offText:"Private"});});');
?>
<form class="form-horizontal" role="form">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your nichname to display.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Phone</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your real name.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Facebook</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your real name.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Twtter</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your real name.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Google Plus</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your real name.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">AIM</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your real name.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">ICQ</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your real name.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">SKYPE</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your real name.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">MSN</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your real name.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Home Page</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your real name.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">Save</button>
    </div>
  </div>
</form>