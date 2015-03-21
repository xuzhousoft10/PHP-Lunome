<?php 
use X\Core\X;
use X\Module\Account\Module as AccountModule;
use X\Module\Account\Service\Account\Service as AccountService;
/* @var $this \X\Service\XView\Core\Handler\Html */
$vars = get_defined_vars();
$friends = $vars['friends'];
$module = X::system()->getModuleManager()->get(AccountModule::getModuleName());
$moduleConfiguration = $module->getConfiguration();
$sexNames = $moduleConfiguration->get('account_profile_sex_names');
$sexSigns = $moduleConfiguration->get('account_profile_sex_signs');
$sexualityNames = $moduleConfiguration->get('account_profile_sexuality');
$emotionStatusNames = $moduleConfiguration->get('account_profile_emotion_status');
/* @var $accountService AccountService */
$accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
$regionManager = $accountService->getRegionManager();
?>
<div class="col-md-9">
<strong>我的好友</strong>
<hr class="margin-top-10">
<div class="clearfix">
<?php foreach ( $friends as $index =>  $friend ) :?>
<?php /* @var $friend \X\Module\Account\Service\Account\Core\Instance\Account */ ?>
<?php $profile = $friend->getProfileManager(); ?>
    <div class="well-sm padding-0 clearfix col-md-5 padding-left-10">
        <div class="well well-sm">
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
                            <span class="text-info" title="性别:<?php echo $sexNames[(int)$profile->get('sex')]; ?>">
                                <?php echo $sexSigns[(int)$profile->get('sex')];?>
                            </span>
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
                <a class="btn btn-primary btn-xs" target="_blank" href="/?module=account&action=interaction/index&id=<?php echo $profile->get('account_id');?>">互动</a>
                <a class="btn btn-primary btn-xs" target="_blank" href="/?module=account&action=chat/index&friend=<?php echo $profile->get('account_id');?>">聊天</a>
                <a class="btn btn-primary btn-xs" target="_blank" href="/?module=account&action=home/index&id=<?php echo $profile->get('account_id');?>">主页</a>
            </div>
        </div>
    </div>
    <?php if (0 === ($index%2)) : ?>
        <div class="col-md-1"></div>
    <?php endif; ?>
<?php endforeach; ?>
</div>
<?php $pager = $vars['pager']; ?>
<?php if ( false !== $pager['prev'] || false !== $pager['next'] ) : ?>
<div>
    <nav>
        <ul class="pager">
            <li class="previous<?php echo (false === $pager['prev']) ? ' disabled' : ''; ?>">
                <?php if (false !== $pager['prev']) :?>
                    <a  href="/?module=lunome&action=user/friend/index&page=<?php echo $pager['prev'];?>"
                        class="movie-characters-container-pager"
                    >&larr; 上一页</a>
                <?php endif; ?>
            </li>
            <li><?php echo $pager['current'];?> / <?php echo $pager['pageCount'];?></li>
            <li class="next<?php echo (false === $pager['next']) ? ' disabled' : ''; ?>">
                <?php if (false !== $pager['next']) :?>
                    <a  href="/?module=lunome&action=user/friend/index&page=<?php echo $pager['next'];?>"
                        class="movie-characters-container-pager"
                    >下一页&rarr;</a>
                <?php endif; ?>
            </li>
        </ul>
    </nav>
</div>
<?php endif; ?>
</div>