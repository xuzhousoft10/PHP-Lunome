<?php use X\Core\X; ?>
<?php $vars = get_defined_vars(); ?>
<?php $movies = $vars['movies']; ?>
<?php $friendInformation = $vars['friendInformation']; ?>
<?php $assetsURL = $vars['assetsURL']; ?>
<div class="col-md-9">
    <?php if (empty($movies['like']) && empty($movies['dislike'])) : ?>
        <p>
            额～～～ 怎么说呢， 反正找不到你们都想看的电影， 去<?php echo $friendInformation->nickname; ?>的
            <a href="/?module=lunome&action=movie/home/index&id=<?php echo $friendInformation->account_id; ?>&mark=1">
            主页
            </a>
            看看吧。
        </p>
    <?php else : ?>
        <?php if ( !empty($movies['like']) ) : ?>
            <p>你和TA同时喜欢的电影, 讨论一下吧(ˉ﹃ˉ）</p>
            <div class="clearfix">
            <?php foreach ( $movies['like'] as $movie ) : ?>
                <div class="pull-left lunome-movie-item">
                    <div data-cover-url="<?php echo $movie['cover']; ?>" class="lunome-movie-poster thumbnail padding-0 margin-bottom-0" >
                        <?php printf('<a href="/?module=lunome&action=movie/detail&id=%s" target="_blank">', $movie['id']); ?>
                            <div class="lunome-movie-desc-no-button">
                                <?php echo $movie['introduction']; ?>
                            </div>
                        <?php echo '</a>'; ?>
                    </div>
                    <div class="white-space-nowrap lunome-movie-title-area-short">
                        <a href="/?module=lunome&action=movie/detail&id=<?php echo $movie['id']; ?>" target="_blank">
                            <strong><?php echo $movie['name']; ?></strong>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if ( !empty($movies['dislike']) ) : ?>
            <p>你和TA同时不喜欢的电影, 你懂的╮(╯_╰)╭</p>
            <div class="clearfix">
            <?php foreach ( $movies['dislike'] as $movie ) : ?>
                <div class="pull-left lunome-movie-item">
                    <div data-cover-url="<?php echo $movie['cover']; ?>" class="lunome-movie-poster thumbnail padding-0 margin-bottom-0" >
                        <?php printf('<a href="/?module=lunome&action=movie/detail&id=%s" target="_blank">', $movie['id']); ?>
                            <div class="lunome-movie-desc-no-button">
                                <?php echo $movie['introduction']; ?>
                            </div>
                        <?php echo '</a>'; ?>
                    </div>
                    <div class="white-space-nowrap lunome-movie-title-area-short">
                        <a href="/?module=lunome&action=movie/detail&id=<?php echo $movie['id']; ?>" target="_blank">
                            <strong><?php echo $movie['name']; ?></strong>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>