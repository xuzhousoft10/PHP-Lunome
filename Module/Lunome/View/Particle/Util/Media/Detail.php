<?php 
use X\Module\Lunome\Service\Movie\Service;
$vars = get_defined_vars();
$media = $vars['media'];
$markCount = $vars['markCount'];
?>
<div class="row margin-top-5">
    <div class="col-md-2 padding-0">
        <img src="/?module=lunome&action=movie/poster&id=<?php echo $media['id']?>" width="200" height="300">
    </div>
    <div class="col-md-10">
        <h4><?php echo $media['name'];?></h4>
        <br>
        <table class="table table-bordered">
            <tr>
                <td>时长: <?php echo $media['length'];?>分钟</td>
                <td>地区: <?php echo $media['region'];?></td>
                <td>类型: <?php echo $media['category'];?></td>
                <td>语言: <?php echo $media['language'];?></td>
            </tr>
            <tr>
                <td>导演: <?php echo $media['director'];?></td>
                <td>编剧: <?php echo $media['writer'];?></td>
                <td>制片人: <?php echo $media['producer'];?></td>
                <td>监制:<?php echo $media['executive'];?></td>
            </tr>
            <tr>
                <td colspan="4">主演： <?php echo $media['actor'];?></td>
            </tr>
            <tr>
                <td>全网</td>
                <td>想看: <?php echo $markCount[Service::MARK_INTERESTED];?></td>
                <td>已看: <?php echo $markCount[Service::MARK_WATCHED];?></td>
                <td>不喜欢: <?php echo $markCount[Service::MARK_IGNORED];?></td>
            </tr>
        </table>
        <div class="btn-group">
            <button type="button" class="btn btn-success">想看</button>
            <button type="button" class="btn btn-info">已看</button>
            <button type="button" class="btn btn-default">不喜欢</button>
        </div>
    </div>
</div>
<hr>
<div class="margin-top-5">
    <p><?php echo $media['introduction'];?></p>
</div>