<?php 
use X\Module\Lunome\Service\Movie\Service;
$vars = get_defined_vars();
$media = $vars['media'];
$myMark = $vars['myMark'];
$markCount = $vars['markCount'];
?>
<tr>
    <td>时长: <?php echo intval($media['length']/60);?>分钟</td>
    <td>
        地区: 
        <?php if ( null === $media['region'] ): ?>
            其他
        <?php else :?>
            <a href="/?module=lunome&action=movie/index&query[region]=<?php echo $media['region']->id;?>&mark=<?php echo $myMark;?>">
                <?php echo $media['region']->name;?>
            </a>
        <?php endif; ?>
    </td>
    <td>
        类型: 
        <?php if ( null === $media['category'] ) : ?>
            其他
        <?php else: ?>
            <?php foreach ( $media['category'] as $category ) : ?>
                <a href="/?module=lunome&action=movie/index&query[category]=<?php echo $category->id;?>&mark=<?php echo $myMark;?>">
                    <?php echo $category->name;?>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </td>
    <td>
        语言: 
        <?php if ( null === $media['language'] ) : ?>
            其他
        <?php else: ?>
            <a href="/?module=lunome&action=movie/index&query[language]=<?php echo $media['language']->id;?>&mark=<?php echo $myMark;?>">
                <?php echo $media['language']->name;?>
            </a>
        <?php endif; ?>
    </td>
</tr>
<tr>
    <td>
        导演: 
        <?php foreach ( $media['directors'] as $director ) : ?>
            <a href="/?module=lunome&action=movie/index&query[name]=<?php echo urlencode('导演:'.$director->name);?>&mark=<?php echo $myMark;?>">
                <?php echo $director->name; ?>
            </a>
        <?php endforeach; ?>
    </td>
    <td colspan="3">
        主演： 
        <?php foreach ( $media['actors'] as $actor ) : ?>
            <a href="/?module=lunome&action=movie/index&query[name]=<?php echo urlencode('演员:'.$actor->name);?>&mark=<?php echo $myMark;?>">
                <?php echo $actor->name; ?>
            </a>
        <?php endforeach; ?>
    </td>
</tr>
<tr>
    <td colspan="4">&nbsp;</td>
<tr>
<tr>
    <td colspan="4">&nbsp;</td>
</tr>
<tr>
    <td>想看: <?php echo $markCount[Service::MARK_INTERESTED];?></td>
    <td>已看: <?php echo $markCount[Service::MARK_WATCHED];?></td>
    <td>忽略: <?php echo $markCount[Service::MARK_IGNORED];?></td>
    <td></td>
</tr>