<?php /* @var $this \X\Service\XView\Core\Handler\Html */ ?>
<?php $particleManager = $this->getParticleViewManager(); ?>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <?php foreach ( $particleManager->getList() as $particleName ): ?>
                    <?php $particleView = $particleManager->get($particleName); ?>
                    <?php if ( 'right' !== $particleView->getOptionManager()->get('zone', 'left') ) : ?>
                        <?php echo $particleView->toString(); ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="col-md-3">
                <?php foreach ( $particleManager->getList() as $particleName ): ?>
                    <?php $particleView = $particleManager->get($particleName); ?>
                    <?php if ( 'right' === $particleView->getOptionManager()->get('zone', 'left') ) : ?>
                        <?php echo $particleView->toString(); ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>