$(document).ready(function() {
    /* 当省份变化时， 更新城市数据。 */
    $('#user-friend-search-living-province').change(function() {
        if ( 0 === $(this).val().length ) {
            $('#user-friend-search-living-city').empty();
            return;
        }
        $.get('/?module=lunome&action=region/getOption', {
            parent:$('#user-friend-search-living-province').val(),
            selected:$('#user-friend-search-living-city').attr('data-value')
        }, function( response ) {
            $('#user-friend-search-living-city').html(response);
        }, 'html');
    });
    
    /* 当国家变化时， 更新省份数据。 */
    $('#user-friend-search-living-country').change(function() {
        if ( 0 === $(this).val().length ) {
            $('#user-friend-search-living-province').val('').trigger('change').empty();
            return;
        }
        $.get('/?module=lunome&action=region/getOption', {
            parent:$('#user-friend-search-living-country').val(),
            selected:$('#user-friend-search-living-province').attr('data-value')
        }, function( response ) {
            $('#user-friend-search-living-province').html(response).trigger('change');
        }, 'html');
    });
    
    /* 初始化国家数据。 */
    $.get('/?module=lunome&action=region/getOption', {
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
});