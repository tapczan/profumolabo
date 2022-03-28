var customization_idpa = false;

$(document).ready(function(){
	if ($('.product-customization').length) {
		
		prestashop.on('updatedProduct', function(event) { 
			if (event.id_product_attribute) {
				if (!customization_idpa) {
					$('<input type="hidden" name="id_product_attribute" id="customization-idpa" value="' + event.id_product_attribute + '" />').insertBefore('button[name=submitCustomizedData]');
				} else {
					$('#customization-idpa').val(event.id_product_attribute);
				}
			}
		});		
		
	}
});
