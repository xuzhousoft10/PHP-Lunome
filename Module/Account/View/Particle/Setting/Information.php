<?php 
$vars = get_defined_vars();
/* @var $account \X\Module\Account\Service\Account\Core\Manager\ProfileManager */
$account = $vars['account']; 
$sexMap = $vars['sexMap'];
$sexualityMap = $vars['sexualityMap'];
$emotionMap = $vars['emotionMap'];
?>
<form action="/?module=account&action=setting/information" method="post" class="form-horizontal">
<div class="col-md-9 thumbnail">
    <h5>个人信息更新</h5>
    <hr>
    <div class="form-group">
        <label class="col-sm-2 control-label">邮箱</label>
        <div class="col-sm-10">
            <input  type="text" 
                    class="form-control"
                    name="information[email]"
                    value="<?php echo $account->get('email'); ?>"
            >
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">QQ</label>
        <div class="col-sm-10">
            <input  type    = "text" 
                    class   = "form-control"
                    name    = "information[qq]"
                    value   = "<?php echo $account->get('qq'); ?>"
            >
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">手机</label>
        <div class="col-sm-10">
            <input  type    = "text" 
                    name    = "information[cellphone]"
                    class   = "form-control"
                    value   = "<?php echo $account->get('cellphone'); ?>"
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
                    value   = "<?php echo $account->get('birthday'); ?>"
            >
        </div>
        <div class="col-sm-2">
            <input  type="hidden" name="information[is_lunar_calendar]" value="0">
            <input  type        = "checkbox" 
                    name        = "information[is_lunar_calendar]" 
                    value       = "1" 
                    <?php echo (0===(int)$account->get('is_lunar_calendar')) ? '' : 'checked'; ?>
            > 阴历
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">性别</label>
        <div class="col-sm-10">
            <select class       = "form-control value-init-required"
                    name        = "information[sex]"
                    data-value  = "<?php echo $account->get('sex'); ?>"
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
        <div class="col-sm-10">
            <select class       = "form-control value-init-required"
                    name        = "information[sexuality]"
                    data-value  = "<?php echo $account->get('sexuality'); ?>"
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
        <div class="col-sm-10">
            <select class       = "form-control value-init-required"
                    name        = "information[emotion_status]"
                    data-value  = "<?php echo $account->get('emotion_status'); ?>"
            >
                <option value="0"></option>
                <?php foreach ( $emotionMap as $key => $value ) : ?>
                    <option value="<?php echo $key;?>"><?php echo $value; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">居住地</label>
        <div class="col-sm-3">
            <select class       = "form-control" 
                    id          = "user-setting-information-living-country"
                    name        = "information[living_country]"
                    data-value  = "<?php echo $account->get('living_country'); ?>"
            ></select>
        </div>
        <div class="col-sm-3">
            <select class       = "form-control" 
                    id          = "user-setting-information-living-province"
                    name        = "information[living_province]"
                    data-value  = "<?php echo $account->get('living_province'); ?>"
            ></select>
        </div>
        <div class="col-sm-3">
            <select class       = "form-control" 
                    id          = "user-setting-information-living-city"
                    name        = "information[living_city]"
                    data-value  = "<?php echo $account->get('living_city'); ?>"
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