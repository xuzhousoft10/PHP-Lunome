<?php use X\Core\X; ?>
<?php $assetsURL = X::system()->getConfiguration()->get('assets-base-url'); ?>
<?php $this->addCssLink('Bootstrap-Date-Picker', $assetsURL.'/library/bootstrap/plugin/bootstrap-datepicker/css/datepicker3.css'); ?>
<?php $this->addScriptFile('Bootstrap-Date-Picker', $assetsURL.'/library/bootstrap/plugin/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>
<?php $this->addScriptFile('Bootstrap-Date-Picker-Language', $assetsURL.'/library/bootstrap/plugin/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js'); ?>
<?php $this->addScriptFile('User-Setting-Information', $assetsURL.'/js/user_setting_information.js'); ?>

<?php $vars = get_defined_vars(); ?>
<?php /* @var $account \X\Module\Lunome\Model\Account\AccountInformation */ ?>
<?php $account = $vars['account']; ?>
<form action="/?module=lunome&action=user/setting/information" method="post" class="form-horizontal">
<div class="col-md-9 thumbnail">
    <h5>个人信息更新</h5>
    <hr>
    <div class="form-group">
        <label class="col-sm-2 control-label">邮箱</label>
        <div class="col-sm-10">
            <input  type="text" 
                    class="form-control"
                    name="information[email]"
                    value="<?php echo empty($account) ? '' : $account->email; ?>"
            >
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">QQ</label>
        <div class="col-sm-10">
            <input  type    = "text" 
                    class   = "form-control"
                    name    = "information[qq]"
                    value   = "<?php echo empty($account) ? '' : $account->qq; ?>"
            >
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">手机</label>
        <div class="col-sm-10">
            <input  type    = "text" 
                    name    = "information[cellphone]"
                    class   = "form-control"
                    value   = "<?php echo empty($account) ? '' : $account->cellphone; ?>"
            >
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">生日</label>
        <div class="col-sm-8">
            <input  type    = "text" 
                    id      = "user-setting-information-birthday"
                    name    = "information[birthday]"
                    class   = "form-control"
                    value   = "<?php echo empty($account) ? '' : $account->birthday; ?>"
            >
        </div>
        <div class="col-sm-2">
            <input  type="hidden" name="information[is_lunar_calendar]" value="0">
            <input  type        = "checkbox" 
                    name        = "information[is_lunar_calendar]" 
                    value       = "1" 
                    <?php echo (empty($account) || 0 === $account->is_lunar_calendar*1 ) ? '' : 'checked'; ?>
            > 阴历
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">性别</label>
        <div class="col-sm-10">
            <select class       = "form-control value-init-required"
                    name        = "information[sex]"
                    data-value  = "<?php echo empty($account) ? 0 : $account->sex; ?>"
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
        <div class="col-sm-10">
            <select class       = "form-control value-init-required"
                    name        = "information[sexuality]"
                    data-value  = "<?php echo empty($account) ? 0 : $account->sexuality; ?>"
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
        <div class="col-sm-10">
            <select class       = "form-control value-init-required"
                    name        = "information[emotion_status]"
                    data-value  = "<?php echo empty($account) ? 0 : $account->emotion_status; ?>"
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
    
    <div class="form-group">
        <label class="col-sm-2 control-label">居住地</label>
        <div class="col-sm-3">
            <select class       = "form-control" 
                    id          = "user-setting-information-living-country"
                    name        = "information[living_country]"
                    data-value  = "<?php echo empty($account) ? '' : $account->living_country; ?>"
            ></select>
        </div>
        <div class="col-sm-3">
            <select class       = "form-control" 
                    id          = "user-setting-information-living-province"
                    name        = "information[living_province]"
                    data-value  = "<?php echo empty($account) ? '' : $account->living_province; ?>"
            ></select>
        </div>
        <div class="col-sm-3">
            <select class       = "form-control" 
                    id          = "user-setting-information-living-city"
                    name        = "information[living_city]"
                    data-value  = "<?php echo empty($account) ? '' : $account->living_city; ?>"
            ></select>
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">保存</button>
        </div>
    </div>
</div>
</form>