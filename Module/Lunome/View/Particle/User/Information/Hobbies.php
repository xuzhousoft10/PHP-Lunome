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
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
    <span class="label label-success" style="line-height: 3em;">Success <span class="badge">x</span></span>
</div>
<div style="padding:10px">
<form class="form-inline">
  <div class="form-group">
    <label class="sr-only" for="exampleInputPassword2">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password">
  </div>
  <button type="submit" class="btn btn-default">Add</button>
</form>
</div>