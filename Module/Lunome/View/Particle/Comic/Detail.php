<?php 
use X\Module\Lunome\Service\Comic\Service;
$vars = get_defined_vars();
$media = $vars['media'];
$markCount = $vars['markCount'];
?>
<tr>
    <td>作者: <?php echo $media['author'];?></td>
    <td>地区: <?php echo $media['region'];?></td>
    <td>状态: <?php echo $media['status'];?></td>
</tr>
<tr>
    <td>连载杂志: <?php echo $media['magazine'];?></td>
    <td>出版社: <?php echo $media['press'];?></td>
    <td>出版时间： <?php echo $media['published_at'];?>年</td>
    <td>完结时间： <?php echo $media['finished_at'];?>年</td>
</tr>
<tr>
    <td>集数: <?php echo $media['episode_count'];?></td>
    <td>类型: <?php echo $media['category'];?></td>
    <td>首映时间: <?php echo $media['premiered_at'];?></td>
</tr>
<tr>
    <td colspan="4">主角: <?php echo $media['character'];?></td>
</tr>
<tr>
    <td>想看: <?php echo $markCount[Service::MARK_INTERESTED];?></td>
    <td>在看: <?php echo $markCount[Service::MARK_WATCHING];?></td>
    <td>已看: <?php echo $markCount[Service::MARK_WATCHED];?></td>
    <td>不喜欢: <?php echo $markCount[Service::MARK_IGNORED];?></td>
</tr>