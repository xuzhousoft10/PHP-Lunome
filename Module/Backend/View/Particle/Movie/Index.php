<?php 
$vars = get_defined_vars();
$movies = $vars['movies'];
$condition = $vars['condition'];
?>
<div class="well well-sm">
    <form class="form-inline" action="/" method="get">
        <input type="hidden" name="module" value="backend">
        <input type="hidden" name="action" value="movie/index">
        <div class="form-group">
            <label>名称</label>
            <input 
                type="text" 
                class="form-control" 
                placeholder="名称查询" 
                name="condition[name]"
                value="<?php echo isset($condition['name']) ? $condition['name'] : '';?>"
            >
        </div>
        <button type="submit" class="btn btn-default">
            <span class="glyphicon glyphicon-search"></span>
        </button>
    </form>
</div>
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead><tr><td>名称</td><td></td></tr></thead>
    <tbody>
    <?php foreach ( $movies as $movie ) : ?>
        <tr>
            <td><?php echo $movie['name']; ?></td>
            <td><a class="btn btn-xs" href="/?module=backend&action=movie/detail&id=<?php echo $movie['id'];?>">详细</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>