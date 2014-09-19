<?php 
/**
 * The istory view of xurl service
 *
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */

/**
 * Vars of the view.
 */
$vars = get_defined_vars();
$histories = $vars['histories'];
$pos = isset($_GET['pos']) ? $_GET['pos'] : 0;
$length = X\Core\X::system()->getServiceManager()->get('XRequest')->getHistoryNumberPerPage();

$url = parse_url($_SERVER['REQUEST_URI']);
parse_str($url['query'], $query);
$query['pos'] = (0 > $pos-$length) ? 0 : $pos-$length;
$prev = sprintf('%s?%s', $url['path'], http_build_query($query));
$query['pos'] = $pos+$length;
$next = sprintf('%s?%s', $url['path'], http_build_query($query));
?>
<table class="table">
<thead>
    <tr>
        <td>#</td>
        <td>URL</td>
        <td>IP</td>
        <td>Location</td>
        <td>Date</td>
        <td>Time Spand</td>
    </tr>
</thead>
<tbody>
    <?php foreach ( $histories as $history ) : ?>
    <tr>
        <td><?php echo $history['id']; ?></td>
        <td><?php echo $history['url']; ?></td>
        <td><?php echo $history['ip']; ?></td>
        <td><?php echo $history['location']; ?></td>
        <td><?php echo $history['date']; ?></td>
        <td><?php echo $history['time']; ?></td>
    </tr>
    <?php endforeach; ?>
</tbody>
</table>

<div>
    <a href="<?php echo $prev; ?>">Prev</a>
    &nbsp;&nbsp;
    <span>(&nbsp;<?php echo $pos+1; ?> ~ <?php echo $pos+$length;?>&nbsp;)</span>
    &nbsp;&nbsp;
    <a href="<?php echo $next; ?>">Next</a>
</div>