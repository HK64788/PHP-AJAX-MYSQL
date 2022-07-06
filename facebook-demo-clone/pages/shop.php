<?php
	session_start();
	require_once '../engine/DB.php';

	if (!isset($_SESSION["user_login"])) {
		header("Location: ../index.php");
		die;
	}

	if (isset($_SESSION["profil_login"])) {
		unset($_SESSION["profil_login"]);
	}

	


	$users = $_DB->get_data();

	if ($_SESSION['user_login'] != 'admin') {
		foreach ($users as $user) {
			if ($user->get_login() == $_SESSION["user_login"]){
				$login = $user->get_login();
				$password = $user->get_password();
				$name = $user->get_name();
				$surname = $user->get_surname();
				$age = $user->get_age();
				$main__img = $user->get_main__img();
				$admin_rights = $user->get_admin_rights();
				$bg__img = $user->get_bg__img();
				$gallery = $user->get_gallery();

			}
		}
	} else {
		$main__img = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQGErvmomYWjrPiB5in9Kem7Acoi_m6607w2g&usqp=CAU';
		$name = 'Mark';
		$surname = 'Zuckerberg';
		$bg__img = "https://wallpaperaccess.com/full/2547005.jpg";
		$bool = false;
	}

	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" href="../css/style.css">
	<title><?=$name?> Page</title>
</head>
<body style="background: #18191A;">
	<?php require('../blocks/header.php')?>
	<p class="error">
		<?php
			if (isset($_SESSION['error'])) {
				echo $_SESSION['error'];
				unset($_SESSION['error']);
			}
		?>
	</p>

	<input type="hidden" id="user_login" value="<?=$_SESSION['user_login']?>">
	<section class="products">
		<h1 class="text-center text-white mb-5">Продукты</h1>
		<div class="container">
			<div class="product_search hk-pr">
				<input type="text" id="main_product_search" class="form-control my-3 w-50" placeholder="Поиск...">
				<div id="draw_search">
					
				</div>
			</div>
			
			<div class="products_inner" id="product_inner_main">


				

			</div>
		</div>
	</section>


	<div class="modal bd-example-modal-lg	 fade" id="product_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">	
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Корзинка</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body p-0">
	       
	      	<div class="w-100">
	      		<div>
	      			<div class="hk-pr">
	      				<img src=""  id="shop_product_image" alt="">
	      				<i id="shop_product_sale"></i>
	      			</div>	
	      		</div>
	      		<div class="p-3">
	      			<div class="w-50">
		      			<h2 id="shop_product_name"></h2>
		      			<p id="shop_product_price" class="hk-bold"></p>
		      			<br>
		      			<div class="d-flex justify-content-between align-items-center">
		      				<p>Размер: </p>
		      				<p id="shop_product_size" class="hk-bold"></p>	
		      			</div>
		      			<div class="d-flex justify-content-between align-items-center">
		      				<p>Бренд: </p>
		      				<p id="shop_product_brand" class="hk-bold"></p>
		      			</div>
		      			<br>
		      		</div>
		      		<hr>
		      		<div>
		      			<span class="hk-bold">Описание:</span>
		      			
	      				<p id="shop_product_description"></p>
	      				<br>
	      				<form id="add_cart_form" action="../engine/requests.php" method="POST">
	      					<input type="hidden" name="add_cart_submit" value="awgawg">
	      					<input type="hidden" name="shop_product_id" value="">
	      					<button type="submit" name="cart_btn_sub" class="product_btn fs-5">
								Добавить в корзину
							</button>
	      				</form>
	      				
		      		</div>
	      			
	      		</div>
	      		


      			
	      	</div>
	        
	      </div>
	    </div>
	  </div>
	</div>

	<div id="open_cart_btn">
		<i class="fa-solid fa-cart-shopping"></i>
	</div>


	<div class="modal bd-example-modal-lg	 fade" id="cart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">	
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Корзинка</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	      	<div id="cart_content">
	      		
	      	</div>	
	      	<div class="error py-3">Всего:  <span id="total_price">0</span>$</div>
	      </div>
	    </div>
	  </div>
	</div>
				

	

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="../js/main.js"></script>
	<script src="../js/mainAjax.js"></script>
	

</body>
</html>