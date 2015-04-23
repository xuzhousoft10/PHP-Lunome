<?php use X\Service\XView\Core\Handler\Html;
$vars = get_defined_vars(); ?>
<?php $assetsURL = $vars['assetsURL']; ?>
<?php $movie = $vars['movie'];?>
<button id="suggest-friends" class="btn btn-default btn-block btn-sm">推荐给好友</button>
<br>

<div class="modal fade" id="friend-seletor" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">选择好友</h4>
      </div>
      <div class="modal-body">
        <div id="friend-seletor-processing" class="text-center"><img alt="加载中..." src="<?php echo $assetsURL;?>/image/loadding.gif"></div>
        <div id="friend-seletor-list-container" class="clearfix"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button id="friend-seletor-done" type="button" class="btn btn-primary">确定</button>
      </div>
    </div>
  </div>
</div>

<script>
function friendSelector( ) {
    this.friends    = {};
    this.selected   = {};
}

friendSelector.prototype.onSelectionDone = null;
friendSelector.prototype.close = function() {
    $('#friend-seletor').modal('hide');
}
friendSelector.prototype.show = function() {
    var $this = this;
    $('#friend-seletor').modal('show');
    
    $.get('/index.php?module=account&action=friend/getList', {}, function(response) {
        $('#friend-seletor-processing').hide();
        $('#friend-seletor-list-container').empty();

        for ( var i in response.data ) {
            $this.friends[response.data[i].account_id] = response.data[i];
            
            $('<div>')
            .css('border-radius','5px')
            .css('cursor', 'pointer')
            .addClass('col-md-5')
            .addClass('margin-bottom-10')
            .addClass('padding-5')
            .addClass('margin-left-5')
            .append($('<img>').attr('src', response.data[i].photo).addClass('img-circle').width(30))
            .append(response.data[i].nickname)
            .appendTo('#friend-seletor-list-container')
            .attr('data-friend-account', response.data[i].account_id)
            .click(function() {
                var accountID = $(this).attr('data-friend-account');
                if ( 'undefined' == typeof($this.selected[accountID]) ) {
                    $(this).css('background-color','#337AB7');
                    $this.selected[accountID] = $this.friends[accountID];
                } else {
                    delete $this.selected[accountID];
                    $(this).css('background-color','');
                }
            });
        }
    }, 'json');

    $('#friend-seletor-done').unbind('click');
    $('#friend-seletor-done').click(function(){
        if ( 0 == Object.keys($this.selected).length ) {
            alert('至少要选择1名好友~~~');
            return;
        }

        $('#friend-seletor-processing').show();
        $('#friend-seletor-list-container').empty();
        if ( null != $this.onSelectionDone ) {
            $this.onSelectionDone();
        } else {
            $('#friend-seletor').modal('hide');
        }
    });

    $('#friend-seletor').on('hidden.bs.modal', function (e) {
        $('#friend-seletor-processing').show();
        $('#friend-seletor-list-container').empty();
    });
}

$(document).ready(function() {
$('#suggest-friends').click(function() {
    var selector = new friendSelector();
    selector.onSelectionDone = function() {
        var list = [];
        for( var i in selector.selected ) {
            list.push(i);
        }

        $.post('/?module=movie&action=interaction/sendSuggestion', {
            movie   : <?php echo Html::JavascriptValueEncode($movie->get('id'));?>,
            friends : list,
        }, function() {
            alert('发送成功');
            selector.close();
        }, 'json');
        return false;
    };
    selector.show();
});
});
</script>