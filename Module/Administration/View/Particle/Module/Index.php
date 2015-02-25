<?php $vars = get_defined_vars(); ?>
<?php $modules = $vars['modules']; ?>
<?php foreach ( $modules as $module ) : ?>
<?php /* @var $module \\X\Core\Module\XModule */ ?>
<div class="panel panel-default">
  <div class="panel-heading" style="padding:5px 10px;">
    <h3 class="panel-title">
      <a href="/?module=administration&action=module/detail&name=<?php echo $module->getName();?>">
        <?php echo $module->getPrettyName(); ?> 
        <small>(Ver <?php echo implode('-', $module->getVersion());?>)</small>
      </a>
    </h3>
  </div>
  <div class="panel-body" style="padding:10px;">
    <?php echo $module->getDescription(); ?>
  </div>
  <div class="panel-footer" style="padding:5px 10px;">
    <?php $status = $module->isDefaultModule() ? 'success' : 'primary'; ?>
    <span class="label label-<?php echo $status;?>">默认模块</span>
    
    <?php $status = $module->isEnabled() ? 'success' : 'primary'; ?>
    <span class="label label-<?php echo $status;?>">已启用</span>
  </div>
</div>
<?php endforeach; ?>