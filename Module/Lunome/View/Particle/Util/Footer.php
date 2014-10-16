<?php
/* @var $this \X\Service\XView\Core\Handler\Html */
$this->addStyle('#footer', array('border-top'=>'1px solid #E5E5E5'));
?>
<div class="text-center" id="main-footer">
  <br/>
  <P><a href="#">Help</a> | <a href="#">About</a> | <a href="#">@Twitter</a> | <a href="#">Connect Us</a></P>
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