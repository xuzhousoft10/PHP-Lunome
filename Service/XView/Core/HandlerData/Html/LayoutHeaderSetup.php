<div id="header">
    <?php foreach ( $this->particles as $name => $particle ) : ?>
        <?php if ('header' === $particle['zone']) : ?>
            <?php echo $particle['content']; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</div>