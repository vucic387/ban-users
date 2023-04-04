jQuery(document).ready(function($) {

    $('.data-table').DataTable();

    $('#w3dev-table-banned-users').on('click', '.js-unban-user', function(e) {

    	var _this = $(this);
    	_this.addClass('success');
    	var _user_id   = _this.data('user-id');

        _this.find('.fa-stack-1x').removeClass('fa-ban').addClass('fa-spinner fa-spin fa-fw');
       
        var _row    = $('#w3dev-table-banned-users').find('#row-'+_user_id);
        var _pre    = _row.prev('tr');
        var _tbl    = _row.parent().parent();
        var table   = _tbl.DataTable();

        var data = {
            'action': 'w3dev_quick_unban_user',
            'user_id': _user_id
        };

        $.post(ajaxurl, data, function(response) {

	        setTimeout(function(){ 

		        _this.find('.fa-stack-1x').removeClass('fa-spinner fa-spin fa-fw').addClass('fa-check');
	        	setTimeout(function(){ table.row(_row).remove().draw(); }, 3000);

	        }, 250);

        });
       
    });
    
});