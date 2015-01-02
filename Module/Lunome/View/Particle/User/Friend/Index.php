<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
$vars = get_defined_vars();
$friends = $vars['friends'];
?>
<?php $sexMap = array(''=>'保密', '0'=>'保密','1'=>'♂','2'=>'♀','3'=>'其他'); ?>
<?php $sexualitysMap = array(''=>'保密', '0'=>'保密','1'=>'异性','2'=>'同性','3'=>'双性','4'=>'无性','5'=>'二禁'); ?>
<?php $emotionStatusMap = array(''=>'保密', '0'=>'保密','1'=>'单身','2'=>'热恋中','3'=>'同居','4'=>'已订婚','5'=>'已婚','6'=>'分居','7'=>'离异','8'=>'很难说','9'=>'其他'); ?>
<div class="col-md-9">
<?php foreach ( $friends as $friend ) :?>
    <div class="well-sm padding-0 clearfix">
        <div class="col-md-5 well well-sm clearfix">
            <div class="pull-left">
                <img    class="img-thumbnail"
                        alt="<?php echo $friend->nickname;?>" 
                        src="<?php echo $friend->photo;?>"
                        width="80"
                        height="80"
                >
            </div>
            <div class="pull-left padding-left-5">
                <p>
                    <strong>
                        <span class="text-info"><?php echo $sexMap[$friend->sex];?></span>
                        <?php echo $friend->nickname;?>
                    </strong>
                </p>
                <p>
                    <span class="glyphicon glyphicon-heart"></span>
                    <?php echo $sexualitysMap[$friend->sexuality];?>
                    <span class="glyphicon glyphicon-user"></span>
                    <?php echo $emotionStatusMap[$friend->emotion_status];?>
                </p>
                <p>
                    <?php echo $friend->living_country;?>
                    <?php echo $friend->living_province;?>
                    <?php echo $friend->living_city;?>
                </p>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>