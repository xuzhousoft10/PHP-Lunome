<?php 
$vars = get_defined_vars();
$marks = $vars['marks'];
$medias = $vars['medias'];
$markActions = $vars['markActions'];
$pager = $vars['pager'];
$mediaType = $vars['mediaType'];
$mediaTypeName = $vars['mediaTypeName'];
?>
<div class="panel panel-default">
    <!-- Media Index Header Start -->
    <div class="panel-heading padding-0">
        <nav class="navbar navbar-default navbar-static-top navbar navbar-inverse margin-bottom-0">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/?module=lunome&action=<?php echo $mediaType;?>/index"><?php echo $mediaTypeName; ?></a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                    <?php foreach ( $marks as $markCode => $mark ) :?>
                        <li class="<?php if ($mark['isActive']) :?>active<?php endif; ?>">
                            <a href="<?php printf('/?module=lunome&action=%s/index&mark=%s', $mediaType,$markCode); ?>">
                                <?php echo $mark['name'];?> (<?php echo $mark['count']; ?>)
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <!-- Media Index Header End -->
    
    <!-- Media List Start -->
    <div class="panel-body">
        <div class="clearfix lnm-media-list-container">
            <?php foreach ( $medias as $index => $media ) : ?>
                <div class="pull-left lnm-media-list-item-container">
                    <div class="lnm-media-list-item" data-poster="<?php echo $media['id'];?>" data-media-type="<?php echo $mediaType;?>" >
                        <div class="btn-group btn-group-justified lnm-media-list-item-mark-container">
                            <?php foreach ( $markActions as $markCode => $markAction ) : ?>
                            <div class="btn-group btn-group-sm">
                                <a  class="btn btn-<?php echo $markAction['style'];?>" 
                                    href="<?php printf('/?module=lunome&action=%s/mark&mark=%s&id=%s', $mediaType, $markCode, $media['id']); ?>"
                                ><?php echo $markAction['name'];?></a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="white-space-nowrap">
                        <strong><?php echo $media['name'];?></strong>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Media List End -->
  
    <!-- Pager -->
    <div class="panel-footer clearfix">
        <ul class="pagination pull-right">
            <li>
                <a  href    = "/?module=lunome&action=<?php echo $mediaType;?>/index&mark=<?php echo $pager['params']['mark'];?>" 
                    class   = "<?php if (!$pager['canPrev']):?>disabled<?php endif;?>"
                >首页</a>
            </li>
            <li>
                <a  href    = "/?module=lunome&action=<?php echo $mediaType;?>/index&mark=<?php echo $pager['params']['mark'];?>&page=<?php echo $pager['prev'];?>" 
                    class   = "<?php if (!$pager['canPrev']):?>disabled<?php endif;?>"
                >上一页</a>
            </li>
            <?php foreach ($pager['items'] as $pageItem ) : ?>
                <li <?php if ($pageItem == $pager['current']) :?>class="active"<?php endif;?>>
                    <a href="/?module=lunome&action=<?php echo $mediaType;?>/index&mark=<?php echo $pager['params']['mark'];?>&page=<?php echo $pageItem;?>"><?php echo $pageItem;?></a>
                </li>
            <?php endforeach; ?>
            <li>
                <a  href    = "/?module=lunome&action=<?php echo $mediaType;?>/index&mark=<?php echo $pager['params']['mark'];?>&page=<?php echo $pager['next'];?>" 
                    class   = "<?php if (!$pager['canNext']):?>disabled<?php endif;?>"
                >下一页</a>
            </li>
            <li>
                <a  href    = "/?module=lunome&action=<?php echo $mediaType;?>/index&mark=<?php echo $pager['params']['mark'];?>&page=<?php echo $pager['total'];?>" 
                    class   = "<?php if (!$pager['canPrev']):?>disabled<?php endif;?>"
                >尾页</a>
            </li>
        </ul>
    </div>
</div>