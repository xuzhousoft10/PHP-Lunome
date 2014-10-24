<?php 
$vars = get_defined_vars();
$mediaId = $vars['mediaId'];
?>
<div>
    <div>
        <h4>高:300 宽:200</h4>
        <img src="/index.php?module=backend&action=movie/poster/download&id=<?php echo $mediaId?>">
    </div>
    <br/>
    <a href="<?php echo $vars['returnURL'];?>" class="btn btn-primary">返回</a>
</div>