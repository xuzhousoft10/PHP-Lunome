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
<form class="form-horizontal">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Relationship</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your nichname to display.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Relationship With</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your real name.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Education</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your real name.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Work</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your real name.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">School/Company</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your real name.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Living Place</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your real name.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Contact Place</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your real name.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Urgent Contacts</label>
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