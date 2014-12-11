<div id="header">
    <?php foreach ( $this->particles as $name => $particle ) : ?>
        <?php if (isset($particle['option']['zone']) && 'header' === $particle['option']['zone']) : ?>
            <?php echo $particle['content']; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</div>