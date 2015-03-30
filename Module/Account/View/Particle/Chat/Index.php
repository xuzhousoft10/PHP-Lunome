<?php use X\Core\X; ?>
<?php use X\Module\Account\Module as AccountModule; ?>
<?php use X\Module\Account\Service\Account\Service as AccountService; ?> 

<?php $vars = get_defined_vars(); ?>
<?php /* @var $friend \X\Module\Account\Service\Account\Core\Manager\ProfileManager */ ?>
<?php $friend = $vars['friend']; ?>
<?php $module = X::system()->getModuleManager()->get(AccountModule::getModuleName()); ?>
<?php $moduleConfiguration = $module->getConfiguration(); ?>
<?php $sexNames = $moduleConfiguration->get('account_profile_sex_names'); ?>
<?php $sexSigns = $moduleConfiguration->get('account_profile_sex_signs'); ?>
<?php $sexualityNames = $moduleConfiguration->get('account_profile_sexuality'); ?>
<?php $emotionStatusNames = $moduleConfiguration->get('account_profile_emotion_status'); ?>
<?php /* @var $accountService AccountService */ ?>
<?php $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName()); ?>
<?php $reginManager = $accountService->getRegionManager(); ?>

<?php 
/* @var $this \X\Service\XView\Core\Util\HtmlView\ParticleView */
$scriptManager = $this->getManager()->getHost()->getScriptManager();
$scriptManager->add('account-chat-index')->setSource('js/user/chat.js');
?>
<div class="thumbnail clearfix">
    <div class="pull-left">
        <img    class="thumbnail padding-0 margin-0" 
                alt="<?php echo $friend->get('nickname');?>" 
                src="<?php echo $friend->get('photo');?>"
                width="80"
                height="80"
        >
    </div>
    
    <div class="pull-left padding-left-10">
        <p>
            <strong>
                <span class="text-info" title="性别:<?php echo $sexNames[(int)$friend->get('sex')]; ?>">
                    <?php echo $sexSigns[(int)$friend->get('sex')];?>
                </span>
                <?php echo $friend->get('nickname');?>
            </strong>
        </p>
        <p>
            <span class="glyphicon glyphicon-heart"></span>
            <?php echo $sexualityNames[(int)$friend->get('sexuality')];?>
            <span class="glyphicon glyphicon-user"></span>
            <?php echo $emotionStatusNames[(int)$friend->get('emotion_status')]; ?>
        </p>
        <p>
            <?php echo $reginManager->getNameByID($friend->get('living_country'));?>
            <?php echo $reginManager->getNameByID(('living_province'));?>
            <?php echo $reginManager->getNameByID($friend->get('living_city'));?>
        </p>
    </div>
</div>
<div    id="user-chat-container-container" 
        class="thumbnail"
        data-friend-id="<?php echo $friend->get('account_id'); ?>"
></div>
<div class="clearfix">
    <div class="input-group">
      <input id="user-chat-content" type="text" class="form-control">
      <span class="input-group-btn">
        <button id="user-chat-send-button" class="btn btn-primary" type="button">发送</button>
      </span>
    </div>
</div>