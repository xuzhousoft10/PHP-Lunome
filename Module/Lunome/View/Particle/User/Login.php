<?php 
/* @var $this X\Service\XView\Core\Handler\Html */
$vars = get_defined_vars();
$backgroundImage = $vars['backgroundImage'];
$QQLoginIcon = $vars['QQLoginIcon'];
$this->addStyle('body', array(
    'background'=>'url('.$backgroundImage.')', 
    'background-size'=>'100% auto'));
?>
<div class="container-fluid margin-top-100">
    <div class="row">
        <div class="col-md-8"></div>
        <div class="col-md-4">
            <a href="/index.php?module=lunome&action=user/login/qq">
                <img src="<?php echo $QQLoginIcon; ?>">
            </a>
        </div>
    </div>
</div>