<?php 
$vars = get_defined_vars();
$media = $vars['media'];
$mediaType = $vars['mediaType'];
$mediaName = $vars['mediaName'];
$markCount = $vars['markCount'];
$myMark = $vars['myMark'];
$markStyles = $vars['markStyles'];
$markNames = $vars['markNames'];
$markUrlFormat = sprintf('/?module=lunome&action=%s/mark&mark=%%s&id=%%s', strtolower($mediaType));
?>
<div class="row margin-top-5">
    <ol class="breadcrumb">
        <li><a href="/?module=lunome&action=<?php echo strtolower($mediaType)?>/index"><?php echo $mediaName;?></a></li>
        <li class="active"><?php echo $media['name'];?></li>
    </ol>
    
    <div class="col-md-2 padding-0">
        <img src="<?php echo $media['cover'];?>" width="200" height="300">
    </div>
    <div class="col-md-10">
        <h4>
            <?php echo $media['name'];?>
            <small>
                 --
                 <span class="label label-<?php echo $markStyles[$myMark];?>">
                    <?php echo $markNames[$myMark];?>
                 </span>
            </small>
        </h4>
        <br>
        <table class="table table-bordered">
            <?php require dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.$mediaType.DIRECTORY_SEPARATOR.'Detail.php';?>
        </table>
        <div class="btn-group">
            <?php foreach ( $markNames as $markKey => $markName ) : ?>
                <?php if ( 0 === $markKey || $myMark === $markKey ) :?>
                    <?php continue; ?>
                <?php endif; ?>
                <a  class="btn btn-<?php echo $markStyles[$markKey];?>" 
                    href="<?php printf($markUrlFormat, $markKey, $media['id']); ?>"
                ><?php echo $markName;?></a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<hr>
<div class="margin-top-5">
    <p><?php echo $media['introduction'];?></p>
</div>