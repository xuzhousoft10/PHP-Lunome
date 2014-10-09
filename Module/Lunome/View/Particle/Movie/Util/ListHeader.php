<?php 
$vars = get_defined_vars();
$marks = array();
$marks['unmarked']      = array('name'=>'所有',   'link'=>'/?module=lunome&action=movie/index');
$marks['interested']    = array('name'=>'想看',   'link'=>'/?module=lunome&action=movie/index&mark=interested');
$marks['watched']       = array('name'=>'已看',   'link'=>'/?module=lunome&action=movie/index&mark=watched');
$marks['ignored']       = array('name'=>'不喜欢', 'link'=>'/?module=lunome&action=movie/index&mark=ignored');
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
                    <li class="<?php if ($markInfo['active'] === $name) :?>active<?php endif; ?>">
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