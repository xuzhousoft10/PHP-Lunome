<?php use X\Service\XDatabase\Core\ActiveRecord\Criteria; ?>
<?php use X\Module\Movie\Service\Movie\Service; ?>
<?php use X\Service\XView\Core\Handler\Html; ?>
<?php $vars = get_defined_vars(); ?>
<?php $markNames = $vars['markNames']; ?>
<?php $markStyles = $vars['markStyles']; ?>
<div class="panel panel-default">
    <div class="panel-heading">最新电影</div>
    <div class="panel-body">
        <?php $criteria = new Criteria(); ?>
        <?php $criteria->addOrder('date', 'DESC');?>
        <?php $criteria->limit = 5; ?>
        <?php /* @var $movieService Service */ ?>
        <?php $movieService = X\Core\X::system()->getServiceManager()->get(Service::getServiceName());?>
        <?php $movies = $movieService->getCurrentAccount()->findUnmarked($criteria);?>
        <?php foreach ($movies as $movie):?>
            <div class="media">
                <div class="media-left">
                    <a href="/?module=movie&action=detail&id=<?php echo $movie->get('id'); ?>">
                        <img    class="media-object" 
                                src="<?php echo $movie->getCoverURL();?>" 
                                alt="<?php echo Html::HTMLEncode($movie->get('name'));?>"
                                width="60"
                                height="80"
                        >
                    </a>
                </div>
                <div class="media-body" style="white-space:nowrap;">
                    <a href="/?module=movie&action=detail&id=<?php echo $movie->get('id'); ?>">
                        <strong><?php echo Html::HTMLEncode($movie->get('name'));?></strong>
                    </a>
                    <br>
                    <?php $movieRegion = $movie->getRegion(); ?>
                    <a class="text-muted" href="/?module=movie&action=index&query[region]=<?php echo $movieRegion->get('id');?>">
                        <?php echo Html::HTMLEncode($movieRegion->get('name'));?>
                    </a>
                    <small><?php echo $movie->get('date');?></small>
                    <br>
                    <?php $movieCategories = $movie->getCategories(); ?> 
                    <?php if ( empty($movieCategories) ) : ?>
                        其他
                    <?php else: ?>
                        <?php $lastMark = count($movieCategories)-1; ?>
                        <?php foreach ( $movieCategories as $index => $category ) : ?>
                            <a class="text-muted" href="/?module=movie&action=index&query[category]=<?php echo $category->get('id');?>">
                                <?php echo Html::HTMLEncode($category->get('name'));?>
                            </a>
                            <?php if($index!==$lastMark):?>/<?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <br>
                    <div class="btn-group">
                        <?php foreach ( $markNames as $markKey => $markName ) : ?>
                            <?php if ( 0 === $markKey) :?>
                                <?php continue; ?>
                            <?php endif; ?>
                            <a  class="btn btn-default btn-xs" 
                                href="/?module=movie&action=mark&mark=<?php echo $markKey; ?>&id=<?php echo $movie->get('id'); ?>&redirect=true"
                            ><?php echo $markName;?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>