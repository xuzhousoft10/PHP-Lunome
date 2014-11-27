<?php 
use X\Module\Lunome\Service\Movie\Service as MovieService;

$vars = get_defined_vars();
$media = $vars['media'];
$mediaType = $vars['mediaType'];
$mediaName = $vars['mediaName'];
$markCount = $vars['markCount'];
$myMark = $vars['myMark'];
$markStyles = $vars['markStyles'];
$markNames = $vars['markNames'];
$markUrlFormat = sprintf('/?module=lunome&action=%s/mark&mark=%%s&id=%%s', strtolower($mediaType));
if ( MovieService::MARK_INTERESTED == $myMark ) {
    $shareMessageTitle = '求包养';
    $shareMessageContent = "怀着各种复杂与激动的心情， 我来到了这里， 我抬头， 望了望天，想起了你，此时此刻， 我的心情不是别人所能理解的，土豪，请我看场《{$media['name']}》呗？";
} else if ( MovieService::MARK_WATCHED == $myMark ) {
    $shareMessageTitle = '推荐给好友';
    $shareMessageContent = "看完《{$media['name']}》， 我和我的小伙伴们都惊呆了！ GO！ GO! GO! ";
} else {
    $shareMessageTitle = '分享';
    $shareMessageContent = "";
}
?>
<div class="row margin-top-5">
    <ol class="breadcrumb">
        <li><a href="/?module=lunome&action=<?php echo strtolower($mediaType)?>/index"><?php echo $mediaName;?></a></li>
        <li class="active"><?php echo $media['name'];?></li>
    </ol>
    
    <div class="col-md-2 padding-0">
        <a href="http://www.baidu.com/s?wd=<?php echo urlencode($media['name']); ?>" target="_black">
            <img src="<?php echo $media['cover'];?>" width="200" height="300">
        </a>
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
        <div class="clearfix">
            <div class="btn-group pull-left">
                <?php foreach ( $markNames as $markKey => $markName ) : ?>
                    <?php if ( 0 === $markKey || $myMark === $markKey ) :?>
                        <?php continue; ?>
                    <?php endif; ?>
                    <a  class="btn btn-<?php echo $markStyles[$markKey];?>" 
                        href="<?php printf($markUrlFormat, $markKey, $media['id']); ?>"
                    ><?php echo $markName;?></a>
                <?php endforeach; ?>
            </div>
            <div class="pull-right text-right">
                <span class="pull-left" style="line-height: 30px;"><?php echo $shareMessageTitle; ?>&nbsp;<span class="glyphicon glyphicon-hand-right"></span>&nbsp;</span>
                <div class = "pull-left lnm-qzone-share-container" >
                    <script type="text/javascript">
                    (function(){
                    var p = {
                        url         : location.href,
                        showcount   : '0',
                        desc        : '<?php echo $shareMessageContent;?>',
                        summary     : <?php echo json_encode(array('message'=>$media['introduction']));?>.message,
                        title       : '<?php echo $media['name'];?>',
                        site        : 'Lunome',
                        pics        : '<?php echo $media['cover'];?>',
                        style       : '202',
                        width       : 24,
                        height      : 24
                    };
                    
                    var s = [];
                    for(var i in p){
                    s.push(i + '=' + encodeURIComponent(p[i]||''));
                    }
                    document.write(['<a version="1.0" class="qzOpenerDiv" href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?',s.join('&'),'" target="_blank">分享</a>'].join(''));
                    })();
                    </script>
                    <script src="http://qzonestyle.gtimg.cn/qzone/app/qzlike/qzopensl.js#jsdate=20111201" charset="utf-8"></script>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="margin-top-5">
    <p><?php echo $media['introduction'];?></p>
</div>