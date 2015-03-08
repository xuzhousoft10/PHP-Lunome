<?php 
use X\Core\X;

/* @var $this \X\Service\XView\Core\Util\HtmlView\ParticleView */
$assetsURL = X::system()->getConfiguration()->get('assets-base-url');
$view = $this->getManager()->getHost();

$view->getLinkManager()->addCSS('ERROR-404-CSS', $assetsURL.'/css/404.css');
$view->getStyleManager()->add('body', array('background-image'=>'url('.$assetsURL.'/image/404.jpg)', 'background-size'=>'100%'));
$view->getScriptManager()->addFile('JQUERY', $assetsURL.'/library/jquery/jquery-1.11.1.min.js');
$view->getScriptManager()->addFile('JQUERY-TYPETYPE', $assetsURL.'/library/jquery/plugin/typetype.js');
$view->getScriptManager()->addFile('ERROR-404', $assetsURL.'/js/error_404.js');

$this->title = "404 内容无法找到～～～";
?>
<div class="type-area-container">
<textarea class="type-area"></textarea>
</div>
<div class="type-area-container">
<a href="/">返回首页</a>
</div>