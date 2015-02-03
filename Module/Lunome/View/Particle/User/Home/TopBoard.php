<?php $vars = get_defined_vars(); ?>
<?php $homeUser = $vars['homeUser']; ?>
<div class="thumbnail clearfix">
    <div class="col-md-2 text-center">
        <img class="img-thumbnail" width="100" height="100" alt="<?php echo $homeUser['nickname']; ?>" src="<?php echo $homeUser['photo']; ?>">
    </div>
    <div class="col-md-10">
        <table class="table">
            <tr>
                <td><?php echo $homeUser['nickname'];?></td>
                <td>&nbsp;</td> 
                <td>帐号&nbsp;&nbsp;<?php echo $homeUser['account_number'];?></td>
            </tr>
            
            <tr>
                <td>
                    <?php echo $homeUser['living_country']; ?>
                    <?php echo $homeUser['living_province']; ?>
                    <?php echo $homeUser['living_city']; ?>
                </td>
                <td>&nbsp;</td>
                <td>
                    <span ><?php echo $homeUser['sex']; ?></span>
                    <span class="glyphicon glyphicon-heart"></span>
                    <?php echo $homeUser['sexuality'];?>
                    <span class="glyphicon glyphicon-user"></span>
                    <?php echo $homeUser['emotion_status'];?>
                </td>
            </tr>
            
            <tr>
                <td></td><td></td><td></td>
            </tr>
        </table>
    </div>
</div>
