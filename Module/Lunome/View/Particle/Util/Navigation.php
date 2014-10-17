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
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-send"></span>
            好友 
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" style="width:310px;height:600px;overflow-y:auto;padding:0px;">
           <li>
                <div class="list-group" style="margin-bottom:0px;">
                    <?php for ( $i=0; $i<9; $i++ ):?>
                        <a href="#" class="list-group-item" style="padding-top:0px;padding-bottom:0px;">
                            <div class="row" style="height:60px;">
                                <div class="pull-left" style="margin: 5px;">
                                    <img src="<?php echo $user['photo']; ?>" class="img-thumbnail" style="height:50px;" />
                                </div>
                                <div class="pull-left" style="margin: 5px;">
                                    <strong>Lana Lane</strong>
                                    <span class="pull-right"><small>金条：<?php echo rand(10, 100);?>米</small></span>
                                    <div style="padding-top: 10px">
                                        <small>
                                            <span class="label label-success">电影 <?php echo rand(10, 100);?></span> 
                                            <span class="label label-success">电视 <?php echo rand(10, 100);?></span> 
                                            <span class="label label-success">动漫 <?php echo rand(10, 100);?></span> 
                                            <span class="label label-success">书籍 <?php echo rand(10, 100);?></span> 
                                            <span class="label label-success">游戏 <?php echo rand(10, 100);?></span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endfor;?>
                </div>
            </li>
            <li role="presentation" class="divider"></li>
            <li>
                <a href="http://www.baidu.com">
                    <button type="button" class="btn btn-primary btn-lg btn-block">查找好友</button>
                </a>
            </li>
          </ul>
        </li>
        
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