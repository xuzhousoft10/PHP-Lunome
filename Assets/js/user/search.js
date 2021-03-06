$(document).ready(function() {
    /* 当省份变化时， 更新城市数据。 */
    $('#user-friend-search-living-province').change(function() {
        var province = $('#user-friend-search-living-province').val();
        if ( null == province || 0 == province.length ) {
            $('#user-friend-search-living-city').empty();
            return;
        }
        $.get('/?module=account&action=region/getOption', {
            parent:$('#user-friend-search-living-province').val(),
            selected:$('#user-friend-search-living-city').attr('data-value')
        }, function( response ) {
            $('#user-friend-search-living-city').html(response);
        }, 'html');
    });
    
    /* 当国家变化时， 更新省份数据。 */
    $('#user-friend-search-living-country').change(function() {
        if ( 0 === $('#user-friend-search-living-country').val().length ) {
            $('#user-friend-search-living-province').val('').trigger('change').empty();
            return;
        }
        $.get('/?module=account&action=region/getOption', {
            parent:$('#user-friend-search-living-country').val(),
            selected:$('#user-friend-search-living-province').attr('data-value')
        }, function( response ) {
            $('#user-friend-search-living-province').html(response).trigger('change');
        }, 'html');
    });
    
    /* 初始化国家数据。 */
    $.get('/?module=account&action=region/getOption', {
        selected:$('#user-friend-search-living-country').attr('data-value')
    }, function( response ) {
        $('#user-friend-search-living-country').html(response).trigger('change');
    }, 'html');
    
    /* 初始化下拉框的值 */
    var selectors = $('.value-init-required');
    for ( i=0; i<selectors.length; i++ ) {
        selectors.eq(i).val(selectors.eq(i).attr('data-value'));
    }
    
    /* 初始化高级搜索选项 */
    $('#advance-search-container').hide();
    $('#advance-search-trigger').click(function() {
        $('#advance-search-container').toggle();
        if ( 'none' == $('#advance-search-container').css('display') ) {
            $('.advance-search-item').val('');
        }
    });
    
    /* 点击添加好友时， 打开对话框。 */
    $('.btn-add-as-friend-open-dialog').click(function() {
        if ( 1 > $('#result-container').attr('data-people-left')*1 ) {
            alert('已达到好友上限， 无法添加好友~~~');
            return false;
        }
        $('#add-friend-dialog').modal('show');
        $('#btn-add-as-friend').attr('data-recipient', $(this).attr('data-recipient'));
        return false;
    });
    
    /* 绑定点击事件到添加好友按钮 */
    $('#btn-add-as-friend').click(function() {
       $.post('/?module=account&action=friend/SendToBeFriendRequest', {
           recipient : $(this).attr('data-recipient'),
           message   : $('#add-as-friend-message').val(),
       }, function( response ) {
           alert('请求已发送，请等待对方确认。');
           $('#add-friend-dialog').modal('hide');
       }, 'text');
    });
    
    /* 清空好友信息请求 */
    $('#add-friend-dialog').on('hidden.bs.modal', function (e) {
        $('#btn-add-as-friend').attr('data-recipient', '');
        $('#add-as-friend-message').val('');
    });
});