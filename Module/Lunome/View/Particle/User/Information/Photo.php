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
<div style="padding:10px">
    <img src="http://www.gravatar.com/avatar/5b5854d237940c86212d59ff12590e6e?size=150" class="img-thumbnail">
</div>
<div style="padding:10px">
<form class="form-inline">
  <div class="form-group">
    <label class="sr-only" for="exampleInputPassword2">Password</label>
    <input type="file" class="" id="exampleInputPassword2">
  </div>
  <button type="submit" class="btn btn-default">Add</button>
</form>
</div>