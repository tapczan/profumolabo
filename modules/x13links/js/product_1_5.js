var continue_form = false;

$(document).ready(function() {
	
	$(document).on('submit', '#product_form', function(e) {
		if (continue_form) {
			return true;
		}
		
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

				  continue_form = true;
				  $('#product_form').submit();
/*
				 var btn_submit = $('#product_form_submit_btn');

				  // Avoid double click
				  if (submited)
					return false;
				  submited = true;

				  //add hidden input to emulate submit button click when posting the form -> field name posted
				  btn_submit.before('<input type="hidden" name="' + btn_submit.attr("name") + '" value="1" />');

				  $('#product_form').submit();
				  return false;

*/
				}
			}	
		});		
		
		return false;
	});
	
	$(document).on('click', '#x13links-cancel', function(e) {
		e.preventDefault();
		
		$('#x13links-modal').remove();
		$('#x13links-submit-hidden').remove();
		
		var btn_submit = $('#product_form_submit_btn');
		$('input[type=hidden][name=' + btn_submit.attr("name") + ']').remove();
		submited = false;
		
		return false;
	});
	
	$(document).on('click', '#x13links-save', function(e) {
		e.preventDefault();
		
		continue_form = true;
		$('#product_form').submit();
		
		return false;
	});
	
});