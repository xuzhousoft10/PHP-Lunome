<?php 
use X\Module\Lunome\Service\Book\Service as BookService;
$vars   = get_defined_vars();
$marks  = array();
$marks[BookService::MARK_UNMARKED]      = array('name'=>'所有',   'link'=>'/?module=lunome&action=book/index');
$marks[BookService::MARK_INTERESTED]    = array('name'=>'想读',   'link'=>'/?module=lunome&action=book/index&mark='.BookService::MARK_INTERESTED);
$marks[BookService::MARK_READING]       = array('name'=>'在读',   'link'=>'/?module=lunome&action=book/index&mark='.BookService::MARK_READING);
$marks[BookService::MARK_READ]          = array('name'=>'已读',   'link'=>'/?module=lunome&action=book/index&mark='.BookService::MARK_READ);
$marks[BookService::MARK_IGNORED]       = array('name'=>'不喜欢', 'link'=>'/?module=lunome&action=book/index&mark='.BookService::MARK_IGNORED);
$markInfo = $vars['markInfo'];
?>
<div class="panel-heading" style="padding: 0px;">
    <nav class="navbar navbar-default navbar-static-top navbar navbar-inverse" style="margin-bottom: 0px;">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/?module=lunome&action=book/index">图书</a>
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