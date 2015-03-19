<?php /* @var $this \X\Service\XView\Core\Handler\Html */ ?>
<?php 
$vars = get_defined_vars();
$notifications = $vars['notifications']; 
?>
<ul class="list-group">
<?php foreach ( $notifications as $notification ) : ?>
    <li class="list-group-item user-notification-container-item">
        <?php $handler = function() use ($notification) { require $notification->getView(); }; ?>
        <?php $handler(); ?>
    </li>
<?php endforeach; ?>
</ul>