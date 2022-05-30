$(document).ready(function(){
	if((typeof config_personal != 'undefined') && !config_personal) {
		$('body').on('input', 'input#vat_number', function(){
			toggleinvoice();
		});
		toggleinvoice();
	}

	$(document).on('change', '[name="recieptorinvoice"]', function () {
		recieptorinvoice();
	});

	$(document).on('click', '.x13recieptorinvoice span label', function() {
		$('input#' + $(this).prop('for')).trigger('click').trigger('change');
	});

	// the checkout && supercheckout
	if ($('#tc-container').length || $('#module-supercheckout-supercheckout').length) {
		setTimeout(function() {
			recieptorinvoice();
		}, 4000);
	}

	// onepagecheckoutps
	if ($('#onepagecheckoutps_contenedor').length) {
		$(document).on('opc-load-review:completed', function(e) {
			recieptorinvoice();
	    });	
	} else {
		recieptorinvoice();
	}

	// steasycheckout
	if (typeof steco !== 'undefined' && typeof prestashop !== 'undefined') {
		prestashop.on('steco_event_updated', function () {
			recieptorinvoice();
		});
	}

	if (typeof x13currentChoice !== 'undefined' && x13currentChoice == 'none') {
		// $('<div class="alert alert-warning" id="x13noDocumentWarning">' + x13noDocumentWarning + '</div>').insertBefore('#HOOK_PAYMENT');
		// x13opc
		$('#submitOrder').css('pointer-events', 'none').fadeTo(300, .5);
	}

	if (typeof x13defaultNoDocument !== 'undefined' && x13defaultNoDocument == true) {
		var callerBtn = '#HOOK_PAYMENT .payment_module, #HOOK_PAYMENT .payment_module_real';

		if (!x13is16opc && typeof prestashop == 'undefined') {
			switch (x13iorHook) {
				case 'beforeCarrier':
					callerBtn = 'button[name="processCarrier"]';
					break;

				case 'displayShoppingCartFooter':
					callerBtn = '#order .standard-checkout.button';
					break;

				default:
					break;
			}
		}

		if (typeof prestashop !== 'undefined') {
			switch (x13iorHook) {
				case 'beforeCarrier':
					callerBtn = 'button[name="confirmDeliveryOption"]';
					break;

				case 'displayShoppingCartFooter':
					callerBtn = '#cart .checkout.cart-detailed-actions .btn';
					break;

				case 'displayPaymentTop':
					callerBtn = '#conditions-to-approve input';
					break;
			
				default:
					break;
			}
		}

		$(document).on('click', callerBtn, function(e) {
			var roi = $('[name="recieptorinvoice"]:checked').val();
			if (!roi) {
				alert(x13noDocumentWarning);
				$('html,body').animate({scrollTop: $('#x13recieptorinvoice_wrapper').offset().top-150}, 'slow');
				e.preventDefault();
				e.stopPropagation();
				return false;
			}
		});
	}
});

function recieptorinvoice() {
	if ($('[name="recieptorinvoice"]').length) {
		var roi = $('[name="recieptorinvoice"]:checked').val();
		if (!roi) {
			return;
		}
		x13currentChoice = roi;

		if (x13currentChoice !== 'none') {
			$('#x13noDocumentWarning').hide();
			// x13opc
			$('#submitOrder').css('pointer-events', 'all').fadeTo(300, 1);
		}

		var request = {
			use_parent_structure: false,
			data: {
				'method' : 'setForCart',
				'data' : {
					'id_cart' : roi_id_cart,
					'recieptorinvoice' : roi
				}
			}
		};
		var path;
		if (typeof baseDir == 'undefined' && typeof prestashop != 'undefined') {
			path = prestashop.urls.base_url;
		} else {
			path = baseDir;
		}
		$.post(path + 'modules/x13paragonlubfaktura/ajax.php', request, function(d){
			// callback
		});
	}
}

function toggleinvoice(){
	if (typeof prestashop !== 'undefined' || $('#HOOK_SHOPPING_CART').length > 0) {
		return;
	}
	
	if($('#address_invoice .address_vat_number').length > 0) {
		$('#x13recieptorinvoice-invoice input').removeAttr('disabled');
		$('#x13recieptorinvoice-invoice').css('opacity', 1);
		$('.x13recieptorinvoice-error').hide();
	}
	else {
		$('#x13recieptorinvoice-invoice input').attr('disabled', 'disabled');
		$('#x13recieptorinvoice-invoice').css('opacity', 0.4);
		$('#recieptorinvoice_reciept').trigger('click');
		$('.x13recieptorinvoice-error').show();
	}
	if($('input#vat_number').length > 0) {
		if($('input#vat_number').val().replace(' ', '').length > 0) {
			$('#x13recieptorinvoice-invoice input').removeAttr('disabled');
			$('#x13recieptorinvoice-invoice').css('opacity', 1);
			$('.x13recieptorinvoice-error').hide();
		}
		else {
			$('#x13recieptorinvoice-invoice input').attr('disabled', 'disabled');
			$('#x13recieptorinvoice-invoice').css('opacity', 0.4);
			$('.x13recieptorinvoice-error').show();
			$('#recieptorinvoice_reciept').trigger('click');
		}
	}
}
