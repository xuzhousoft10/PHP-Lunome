<?php use X\Core\X; ?>
<?php $assetsURL = X::system()->getConfiguration()->get('assets-base-url'); ?>
<?php $this->addScriptFile('User-Setting-Information', $assetsURL.'/js/user_friend_search.js'); ?>
<?php $vars = get_defined_vars(); ?>
<?php $informations = $vars['informations']; ?>
<?php $condition = $vars['condition']; ?>
<form action="/?module=lunome&action=user/friend/search" method="post" class="form-horizontal">
<div class="col-md-9 clearfix">
    <div class="clearfix thumbnail">
        <div class="input-group">
            <input  name        = "condition[main]" 
                    type        = "text" 
                    class       = "form-control" 
                    placeholder = "支持帐号，邮箱，手机，QQ查找" 
                    value       = "<?php echo empty($condition) ? '' : $condition['main'];?>"
            >
            <span class="input-group-btn">
                <button class="btn btn-default" type="button" id="advance-search-trigger">
                    高级选项
                </button>
                <button class="btn btn-default" type="submit">
                    <span class="glyphicon glyphicon-search"></span>
                </button>
            </span>
        </div>
        <div id="advance-search-container">
            <hr>
            <div class="form-group">
                <label class="col-sm-2 control-label">居住地</label>
                <div class="col-sm-3">
                    <select class       = "form-control advance-search-item" 
                            id          = "user-friend-search-living-country"
                            name        = "condition[living_country]"
                            data-value  = "<?php echo empty($condition) ? '' : $condition['living_country'];?>"
                    ></select>
                </div>
                <div class="col-sm-3">
                    <select class       = "form-control advance-search-item" 
                            id          = "user-friend-search-living-province"
                            name        = "condition[living_province]"
                            data-value  = "<?php echo empty($condition) ? '' : $condition['living_province'];?>"
                    ></select>
                </div>
                <div class="col-sm-3">
                    <select class       = "form-control advance-search-item" 
                            id          = "user-friend-search-living-city"
                            name        = "condition[living_city]"
                            data-value  = "<?php echo empty($condition) ? '' : $condition['living_city'];?>"
                    ></select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">性别</label>
                <div class="col-sm-9">
                    <select class       = "form-control value-init-required advance-search-item"
                            name        = "condition[sex]"
                            data-value  = "<?php echo empty($condition) ? '' : $condition['sex'];?>"
                    >
                        <option value="0"></option>
                        <option value="1">男</option>
                        <option value="2">女</option>
                        <option value="3">其他</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">性取向</label>
                <div class="col-sm-9">
                    <select class       = "form-control value-init-required advance-search-item"
                            name        = "condition[sexuality]"
                            data-value  = "<?php echo empty($condition) ? '' : $condition['sexuality'];?>"
                    >
                        <option value="0"></option>
                        <option value="1">异性</option>
                        <option value="2">同性</option>
                        <option value="3">双性</option>
                        <option value="4">无性</option>
                        <option value="5">二禁</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">情感状态</label>
                <div class="col-sm-9">
                    <select class       = "form-control value-init-required advance-search-item"
                            name        = "condition[emotion_status]"
                            data-value  = "<?php echo empty($condition) ? '' : $condition['emotion_status'];?>"
                    >
                        <option value="0"></option>
                        <option value="1">单身</option>
                        <option value="2">热恋中</option>
                        <option value="3">同居</option>
                        <option value="4">已订婚</option>
                        <option value="5">已婚</option>
                        <option value="6">分居</option>
                        <option value="7">离异</option>
                        <option value="8">很难说</option>
                        <option value="9">其他</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ( false === $informations ) : ?>
    <?php elseif ( 0 === count($informations) ) : ?>
        <div class="clearfix">
            <div class="pull-left">
                <img src="<?php echo $assetsURL;?>/image/nothing.gif" width="100" height="100">
            </div>
            <div class="margin-top-70 text-muted">
                <small>额~~~ 没有找到~~~</small>
            </div>
        </div>
    <?php else : ?>
        <div class="clearfix thumbnail">
            <?php $sexMap = array(''=>'保密', '0'=>'保密','1'=>'♂','2'=>'♀','3'=>'其他'); ?>
            <?php $sexualitysMap = array(''=>'保密', '0'=>'保密','1'=>'异性','2'=>'同性','3'=>'双性','4'=>'无性','5'=>'二禁'); ?>
            <?php $emotionStatusMap = array(''=>'保密', '0'=>'保密','1'=>'单身','2'=>'热恋中','3'=>'同居','4'=>'已订婚','5'=>'已婚','6'=>'分居','7'=>'离异','8'=>'很难说','9'=>'其他'); ?>
            <?php foreach ( $informations as $information ) : ?>
                <div class="col-md-5 well well-sm clearfix">
                    <div class="pull-left">
                        <img    class="img-thumbnail"
                                alt="<?php echo $information['account']['nickname'];?>" 
                                src="<?php echo $information['account']['photo'];?>"
                                width="80"
                                height="80"
                        >
                    </div>
                    <div class="pull-left padding-left-5">
                        <p>
                            <strong>
                                <span class="text-info"><?php echo $sexMap[$information['sex']];?></span>
                                <?php echo $information['account']['nickname'];?>
                            </strong>
                        </p>
                        <p>
                            <span class="glyphicon glyphicon-heart"></span>
                            <?php echo $sexualitysMap[$information['sexuality']];?>
                            <span class="glyphicon glyphicon-user"></span>
                            <?php echo $emotionStatusMap[$information['emotion_status']];?>
                        </p>
                        <p>
                            <?php echo $information['living_country'];?>
                            <?php echo $information['living_province'];?>
                            <?php echo $information['living_city'];?>
                        </p>
                    </div>
                    <div class="text-right">
                        <a  href            = "#" 
                            class           = "btn btn-primary btn-xs btn-add-as-friend-open-dialog"
                            data-recipient  = "<?php echo $information['account_id']; ?>"
                        >加为好友</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</form>

<!-- add friend dialog -->
<div class="modal fade" id="add-friend-dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">添加好友</h4>
            </div>
            <div class="modal-body">
                <textarea class="width-full" id="add-as-friend-message"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type            = "button" 
                        class           = "btn btn-primary " 
                        id              = "btn-add-as-friend" 
                >发送请求</button>
            </div>
        </div>
    </div>
</div>