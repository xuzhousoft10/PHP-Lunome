<?php 
use X\Core\X;
use X\Module\Account\Module as AccountModule;
use X\Module\Account\Service\Account\Service as AccountService;
$vars = get_defined_vars();
/* @var $homeUser \X\Module\Account\Service\Account\Core\Instance\Account */
$homeUser = $vars['homeUser'];
$profile = $homeUser->getProfileManager();
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
<div class="thumbnail clearfix">
    <div class="col-md-2 text-center">
        <img class="img-thumbnail" width="100" height="100" alt="<?php echo $profile->get('nickname'); ?>" src="<?php echo $profile->get('photo'); ?>">
    </div>
    <div class="col-md-10">
        <table class="table">
            <tr>
                <td><?php echo $profile->get('nickname');?></td>
                <td>&nbsp;</td> 
                <td>帐号&nbsp;&nbsp;<?php echo $profile->get('account_number');?></td>
            </tr>
            
            <tr>
                <td>
                    <?php echo $regionManager->getNameByID($profile->get('living_country')); ?>
                    <?php echo $regionManager->getNameByID($profile->get('living_province')); ?>
                    <?php echo $regionManager->getNameByID($profile->get('living_city')); ?>
                </td>
                <td>&nbsp;</td>
                <td>
                    <span ><?php echo $sexNames[(int)$profile->get('sex')]; ?></span>
                    <span class="glyphicon glyphicon-heart"></span>
                    <?php echo $sexualityNames[(int)$profile->get('sexuality')];?>
                    <span class="glyphicon glyphicon-user"></span>
                    <?php echo $emotionStatusNames[(int)$profile->get('emotion_status')];?>
                </td>
            </tr>
            
            <tr>
                <td></td><td></td><td></td>
            </tr>
        </table>
    </div>
</div>
