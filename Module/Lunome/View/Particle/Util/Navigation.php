<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
$this->addStyle('body', array('padding-top'=>'50px'));
?>
<nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Lunome</a>
    </div>
    
    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="http://www.gravatar.com/avatar/5b5854d237940c86212d59ff12590e6e?size=20" class="img-circle" />
            Michael Luthor 
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li>
              <a href="#">
                <span class="glyphicon glyphicon-user"></span>
                My Profile
              </a>
            </li>
            <li class="divider"></li>
            <li>
              <a href="#">
                <span class="glyphicon glyphicon-heart"></span>
                Security Center
              </a>
            </li>
            <li>
              <a href="#">
                <span class="glyphicon glyphicon-cog"></span>
                Personal Setting
              </a>
            </li>
            <li class="divider"></li>
            <li>
              <a href="#">
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