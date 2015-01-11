<?php 
use X\Core\X;

/* @var $this \X\Service\XView\Core\Handler\Html */
$assetsURL = X::system()->getConfiguration()->get('assets-base-url');
$this->addCssLink('ERROR-404-CSS', $assetsURL.'/css/404.css');
$this->addScriptFile('JQUERY', $assetsURL.'/library/jquery/jquery-1.11.1.min.js');
$this->addScriptFile('JQUERY-TYPETYPE', $assetsURL.'/library/jquery/plugin/typetype.js');
$this->addScriptFile('ERROR-404', $assetsURL.'/js/error_404.js');
$this->addStyle('body', array('background-image'=>'url('.$assetsURL.'/image/404.jpg)', 'background-size'=>'100%'));
$this->title = "404 内容无法找到～～～";
?>
<div class="type-area-container">
<textarea class="type-area"></textarea>
</div>
<div class="type-area-container">
<a href="/">返回首页</a>
</div>