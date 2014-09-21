<div id="footer">
    <?php foreach ( $this->particles as $name => $particle ) : ?>
        <?php if ('footer' === $particle['zone']) : ?>
            <?php echo $particle['content']; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</div>