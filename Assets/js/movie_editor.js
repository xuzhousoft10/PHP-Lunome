$(document).ready(function() {
    /* 该按钮用来展开与合并不重要的电影信息。 */
    $('#more-information-trigger').click(function() {
        var isOpen = 'open' == $(this).attr('data-status');
        $(this).html('');
        $(this).attr('data-status', isOpen ? 'closed' : 'open');
        
        if ( isOpen ) {
            $(this).html('<span class="glyphicon glyphicon-chevron-down"></span> 展开');
            $('#more-information-container').hide();
        } else {
            $(this).html('<span class="glyphicon glyphicon-chevron-up"></span> 关闭');
            $('#more-information-container').show();
        }
        
        return false;
    });
    $('#more-information-container').hide();
    
    /* 新建封面文件选择元素。 */
    var generateCoverFileElem = function() {
        $('<input id="movie-cover-file" name="movie[cover]" type="file" class="hidden">')
        .change(function() {
            $('#movie-cover-file-trigger').text('已选择').removeClass('btn-success').addClass('btn-primary');
            
            var file = $('#movie-cover-file')[0].files[0];
            var imageType = /image.*/;     
            if (!file.type.match(imageType)) {
                alert("合作一下嘛～～～ 封面只能是图片呦～～～");
                $('#movie-cover-file-trigger').text('选择电影封面').removeClass('btn-primary').addClass('btn-success');
                $(this).remove();
                generateCoverFileElem();
            }
            
            var img = $('#movie-cover-previewer')[0];
            img.file = file;
            var reader = new FileReader();
            reader.onload = (function(aImg) { 
                return function(e) { 
                    aImg.src = e.target.result;
                    $('#movie-cover-previewer').width(200).height(300);
                }; 
            })(img);
            reader.readAsDataURL(file);
        })
        .appendTo('#movie-cover-select-container');
    }
    generateCoverFileElem();
    
    /* 使用其他按钮代替默认的文件选择按钮。 */
    $('#movie-cover-file-trigger').click(function() {
        $('#movie-cover-file').trigger('click');
    });
    
    /* 初始化日期选择器 */
    $('#movie-data').datepicker({format: 'yyyy-mm-dd', language:'zh-CN'});
    
    /* 长度限制为只能为数字。 */
    $('#movie-length').keyup(function(){
        var tmptxt = $(this).val();
        $(this).val(tmptxt.replace(/\D|^0/g,''));
    })
    .bind('paste',function(){
        var tmptxt = $(this).val();
        $(this).val(tmptxt.replace(/\D|^0/g,''));
    })
    .css("ime-mode", "disabled");
    
    /* 更多分类只有点击更多之后才显示 */
    $('#more-categories-container').hide();
    $('#more-categories-container-trigger').click(function() {
        $('#more-categories-container').toggle();
        return false;
    });
});