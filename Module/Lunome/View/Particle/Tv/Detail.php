<?php 
use X\Module\Lunome\Service\Tv\Service;
$vars = get_defined_vars();
$media = $vars['media'];
$markCount = $vars['markCount'];
$myMark = $vars['myMark'];
$markUrlFormat = '/?module=lunome&action=%s/mark&mark=%s&id=%s';
?>
<div class="row margin-top-5">
    <ol class="breadcrumb">
        <li><a href="/?module=lunome&action=tv/index">电视剧</a></li>
        <li class="active"><?php echo $media['name'];?></li>
    </ol>
    
    <div class="col-md-2 padding-0">
        <img src="/?module=lunome&action=tv/poster&id=<?php echo $media['id']?>" width="200" height="300">
    </div>
    <div class="col-md-10">
        <h4>
            <?php echo $media['name'];?>
            <small>
                 --
                 <?php if ( Service::MARK_UNMARKED === $myMark ): ?>
                    <span class="label label-warning">未标记</span>
                <?php elseif ( Service::MARK_WATCHING === $myMark ) : ?>
                    <span class="label btn-primary">在看</span>
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
                <td>集数: <?php echo $media['episode_count'];?>集</td>
                <td>每集长度: <?php echo $media['episode_length'];?>分钟</td>
                <td>季数：<?php echo $media['season_count'];?>季</td>
                <td>首播：<?php echo $media['premiered_at'];?></td>
            </tr>
            <tr>
                <td>区域: <?php echo $media['region'];?></td>
                <td>语言：<?php echo $media['language'];?></td>
                <td>类型: <?php echo $media['category'];?></td>
                <td></td>
            </tr>
            <tr>
                <td>导演: <?php echo $media['director'];?></td>
                <td>编剧: <?php echo $media['writer'];?></td>
                <td>制片人: <?php echo $media['producer'];?></td>
                <td>监制: <?php echo $media['executive'];?></td>
            </tr>
            <tr>
                <td colspan="4">主演： <?php echo $media['actor'];?></td>
            </tr>
            <tr>
                <td>想看: <?php echo $markCount[Service::MARK_INTERESTED];?></td>
                <td>在看: <?php echo $markCount[Service::MARK_WATCHING];?></td>
                <td>已看: <?php echo $markCount[Service::MARK_WATCHED];?></td>
                <td>不喜欢: <?php echo $markCount[Service::MARK_IGNORED];?></td>
            </tr>
        </table>
        <div class="btn-group">
            <?php if ( Service::MARK_UNMARKED === $myMark ): ?>
                <a  class="btn btn-success" 
                    href="<?php printf($markUrlFormat, 'tv', Service::MARK_INTERESTED, $media['id']); ?>"
                >想看</a>
                <a  class="btn btn-primary" 
                    href="<?php printf($markUrlFormat, 'tv', Service::MARK_WATCHING, $media['id']); ?>"
                >在看</a>
                <a  class="btn btn-info" 
                    href="<?php printf($markUrlFormat, 'tv', Service::MARK_WATCHED, $media['id']); ?>"
                >已看</a>
                <a  class="btn btn-default" 
                    href="<?php printf($markUrlFormat, 'tv', Service::MARK_IGNORED, $media['id']); ?>"
                >不喜欢</a>
            <?php elseif ( Service::MARK_INTERESTED === $myMark ) : ?>
                <a  class="btn btn-warning" 
                    href="<?php printf($markUrlFormat, 'tv', Service::MARK_UNMARKED, $media['id']); ?>"
                >不想看了</a>
                <a  class="btn btn-primary" 
                    href="<?php printf($markUrlFormat, 'tv', Service::MARK_WATCHING, $media['id']); ?>"
                >在看</a>
                <a  class="btn btn-info" 
                    href="<?php printf($markUrlFormat, 'tv', Service::MARK_WATCHED, $media['id']); ?>"
                >已看</a>
                <a  class="btn btn-default" 
                    href="<?php printf($markUrlFormat, 'tv', Service::MARK_IGNORED, $media['id']); ?>"
                >不喜欢</a>
            <?php elseif ( Service::MARK_WATCHING === $myMark ) : ?>
                <a  class="btn btn-info" 
                    href="<?php printf($markUrlFormat, 'tv', Service::MARK_WATCHED, $media['id']); ?>"
                >已看</a>
                <a  class="btn btn-default" 
                    href="<?php printf($markUrlFormat, 'tv', Service::MARK_IGNORED, $media['id']); ?>"
                >不喜欢</a>
            <?php elseif ( Service::MARK_WATCHED === $myMark ) : ?>
                <a  class="btn btn-success" 
                    href="<?php printf($markUrlFormat, 'tv', Service::MARK_INTERESTED, $media['id']); ?>"
                >想看</a>
                <a  class="btn btn-default" 
                    href="<?php printf($markUrlFormat, 'tv', Service::MARK_IGNORED, $media['id']); ?>"
                >不喜欢</a>
            <?php elseif ( Service::MARK_IGNORED === $myMark ) : ?>
                <a  class="btn btn-success" 
                    href="<?php printf($markUrlFormat, 'tv', Service::MARK_INTERESTED, $media['id']); ?>"
                >想看</a>
                <a  class="btn btn-primary" 
                    href="<?php printf($markUrlFormat, 'tv', Service::MARK_WATCHING, $media['id']); ?>"
                >在看</a>
                <a  class="btn btn-info" 
                    href="<?php printf($markUrlFormat, 'tv', Service::MARK_WATCHED, $media['id']); ?>"
                >已看</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<hr>
<div class="margin-top-5">
    <p><?php echo $media['introduction'];?></p>
</div>