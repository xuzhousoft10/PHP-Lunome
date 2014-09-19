/**
 * QuestionMarkdown
 * 
 * This script use to add tool bar to a textarea box and make it
 * to be a easy-using markdown editor for question module. It also
 * suilt for answer editor, cause it has same rules with question
 * describe.
 * 
 * @author Michael Luthor <michaelluthor@163.com>
 * @type jQuery textarea
 */
function QuestionMarkdown( textareaId ) {
	var textarea = $('#'+textareaId);
	this._createToolBar(textarea);
}

/**
 * Create a tool for editor.
 * 
 * @param jQuery textarea
 * @returns void
 */
QuestionMarkdown.prototype._createToolBar = function( textarea ) {
	var container = $('<div></div>').attr('data-editor', textarea.attr('id'));
	this._createToolBarItemHeader(container);
	this._createToolBarItemBold(container);
	this._createToolBarItemCode(container);
	this._createToolBarItemImage(container);
	this._createToolBarItemMarker(container);
	this._createToolBarItemBlockQuote(container);
	this._createToolBarItemHorizontal(container);
	this._createToolBarItemLink(container);
	this._createToolBarItemList(container);
	this._createToolBarItemHelp(container);
	this._createToolBarItemPreview(container);
	
	textarea.before(container);
};

/**
 * Create tool bar item for insert header
 * 
 * @param jQuery container
 * @returns void
 */
QuestionMarkdown.prototype._createToolBarItemHeader = function( container ) {
	var $this = this;
	var dropdown = $('<div class="dropdown" style="display:inline"></div>');
	
	var trigger = $('<a data-toggle="dropdown" href="#"></a>');
	trigger.html('<span class="glyphicon glyphicon-header"></span>');
	dropdown.append(trigger);
	
	var items = $('<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel"></ul>')
	for ( var i=1; i<=6; i++ ) {
		var item = $('<a></a>');
		item.attr('href', '#')
			.attr('data-level', i)
			.attr('data-editor', container.attr('data-editor'))
			.html('<strong>H'+i+'</strong>')
			.click(function() {
				$this._handleToolBarItemHeader($this, this);
				return false;
			});
		
		items.append($('<li></li>').append(item));
	}
	dropdown.append(items);
	
	container.append(dropdown);
};

/**
 * Handle tool bar item hander
 * 
 * @returns void
 */
QuestionMarkdown.prototype._handleToolBarItemHeader = function(editor, trigger) {
	var textarea = $(trigger).attr('data-editor');
	var selected = this._getSelectedTextFromTextarea(textarea);
	if ( 0 == selected.length ) {
		return;
	}
		
	var mark = '';
	for ( var i=0; i<$(trigger).attr('data-level'); i++ ) {
		mark += '#';
	}
	mark += (' ' + selected);
	editor._replaceSelectedTextInTextarea(textarea, mark);
	$('#'+textarea).click();
};

/**
 * Create tool bar item blod.
 * 
 * @returns void
 */
QuestionMarkdown.prototype._createToolBarItemBold = function(container) {
	this._addDefaultItemTrigger(container, 'bold', this._handleToolBarItemBold);
};

/**
 * Handle tool bar item bold
 * 
 * @returns void
 */
QuestionMarkdown.prototype._handleToolBarItemBold = function(editor, trigger) {
	var textarea = $(trigger).attr('data-editor');
	var selected = editor._getSelectedTextFromTextarea(textarea);
	if ( 0 == selected.length ) {
		return;
	}
	
	var mark = '**'+selected+'**';
	editor._replaceSelectedTextInTextarea(textarea, mark);
};

/**
 * Create tool bar item code
 * 
 * @returns void
 */
QuestionMarkdown.prototype._createToolBarItemCode = function(container) {
	this._addDefaultItemTrigger(container, 'copyright-mark', this._handleToolBarItemCode);
};

/**
 * Handle tool bar item code.
 * 
 * @returns void
 */
QuestionMarkdown.prototype._handleToolBarItemCode = function( editor, trigger ) {
	var textarea = $(trigger).attr('data-editor');
	var selected = editor._getSelectedTextFromTextarea(textarea);
	if ( 0 == selected.length ) {
		return;
	}
	
	selected = selected.split("\n");
	for ( var line in selected ) {
		selected[line] = '    ' + selected[line];
	}
	
	var jQueryTextarea = $('#'+textarea);
	var prevChar = jQueryTextarea.val().substring(jQueryTextarea[0].selectionStart-1, 1);
	if ( "\n" == prevChar ) {
		selected = "\n"+selected.join("\n");
	} else {
		selected = "\n\n"+selected.join("\n")+"\n\n";
	}
	editor._replaceSelectedTextInTextarea(textarea, selected);
};

/**
 * Create tool bar item image.
 * 
 * @returns void
 */
QuestionMarkdown.prototype._createToolBarItemImage = function(container) {
	this._addDefaultItemTrigger(container, 'picture', this._handleToolBarItemImage);
};

/**
 * Handle tool bar item image.
 * 
 * @returns void
 */
QuestionMarkdown.prototype._handleToolBarItemImage = function( editor, trigger ) {
	var textarea = $(trigger).attr('data-editor');
	var selected = editor._getSelectedTextFromTextarea(textarea);
	if ( 0 == selected.length ) {
		return;
	}
	selected = "[img:"+selected+"]";
	editor._replaceSelectedTextInTextarea(textarea, selected);
};

/**
 * Create tool bar item marker
 * 
 * @returns void
 */
QuestionMarkdown.prototype._createToolBarItemMarker = function( container ) {
	this._addDefaultItemTrigger(container, 'exclamation-sign', this._handleToolBarItemMarker);
};

/**
 * Handle tool bar item marker
 * 
 * @returns void
 */
QuestionMarkdown.prototype._handleToolBarItemMarker = function( editor, trigger ) {
	var textarea = $(trigger).attr('data-editor');
	var selected = editor._getSelectedTextFromTextarea(textarea);
	if ( 0 == selected.length ) {
		return;
	}
	
	if ( -1 == selected.indexOf("`") ) {
		selected = "`" + selected + "`";
	} else {
		selected = "``" + selected + "``";
	}
	editor._replaceSelectedTextInTextarea(textarea, selected);
};

/**
 * Create tool bar item block quote
 * 
 * @returns void
 */
QuestionMarkdown.prototype._createToolBarItemBlockQuote = function( container ) {
	this._addDefaultItemTrigger(container, 'comment', this._handleToolBarItemBlockQuote);
};

/**
 * Handle tool bar item block quote
 * 
 * @returns void
 */
QuestionMarkdown.prototype._handleToolBarItemBlockQuote = function( editor, trigger ) {
	var textarea = $(trigger).attr('data-editor');
	var selected = editor._getSelectedTextFromTextarea(textarea);
	if ( 0 == selected.length ) {
		return;
	}
	
	selected = selected.split("\n");
	for ( var line in selected ) {
		selected[line] = '> ' + selected[line];
	}
	
	selected = selected.join("\n");
	var jQueryTextarea = $('#'+textarea);
	var prevChar = jQueryTextarea.val().substring(jQueryTextarea[0].selectionStart-1, 1);
	if ( "\n" != prevChar ) {
		selected = "\n"+selected;
	}
	
	var nextChar = jQueryTextarea.val().substring(jQueryTextarea[0].selectionEnd+1, 1);
	if ( "\n" != nextChar ) {
		selected = selected+"\n\n";
	}
	
	editor._replaceSelectedTextInTextarea(textarea, selected);
};

/**
 * Create tool bar item horizontal
 * 
 * @returns void
 */
QuestionMarkdown.prototype._createToolBarItemHorizontal = function( container ) {
	this._addDefaultItemTrigger(container, 'minus', this._handleToolBarItemHorizontal);
};

/**
 * Handle tool bar item horizontal
 * 
 * @returns void
 */
QuestionMarkdown.prototype._handleToolBarItemHorizontal = function( editor, trigger ) {
	var textarea = $(trigger).attr('data-editor');
	var jQueryTextarea = $('#'+textarea);
	var pos = jQueryTextarea[0].selectionStart;
	var val = jQueryTextarea.val();
	var mark = "* * *";
	if ( 0 !==pos && "\n" != val.substr(pos-1, 1) ) {
		mark = "\n"+mark;
	}
	
	if ( "\n" != val.substr(pos+1, 1) ) {
		mark = mark+"\n";
	}
	
	var newValue = val.substr(0, pos) + mark + val.substr(pos);
	jQueryTextarea.val(newValue);
};

/**
 * Create tool bar item link
 * 
 * @returns void
 */
QuestionMarkdown.prototype._createToolBarItemLink = function( container ) {
	this._addDefaultItemTrigger(container, 'link', this._handleToolBarItemLink);
};

/**
 * Handler tool bar item link
 * 
 * @returns void
 */
QuestionMarkdown.prototype._handleToolBarItemLink = function( editor, trigger ) {
	var link = prompt("Paste url here:");
	var textarea = $(trigger).attr('data-editor');
	var selected = editor._getSelectedTextFromTextarea(textarea);
	if ( 0 == selected.length ) {
		return;
	}
	selected = "["+selected+"]("+link+")";
	editor._replaceSelectedTextInTextarea(textarea, selected);
};

/**
 * Create tool bar item list
 * 
 * @returns void
 */
QuestionMarkdown.prototype._createToolBarItemList = function( container ) {
	this._addDefaultItemTrigger(container, 'list', this._handleToolBarItemList);
};

/**
 * Handle tool bar item list
 * 
 * @returns void
 */
QuestionMarkdown.prototype._handleToolBarItemList = function( editor, trigger ) {
	var textarea = $(trigger).attr('data-editor');
	var selected = editor._getSelectedTextFromTextarea(textarea);
	selected = selected.split("\n");
	if ( 0 == selected.length ) {
		return;
	}
	
	for ( var line in selected ) {
		selected[line] = '+ ' + selected[line];
	}
	
	var jQueryTextarea = $('#'+textarea);
	var startPos = jQueryTextarea[0].selectionStart;
	var prevChar = jQueryTextarea.val().substring(startPos-1, 1);
	if ( 0 == startPos ) {
		selected = selected.join("\n");
	} else if ( "\n" == prevChar ) {
		selected = "\n"+selected.join("\n");
	} else {
		selected = "\n\n"+selected.join("\n")+"\n\n";
	}
	editor._replaceSelectedTextInTextarea(textarea, selected);
};

/**
 * Add a default item trigger
 * 
 * @return void
 */
QuestionMarkdown.prototype._addDefaultItemTrigger =function(container, icon, handler) {
	var $this = this;
	var item = $('<a></a>')
		.attr('href', '#')
		.attr('data-editor', container.attr('data-editor'))
		.click(function() {
			handler($this, this);
			var textarea = $(this).attr('data-editor');
			$('#'+textarea).trigger('update');
			return false;
		})
		.html('<span class="glyphicon glyphicon-'+icon+'"></span>');
	
	container.append('&nbsp;&nbsp;')
	container.append(item);
};

/**
 * Get selected text from text area
 * 
 * @returns string
 */
QuestionMarkdown.prototype._getSelectedTextFromTextarea = function( textarea ) {
	textarea = $('#'+textarea);
	var value = textarea.val();
	var start = textarea[0].selectionStart;
	var end = textarea[0].selectionEnd;
	return value.substring(start, end);
};

/**
 * Replace selected text in textarea
 * 
 * @returns void
 */
QuestionMarkdown.prototype._replaceSelectedTextInTextarea = function( textarea, newValue ) {
	textarea = $('#' + textarea);
	var oldVal = textarea.val();
	var start = textarea[0].selectionStart;
	var end = textarea[0].selectionEnd;
	var selected = oldVal.substring(start, end);
	
	var newVal = oldVal.substr(0, start);
	newVal += newValue;
	newVal += oldVal.substr(end);
	textarea.val(newVal);
};

/**
 * Create tool bar item preview.
 * 
 * @returns void
 */
QuestionMarkdown.prototype._createToolBarItemPreview = function( container ) {
	var $this = this;
	var item = $('<a></a>')
		.addClass('pull-right')
		.attr('href', '#')
		.attr('data-editor', container.attr('data-editor'))
		.click(function() {
			$this._handleToolBarItemPreview($this, this);
		})
		.html('<span class="glyphicon glyphicon-eye-open"></span>');
	
	container.append('<span class="pull-right">&nbsp;&nbsp;&nbsp;</span>')
	container.append(item);
};

/**
 * Handle tool bar item preview.
 * 
 * @returns void
 */
QuestionMarkdown.prototype._handleToolBarItemPreview = function ( editor, trigger ) {
	var textarea = $(trigger).attr('data-editor');
	textarea = $('#' + textarea);
	
	if ( 0 == textarea.val().length ) {
		alert('No content in the editor!');
		return false;
	} 
	
	var options = 
		'fullscreen=no,'+
		'toolbar=no,'+
		'location=0,'+
		'directories=no,'+
		'status=no,'+
		'menubar=no,'+
		'scrollbars=yes,'+
		'resizable=no,'+
		'copyhistory=no,'+
		'width=750,'+
		'height=600,'+
		'left=800,'+
		'top=50';
	window.open (textarea.attr('data-preview-url'),"Sample", options);
	return false;
};

/**
 * Create tool bar item help
 * 
 * @returns void
 */
QuestionMarkdown.prototype._createToolBarItemHelp = function( container ) {
	var $this = this;
	var item = $('<a></a>')
		.addClass('pull-right')
		.attr('href', '#')
		.attr('data-editor', container.attr('data-editor'))
		.click(function() {
			$this._handleToolBarItemHelp($this, this);
		})
		.html('<span class="glyphicon glyphicon-question-sign"></span>');
	
	container.append('<span class="pull-right">&nbsp;</span>')
	container.append(item);
};

/**
 * Handle tool bar item help
 * 
 * @returns void
 */
QuestionMarkdown.prototype._handleToolBarItemHelp = function( editor, trigger ) {
	var textarea = $(trigger).attr('data-editor');
	textarea = $('#' + textarea);
	var options = 
		'fullscreen=no,'+
		'toolbar=no,'+
		'location=0,'+
		'directories=no,'+
		'status=no,'+
		'menubar=no,'+
		'scrollbars=yes,'+
		'resizable=no,'+
		'copyhistory=no,'+
		'width=550,'+
		'height=600,'+
		'left=800,'+
		'top=50';
	window.open (textarea.attr('data-help-url'),"Sample", options);
	return false;
}