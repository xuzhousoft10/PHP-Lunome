<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
$this->addStyle('#content-left', array(
    'width' => '20%',
    'float' => 'left',
));
?>

<div class="list-group">
  <a href="/?module=lunome&action=user/information/basic" class="list-group-item">Basic</a>
  <a href="/?module=lunome&action=user/information/photo" class="list-group-item">Photo</a>
  <a href="/?module=lunome&action=user/information/status" class="list-group-item">Status</a>
  <a href="/?module=lunome&action=user/information/contact" class="list-group-item">Contact</a>
  <a href="/?module=lunome&action=user/information/working" class="list-group-item">Working History</a>
  <a href="/?module=lunome&action=user/information/education" class="list-group-item">Education History</a>
  <a href="/?module=lunome&action=user/information/lived" class="list-group-item">Lived Places</a>
  <a href="/?module=lunome&action=user/information/hobbies" class="list-group-item">Hobbies</a>
</div>