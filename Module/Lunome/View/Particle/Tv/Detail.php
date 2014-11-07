<?php 
use X\Module\Lunome\Service\Movie\Service;
$vars = get_defined_vars();
$media = $vars['media'];
$markCount = $vars['markCount'];
$myMark = $vars['myMark'];
$markUrlFormat = '/?module=lunome&action=%s/mark&mark=%s&id=%s';
?>
<div class="row margin-top-5">
    <ol class="breadcrumb">
        <li><a href="/?module=lunome&action=movie/index">电影</a></li>
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
                <td>集数: 216集</td>
                <td>每集长度: 42~50分钟</td>
                <td>季数：10季</td>
                <td>首播：2014-12-15</td>
            </tr>
            <tr>
                <td>区域: 美国</td>
                <td>语言：英语</td>
                <td>类型: 科幻，爱情，动作</td>
                <td></td>
            </tr>
            <tr>
                <td>导演: 速度发速度否</td>
                <td>编剧: 阿斯顿否</td>
                <td>制片人: 玩儿额外人</td>
                <td>监制:大法官大概</td>
            </tr>
            <tr>
                <td colspan="4">主演： 阿斯官方撒否撒旦否撒旦否</td>
            </tr>
            <tr>
                <td>全网</td>
                <td>想看: 1111</td>
                <td>已看: 2222</td>
                <td>不喜欢: 33333</td>
            </tr>
        </table>
        <div class="btn-group">
            <?php if ( Service::MARK_UNMARKED === $myMark ): ?>
                <a  class="btn btn-success" 
                    href="<?php printf($markUrlFormat, 'movie', Service::MARK_INTERESTED, $media['id']); ?>"
                >想看</a>
                <a  class="btn btn-info" 
                    href="<?php printf($markUrlFormat, 'movie', Service::MARK_WATCHED, $media['id']); ?>"
                >已看</a>
                <a  class="btn btn-default" 
                    href="<?php printf($markUrlFormat, 'movie', Service::MARK_IGNORED, $media['id']); ?>"
                >不喜欢</a>
            <?php elseif ( Service::MARK_INTERESTED === $myMark ) : ?>
                <a  class="btn btn-warning" 
                    href="<?php printf($markUrlFormat, 'movie', Service::MARK_UNMARKED, $media['id']); ?>"
                >不想看了</a>
                <a  class="btn btn-info" 
                    href="<?php printf($markUrlFormat, 'movie', Service::MARK_WATCHED, $media['id']); ?>"
                >已看</a>
                <a  class="btn btn-default" 
                    href="<?php printf($markUrlFormat, 'movie', Service::MARK_IGNORED, $media['id']); ?>"
                >不喜欢</a>
            <?php elseif ( Service::MARK_WATCHED === $myMark ) : ?>
                <a  class="btn btn-success" 
                    href="<?php printf($markUrlFormat, 'movie', Service::MARK_INTERESTED, $media['id']); ?>"
                >想看</a>
                <a  class="btn btn-default" 
                    href="<?php printf($markUrlFormat, 'movie', Service::MARK_IGNORED, $media['id']); ?>"
                >不喜欢</a>
            <?php elseif ( Service::MARK_IGNORED === $myMark ) : ?>
                <a  class="btn btn-success" 
                    href="<?php printf($markUrlFormat, 'movie', Service::MARK_INTERESTED, $media['id']); ?>"
                >想看</a>
                <a  class="btn btn-info" 
                    href="<?php printf($markUrlFormat, 'movie', Service::MARK_WATCHED, $media['id']); ?>"
                >已看</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<hr>
<div class="margin-top-5">
    <p>
        《超人前传》是美国华纳兄弟公司旗下电视台播出的电视剧。
        1989年10月发生的一场流星雨改变了小镇所有人的命运，从此镇上开始有许多奇怪的事情发生。
        一艘承载着一个小男孩的外星飞船伴随着流星雨降落在了玉米田里，小男孩走出飞船，
        走向了因流星雨袭击翻倒的卡车里的肯特夫妇。
        多年后肯特夫妇的养子克拉克在成长过程中渐渐认识到了自己的宿命。
        本剧主要讲述了男主角克拉克·肯特成为超人之前的经历。全剧已于2011年剧终，共有10季。
    </p>
</div>