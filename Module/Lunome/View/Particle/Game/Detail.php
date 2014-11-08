<?php 
use X\Module\Lunome\Service\Book\Service;
$vars = get_defined_vars();
$media = $vars['media'];
$markCount = $vars['markCount'];
?>
<tr>
    <td>游戏类型: <?php echo $media['category'];?></td>
    <td>玩家人数: <?php echo $media['is_multi_player'];?></td>
    <td>游戏画面: <?php echo $media['screen_dimension'];?></td>
    <td>地区: <?php echo $media['area'];?></td>
</tr>
<tr>
    <td>发行日期: <?php echo $media['published_at'];?></td>
    <td>开发商: <?php echo $media['published_by'];?></td>
    <td>发行商: <?php echo $media['developed_by'];?></td>
    <td></td>
</tr>
<tr>
    <td>想看: <?php echo $markCount[Service::MARK_INTERESTED];?></td>
    <td>在看: <?php echo $markCount[Service::MARK_READING];?></td>
    <td>已看: <?php echo $markCount[Service::MARK_READ];?></td>
    <td>不喜欢: <?php echo $markCount[Service::MARK_IGNORED];?></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td></td>
    <td></td>
    <td></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td></td>
    <td></td>
    <td></td>
</tr>