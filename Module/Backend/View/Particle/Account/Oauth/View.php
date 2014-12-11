<?php
use X\Module\Lunome\Model\Oauth20Model;
$vars = get_defined_vars();
$oauth = $vars['oauth'];
?>
<div>
    <table class="table table-striped table-bordered table-hover">
        <tr>
            <td>服务器</td>
            <td>
                <?php 
                switch ( $oauth['server'] ) {
                case Oauth20Model::SERVER_QQ :
                    echo '腾讯QQ'; break;
                default:
                    echo $oauth['server']; break;
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>Open ID</td>
            <td><?php echo $oauth['openid'];?></td>
        </tr>
        <tr>
            <td>Access Token</td>
            <td><?php echo $oauth['access_token'];?></td>
        </tr>
        <tr>
            <td>Refresh Token</td>
            <td><?php echo $oauth['refresh_token'];?></td>
        </tr>
        <tr>
            <td>过期时间</td>
            <td><?php echo $oauth['expired_at']; ?></td>
        </tr>
    </table>
    
    <a href="<?php echo $vars['returnURL'];?>" class="btn btn-primary">返回</a>
</div>