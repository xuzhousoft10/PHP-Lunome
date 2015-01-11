<?php $vars = get_defined_vars(); ?>
<?php $optionStatus = empty($vars['selected']) ? 'selected' : ''; ?>
<option value="" <?php echo $optionStatus;?>></option>
<?php foreach ( $vars['regions'] as $region ) : ?>
    <?php $optionStatus = ( $region->id === $vars['selected'] ) ? 'selected' : ''; ?>
    <option value="<?php echo $region->id; ?>" <?php echo $optionStatus; ?>>
        <?php echo $region->name; ?>
    </option>
<?php endforeach; ?>
