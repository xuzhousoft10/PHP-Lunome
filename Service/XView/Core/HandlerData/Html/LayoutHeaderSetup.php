<div id="header">
    <?php foreach ( $this->particles as $name => $particle ) : ?>
        <?php if (isset($particle['zone']) && 'header' === $particle['zone']) : ?>
            <?php echo $particle['content']; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</div>