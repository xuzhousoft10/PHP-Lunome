<?php 
$vars = get_defined_vars();
/* @var $regions \X\Module\Lunome\Model\Movie\MovieRegionModel[] */
$regions = $vars['searchData']['regions'];
$languages = $vars['searchData']['languages'];
$categories = $vars['searchData']['categories'];
?>
<div>
年份： 
    <span class="media-search-condition-label text-primary" data-attr="date" data-value="">全部</span> 
    <?php $year = date('Y', time());?>
    <?php for ( $i=0; $i<15; $i++ ) :?>
        <span class="media-search-condition-label text-primary" data-attr="date" data-value="<?php echo $year;?>"><?php echo $year--; ?></span>
    <?php endfor; ?>
    <select class="media-search-condition-select" data-attr="date" >
        <option value="">&nbsp;&nbsp;&nbsp;&gt;&gt;
        <?php while ( $year >= 1896 ) : ?>
            <option value="<?php echo $year; ?>"><?php echo $year; ?>
            <?php $year--;?>
        <?php endwhile; ?>
    </select>
<br>
地区： 
    <span class="media-search-condition-label text-primary" data-attr="region" data-value="">全部</span>
    <?php foreach ( $regions as $index => $region ) : ?>
        <span class="media-search-condition-label text-primary" data-attr="region" data-value="<?php echo $region->id;?>"><?php echo $region->name; ?></span>
        <?php unset($regions[$index]);?>
        <?php if ( $index >= 15 ): ?>
            <?php break;?>
        <?php endif; ?>
    <?php endforeach; ?> 
    <select class="media-search-condition-select" data-attr="region" >
        <option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&gt;&gt;
        <?php while ( !empty($regions) ) : ?>
            <?php $region = array_shift($regions);?>
            <option value="<?php echo $region->id; ?>"><?php echo $region->name; ?>
        <?php endwhile; ?>
    </select>
<br>
语言：
    <span class="media-search-condition-label text-primary" data-attr="language" data-value="">全部</span>
    <?php foreach ( $languages as $index => $language ) : ?>
        <span class="media-search-condition-label text-primary" data-attr="language" data-value="<?php echo $language->id;?>"><?php echo $language->name; ?></span>
        <?php unset($languages[$index]);?>
        <?php if ( $index >= 15 ): ?>
            <?php break;?>
        <?php endif; ?>
    <?php endforeach; ?> 
    <select class="media-search-condition-select" data-attr="language" >
        <option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&gt;&gt;
        <?php while ( !empty($languages) ) : ?>
            <?php $language = array_shift($languages);?>
            <option value="<?php echo $language->id; ?>"><?php echo $language->name; ?>
        <?php endwhile; ?>
    </select>
<br>
分类：
    <span class="media-search-condition-label text-primary" data-attr="category" data-value="">全部</span>
    <?php foreach ( $categories as $index => $category ) : ?>
        <span class="media-search-condition-label text-primary" data-attr="category" data-value="<?php echo $category->id; ?>"><?php echo $category->name; ?></span>
        <?php unset($categories[$index]);?>
        <?php if ( $index >= 15 ): ?>
            <?php break;?>
        <?php endif; ?>
    <?php endforeach; ?> 
    <select class="media-search-condition-select" data-attr="category" >
        <option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&gt;&gt;
        <?php while ( !empty($categories) ) : ?>
            <?php $category = array_shift($categories);?>
            <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?>
        <?php endwhile; ?>
    </select>
</div>