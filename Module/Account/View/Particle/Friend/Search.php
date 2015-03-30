<?php 
use X\Core\X;
use X\Module\Account\Module as AccountModule;
use X\Module\Account\Service\Account\Service as AccountService;

$vars = get_defined_vars();
$informations = $vars['informations'];
$condition = $vars['condition']; 
$assetsURL = $vars['assetsURL'];
$pager = $vars['pager'];
$sexMap = $vars['sexMap'];
$sexualityMap = $vars['sexualityMap'];
$emotionMap = $vars['emotionMap'];
$peopleLeft = $vars['peopleLeft'];
$toBeFriendMessageLength = $vars['toBeFriendMessageLength'];

$module = X::system()->getModuleManager()->get(AccountModule::getModuleName());
$moduleConfiguration = $module->getConfiguration();
$sexNames = $moduleConfiguration->get('account_profile_sex_names');
$sexSigns = $moduleConfiguration->get('account_profile_sex_signs');
$sexualityNames = $moduleConfiguration->get('account_profile_sexuality');
$emotionStatusNames = $moduleConfiguration->get('account_profile_emotion_status');
/* @var $accountService AccountService */
$accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
$regionManager = $accountService->getRegionManager();

/* @var $this \X\Service\XView\Core\Util\HtmlView\ParticleView */
$scriptManager = $this->getManager()->getHost()->getScriptManager();
$scriptManager->add('account-friend-search')->setSource('js/user/search.js');
?>
<form action="/?module=account&action=friend/search" method="post" class="form-horizontal">
<div class="col-md-9 clearfix">
    <div class="clearfix thumbnail">
        <div class="input-group">
            <input  name        = "condition[main]" 
                    type        = "text" 
                    class       = "form-control" 
                    placeholder = "支持帐号，邮箱，手机，QQ查找" 
                    value       = "<?php echo empty($condition)||!isset($condition['main']) ? '' : $condition['main'];?>"
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
                            data-value  = "<?php echo empty($condition)||!isset($condition['living_country']) ? '' : $condition['living_country'];?>"
                    ></select>
                </div>
                <div class="col-sm-3">
                    <select class       = "form-control advance-search-item" 
                            id          = "user-friend-search-living-province"
                            name        = "condition[living_province]"
                            data-value  = "<?php echo empty($condition)||!isset($condition['living_province']) ? '' : $condition['living_province'];?>"
                    ></select>
                </div>
                <div class="col-sm-3">
                    <select class       = "form-control advance-search-item" 
                            id          = "user-friend-search-living-city"
                            name        = "condition[living_city]"
                            data-value  = "<?php echo empty($condition)||!isset($condition['living_city']) ? '' : $condition['living_city'];?>"
                    ></select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">性别</label>
                <div class="col-sm-9">
                    <select class       = "form-control value-init-required advance-search-item"
                            name        = "condition[sex]"
                            data-value  = "<?php echo empty($condition)||!isset($condition['sex']) ? '' : $condition['sex'];?>"
                    >
                        <option value="0"></option>
                        <?php foreach ( $sexMap as $key => $value ) : ?>
                            <option value="<?php echo $key;?>"><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">性取向</label>
                <div class="col-sm-9">
                    <select class       = "form-control value-init-required advance-search-item"
                            name        = "condition[sexuality]"
                            data-value  = "<?php echo empty($condition)||!isset($condition['sexuality']) ? '' : $condition['sexuality'];?>"
                    >
                        <option value="0"></option>
                        <?php foreach ( $sexualityMap as $key => $value ) : ?>
                            <option value="<?php echo $key;?>"><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">情感状态</label>
                <div class="col-sm-9">
                    <select class       = "form-control value-init-required advance-search-item"
                            name        = "condition[emotion_status]"
                            data-value  = "<?php echo empty($condition)||!isset($condition['emotion_status']) ? '' : $condition['emotion_status'];?>"
                    >
                        <option value="0"></option>
                        <?php foreach ( $emotionMap as $key => $value ) : ?>
                            <option value="<?php echo $key;?>"><?php echo $value; ?></option>
                        <?php endforeach; ?>
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
        <div class="thumbnail">
            <div class="clearfix" id="result-container" data-people-left="<?php echo $peopleLeft;?>">
            <?php foreach ( $informations as $index => $information ) : ?>
            <?php /* @var $information \X\Module\Account\Service\Account\Core\Instance\Account */ ?>
            <?php $profile = $information->getProfileManager(); ?>
                <div class="col-md-5 well well-sm clearfix">
                    <div class="clearfix">
                        <div class="pull-left">
                            <img    class="img-thumbnail"
                                    alt="<?php echo $profile->get('nickname');?>" 
                                    src="<?php echo $profile->get('photo');?>"
                                    width="80"
                                    height="80"
                            >
                        </div>
                        <div class="pull-left padding-left-5 user-information-text-area">
                            <p>
                                <strong>
                                    <span class="text-info"><?php echo $sexSigns[(int)$profile->get('sex')];?></span>
                                    <?php echo $profile->get('nickname');?>
                                </strong>
                            </p>
                            <p>
                                <span class="glyphicon glyphicon-heart"></span>
                                <?php echo $sexualityNames[(int)$profile->get('sexuality')];?>
                                <span class="glyphicon glyphicon-user"></span>
                                <?php echo $emotionStatusNames[(int)$profile->get('emotion_status')];?>
                            </p>
                            <p>
                                <?php echo $regionManager->getNameByID($profile->get('living_country'));?>
                                <?php echo $regionManager->getNameByID($profile->get('living_province'));?>
                                <?php echo $regionManager->getNameByID($profile->get('living_city'));?>
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <a  href            = "#" 
                            class           = "btn btn-primary btn-xs btn-add-as-friend-open-dialog"
                            data-recipient  = "<?php echo $profile->get('account_id');?>"
                        >加为好友</a>
                    </div>
                </div>
                <?php if ( 0 === ($index%2) ) : ?>
                    <div class="col-md-1"></div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php if ( false !== $pager['prev'] || false !== $pager['next'] ) : ?>
        <div>
            <nav>
                <ul class="pager">
                    <li class="pull-left<?php echo (false === $pager['prev']) ? ' disabled' : ''; ?>">
                        <?php if (false !== $pager['prev']) :?>
                            <button name="page" value="<?php echo $pager['prev']?>" type="submit" class="btn btn-default">&larr; 上一页</button>
                        <?php endif; ?>
                    </li>
                    <li><?php echo $pager['current'];?> / <?php echo $pager['pageCount'];?></li>
                    <li class="pull-right<?php echo (false === $pager['next']) ? ' disabled' : ''; ?>">
                        <?php if (false !== $pager['next']) :?>
                            <button name="page" value="<?php echo $pager['next']?>" type="submit" class="btn btn-default">下一页&rarr;</button>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
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
                <textarea class="width-full" id="add-as-friend-message" maxlength="<?php echo $toBeFriendMessageLength;?>"></textarea>
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