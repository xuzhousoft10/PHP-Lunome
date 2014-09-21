<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
$this->addStyle('body', array('margin'=>'0'));

$this->addStyle('#content', array(
    'margin-right'  => 'auto',
    'margin-left'   => 'auto',
    'padding-left'  => '15px',
    'padding-right' => '15px',
));

$this->addStyle('#content', array('width' => '750px'), '(min-width:768px)');
$this->addStyle('#content', array('width' => '970px'), '(min-width:992px)');
$this->addStyle('#content', array('width' => '1170px'), '(min-width:1200px)');
?>