<?php use X\Core\X; ?>
<?php $vars = get_defined_vars(); ?>
<?php $assetsURL = X::system()->getConfiguration()->get('assets-base-url'); ?>
<?php $accounts = $vars['accounts']; ?>
<?php $sexMap = array(''=>'＊', '0'=>'＊','1'=>'♂','2'=>'♀','3'=>'？'); ?>
<?php $sexNameMap = array(''=>'保密', '0'=>'保密','1'=>'男','2'=>'女','3'=>'其他'); ?>
<?php $sexualitysMap = array(''=>'保密', '0'=>'保密','1'=>'异性','2'=>'同性','3'=>'双性','4'=>'无性','5'=>'二禁'); ?>
<?php $emotionStatusMap = array(''=>'保密', '0'=>'保密','1'=>'单身','2'=>'热恋中','3'=>'同居','4'=>'已订婚','5'=>'已婚','6'=>'分居','7'=>'离异','8'=>'很难说','9'=>'其他'); ?>
<?php $listID = 'marked-user-list-'.uniqid(); ?>
<?php if ( empty($accounts) ): ?>
    <div class="clearfix">
        <div class="pull-left">
            <img src="<?php echo $assetsURL;?>/image/nothing.gif" width="100" height="100">
        </div>
        <div class="margin-top-70 text-muted">
            <small>还被标记过～～～</small>
        </div>
    </div>
<?php else : ?>
<div id="<?php echo $listID; ?>">
    <?php foreach ( $accounts as $account ) : ?>
        <?php printf('<a href="/?module=lunome&action=movie/home/index&id=%s" target="_blank">', $account->account_id); ?>
        <div class="thumbnail margin-bottom-5 clearfix">
            <div class="pull-left">
                <img class="thumbnail padding-0" src="<?php echo $account->photo; ?>" width="60" height="60">
            </div>
            <div class="pull-left padding-left-10">
                <span class="text-info" title="性别:<?php echo $sexNameMap[$account->sex]; ?>"><?php echo $sexMap[$account->sex];?></span>
                <strong><?php echo $account->nickname; ?></strong><br><br>
                <small>
                    <span class="glyphicon glyphicon-heart"></span>
                    <?php echo $sexualitysMap[$account->sexuality];?>
                    <span class="glyphicon glyphicon-user"></span>
                    <?php echo $emotionStatusMap[$account->emotion_status];?>
                </small>
            </div>
        </div>
        <?php echo '</a>'; ?>
    <?php endforeach; ?>
    
    <?php $pager = $vars['pager']; ?>
    <?php $pagerParms = array('id'=>$vars['id'], 'mark'=>$vars['mark'], 'scope'=>$vars['scope']); ?>
    <?php $pagerParms = http_build_query($pagerParms);?>
    <?php if ( false !== $pager['prev'] || false !== $pager['next'] ) : ?>
    <div>
        <nav>
            <ul class="pager">
                <li class="previous<?php echo (false === $pager['prev']) ? ' disabled' : ''; ?>">
                    <?php if (false !== $pager['prev']) :?>
                        <a  href="/?module=lunome&action=movie/markedUserList&<?php echo $pagerParms; ?>&page=<?php echo $pager['prev'];?>"
                            class="marked-user-list-pager"
                        >&larr; 上一页</a>
                    <?php endif; ?>
                </li>
                <li class="next<?php echo (false === $pager['next']) ? ' disabled' : ''; ?>">
                    <?php if (false !== $pager['next']) :?>
                        <a  href="/?module=lunome&action=movie/markedUserList&<?php echo $pagerParms; ?>&page=<?php echo $pager['next'];?>"
                            class="marked-user-list-pager"
                        >下一页&rarr;</a>
                    <?php endif; ?>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    $('.marked-user-list-pager').click(function() {
        $('#<?php echo $listID;?>').addClass('text-center').empty()
        .html('<img src="<?php echo $assetsURL;?>/image/loadding.gif">');

        var $this = $(this);
        $.get($this.attr('href'), {}, function( response ) {
            var container = $('#<?php echo $listID;?>').parent();
            console.log(container);
            container.removeClass('text-center').html(response);
            container.parent().css('padding','0');
        }, 'text');
        return false;
    });
});
</script>
<?php endif; ?>