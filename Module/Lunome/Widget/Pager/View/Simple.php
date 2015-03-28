<?php /* @var $this \X\Module\Lunome\Widget\Pager\Simple */ ?>
<!-- Simple pager -->
<div>
    <nav>
        <ul class="pager">
            <?php if ($this->isPrevPageAvailable()) : ?>
            <li class="previous">
                <a  href="<?php echo $this->getPrevPageURL(); ?>" class="<?php echo $this->getPrevPageButtonClass(); ?>">&larr; 上一页</a>
            </li>
            <?php endif; ?>
            <?php echo $this->getCenterViewContents(); ?>
            <?php if ($this->isNextPageAvailabel()) : ?>
            <li class="next">
                <a  href="<?php echo $this->getNextPageURL(); ?>" class="<?php echo $this->getNextPageButtonClass(); ?>">下一页&rarr;</a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>