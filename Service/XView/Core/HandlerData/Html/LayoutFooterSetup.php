<div id="footer">
    <?php foreach ( $this->particles as $name => $particle ) : ?>
        <?php if (isset($particle['zone']) && 'footer' === $particle['zone']) : ?>
            <?php echo $particle['content']; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</div>