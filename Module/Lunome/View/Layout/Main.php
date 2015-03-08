<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
$this->getStyleManager()->add('body', array('padding-top'=>'70px'));
$vars = get_defined_vars();
$mainMenu = $vars['mainMenu'];
$user = $vars['user'];
?>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <div class="text-center well well-sm">
                <img src="<?php echo $user['photo']; ?>" alt="..." class="img-thumbnail"><br/>
                <h4><?php echo $user['nickname']; ?></h4>
                <div class="text-left">
                    <small>帐号：<?php echo $user['account']; ?></small><br/>
                </div>
            </div>
            <div class="list-group">
                <?php foreach ( $mainMenu as $index => $item ) :?>
                    <a  href="<?php echo $item['link']?>" 
                        class="list-group-item <?php if($item['isActive']):?>active<?php endif;?>"
                    ><?php echo $item['label'];?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-md-10">
            <?php echo $this->getParticleViewManager()->toString(); ?>
        </div>
    </div>
</div>
</body>