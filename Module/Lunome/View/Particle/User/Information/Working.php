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
<div style="padding: 10px;">
    <div class="alert alert-info">
        <strong>2012-12-25 ~ 2014-04-01</strong><br/>
        Programmer @ Xuzhousoft Co. Ltd.<br/>
        <br/>
        <button>Delete</button>
    </div>
    <div class="alert alert-info">
        <strong>2012-12-25 ~ 2014-04-01</strong><br/>
        Programmer @ Xuzhousoft Co. Ltd.<br/>
        <br/>
        <button>Delete</button>
    </div>
    <div class="alert alert-info">
        <strong>2012-12-25 ~ 2014-04-01</strong><br/>
        Programmer @ Xuzhousoft Co. Ltd.<br/>
        <br/>
        <button>Delete</button>
    </div>
</div>

<form class="form-horizontal">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Company</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your nichname to display.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Position</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your real name.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
    <div class="col-sm-8">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your real name.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
    <div class="col-sm-2">
      <div class="checkbox">
        <label>
          <input type="checkbox"> I still here
        </label>
      </div>
    </div>
    <div class="col-sm-6">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Your real name.">
    </div>
    <div class="col-sm-2">
      <input type="checkbox" name="my-checkbox" class="user-info-switch" checked />
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">Add</button>
    </div>
  </div>
</form>