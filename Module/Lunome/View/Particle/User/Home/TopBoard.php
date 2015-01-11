<?php $vars = get_defined_vars(); ?>
<?php $homeUser = $vars['homeUser']; ?>
<?php $sexMap = array(''=>'＊', '0'=>'＊','1'=>'♂','2'=>'♀','3'=>'？'); ?>
<?php $sexNameMap = array(''=>'保密', '0'=>'保密','1'=>'男','2'=>'女','3'=>'其他'); ?>
<?php $sexualitysMap = array(''=>'保密', '0'=>'保密','1'=>'异性','2'=>'同性','3'=>'双性','4'=>'无性','5'=>'二禁'); ?>
<?php $emotionStatusMap = array(''=>'保密', '0'=>'保密','1'=>'单身','2'=>'热恋中','3'=>'同居','4'=>'已订婚','5'=>'已婚','6'=>'分居','7'=>'离异','8'=>'很难说','9'=>'其他'); ?>
<div class="thumbnail clearfix">
    <div class="col-md-2 text-center">
        <img class="img-thumbnail" width="100" height="100" alt="<?php echo $homeUser->nickname; ?>" src="<?php echo $homeUser->photo; ?>">
    </div>
    <div class="col-md-10">
        <table class="table">
            <tr>
                <td><?php echo $homeUser->nickname;?></td>
                <td>&nbsp;</td> 
                <td>帐号&nbsp;&nbsp;<?php echo $homeUser->account_number;?></td>
            </tr>
            
            <tr>
                <td>
                    <?php echo $homeUser->living_country; ?>
                    <?php echo $homeUser->living_province; ?>
                    <?php echo $homeUser->living_city; ?>
                </td>
                <td>&nbsp;</td>
                <td>
                    <span ><?php echo $sexNameMap[$homeUser->sex]; ?></span>
                    <span class="glyphicon glyphicon-heart"></span>
                    <?php echo $sexualitysMap[$homeUser->sexuality];?>
                    <span class="glyphicon glyphicon-user"></span>
                    <?php echo $emotionStatusMap[$homeUser->emotion_status];?>
                </td>
            </tr>
            
            <tr>
                <td></td><td></td><td></td>
            </tr>
        </table>
    </div>
</div>
