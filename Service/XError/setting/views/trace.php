<?php 
/**
 * The trace view of XError
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/assets/css/bootstrap-fix.css">
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script src="/assets/js/application.js"></script>
    <title>Debug Trace</title>
    <style>
    .trace-item-parameter {cursor: pointer;}
    .trace-item-call-file {display:none;}
    </style>
    <script type="text/javascript">
    $(document).ready(function(){
        $('.trace-item-call-name').click(function(){
            var index = $(this).attr('data-callFileIndex');
            var file = '#trace-item-call-file-'+index;
            $(file).toggle();
        });
    });
    </script>
</head>
<body>
<?php 
use \X\Service\XError\Reporter\XTraceItem;
$traceItems = XTraceItem::getTraceItems();
?>
<div class="container">
<div class="alert alert-danger">
    <strong><?php echo $number; ?></strong> <?php echo $message; ?><br/>
    #<?php echo $line; ?> @<?php echo $file; ?><br/>
    <?php  var_dump($context); ?>
</div>
<ul class="list-unstyled">
<?php foreach ( $traceItems as $itemIndex => $traceItem ) : ?>
    <li>
        <div class="well well-sm">
            <div>
                <span class="trace-item-call-name" data-callFileIndex="<?php echo $itemIndex?>" >
                    <?php echo $traceItem->getCalledName(); ?>
                </span>
                (
                <?php foreach ( $traceItem->getParameters() as $index => $parameter ) : ?>
                    <span  
                        data-content="<?php echo $parameter['detail'];?>"
                        data-toggle="popover"
                        data-placement="top"
                        data-html = "true"
                        class="text-primary trace-item-parameter">
                        <?php echo $parameter['text']; ?>
                        <?php if ($index !== $traceItem->getParameterCount()-1) : ?>
                        ,
                        <?php endif; ?>
                    </span>
                <?php endforeach; ?>
                )
            </div>
            <div id="trace-item-call-file-<?php echo $itemIndex?>" class="trace-item-call-file well well-sm">
                <blockquote>
                    <small><?php echo $traceItem->getFileName(); ?></small>
                </blockquote>
                <?php echo $traceItem->getContext();?>
            </div>
        </div>
    </li>
<?php endforeach; ?>
</ul>
</div>
</body>
</html>