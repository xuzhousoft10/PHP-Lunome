<?php 
use X\Module\Lunome\Service\Book\Service;
$vars = get_defined_vars();
$media = $vars['media'];
$markCount = $vars['markCount'];
?>
<tr>
    <td>作者: <?php echo $media['author'];?></td>
    <td>类别: <?php echo $media['category']; ?></td>
    <td>出版时间: <?php echo $media['published_at']; ?></td>
    <td>出版社: <?php echo $media['published_by'];?></td>
</tr>
<tr>
    <td>总字数：<?php echo $media['word_count'];?></td>
    <td>状态：<?php echo $media['status'];?></td>
    <td></td>
    <td></td>
</tr>
<tr>
    <td>想看: <?php echo $markCount[Service::MARK_INTERESTED];?></td>
    <td>在看: <?php echo $markCount[Service::MARK_READING];?></td>
    <td>已看: <?php echo $markCount[Service::MARK_READ];?></td>
    <td>忽略: <?php echo $markCount[Service::MARK_IGNORED];?></td>
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