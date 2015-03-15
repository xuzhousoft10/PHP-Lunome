<?php $vars = get_defined_vars(); ?>
<?php $friend = $vars['friend']; ?>
<div class="thumbnail clearfix">
    <div class="pull-left">
        <img    class="thumbnail padding-0 margin-0" 
                alt="<?php echo $friend['nickname'];?>" 
                src="<?php echo $friend['photo'];?>"
                width="80"
                height="80"
        >
    </div>
    
    <div class="pull-left padding-left-10">
        <p>
            <strong>
                <span class="text-info" title="性别:<?php echo $friend['sexSign']; ?>"><?php echo $friend['sex'];?></span>
                <?php echo $friend['nickname'];?>
            </strong>
        </p>
        <p>
            <span class="glyphicon glyphicon-heart"></span>
            <?php echo $friend['sexuality'];?>
            <span class="glyphicon glyphicon-user"></span>
            <?php echo $friend['emotion_status'];?>
        </p>
        <p>
            <?php echo $friend['living_country'];?>
            <?php echo $friend['living_province'];?>
            <?php echo $friend['living_city'];?>
        </p>
    </div>
</div>
<div    id="user-chat-container-container" 
        class="thumbnail"
        data-friend-id="<?php echo $friend['account_id']; ?>"
></div>
<div class="clearfix">
    <div class="input-group">
      <input id="user-chat-content" type="text" class="form-control">
      <span class="input-group-btn">
        <button id="user-chat-send-button" class="btn btn-primary" type="button">发送</button>
      </span>
    </div>
</div>