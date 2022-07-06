$(document).ready(function(){
	const url = "../engine/requests.php";
	const type = "POST";

	$("#add_product").on("submit",function(e){
		e.preventDefault()

		const data = new FormData(this)
		$.ajax({
			url,
			type,
			data,
			contentType : false,
			processData : false,
			success : function(response){
				if (response != '') {
					alert(response)
				}
				$(".modal").find('.close').click();
				getAllProducts()
			}
		})

	})



	function drawProduct(datas){
		let html = ''

		for (const data of datas){
			let {id,name,price,size,brand,image} = data


			html+=`<tr data-id="${id}">
				<td><img src="${image}" class="table_img" alt="Nkar chka"></td></td>
				<td>${name}</td>
				<td>${price}$</td>
				<td>${size}</td>
				<td>${brand}</td>
				<td>
					<button type="button" class="btn btn-danger delate">Delate</button>
					<button type="button" class="btn btn-warning read">Read</button>
					<button type="button" class="btn btn-success edit">Edit</button>
				</td>


			</tr>`
		}

		$("#table_body").html(html);
	}

	function drawProductMainPage(datas){
		let html = ''

		for (const data of datas){
			let {id,name,price,size,brand,image,sale} = data

			var price_html

			if (sale != 0) {

				let sale_price = price/100*sale
	
				let end_price = Math.floor(price-sale_price)
				
				price_html = '<span class="hk-td-del">'+price+'$'+'</span>'+end_price+'$'
			} else {
				price_html = price+'$'
			}

			html+=`
			<div class="product_item" data-id="${id}">
			<div class="product_image">
				<img src="${image}" alt="">
			</div>
			<div class="product_content">
				<h4 class="product_title">${name}</h4>
				<p class="product_price">${price_html}</p>
				<div class="product_btn">
					<a href="#">Посмотреть</a>
				</div>
			</div>
		</div>

			`
		}

		$("#product_inner_main").html(html);
	}

	function drawSearchProducts(datas){
		let html = ''



		for (const data of datas){
			let {id,name,price,size,brand,image,sale} = data


			html+=`
				<div class="search_helper product_item" data-id="${id}">
					<div class="search_image">
						<img src="${image}" alt="">
					</div>
					<h4 class="product_title">${name}</h4>
				</div>
			
			`
		}

		$("#draw_search").html(html);
	}

	function getAllProducts(){
		$.ajax({
			url,
			type,
			data: {get_all_products: 'getAll'},
			success: function(response){
				drawProduct(JSON.parse(response))
				drawProductMainPage(JSON.parse(response))
			}
		})

	}

	$(document).on('click','.delate',function(){
		const parent = $(this).parents('tr')
		const id = parent.data('id')
		$.ajax({
			url,
			type,
			data:{delete_product: id},
			success: function(){
				parent.remove();
			}
		})
	});


	$(".like_form").on("submit",function(e){
		e.preventDefault()

		const data = new FormData(this)
		$.ajax({
			url,
			type,
			data,
			contentType : false,
			processData : false,
			success : function(response){
				let likes_count = document.querySelectorAll('.like_count')
				likes_count[e.target.id].innerHTML = response


			}
		})

	})

	$(".comment_form").on("submit",function(e){
		e.preventDefault()

		const data = new FormData(this)
		$.ajax({
			url,
			type,
			data,
			contentType : false,
			processData : false,
			success : function(response){
				let comment = JSON.parse(response);


				
				reloadComments(comment,e.target.id)
			}
		})
	

	})

	function reloadComments(comment,id){
		let allComments = document.querySelectorAll('.comments')

		allComments[id].innerHTML +=
		`
		<div class="comment_user">
			<a href="#" class="icon_author">
				<img src="${comment.main__img}" alt="">
			</a>
			<div class="comment_content my-comment">
				<h6>${comment.name} ${comment.surname}</h6>
				<p>${comment.comment_content}</p>

			</div>
		</div>
		`
	}


	$('#search').on('keyup',function(){
		let value = $('#search').val()
		$.ajax({
			url,
			type,
			data: {search_product: value},
			success : function(response){
				drawProduct(JSON.parse(response))
			}
		})
	})

	$('#main_product_search').on('keyup',function(){
		let value = $('#main_product_search').val()
		if (value != '') {
			$.ajax({
				url,
				type,
				data: {search_product: value},
				success : function(response){
					if (response!='[]') {
						drawSearchProducts(JSON.parse(response))
						$('#draw_search').css('display','block')
					}	
				}
			})
		} else {
			$('#draw_search').css('display','none')
		}
	})

	$(document).on('click','.read',function(){
		const parent = $(this).parents('tr')
		const id = parent.data('id')
		const modal = $('#readModal')
		$.ajax({
			url,
			type,
			data:{read_product: id},
			success: function(response){
				let {id,name,price,size,brand,description,sale,image} = JSON.parse(response)[0]


				modal.modal('toggle')
				
				$('#read_image').attr('src',image)

				$('#read_name').html(name)
				$('#read_price').html(price+'$')
				$('#read_brand').html(brand)
				$('#read_desctription').html(description)
				$('#read_sale').html(sale)
				$('#read_size').html(size)





			}
		})
	});


	$(document).on('click','.edit',function(){
		const parent = $(this).parents('tr')
		const id = parent.data('id')
		const modal = $('#edit__modal')
		$.ajax({
			url,
			type,
			data:{read_product: id},
			success: function(response){
				let {id,name,price,size,brand,description,sale,image} = JSON.parse(response)[0]


				modal.modal('toggle')
				
				modal.find('input[name="edit_product_id"]').val(id)
				modal.find('input[name="hidden_product_img"]').val(image)
				modal.find('input[name="product_edit_name"]').val(name)
				modal.find('input[name="product_edit_price"]').val(price)
				modal.find('input[name="product_edit_size"]').val(size)
				modal.find('input[name="product_edit_brand"]').val(brand)
				modal.find('textarea[name="product_edit_description"]').val(description)
				// console.log(modal.find('input[name="edit_description"]'))
				modal.find('input[name="product_edit_sale"]').val(sale)
			}
		})
	});


	$("#edit_product").on("submit",function(e){
		e.preventDefault()

		const data = new FormData(this)
		$.ajax({
			url,
			type,
			data,
			contentType : false,
			processData : false,
			success : function(response){
				if (response != '') {
					alert(response)
				}
				$('#edit__modal').modal('toggle')
				getAllProducts()
			}
		})

	})


	$(document).on('click','.close',function(){
		$('.modal').modal('hide')
	});

	$(document).on('click','.product_item',function(){
		const id = $(this).data('id')
		const modal  = $('#product_modal')

		$('#draw_search').css('display','none')

		$.ajax({
			url,
			type,
			data:{read_product: id},
			success: function(response){
				let {id,name,price,size,brand,description,sale,image} = JSON.parse(response)[0]

				
				

				$('#product_modal').modal('toggle')

				modal.find('input[name="shop_product_id"]').val(id)
				$('#shop_product_name').html(name)
				$('#shop_product_price').html(price+'$')
				$('#shop_product_size').html(size)
				$('#shop_product_brand').html(brand)
				$('#shop_product_description').html(description)

				$('#shop_product_image').attr('src',image)

				if (sale != 0) {

					let sale_price = price/100*sale
				
					let end_price = Math.floor(price-sale_price)

					$('#shop_product_sale').css("display","block");

					$('#shop_product_sale').html(sale+'%')
					$('#shop_product_price').html('<span class="hk-td-del">'+price+'$'+'</span>'+end_price+'$')
				} else {
					$('#shop_product_sale').css("display","none");
				}





			}
		})



	});

	$("#add_cart_form").on("submit",function(e){
		e.preventDefault()

		const data = new FormData(this)
		$.ajax({
			url,
			type,
			data,
			contentType : false,
			processData : false,
			success : function(){
				getCartContent()
				
			}
		})
	

	})

	function getCartContent(){
		$.ajax({
			url,
			type,
			data:{get_cart_products: "user_login"},
			success: function(response){
				drawCartPorducts(JSON.parse(response))
			}
		})
	}

	function drawCartPorducts(datas){
		if (datas.length > 0) {
			$('#cart_content').html("")
			$('#total_price').html("0")
			for (const data of datas){
				let {id,user_login,product_id,count} = data
				

				$.ajax({
					url,
					type,
					data:{read_product: product_id},
					success: function(response){
						let {id,name,price,size,brand,description,sale,image} = JSON.parse(response)[0]
						var cart_content = document.getElementById('cart_content');


						var price_html

						if (sale != 0) {

							let sale_price = price/100*sale
				
							let end_price = Math.floor(price-sale_price)*count
							
							price_html = end_price
						} else {
							price_html = price*count
						}
						

						
						count = `
						<div class='count_set' data-id="${product_id}">
							<div class='count_num'>
								<a href="#" class="minus-count count_btn">-</a>
								<span class="count">${count}</span>
								<a href="#" class="plus-count count_btn">+</a>
							</div>
							<button class="count-delate btn btn-danger mt-2">
								Удалить
							</button>
						</div>
						
						
						`
						

						cart_content.innerHTML+=
						`
						<div class="cart_product">
							<div class="cart_image">
								<img src="${image}" alt="">
							</div>
							<div class="cart_content_prod">
								<h4 class="product_title">${name}</h4>
								<p class="product_price">${price_html+'$'}</p>
								${count}
							</div>
						</div>
						`	

						let totalPrice = Number(document.getElementById('total_price').innerHTML),
							end_total_price = totalPrice+price_html

						$('#total_price').html(end_total_price)

						


					}
				})
			}
		


			
		} else {
			let no_prod_html = '<h1 class="text-center">Продуктов пока нет</h1>'

			$('#cart_content').html(no_prod_html)
			$('#total_price').html("0")
		}

	}


	$(document).on('click','.minus-count',function(e){
		e.preventDefault()

		const parent = $(this).parents('.count_set')
		const id = $(parent).data('id')

		$.ajax({
			url,
			type,
			data:{minus_product_count: id},
			success: function(){
				getCartContent()
			}
		})
	})

	$(document).on('click','.plus-count',function(e){
		e.preventDefault()

		const parent = $(this).parents('.count_set')
		const id = $(parent).data('id')

		$.ajax({
			url,
			type,
			data:{plus_product_count: id},
			success: function(){
				getCartContent()
			}
		})
	})



	$(document).on('click','.count-delate',function(e){
		e.preventDefault()

		const parent = $(this).parents('.count_set')
		const id = $(parent).data('id')

		$.ajax({
			url,
			type,
			data:{delate_cart_product: id},
			success: function(){
				getCartContent()
			}
		})
	})




	$('#open_cart_btn').on('click',function(){
		$('.modal').modal('hide')
		$('#cart').modal('toggle')
	});


	$('#user_search').on('keyup',function(){
		let value = $('#user_search').val()
		if (value != '') {
			$.ajax({
				url,
				type,
				data: {user_search: value},
				success : function(response){
					if (response!='[]') {
						drawUserSearch(JSON.parse(response))
						
					}	
				}
			})
		} else {
			$('#draw_user_search').css('display','none')
		}
	})

	function drawUserSearch(datas){
		let html = ''

		let user_login = $('#user_login').val()


		for (const data of datas){
			let {id,name,surname,main__img,login,age} = data

			if (login != user_login) {
				$('#draw_user_search').css('display','block')

				html+=`
					<div class="search_user_item" data-login="${login}">
						<div class="icon_author">
							<img src="${main__img}" alt="">
						</div>
						<h4 class="user_search_title">${name} ${surname}</h4>

					</div>
				
				`
			} else {
				$('#draw_user_search').css('display','none')
			}
			
		}

		$("#draw_user_search").html(html);
	}


	$(document).on('click','.search_user_item',function(){
		let login = $(this).data('login')

		$.ajax({
			url,
			type,
			data: {see_profil: "aaaa",see_user_login: login},
			success : function(){
				window.location.href = 'profil.php';
			}
		})

	})

	$(document).on('click','.open_user_cart',function(){
		let login = $(this).data('login')

		$.ajax({
			url,
			type,
			data:{get_all_cart_products: login},
			success: function(response){
				drawAdminCartPorducts(JSON.parse(response))
				$('#cart').modal('toggle')
			}
		})
	})

	function drawAdminCartPorducts(datas){
		console.log(datas)
		if (datas.length > 0) {
			$('#cart_content').html("")
			$('#total_price').html("0")
			for (const data of datas){
				let {id,user_login,product_id,count} = data
				

				$.ajax({
					url,
					type,
					data:{read_product: product_id},
					success: function(response){
						let {id,name,price,size,brand,description,sale,image} = JSON.parse(response)[0]
						var cart_content = document.getElementById('cart_content');

						var price_html

						if (sale != 0) {

							let sale_price = price/100*sale
				
							let end_price = Math.floor(price-sale_price)*count
							
							price_html = end_price
						} else {
							price_html = price
						}
						

						
						count = `
						<div class='count_set'">
							<div class='count_num'>
								<span class="count">${count}</span>	
							</div>
						</div>
						
						
						`
						

						cart_content.innerHTML+=
						`
						<div class="cart_product">
							<div class="cart_image">
								<img src="${image}" alt="">
							</div>
							<div class="cart_content_prod">
								<h4 class="product_title">${name}</h4>
								<p class="product_price">${price_html+'$'}</p>
								${count}
							</div>
						</div>
						`	

						let totalPrice = Number(document.getElementById('total_price').innerHTML),
							end_total_price = totalPrice+price_html

						$('#total_price').html(end_total_price)

						


					}
				})
			}
		


			
		} else {
			let no_prod_html = '<h1 class="text-center">Продуктов пока нет</h1>'

			$('#cart_content').html(no_prod_html)
			$('#total_price').html("0")
		}

	}


	
	
	
	

	getCartContent()
	getAllProducts()

})



