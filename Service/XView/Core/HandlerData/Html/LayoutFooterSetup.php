<div id="footer">
    <?php foreach ( $this->particles as $name => $particle ) : ?>
        <?php if (isset($particle['option']['zone']) && 'footer' === $particle['option']['zone']) : ?>
            <?php echo $particle['content']; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</div>