<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
$this->addStyle('body', array('padding-top'=>'50px'));
$vars = get_defined_vars();
$user = $vars['user'];
?>
<nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">Lunome</a>
        </div>
        
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <?php if ( $user['isGuest'] ) : ?>
                    <li>
                        <a href="/index.php?module=lunome&action=user/login/index">登录</a>
                    </li>
                <?php else : ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo $user['photo']; ?>" class="img-circle" width="20" />
                            <?php echo $user['nickname']; ?> 
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- 
                            <li class="divider"></li>
                            <li>
                            <a href="/?module=lunome&action=security/password">
                            <span class="glyphicon glyphicon-heart"></span>
                            Security Center
                            </a>
                            </li>
                            -->
                            <li>
                                <a href="/?module=lunome&action=user/setting">
                                    <span class=" glyphicon glyphicon-cog"></span>
                                    个人设置
                                </a>
                            </li>
                            <?php if ($user['isAdmin']) : ?>
                                <li class="divider"></li>
                                <li>
                                    <a href="/?module=backend">
                                        <span class="glyphicon glyphicon-tower"></span>
                                        后台管理
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li class="divider"></li>
                            <li>
                                <a href="/?module=lunome&action=user/logout">
                                    <span class="glyphicon glyphicon-off"></span>
                                    退出
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>