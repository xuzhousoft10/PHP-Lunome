<?php 
use X\Module\Lunome\Service\Movie\Service;
$vars = get_defined_vars();
$media = $vars['media'];
$markCount = $vars['markCount'];
?>
<tr>
    <td>时长: <?php echo intval($media['length']/60);?>分钟</td>
    <td>地区: <?php echo $media['region'];?></td>
    <td>类型: <?php echo $media['category'];?></td>
    <td>语言: <?php echo $media['language'];?></td>
</tr>
<tr>
    <td>导演: <?php echo $media['directors'];?></td>
    <td colspan="3">主演： <?php echo $media['actors'];?></td>
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