jQuery(document).ready(function($){

	var cartWrapper = $('.cd-cart-container');
	//product id - you don't need a counter in your real project but you can use your real product id
	var productId = 0;

	if( cartWrapper.length > 0 ) {
		//store jQuery objects
		var cartBody = cartWrapper.find('.body')
		var cartList = cartBody.find('ul').eq(0);
		var cartTotal = cartWrapper.find('.checkout').find('span');
		var cartTrigger = cartWrapper.children('.cd-cart-trigger');
		var cartCount = cartTrigger.children('.count')
		var addToCartBtn = $('.cd-add-to-cart');
		var undo = cartWrapper.find('.undo');
		var undoTimeoutId;

		//add product to cart
		addToCartBtn.on('click', function(event){
			event.preventDefault();
			addToCart($(this));
		});

		$(document).on('click', '.addtocartTrigger', function(e){

			e.preventDefault();

			window.setTimeout(addToCart2($(this)), 2000);

        });

		$(document).on('click', '.addtocartsingleTrigger', function(e){

			e.preventDefault();

			window.setTimeout(addToCart2($(this)), 2000);

        });


		$(document).on('click', '.updatecartTrigger', function(){

			tipo=$(this).data('tipo');

			id=$(this).data('id');

			cantidad=$(this).data('cantidad');
			
			//cantidad=$('#cantidad_'+id+'').val();

			if(tipo=='suma'){

				cantidad=parseInt(cantidad)+1;

			}else{

				cantidad=cantidad-1;

			}

			if (cantidad==0) {

				//alert($('#cd-product-'+id+'').parents('.product'));

				removeProduct($('#cd-product-'+id+'').parents('.product'));

			}else{

				$('#cd-product-'+id+'').val(cantidad);

			}

			


			quickUpdateCart();

		});

		$(document).on('click', '.updatecartsingleTrigger', function(){

			tipo=$(this).data('tipo');
			id=$(this).data('id');
			cantidad=$('#cantidad_'+id+'').val();

			//alert(cantidad+'-'+id+'-'+tipo);

			if(tipo=='suma'){

				cantidad=parseInt(cantidad)+1;

			}else{

				cantidad=cantidad-1;

			}

			//alert(cantidad+'-'+id+'-'+tipo);

			if (cantidad==0) {

				alert($('#cd-product-'+id+'').parents('.product'));

				removeProduct($('#cd-product-'+id+'').parents('.product'));

			}else{

				$('#cd-product-'+id+'').val(cantidad);

			}

			quickUpdateCart();

		});

		//open/close cart
		cartTrigger.on('click', function(event){
			event.preventDefault();
			toggleCart();
		});

		//close cart when clicking on the .cd-cart-container::before (bg layer)
		cartWrapper.on('click', function(event){
			if( $(event.target).is($(this)) ) toggleCart(true);
		});

		//delete an item from the cart
		cartList.on('click', '.delete-item', function(event){
			event.preventDefault();
			removeProduct($(event.target).parents('.product'));
		});

		//update item quantity
		cartList.on('change', 'select', function(event){
			quickUpdateCart();

		});

		//reinsert item deleted from the cart
		undo.on('click', 'a', function(event){
			clearInterval(undoTimeoutId);
			event.preventDefault();
			cartList.find('.deleted').addClass('undo-deleted').one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(){
				$(this).off('webkitAnimationEnd oanimationend msAnimationEnd animationend').removeClass('deleted undo-deleted').removeAttr('style');
				quickUpdateCart();
			});
			undo.removeClass('visible');
		});
	}

	function toggleCart(bool) {
		var cartIsOpen = ( typeof bool === 'undefined' ) ? cartWrapper.hasClass('cart-open') : bool;
		
		if( cartIsOpen ) {
			cartWrapper.removeClass('cart-open');

			$('.checkout').css('background', '#fff');
			//reset undo
			clearInterval(undoTimeoutId);

			undo.removeClass('visible');
			cartList.find('.deleted').remove();

			setTimeout(function(){
				cartBody.scrollTop(0);
				//check if cart empty to hide it
				if( Number(cartCount.find('li').eq(0).text()) == 0) cartWrapper.addClass('empty');
			}, 500);
		
		} else {

			cartWrapper.addClass('cart-open');

			$('.checkout').css('background', '#2c97de');

			
		}
	}

	function addToCart2(trigger) {

		var cartIsEmpty = cartWrapper.hasClass('empty');
		//update cart product list
		addProduct2(trigger);
		//update number of items 
		updateCartCount(cartIsEmpty);
		//update total price
		updateCartTotal(trigger.data('price'), true);
		//show cart
		cartWrapper.removeClass('empty');
	}

	function addToCart(trigger) {

		var cartIsEmpty = cartWrapper.hasClass('empty');
		//update cart product list
		addProduct();
		//update number of items 
		updateCartCount(cartIsEmpty);
		//update total price
		updateCartTotal(trigger.data('price'), true);
		//show cart
		cartWrapper.removeClass('empty');
	}

	function addProduct() {
		//this is just a product placeholder
		//you should insert an item with the selected product info
		//replace productId, productName, price and url with your real product info
		productId = productId + 1;
		var productAdded = $('<li class="product"><div class="product-image"><a href="#0"><img src="img/product-preview.png" alt="placeholder"></a></div><div class="product-details"><h3><a href="#0">Product Name</a></h3><span class="price">$25.99</span><div class="actions"><a href="#0" class="delete-item">Delete</a><div class="quantity"><label for="cd-product-'+ productId +'">Qty</label><span class="select"><select id="cd-product-'+ productId +'" name="quantity"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option></select></span></div></div></div></li>');
		cartList.prepend(productAdded);
	}

	function addProduct2(trigger) {
		//this is just a product placeholder
		//you should insert an item with the selected product info
		//replace productId, productName, price and url with your real product info


		precio=trigger.data('id').toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&.'); 
		precio.slice(0,-3);

		productId = trigger.data('id');

		var productAdded = $('<li class="product"><div class="product-image"><a href="#0"><img class="img-responsive" src="'+trigger.data('imagen')+'" alt="'+trigger.data('name')+'"></a></div><div class="product-details"><h3><a href="#0">'+trigger.data('name')+'</a></h3><span class="price">'+trigger.data('price')+'</span><div class="actions"><a href="#0" data-id="'+trigger.data('id')+'" data-slug="'+trigger.data('slug')+'" class="delete-item col-xs-3">Borrar</a><div class="quantity col-xs-8"><label for="cd-product-'+ productId +'">Cantidad: </label><span class="select"><select class="cartselect" data-id="'+trigger.data('id')+'"  data-slug="'+trigger.data('slug')+'" id="cd-product-'+ productId +'" name="quantity"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option></select></span></div></div></div></li>');
		
		cartList.prepend(productAdded);

	}

	function removeProduct(product) {

		clearInterval(undoTimeoutId);

		cartList.find('.deleted').remove();
		
		var topPosition = product.offset().top - cartBody.children('ul').offset().top ,
			productQuantity = Number(product.find('.quantity').find('select').val()),
			productTotPrice = Number(product.find('.price').text().replace('$', '')) * productQuantity;
		
		product.css('top', topPosition+'px').addClass('deleted');

		//update items count + total price
		updateCartTotal(productTotPrice, false);
		updateCartCount(true, -productQuantity);
		//undo.addClass('visible');

		//wait 8sec before completely remove the item
		undoTimeoutId = setTimeout(function(){
			undo.removeClass('visible');
			cartList.find('.deleted').remove();
		}, 8000);
	}

	function quickUpdateCart() {
		var quantity = 0;
		var price = 0;
		
		cartList.children('li:not(.deleted)').each(function(){
			var singleQuantity = Number($(this).find('select').val());
			quantity = quantity + singleQuantity;
			price = price + singleQuantity*Number($(this).find('.price').text().replace('$', ''));
		});

		cartTotal.text(price);
		cartCount.find('li').eq(0).text(quantity);
		cartCount.find('li').eq(1).text(quantity+1);
	}

	function updateCartCount(emptyCart, quantity) {
		if( typeof quantity === 'undefined' ) {
			var actual = Number(cartCount.find('li').eq(0).text()) + 1;
			var next = actual + 1;
			
			if( emptyCart ) {
				cartCount.find('li').eq(0).text(actual);
				cartCount.find('li').eq(1).text(next);
			} else {
				cartCount.addClass('update-count');

				setTimeout(function() {
					cartCount.find('li').eq(0).text(actual);
				}, 150);

				setTimeout(function() {
					cartCount.removeClass('update-count');
				}, 200);

				setTimeout(function() {
					cartCount.find('li').eq(1).text(next);
				}, 230);
			}
		} else {
			var actual = Number(cartCount.find('li').eq(0).text()) + quantity;
			var next = actual + 1;
			
			cartCount.find('li').eq(0).text(actual);
			cartCount.find('li').eq(1).text(next);
		}
	}

	function updateCartTotal(price, bool) {
		bool ? cartTotal.text( (Number(cartTotal.text()) + Number(price)) )  : cartTotal.text( (Number(cartTotal.text()) - Number(price)) );
	}
});