$(document).ready(function() {
    /* 当省份变化时， 更新城市数据。 */
    $('#user-setting-information-living-province').change(function() {
        if ( 0 === $(this).val().length ) {
            $('#user-setting-information-living-city').empty();
            return;
        }
        $.get('/?module=lunome&action=region/getOption', {
            parent:$('#user-setting-information-living-province').val(),
            selected:$('#user-setting-information-living-city').attr('data-value')
        }, function( response ) {
            $('#user-setting-information-living-city').html(response);
        }, 'html');
    });
    
    /* 当国家变化时， 更新省份数据。 */
    $('#user-setting-information-living-country').change(function() {
        if ( 0 === $(this).val().length ) {
            $('#user-setting-information-living-province').val('').trigger('change').empty();
            return;
        }
        $.get('/?module=lunome&action=region/getOption', {
            parent:$('#user-setting-information-living-country').val(),
            selected:$('#user-setting-information-living-province').attr('data-value')
        }, function( response ) {
            $('#user-setting-information-living-province').html(response).trigger('change');
        }, 'html');
    });
    
    /* 初始化国家数据。 */
    $.get('/?module=lunome&action=region/getOption', {
        selected:$('#user-setting-information-living-country').attr('data-value')
    }, function( response ) {
        $('#user-setting-information-living-country').html(response).trigger('change');
    }, 'html');
    
    /* 声明生日输入框为时间选择器 */
    if ( '0000-00-00' === $('#user-setting-information-birthday').val() ) {
        $('#user-setting-information-birthday').val('');
    }
    $('#user-setting-information-birthday').datepicker({
        autoclose : true,
        clearBtn  : true,
        format    : 'yyyy-mm-dd',
        language  : 'zh-CN'
    });
    
    /* 初始化下拉框的值 */
    var selectors = $('.value-init-required');
    for ( i=0; i<selectors.length; i++ ) {
        selectors.eq(i).val(selectors.eq(i).attr('data-value'));
    }
});