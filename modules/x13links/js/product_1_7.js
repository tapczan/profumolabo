$(document).ready(function() {

	$('#form').submit(function(event) {		
		var data = $('input, textarea, select', '#form').not(':input[type=button], :input[type=submit], :input[type=reset]').serialize() + '&token=' + x13links_token;
		
		$.ajax({
			type: 'POST',
			url: x13links_ajax_url,
			dataType: 'json',
			data: data,
			success: function(jsonData)
			{
				if (typeof(jsonData.duplicates) != 'undefined') {
					showErrorMessage(jsonData.message);
				}
			}	
		});	
		
		return false;	
	});	
});