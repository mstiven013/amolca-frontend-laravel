jQuery(function($) {

	//Button loop function
	$('.cart-btn').on('click', function() {

		if($(this).attr('disabled') !== 'disabled') {
			$(this).attr('disabled', 'disabled');

			let parent = $($(this)).parent('.btns');

			//Boook info
			let add = {
				"quantity": 1,
				"object_id": $(parent).find('.book-id').val(),
				"price": $(parent).find('.book-price').val(),
				"_token": $('meta[name="csrf-token"]').attr('content'),
				"action": "add"
			}

			AddCartProdut(add, 'global');
		}

	});

	// Change quantity en cart page
	$('input.qty').on('change', function() {

		let row = $(this).parent().parent();
		let col = row.find('td.actions');

		let item = {
			"object_id": col.find('.book-id').val(),
			"quantity": $(this).val(),
			"price": col.find('.book-price').val(),
			"_token": $('meta[name="csrf-token"]').attr('content'),
			"action": "update"
		};

		if($(this).val() >= 1){
			AddCartProdut(item, 'cart', $(row.parent()).find('.actions'));
		} else if($(this).val() < 1) {
			DeleteCartProduct(item, 'cart');
		}

	});

	// Button delete product in cart page
	$('button.delete').on('click', function() {
		
		if($(this).attr('disabled') !== 'disabled') {
			$(this).attr('disabled', 'disabled');

			let parent = $(this).parent();

			let item = {
				"object_id": parent.find('.book-id').val(),
				"quantity": $(this).val(),
				"price": parent.find('.book-price').val(),
				"_token": $('meta[name="csrf-token"]').attr('content'),
				"action": "delete"
			};

			DeleteCartProduct(item, 'cart');
		}
	});

	$('.add-to-cart .quantity').on('change', function() {
		if($(this).val() !== '' && $(this).val() > 0) {
			$('.error-cart').css('display', 'none');
		}
	});

	// Change quantity en cart page
	$('.add-to-cart .add-btn').on('click', function(e) {

		e.preventDefault();

		if($('.add-to-cart .quantity').val() == '') {
			$('.error-cart').html('El campo de cantidad no puede estar vacío.');
			$('.error-cart').css('display', 'block');

			return false;
		}

		if($('.add-to-cart .quantity').val() < 1) {
			$('.error-cart').html('El campo de cantidad debe ser mayor a 0.');
			$('.error-cart').css('display', 'block');

			return false;
		}

		let parent = $(this).parent();

		let item = {
			"object_id": parent.find('.book-id').val(),
			"quantity": parent.find('.quantity').val(),
			"price": parent.find('.book-price').val(),
			"_token": $('meta[name="csrf-token"]').attr('content'),
			"action": "update"
		};

		AddCartProdut(item, 'book');

	});

});

const AddCartProdut = (added, page, actions = null) => {

	// console.log(added)

	if($('.loader.fixed').hasClass('hidde')) {
		$('.loader.fixed').removeClass('hidde');
	}

	$.ajax({
		method: "POST",
		url: '/carts',
		data: added
	}).done(function(resp) {

		//console.log(resp)

		$('.top-bar #cart-btn span').html(resp.amountstring);
		$('.cart-btn').removeAttr('disabled');

		if(page == 'cart') {
			$('.cart-totals tr#subtotal td').html(resp.amountstring);
			$('.cart-totals tr#total #price').html(resp.amountstring);

			$('table.cart tbody tr td.actions').each(function() {

				let row = actions;

				let book_id = actions.find('.book-id').val();
				let result = resp.products.filter(product => product.object_id == added.object_id);

				let price = result[0].pricestring;
				let total = result[0].totalstring;

				let price_col = $('tr#' + result[0].object_id).find('td.price');
				let total_col = $('tr#' + result[0].object_id).find('td.total');

				$(price_col).html(price);
				$(total_col).html(total);

			});
		} else {
			let itemNotification = resp.products.filter(product => product.object_id == added.object_id);
			let message = `El libro <b>${itemNotification[0].title}</b>, se agregó exitosamente a tu carrito de compras.`;

			ShowModalResponse('check', message);
		}

		if(!$('.loader.fixed').hasClass('hidde')) {
			$('.loader.fixed').addClass('hidde');
		}
			

	}).catch(function(err){
		console.log(err)
	})

}

const DeleteCartProduct = (deleted, page) => {
	//console.log(deleted)

	if($('.loader.fixed').hasClass('hidde')) {
		$('.loader.fixed').removeClass('hidde');
	}

	$.ajax({
		method: "POST",
		url: '/carts',
		data: deleted
	}).done(function(resp) {

		//console.log(resp)

		if(resp.products === undefined || resp.products === null || resp.products.length < 1) {
		    //console.log('reload')
			window.location.href = window.location.href;
		}

		$('.top-bar #cart-btn span').html(resp.amountstring);

		switch (page) {
			case 'cart':

				$('.cart-totals tr#subtotal td').html(resp.amountstring);
				$('.cart-totals tr#total #price').html(resp.amountstring);
				$('tr#' + deleted.object_id).remove();

				break;
		}

		$('button.delete').removeAttr('disabled');
		if(!$('.loader.fixed').hasClass('hidde')) {
			$('.loader.fixed').addClass('hidde');
		}	

	}).catch(function(err){
		console.log(err)

		let checkIcon = 'icon-check1';
		let errorIcon = 'icon-close1';

		//Agregar el clase al icono de error
		if($('#notification-modal #resp-icon a .icono').hasClass(checkIcon)) {
			$('#notification-modal #resp-icon a .icono').removeClass(checkIcon);
			$('#notification-modal #resp-icon a .icono').addClass(errorIcon);
		}

		//Agregar el color al icono de error
		if($('#notification-modal #resp-icon a').hasClass('check')) {
			$('#notification-modal #resp-icon a').removeClass('check');
			$('#notification-modal #resp-icon a').addClass('error');
		}
	})
}

const ShowModalResponse = (action, msg) => {

	let checkIcon = 'icon-check1';
	let errorIcon = 'icon-close1';

	switch (action) {
		case 'error':

			//Agregar el clase al icono de error
			if($('#notification-modal #resp-icon a .icono').hasClass(checkIcon)) {
				$('#notification-modal #resp-icon a .icono').removeClass(checkIcon);
				$('#notification-modal #resp-icon a .icono').addClass(errorIcon);
			}

			//Agregar el color al icono de error
			if($('#notification-modal #resp-icon a').hasClass('check')) {
				$('#notification-modal #resp-icon a').removeClass('check');
				$('#notification-modal #resp-icon a').addClass('error');
			}
			break;

		case 'check':

			//Agregar el clase al icono de error
			if($('#notification-modal #resp-icon a .icono').hasClass(errorIcon)) {
				$('#notification-modal #resp-icon a .icono').removeClass(errorIcon);
				$('#notification-modal #resp-icon a .icono').addClass(checkIcon);
			}

			//Agregar el color al icono de error
			if($('#notification-modal #resp-icon a').hasClass('error')) {
				$('#notification-modal #resp-icon a').removeClass('error');
				$('#notification-modal #resp-icon a').addClass('check');
			}
			break;
	}

	$('#notification-modal #resp-text').html(msg);

	$('#notification-modal').modal();
	$('#notification-modal').modal('open');

	setTimeout(function() {
		$('#notification-modal').modal('close');
	}, 6000);
}