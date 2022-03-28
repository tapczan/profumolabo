$(document).ready(function() {
	
	$(document).on('click', 'button[name=submitAddproduct], button[name=submitAddproductAndStay]', function(e) {
		e.preventDefault();
		
		if ($('#x13links-submit-hidden').length) {
			$('#x13links-submit-hidden').attr('name', $(this).attr('name'));
		} else {
			$('<input id="x13links-submit-hidden" type="hidden" name="' + $(this).attr('name') + '" value="1" />').appendTo('#product_form');
		}
		
		var data = $('input, textarea, select', '#product_form').not(':input[type=button], :input[type=submit], :input[type=reset]').serialize() + '&token=' + x13links_token;
		
		$.ajax({
			type: 'POST',
			url: x13links_ajax_url,
			dataType: 'json',
			data: data,
			success: function(jsonData)
			{
				if (typeof(jsonData.modal) != 'undefined') {
					$(jsonData.modal).appendTo('#content');
				} else {
					$('#product_form').submit();
				}
			}	
		});		
		
		return false;
	});
	
	$(document).on('click', '#x13links-cancel', function(e) {
		e.preventDefault();
		
		$('#x13links-modal').remove();
		$('#x13links-submit-hidden').remove();
		
		return false;
	});
	
	$(document).on('click', '#x13links-save', function(e) {
		e.preventDefault();
		
		$('#product_form').submit();
		
		return false;
	});
	
});