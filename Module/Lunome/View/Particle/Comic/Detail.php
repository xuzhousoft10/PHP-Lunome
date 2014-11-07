<?php 
use X\Module\Lunome\Service\Comic\Service;
$vars = get_defined_vars();
$media = $vars['media'];
$markCount = $vars['markCount'];
$myMark = $vars['myMark'];
$markUrlFormat = '/?module=lunome&action=comic/mark&mark=%s&id=%s';
?>
<div class="row margin-top-5">
    <ol class="breadcrumb">
        <li><a href="/?module=lunome&action=comic/index">动漫</a></li>
        <li class="active"><?php echo $media['name'];?></li>
    </ol>
    
    <div class="col-md-2 padding-0">
        <img src="/?module=lunome&action=comic/poster&id=<?php echo $media['id']?>" width="200" height="300">
    </div>
    <div class="col-md-10">
        <h4>
            <?php echo $media['name'];?>
            <small>
                 --
                 <?php if ( Service::MARK_UNMARKED === $myMark ): ?>
                    <span class="label label-warning">未标记</span>
                <?php elseif ( Service::MARK_INTERESTED === $myMark ) : ?>
                    <span class="label label-success">想看</span>
                <?php elseif ( Service::MARK_WATCHED === $myMark ) : ?>
                    <span class="label label-info">以看</span>
                <?php elseif ( Service::MARK_IGNORED === $myMark ) : ?>
                    <span class="label label-default">不喜欢</span>
                <?php endif; ?>
            </small>
        </h4>
        <br>
        <table class="table table-bordered">
            <tr>
                <td>作者:高桥留美子</td>
                <td>地区:日本</td>
                <td>状态:未完结</td>
            </tr>
            <tr>
                <td>连载杂志:週刊少年サンデー(週刊少年Sunday)</td>
                <td>出版社:小學館</td>
                <td>连载期间:1996年11月27日－2008年6月18日</td>
                <td>出版期间:1996年－2008年</td>
            </tr>
            <tr>
                <td>集数:757集</td>
                <td>类型:推理动画，少年动画</td>
                <td>首映时间:1992-12-15</td>
            </tr>
            <tr>
                <td colspan="4">主角:犬夜叉,日暮戈薇,弥勒,珊瑚,七宝,云母</td>
            </tr>
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>
        </table>
        <div class="btn-group">
            <?php if ( Service::MARK_UNMARKED === $myMark ): ?>
                <a  class="btn btn-success" 
                    href="<?php printf($markUrlFormat, Service::MARK_INTERESTED, $media['id']); ?>"
                >想看</a>
                <a  class="btn btn-primary" 
                    href="<?php printf($markUrlFormat, Service::MARK_WATCHED, $media['id']); ?>"
                >在看</a>
                <a  class="btn btn-info" 
                    href="<?php printf($markUrlFormat, Service::MARK_WATCHED, $media['id']); ?>"
                >已看</a>
                <a  class="btn btn-default" 
                    href="<?php printf($markUrlFormat, Service::MARK_IGNORED, $media['id']); ?>"
                >不喜欢</a>
            <?php elseif ( Service::MARK_INTERESTED === $myMark ) : ?>
                <a  class="btn btn-warning" 
                    href="<?php printf($markUrlFormat, Service::MARK_UNMARKED, $media['id']); ?>"
                >不想看了</a>
                <a  class="btn btn-primary" 
                    href="<?php printf($markUrlFormat, Service::MARK_WATCHED, $media['id']); ?>"
                >在看</a>
                <a  class="btn btn-info" 
                    href="<?php printf($markUrlFormat, Service::MARK_WATCHED, $media['id']); ?>"
                >已看</a>
                <a  class="btn btn-default" 
                    href="<?php printf($markUrlFormat, Service::MARK_IGNORED, $media['id']); ?>"
                >不喜欢</a>
            <?php elseif ( Service::MARK_WATCHING === $myMark ) : ?>
                <a  class="btn btn-info" 
                    href="<?php printf($markUrlFormat, 'tv', Service::MARK_WATCHED, $media['id']); ?>"
                >看完了</a>
                <a  class="btn btn-default" 
                    href="<?php printf($markUrlFormat, 'tv', Service::MARK_IGNORED, $media['id']); ?>"
                >不喜欢</a>
            <?php elseif ( Service::MARK_WATCHED === $myMark ) : ?>
                <a  class="btn btn-success" 
                    href="<?php printf($markUrlFormat, Service::MARK_INTERESTED, $media['id']); ?>"
                >想看</a>
                <a  class="btn btn-default" 
                    href="<?php printf($markUrlFormat, Service::MARK_IGNORED, $media['id']); ?>"
                >不喜欢</a>
            <?php elseif ( Service::MARK_IGNORED === $myMark ) : ?>
                <a  class="btn btn-success" 
                    href="<?php printf($markUrlFormat, Service::MARK_INTERESTED, $media['id']); ?>"
                >想看</a>
                <a  class="btn btn-info" 
                    href="<?php printf($markUrlFormat, Service::MARK_WATCHED, $media['id']); ?>"
                >已看</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<hr>
<div class="margin-top-5">
    <p>
        正在读初中三年级的女生——日暮戈薇是一名家住在神社的普通女孩子。
        15岁生日那天，被妖怪──百足妖妇拖入神社的枯井中，来到了500年前的日本战国时代，
        并与已被封印的半妖——犬夜叉相遇。犬夜叉自称是为得到四魂之玉以成为真正的妖怪，
        实际上并非完全如此。来到战国时代的戈薇被误认为已逝世的巫女桔梗，而后因其灵力、
        相貌及体内的四魂之玉而令众人认为是桔梗的转世。由于戈薇唤醒了犬夜叉又不小心将
        四魂之玉用箭射碎，并散至各地。为了收集四魂之玉碎片，犬夜叉与戈薇踏上了旅途，
        而与犬夜叉以及后来出现的小妖狐七宝、法师弥勒和除妖师珊瑚一同搜集四魂之玉。而
        且各人都是向著同样是收集四魂之玉的妖怪——奈落为目标进发。
    </p>
</div>