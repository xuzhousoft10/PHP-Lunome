$(document).ready(function() {
	var parentWindow = window.opener;
	var holder = parentWindow.document.getElementById($('#web-data-container').val());
	if ( null == holder ) {
	    alert("System error!");
	    window.opener=null;
	    window.open('','_self');
	    window.close();
	}
	
	$('#content').val(holder.value);
	$('#preview-form').submit();
});