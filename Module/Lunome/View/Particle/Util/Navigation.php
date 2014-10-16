<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
$this->addStyle('body', array('padding-top'=>'50px'));
$vars = get_defined_vars();
$user = $vars['user'];
?>
<nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="/?module=lunome&action=index">Lunome</a>
    </div>
    
    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo $user['photo']; ?>" class="img-circle" width="20" />
            <?php echo $user['nickname']; ?> 
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <!-- 
            <li>
              <a href="/?module=lunome&action=user/information/basic">
                <span class="glyphicon glyphicon-user"></span>
                My Profile
              </a>
            </li>
            <li class="divider"></li>
            <li>
              <a href="/?module=lunome&action=security/password">
                <span class="glyphicon glyphicon-heart"></span>
                Security Center
              </a>
            </li>
            <li>
              <a href="/?module=lunome&action=user/setting/basic">
                <span class="glyphicon glyphicon-cog"></span>
                Personal Setting
              </a>
            </li>
            <li class="divider"></li>
             -->
            <li>
              <a href="/?module=lunome&action=user/logout">
                <span class="glyphicon glyphicon-off"></span>
                Exit
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>