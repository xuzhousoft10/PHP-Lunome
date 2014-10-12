<?php 
use X\Module\Lunome\Service\Game\Service as GameService;
$vars   = get_defined_vars();
$marks  = array();
$marks[GameService::MARK_UNMARKED]      = array('name'=>'所有',   'link'=>'/?module=lunome&action=game/index');
$marks[GameService::MARK_INTERESTED]    = array('name'=>'想玩',   'link'=>'/?module=lunome&action=game/index&mark='.GameService::MARK_INTERESTED);
$marks[GameService::MARK_PLAYING]       = array('name'=>'在玩',   'link'=>'/?module=lunome&action=game/index&mark='.GameService::MARK_PLAYING);
$marks[GameService::MARK_PLAYED]        = array('name'=>'已玩',   'link'=>'/?module=lunome&action=game/index&mark='.GameService::MARK_PLAYED);
$marks[GameService::MARK_IGNORED]       = array('name'=>'不喜欢', 'link'=>'/?module=lunome&action=game/index&mark='.GameService::MARK_IGNORED);
$markInfo = $vars['markInfo'];
?>
<div class="panel-heading" style="padding: 0px;">
    <nav class="navbar navbar-default navbar-static-top navbar navbar-inverse" style="margin-bottom: 0px;">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/?module=lunome&action=book/index">游戏</a>
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