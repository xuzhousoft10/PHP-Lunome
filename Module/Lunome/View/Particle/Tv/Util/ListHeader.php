<?php 
use X\Module\Lunome\Service\Tv\Service as TvService;
$vars = get_defined_vars();
$marks = array();
$marks[TvService::MARK_UNMARKED]     = array('name'=>'所有',   'link'=>'/?module=lunome&action=tv/index');
$marks[TvService::MARK_INTERESTED]   = array('name'=>'想看',   'link'=>'/?module=lunome&action=tv/index&mark='.TvService::MARK_INTERESTED);
$marks[TvService::MARK_WATCHING]     = array('name'=>'在看',   'link'=>'/?module=lunome&action=tv/index&mark='.TvService::MARK_WATCHING);
$marks[TvService::MARK_WATCHED]      = array('name'=>'已看',   'link'=>'/?module=lunome&action=tv/index&mark='.TvService::MARK_WATCHED);
$marks[TvService::MARK_IGNORED]      = array('name'=>'不喜欢', 'link'=>'/?module=lunome&action=tv/index&mark='.TvService::MARK_IGNORED);
$markInfo = $vars['markInfo'];
if ( null === $markInfo['active'] ) {
    $markInfo['active'] = TvService::MARK_UNMARKED;
}
?>
<div class="panel-heading" style="padding: 0px;">
    <nav class="navbar navbar-default navbar-static-top navbar navbar-inverse" style="margin-bottom: 0px;">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/?module=lunome&action=tv/index">电视</a>
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