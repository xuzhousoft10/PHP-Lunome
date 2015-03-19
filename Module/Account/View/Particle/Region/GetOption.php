<?php $vars = get_defined_vars(); ?>
<?php $optionStatus = empty($vars['selected']) ? 'selected' : ''; ?>
<option value="" <?php echo $optionStatus;?>></option>
<?php foreach ( $vars['regions'] as $region ) : ?>
    <?php $optionStatus = ( $region->get('id') === $vars['selected'] ) ? 'selected' : ''; ?>
    <option value="<?php echo $region->get('id'); ?>" <?php echo $optionStatus; ?>>
        <?php echo $region->get('name'); ?>
    </option>
<?php endforeach; ?>
