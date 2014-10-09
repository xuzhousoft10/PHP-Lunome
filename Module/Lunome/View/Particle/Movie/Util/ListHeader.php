<?php 
use X\Module\Lunome\Service\Movie\Service as MovieService;
$vars = get_defined_vars();
$marks = array();
$marks[MovieService::MARK_UNMARKED]      = array('name'=>'所有',   'link'=>'/?module=lunome&action=movie/index');
$marks[MovieService::MARK_INTERESTED]    = array('name'=>'想看',   'link'=>'/?module=lunome&action=movie/index&mark='.MovieService::MARK_INTERESTED);
$marks[MovieService::MARK_WATCHED]       = array('name'=>'已看',   'link'=>'/?module=lunome&action=movie/index&mark='.MovieService::MARK_WATCHED);
$marks[MovieService::MARK_IGNORED]       = array('name'=>'不喜欢', 'link'=>'/?module=lunome&action=movie/index&mark='.MovieService::MARK_IGNORED);
$markInfo = $vars['markInfo'];
?>
<div class="panel-heading" style="padding: 0px;">
    <nav class="navbar navbar-default navbar-static-top navbar navbar-inverse" style="margin-bottom: 0px;">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/?module=lunome&action=movie/index">电影</a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                <?php foreach ( $marks as $name => $mark ) :?>
                    <li class="<?php if ($markInfo['active'] == $name) :?>active<?php endif; ?>">
                        <a href="<?php echo $mark['link']?>">
                            <?php echo $mark['name'];?> (<?php echo $markInfo[$name]; ?>)
                        </a>
                    </li>
                <?php endforeach; ?>
                <?php unset($marks); ?>
                <?php unset($name); ?>
                <?php unset($mark); ?>
                </ul>
            </div>
        </div>
    </nav>
</div>
<?php extract($vars, EXTR_OVERWRITE);?>