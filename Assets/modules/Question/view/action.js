$(document).ready(function(){
    $('textarea').elastic();
    var editor = new QuestionMarkdown('answer-edit-area-content');
    
    $('#answer-edit-area-related-add-button').click(function(){
        var releated = $.trim($('#answer-edit-area-related-link').val());
        if ( 0 == releated.length ) {
            return;
        }
        $('#answer-edit-area-related-link').val('');
        
        var releatedLink = releated;
        var urlRegexParten = /^([a-z]([a-z]|\d|\+|-|\.)*):(\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?((\[(|(v[\da-f]{1,}\.(([a-z]|\d|-|\.|_|~)|[!\$&'\(\)\*\+,;=]|:)+))\])|((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=])*)(:\d*)?)(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*|(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)){0})(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
        if(!urlRegexParten.test(releatedLink)) {
            releatedLink = 'https://www.google.com.hk/search?q='+releated;
        }
        
        $('#answer-edit-area-related-list').append($('<li></li>')
            .append($('<input type="hidden">')
                .attr('name', 'form[relations][R'+(new Date()).getTime()+']')
                .val(releated)
            )
            .append($('<a></a>')
                 .attr('href', '#')
                 .html('<span class="glyphicon glyphicon-trash"></span>')
                 .click(function(){
                     $(this).parent().empty().remove();
                     return false;
                 })
             )
            .append($('<span>&nbsp;&nbsp;&nbsp;</span>'))
            .append($('<a></a>').attr('target', '_black').attr('href', releatedLink).html(releated))
        );
    });
    
    $('#answer-save-button').click(function() {
        $('#answer-edit-area-content').parent().removeClass('has-error');
        if ( 0 == $('#answer-edit-area-content').val().length ) {
            $('#answer-edit-area-content').parent().addClass('has-error');
            return false;
        }
    });
    
    $('.question-answer-edit-button').click(function() {
        $('#answer-edit-area-answer-id').val($(this).attr('data-id'));
        $('#answer-edit-area-condition').val($(this).attr('data-condition'));
        $('#answer-edit-area-content').val($(this).attr('data-content'));
        $('#answer-edit-area-related-list').empty();
        for ( var i=0; i<$(this).attr('data-relation-count'); i++ ) {
            $('#answer-edit-area-related-link').val($(this).attr('data-relation-'+i));
            $('#answer-edit-area-related-add-button').trigger('click');
        }
        $('#answer-edit-area-content').trigger('update');
    });
    
    $('.question-answer-note-add-form').hide();
    $('.question-answer-note-add-button').click(function() {
        $($(this).attr('data-target')).toggle();
    });
    
    $('#question-note-add').hide();
    $('#question-note-add-button').click(function() {
        $('#question-note-add').toggle();
    });
    
    var onAttachSelectorChanged = function() {
    	var index = $('#answer-edit-attach-container').attr('data-index')*1;
        $('<p></p>').append(
            $('<input type="file" />')
                .attr('name', 'attach_'+index )
                .change(onAttachSelectorChanged)
        ).appendTo('#answer-edit-attach-container');
        $('#answer-edit-attach-container').attr('data-index', index+1);
    };
    
    $('.question_answer_attach').change(onAttachSelectorChanged);
});