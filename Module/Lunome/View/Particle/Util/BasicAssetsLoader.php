<?php 
/* @var $this \X\Service\XView\Core\Util\HtmlView\ParticleView */ 
$scriptManager = $this->getManager()->getHost()->getScriptManager();
$scriptManager->add('jquery')->setSource('library/jquery/jquery-1.11.1.min.js');
$scriptManager->add('bootstrap')->setSource('library/bootstrap/js/bootstrap.min.js')->setRequirements('jquery');
$scriptManager->add('waypoints')->setSource('library/jquery/plugin/waypoints.js')->setRequirements('jquery');
$scriptManager->add('application')->setSource('js/application.js')->setRequirements('jquery');

$linkManager = $this->getManager()->getHost()->getLinkManager();
$linkManager->addCSS('bootstrap', 'library/bootstrap/css/bootstrap.min.css');
$linkManager->addCSS('bootstrap-theme','library/bootstrap/css/bootstrap-theme.min.css');
$linkManager->addCSS('application','css/application.css');
$linkManager->addCSS('bootstrap-ext','css/bootstrap-ext.css');
?>