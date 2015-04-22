<?php use X\Service\XView\Core\Handler\Html; ?>
<?php $vars = get_defined_vars(); ?>
<?php $movie = $vars['movie']; ?>
<div class="panel panel-default">
    <div class="panel-heading">分享</div>
    <div class="panel-body">
        <div class="bshare-custom icon-medium">
            <a title="分享到QQ空间" class="bshare-qzone"></a>
            <a title="分享到新浪微博" class="bshare-sinaminiblog"></a>
            <a title="分享到人人网" class="bshare-renren"></a>
            <a title="分享到腾讯微博" class="bshare-qqmb"></a>
            <a title="分享到微信" class="bshare-weixin" href="javascript:void(0);"></a>
            <a title="更多平台" class="bshare-more bshare-more-icon more-style-addthis"></a>
        </div>
        <script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/buttonLite.js#style=-1&amp;uuid=912a0ba7-c53e-4898-a26e-e9eb9366e3ff&amp;pophcol=2&amp;lang=zh"></script>
        <script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/bshareC0.js"></script>
        <script type="text/javascript" charset="utf-8">
        bShare.addEntry({
            title: <?php echo Html::JavascriptValueEncode($movie->get('name')); ?>,
            url: "http://<?php echo $_SERVER['HTTP_HOST']?>/?module=movie&action=detail&id=<?php echo $movie->get('id');?>",
            summary: <?php echo Html::JavascriptValueEncode($movie->get('introduction'));?>,
            pic: "<?php echo $movie->getCoverURL();?>"
        });
        </script>
    </div>
</div>