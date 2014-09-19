$(document).ready(function() {
    $('.category-list-item-delete-link').click(function() {
        return confirm('Are you sure to delete this category ?');
    });
    
    $('.index-category-edit-button').click(function(){
        $('#index-category-edit-name').val($(this).attr('data-name'));
        $('#index-category-edit-id').val($(this).attr('data-id'));
    });
});