<?php
use X\Service\XError\Core\Reporter\Util\XTraceItem;
$traceItems = XTraceItem::getTraceItems();
$vars = get_defined_vars();
?>
<!DOCTYPE>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/assets/css/bootstrap-fix.css">
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <title>程序错误</title>
    <style>
    .trace-item-parameter {cursor: pointer;}
    .trace-item-call-file {display:none;}
    </style>
    <script type="text/javascript">
    $(document).ready(function(){
        $('.trace-item-call-name').click(function(){
            var index = $(this).attr('data-call-file-index');
            var file = '#trace-item-call-file-'+index;
            $(file).toggle();
        });
    });
    </script>
</head>
    <body>
        <div class="container">
            <div class="alert alert-danger">
                <strong><?php echo $vars['number']; ?></strong> <?php echo $vars['message']; ?><br/>
                #<?php echo $vars['line']; ?> @<?php echo $vars['file']; ?><br/>
                <pre><?php print_r($vars['context']); ?></pre>
            </div>
            
            <ul class="list-unstyled">
                <?php foreach ( $traceItems as $itemIndex => $traceItem ) : ?>
                    <li>
                        <div class="well well-sm">
                            <div>
                                <span class="trace-item-call-name" data-call-file-index="<?php echo $itemIndex?>" >
                                    <?php echo $traceItem->getCalledName(); ?>
                                </span>
                                (
                                <?php foreach ( $traceItem->getParameters() as $index => $parameter ) : ?>
                                    <span
                                        data-content="<?php echo $parameter['detail'];?>"
                                        data-toggle="popover"
                                        data-placement="top"
                                        data-html = "true"
                                        class="text-primary trace-item-parameter"
                                    >
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