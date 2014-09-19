$(document).ready(function(){
	$('textarea').elastic();
	$('#form-describe').trigger('update');
	
    /* Init the question markdown editor. */
	var editor = new QuestionMarkdown('form-describe');
    
	/*Bind event to question-edit-file-item */
    var deleteURL = $('#web-data-attach-delete-url').val();
    $('.question-edit-file-item').click(function() {
        var attachId = $(this).attr('data-attach');
        var url = deleteURL.replace('{$attach}', attachId);
        var trigger = $(this);
        trigger.parent().next().next().children().show();
        $.get(url, {}, function( response ) {
            if ( !response.hasError ) {
                trigger.parent().parent().remove();
            } else {
                trigger.parent().next().next().children().hide();
            }
        }, 'json');
        return false;
    });
    
    /*Manage file list.*/
    var fileList = [];
    var onFileSelectorChanged = function() {
        var id = $(this).attr('id');
        if ( -1 != fileList.indexOf(id) ) {
            return true;
        }
        
        fileList.push(id);
        $('<input type="file"/>')
            .attr('id', 'FID_'+((new Date()).getTime()+Math.random()+"").replace('.', ''))
            .change(onFileSelectorChanged)
            .attr('name', 'form[files][file_'+fileList.length+']')
            .appendTo('#question-edit-file-container');
    };
    $('#question-edit-file').change(onFileSelectorChanged);
    
    /*Bind event to save button. */
    $('#question-edit-save-button').click(function(){
        $('#form-subject').parent().parent().removeClass('has-error');
        
        if ( 0 == $.trim($('#form-subject').val()).length ) {
            $('#form-subject').parent().parent().addClass('has-error');
            return false;
        }
        
        return true;
    });
    
    $('#form-category-add-container').hide();
    /* Bind event to category button */
    $('#form-category-add-button').click(function() {
        $('#form-category-add-container').show();
    });
    
    $('#form-category-remove-add-button').click(function() {
        $('#form-category-new-input').val('');
        $('#form-category-add-container').hide();
    });
    
    function getQuestionLabelArrayFromLabelList() {
    	var labels = $('#question-edit-label-list').val();
    	if ( 0 == $.trim(labels).length ) {
    		labels = [];
    	} else {
    		labels = labels.split(',');
    	}
    	return labels;
    }
    
    function refreshQuestionLabelsFromLabelList() {
    	$('#question-edit-label-visible-list-container').empty();
    	var labels = getQuestionLabelArrayFromLabelList();
    	
    	if ( 4 == labels.length ) {
    		$('#question-edit-label-input-box-container').hide();
    	} else {
    		$('#question-edit-label-input-box-container').show();
    		$('#question-edit-label-input-box').val('');
    		$('#question-edit-label-input-box').focus();
    	}
    	
    	for ( var i=0; i<labels.length; i++ ) {
    		$('<div class="col-xs-3"></div>').append($('<div class="input-group"></div>').append(
    			$('<input type="text" class="form-control" disabled />').val(labels[i])
    		).append(
    			$('<span class="input-group-btn"></span>').append(
    				$('<button class="btn btn-default" type="button"></button>')
    				.attr('data-label-name', labels[i])
    				.click(function() {
    					var questionLabels = getQuestionLabelArrayFromLabelList();
    					var index = questionLabels.indexOf($(this).attr('data-label-name'));
    					if ( -1 == index ) {
    						return;
    					} else {
    						questionLabels.splice(index, 1);
    						$('#question-edit-label-list').val(questionLabels.join(','));
    				    	refreshQuestionLabelsFromLabelList();
    					}
    				}).append('<span class="glyphicon glyphicon-remove"></span>')
    			)
    		)).appendTo('#question-edit-label-visible-list-container');
    	}
    }
    
    refreshQuestionLabelsFromLabelList();
    
    $('#question-edit-label-add-button').click(function() {
    	var labels = getQuestionLabelArrayFromLabelList();
    	var newLabel = $.trim($('#question-edit-label-input-box').val());
    	if ( 0 == newLabel.length ) {
    		return;
    	}
    	
    	$('#question-edit-label-input-box').val('');
    	if ( -1 == labels.indexOf(newLabel) ) {
    		labels.push(newLabel);
    	} else {
    		return;
    	}
    	
    	$('#question-edit-label-input-box').val('');
    	$('#question-edit-label-list').val(labels.join(','));
    	refreshQuestionLabelsFromLabelList();
    });
    
    var substringMatcher = function() {
	return function findMatches(q, cb) {
		$.ajaxSetup({async: false});	
		var url = $('#web-data-label-hint-url').val();
	    var prefix = $('#question-edit-label-input-box').val();
	    var response = $.post(url, {prefix:prefix}, function() {}, 'json').responseJSON;
	    var matches = [];
	    var substrRegex = new RegExp(q, 'i');
	    	 
	    $.each(response, function(i, str) {
	    	if (substrRegex.test(str.name)) {
	    		matches.push({ value: str.name });
		    }
	    });
	    	 
	    cb(matches);
	    $.ajaxSetup({async: true});
	};};
    	 
	$('#question-edit-label-input-box').typeahead({
		hint: true,
		highlight: true,
		minLength: 1
	}, {
		name: 'states',
		displayKey: 'value',
		source: substringMatcher()
	});
});