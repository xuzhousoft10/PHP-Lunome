<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $this->title; ?></title>
    <link rel="stylesheet" type="text/css" href="/Assets/library/bootstrap/theme/slate.css">
  </head>
  <body style="padding-top:60px">
    <!-- Header -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">X-Framework管理</a>
        </div>
      </div>
    </nav>
    
    <!-- Main -->
    <div class="container-fluid">
      <div class="col-md-3">
        <div class="list-group">
          <?php foreach ( $this->menu as $key => $item ) : ?>
            <a href="<?php echo $item['link']; ?>" class="list-group-item <?php echo $item['status'];?>">
              <?php echo $item['name']; ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="col-md-9">
        <?php foreach ( $this->breadcrumb as $index => $breadcrumbItem ) : ?>
          <a href="<?php echo $breadcrumbItem['link']; ?>">
            <span class="text-muted"><?php echo $breadcrumbItem['name']?> /</span>
          </a>
        <?php endforeach; ?>
        <hr style="margin:5px">
        <?php foreach ( $this->particles as $name => $particle ) : ?>
          <?php echo $particle['content']; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </body>
</html>