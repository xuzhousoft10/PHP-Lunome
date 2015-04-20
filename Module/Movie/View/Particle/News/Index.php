<?php 
use X\Service\XView\Core\Handler\Html;
$vars = get_defined_vars();
$movie = $vars['movie'];
?>
<ol class="breadcrumb">
    <li><a href="/?module=movie&action=index">电影</a></li>
    <li><a href="/?module=movie&action=detail&id=<?php echo $movie->get('id'); ?>"><?php echo Html::HTMLEncode($movie->get('name'));?></a></li>
    <li class="active">相关新闻</li>
</ol>

<?php $news = $vars['news']; ?>
<?php if ( empty( $news ) ) : ?>
    <div class="clearfix">
        <div class="pull-left">
            <?php $assetsURL = $vars['assetsURL']; ?>
            <img src="<?php echo $assetsURL;?>/image/nothing.gif" width="100" height="100">
        </div>
        <div class="margin-top-70 text-muted">
            <small>空空的~~~</small>
        </div>
    </div>
<?php else :?>
    <ol class="">
        <?php foreach ( $news as $newsItem ): ?>
            <?php /* @var $newsItem \X\Module\Movie\Service\Movie\Core\Instance\News */ ?>
            <li>
                <p>
                    <a class="text-muted" href="<?php echo $newsItem->get('link');?>" target="_blank">
                        <?php echo Html::HTMLEncode($newsItem->get('title')); ?>
                    </a>
                    <small>
                        <img src="<?php echo $newsItem->get('logo');?>">
                        <?php echo Html::HTMLEncode($newsItem->get('source'));?>
                        <?php echo $newsItem->get('time');?>
                    </small>
                </p>
            </li>
        <?php endforeach; ?>
    </ol>
<?php endif; ?>