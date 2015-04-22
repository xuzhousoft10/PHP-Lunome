<?php $vars = get_defined_vars(); ?>
<?php $movie = $vars['movie']; ?>
<?php $movieAccount = $vars['movieAccount']; ?>
<?php 
$scriptManager = $this->getManager()->getHost()->getScriptManager();
$linkManager = $this->getManager()->getHost()->getLinkManager();
$linkManager->addCSS('rate-it', 'library/jquery/plugin/rate/rateit.css');
$scriptManager->add('rate-it')->setSource('library/jquery/plugin/rate/rateit.js')->setRequirements('jquery');
?>
<div class="panel panel-default">
    <div class="panel-heading">评分</div>
    <div class="panel-body">
        <span class="glyphicon glyphicon-thumbs-down"></span> 
        &nbsp;&nbsp;
        <div    id="rate-it-container-<?php echo $movie->get('id');?>"
                data-media-id=""
        ></div>
        &nbsp;&nbsp;
        <span class="glyphicon glyphicon-thumbs-up"></span>
    </div>
</div>
<script>
$(document).ready(function(){
    $('#rate-it-container-<?php echo $movie->get('id');?>')
    .rateit({max:10,step:1,resetable:false, value:<?php echo $movieAccount->getScore($movie->get('id'));?>})
    .bind('over', function (event, value) {
        value = parseInt(value);
        if ( 0 >= value ) {
            value = 1;
        }
        var titles = ['没救了','太差','很差','差','还行','很棒','非常棒','棒级了','超级棒','极品'];
        $(this).attr('title', titles[value-1]); 
    })
    .bind('rated', function (e) {
        var ri = $(this);
        $.get('/?module=movie&action=rate', {
            id:"<?php echo $movie->get('id');?>",
            score:ri.rateit('value'),
        }, function( response ) {
        }, 'json');
    });
});
</script>