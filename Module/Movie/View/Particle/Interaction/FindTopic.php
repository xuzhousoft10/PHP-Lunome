<?php 
$vars = get_defined_vars();
$movies = $vars['movies'];
/* @var $friendInformation \X\Module\Account\Service\Account\Core\Instance\Account */
$friendInformation = $vars['friendInformation'];
$friendProfile = $friendInformation->getProfileManager();
$assetsURL = $vars['assetsURL']; 

/* @var $this \X\Service\XView\Core\Util\HtmlView\ParticleView */
$this->getManager()->getHost()->getScriptManager()->add('topic')->setSource('js/movie/topic.js');
?>
<div class="col-md-9">
    <?php if (empty($movies['liked']) && empty($movies['disliked'])) : ?>
        <p>
            额～～～ 怎么说呢， 反正找不到你们都想看的电影， 去<?php echo $friendProfile->get('nickname'); ?>的
            <a href="/?module=movie&action=home/index&id=<?php echo $friendInformation->getID(); ?>&mark=1">
            主页
            </a>
            看看吧。
        </p>
    <?php else : ?>
        <?php if ( !empty($movies['liked']) ) : ?>
            <p>你和TA同时喜欢的电影, 讨论一下吧(ˉ﹃ˉ）</p>
            <div class="clearfix">
            <?php foreach ( $movies['liked'] as $movie ) : ?>
            <?php /* @var $movie \X\Module\Movie\Service\Movie\Core\Instance\Movie */ ?>
                <div class="pull-left lunome-movie-item">
                    <div data-cover-url="<?php echo $movie->getCoverURL(); ?>" class="lunome-movie-poster thumbnail padding-0 margin-bottom-0" >
                        <?php printf('<a href="/?module=movie&action=detail&id=%s" target="_blank">', $movie->get('id')); ?>
                            <div class="lunome-movie-desc-no-button">
                                <?php echo $movie->get('introduction'); ?>
                            </div>
                        <?php echo '</a>'; ?>
                    </div>
                    <div class="white-space-nowrap lunome-movie-title-area-short">
                        <a href="/?module=lunome&action=movie/detail&id=<?php echo $movie->get('id'); ?>" target="_blank">
                            <strong><?php echo $movie->get('name'); ?></strong>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if ( !empty($movies['disliked']) ) : ?>
            <p>你和TA同时不喜欢的电影, 你懂的╮(╯_╰)╭</p>
            <div class="clearfix">
            <?php foreach ( $movies['disliked'] as $movie ) : ?>
                <div class="pull-left lunome-movie-item">
                    <div data-cover-url="<?php echo $movie->getCoverURL(); ?>" class="lunome-movie-poster thumbnail padding-0 margin-bottom-0" >
                        <?php printf('<a href="/?module=movie&action=detail&id=%s" target="_blank">', $movie->get('id')); ?>
                            <div class="lunome-movie-desc-no-button">
                                <?php echo $movie->get('introduction'); ?>
                            </div>
                        <?php echo '</a>'; ?>
                    </div>
                    <div class="white-space-nowrap lunome-movie-title-area-short">
                        <a href="/?module=lunome&action=movie/detail&id=<?php echo $movie->get('id'); ?>" target="_blank">
                            <strong><?php echo $movie->get('name'); ?></strong>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>