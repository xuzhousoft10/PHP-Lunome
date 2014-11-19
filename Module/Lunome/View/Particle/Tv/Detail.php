<?php 
use X\Module\Lunome\Service\Tv\Service;
$vars = get_defined_vars();
$media = $vars['media'];
$markCount = $vars['markCount'];
?>
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
    <td>忽略: <?php echo $markCount[Service::MARK_IGNORED];?></td>
</tr>