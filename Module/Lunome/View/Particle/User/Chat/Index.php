<?php $vars = get_defined_vars(); ?>
<?php use X\Core\X; ?>
<?php $assetsURL = X::system()->getConfiguration()->get('assets-base-url'); ?>
<?php $this->addScriptFile('user-chat', $assetsURL.'/js/user_chat.js'); ?>
<?php $friend = $vars['friend']; ?>
<?php $sexMap = array(''=>'＊', '0'=>'＊','1'=>'♂','2'=>'♀','3'=>'？'); ?>
<?php $sexNameMap = array(''=>'保密', '0'=>'保密','1'=>'男','2'=>'女','3'=>'其他'); ?>
<?php $sexualitysMap = array(''=>'保密', '0'=>'保密','1'=>'异性','2'=>'同性','3'=>'双性','4'=>'无性','5'=>'二禁'); ?>
<?php $emotionStatusMap = array(''=>'保密', '0'=>'保密','1'=>'单身','2'=>'热恋中','3'=>'同居','4'=>'已订婚','5'=>'已婚','6'=>'分居','7'=>'离异','8'=>'很难说','9'=>'其他'); ?>
<div class="thumbnail clearfix">
    <div class="pull-left">
        <img    class="thumbnail padding-0 margin-0" 
                alt="<?php echo $friend->nickname;?>" 
                src="<?php echo $friend->photo;?>"
                width="80"
                height="80"
        >
    </div>
    
    <div class="pull-left padding-left-10">
        <p>
            <strong>
                <span class="text-info" title="性别:<?php echo $sexNameMap[$friend->sex]; ?>"><?php echo $sexMap[$friend->sex];?></span>
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
<div    id="user-chat-container-container" 
        class="thumbnail"
        data-friend-id="<?php echo $friend->account_id; ?>"
></div>
<div class="clearfix">
    <div class="input-group">
      <input id="user-chat-content" type="text" class="form-control">
      <span class="input-group-btn">
        <button id="user-chat-send-button" class="btn btn-primary" type="button">发送</button>
      </span>
    </div>
</div>