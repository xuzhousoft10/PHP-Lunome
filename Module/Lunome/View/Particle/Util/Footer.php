<?php
/* @var $this \X\Service\XView\Core\Handler\Html */
$this->addStyle('#footer', array('border-top'=>'1px solid #E5E5E5'));
?>
<div class="text-center" id="main-footer">
    <br/>
    <P>
        <a href="/index.php?module=lunome&action=advise">意见建议</a> 
        |
        <a href="/index.php?module=lunome&action=connectus">联系我们</a> 
  Help | <a href="#">About</a> | <a href="#">@Twitter</a> | <a href="#">Connect Us</a></P>
</div>
<br/><br/>
<script>
$(document).ready(function() {
    var top = $('#main-footer')[0].offsetTop;
    var height = $('#main-footer')[0].clientHeight;
    var windowHeight = $(window).height();

    if ( top + height < windowHeight ) {
       $('#main-footer').addClass('navbar-fixed-bottom');
    }
});
</script>