<?php
	session_start();
	require_once '../engine/DB.php.';

	if (!isset($_SESSION['user_login'])) {
		header("Location: ../index.php");
		die;
	}

	$users = $_DB->get_data();


	if ($_SESSION["user_login"] != "admin") {
		foreach ($users as $user) {
			if ($user->get_login() == $_SESSION["user_login"]) {
				if ($user->get_admin_rights() != "true") {
					header("Location: ../index.php");
					die;
				} else {
					$main__img = $user->get_main__img();
					$name = $user->get_name();
					$surname = $user->get_surname();
					$bool = true;
					$bg__img = $user->get_bg__img();
				}
			}
		}
		
	} else{
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
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="../css/style.css">
	<title>Admin</title>
</head>
<body style="background: #18191A;">
	<?php require('../blocks/header.php')?>

	<input class="error" type="hidden" id="error_message" value="<?php 
			if (isset($_SESSION['error'])) {
				echo $_SESSION['error'];
			}
	?>">
		<?php
			if (isset($_SESSION['error'])) {
				?>
				<script>
					let message = document.getElementById('error_message')
					alert(message.value)
				</script>
				<?php
				unset($_SESSION['error']);
			}
		?>

	<section class="home_main">
		<div class="container">
			<div class="cover">
				<?php 
					if ($bg__img != "none") {
						?>
						<img src="<?=$bg__img?>" alt="">
						<?php
					}
				?>
			</div>
			<div class="main_info">
				<div class="profile">
					<div class="profile_cont">
						<div class="profile-img">
							<img src="<?=$main__img?>" alt="">
						</div>
						<div class="profile_info">
							<h1><?=$name?></h1>
							<h1><?=$surname?></h1>
							<a href="#" class="friends">Друзя: 0</a>
						</div>
					</div>
					<div class="profile_settings">
						<div class="btn-wrapper">
							<?php
								if ($bool) {
									?>
									<form action="../engine/requests.php" method="POST">
										<button type="submit" name="profil_exit" class="prof-btn">Обычный режим</button>
									</form>
									<?php
								}
							?>
								
						</div>
					</div>
				</div>

				<hr>

				
				<nav class="home_nav">
					<a href="#" id="0" class="users_nav_link active">Пользователи</a>
					<a href="#" id="1" class="users_nav_link">Продукты</a>
				</nav>
				
			</div>
		</div>
	</section>

	<div class="nav-content">
		
	<section class="users">
		<div class="container">
			<div class="main_info">

				<form action="../engine/requests.php" method="POST">
					<select name="users_filter" class="form-control my-2" id="">
						<option value="all">Все</option>
						<option value="admins">Модер</option>
						<option value="users">Пользователи</option>

					</select>
					<input type="submit" class="btn btn-primary my-2" name="sort_users">
				</form>

				<div class="d-flex justify-content-between flex-wrap">
					<?php 
						foreach ($users as $user) {
							if ($user->get_login() !=  $_SESSION["user_login"]) {
								$name = $user->get_name();
								$surname = $user->get_surname();
								$login = $user->get_login();
								$password = $user->get_password();
								$age = $user->get_age();
								$id = $user->get_id();
								$main_img = $user->get_main__img();
								$rights = $user->get_rights();
								$admin_rights = $user->get_admin_rights();

								if (isset($_SESSION['sort_type'])) {
									if ($_SESSION['sort_type'] == 'admins') {
										if ($admin_rights == 'false') {
											continue;
										}
									} else if ($_SESSION['sort_type'] == 'users'){
										if ($admin_rights == 'true') {
											continue;
										}
									}
								}
							
							?>

								<form class="admin-form"  action="../engine/requests.php" method="POST">
									<input type="hidden" name="delate_user_login" value="<?=$login?>">
									<div class="user_img">
										<img src="<?=$main_img?>" alt="">
									</div>
									<div class="user_cont">
										<p><?=$name?> <?=$surname?></p>
										<?php
											if ($_SESSION["user_login"] == "admin") {
												?>
												<input type="submit" class="hk-btn-delate mb-2" value="Удалить" name="delate_user">	
												<?php 										
													if ($admin_rights != "true") {

														?>
															<input type="submit" class="hk-btn-delate mb-2" value="Сделать Админом" name="admin_user">
														<?php
													} else {
														?>
															<input type="submit" class="hk-btn-delate mb-2" value="Удалить Админ" name="admin_user">
														<?php
													}
											}
										?>
										<?php 										
											if ($rights == "true") {

												?>
													<input type="submit" class="hk-btn-delate " value="Блокировать" name="block_user">
												<?php
											} else {
												?>
													<input type="submit" class="hk-btn-delate" value="Разблокировать" name="block_user">
												<?php
											}
										?>
										
										<input type="button" class="hk-btn-delate mt-2 open_user_cart" data-login="<?=$login?>" value="Заказы" name="show_cart_products">

									</div>									
								</form>


							<?php
							}
						}
					?>
				</div>
				
			</div>
		</div>
	</section>


	<section class="questions">
		<div class="container">
			<div class="questions_inner">
				<?php 
					$questions = $_DB->get_help_data();

					foreach ($questions as $quest){
						if ($quest->get_user_login() != $_SESSION['user_login']) {
							?>	
							<div class="question_item">
								<div class="comment_user">
									<?php

										foreach ($users as $user) {
											if ($user->get_login() == $quest->get_user_login()) {
												?> 
													<a href="#" class="icon_author ">
														<img src="<?=$user->get_main__img()?>" alt="">
													</a>
													<div class="comment_content mx-4">
														<h6><?=$user->get_name()?> <?=$user->get_surname()?></h6>
														<p><?=$quest->get_question()?></p>
													</div>

												<?php
											}
										}
							
									?>
								</div>
								<form action="../engine/requests.php" method="POST" class="admin_question">
									<input type="hidden" name="user_id" value="<?=$quest->get_id()?>">
									<p class="error">
										<?php
											if (isset($_SESSION['error'])) {
												echo $_SESSION['error'];
												unset($_SESSION['error']);
											}	
										?>
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<input type="text" name="admin_answer"  class="question_input">
										<button type="submit" name="help_answer_submit" class="question_submit">Ответить</button>
									</div>	
								</form>
							</div>
							
								
							<?php
						}
					}
				?>
			</div>
		</div>
	</section>
	</div>






	<div class="nav-content" style="display: none">
		<p class="error">
			<?php
				if (isset($_SESSION['error'])) {
					echo $_SESSION['error'];
					unset($_SESSION['error']);
				}
			?>
		</p>
		<section class="products-admin">
			<div class="container">
				<div class="products_inner-admin">
					<!-- Button trigger modal -->
					<div class="d-flex justify-content-between align-items-center">
						<button type="button" class="btn btn-primary my-2" data-toggle="modal" data-target="#exampleModal">
						  Создать продукт
						</button>
						<input type="text" id="search" placeholder="Поиск" class="form-control w-50">
					</div>
					

					
					<table class="product_table table table-sm table-dark">
						<thead>
							<th>Img</th>
							<th>Name</th>
							<th>Price</th>
							<th>Size</th>
							<th>Brand</th>
							<th>buttons</th>
						</thead>
						<tbody id="table_body">
							
						</tbody>
						
					</table>
				</div>
			</div>
		</section>
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



	<!-- Modal -->
	<div class="modal fade" id="edit__modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">	
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Создание продукта</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <form id="edit_product" action="../engine/requests.php" method="POST" enctype="multipart/form-data">
	        	<input type="hidden" value="awafawf" name="product_edit_submit">
	        	<input type="hidden" value="" name="edit_product_id">
	        	<input type="hidden" value="" name="hidden_product_img" >
	        	
			  <div class="form-group">
			    <label for="formGroupExampleInput">Name</label>
			    <input type="text" name="product_edit_name" class="form-control" id="formGroupExampleInput" >
			  </div>
			  <div class="form-group">
			    <label for="formGroupExampleInput1">Price</label>
			    <input type="number" name="product_edit_price" class="form-control" id="formGroupExampleInput1" >
			  </div>
			  <div class="form-group">
			    <label for="formGroupExampleInput2">Size</label>
			    <input type="number" name="product_edit_size" class="form-control" id="formGroupExampleInput2" >
			  </div>
			  <div class="form-group">
			    <label for="formGroupExampleInput3">Brand</label>
			    <input type="text" name="product_edit_brand" class="form-control" id="formGroupExampleInput3">
			  </div>
			  <div class="form-group">
			    <label for="formGroupExampleInput4">Description</label>
			    <textarea type="text" name="product_edit_description" class="form-control" rows="10" cols="20" id="formGroupExampleInput4"></textarea>
			  </div>
			  <div class="form-group">
			    <label for="formGroupExampleInput5">Sale</label>
			    <input type="number" name="product_edit_sale" class="form-control" id="formGroupExampleInput5">
			  </div>
			  <div class="form-group">
			    <label for="formGroupExampleInput6">Image</label>
			    <input type="file" name="edit_prod_img" class="form-control" id="formGroupExampleInput6">
			  </div>
			  <div class="form-group">
			    <input type="submit" name="product_edit_sub" class="btn btn-primary form-control">
			  </div>

	        </form>
	      </div>
	    </div>
	  </div>
	</div>



	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Создание продукта</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <form id="add_product" action="../engine/requests.php" method="POST" enctype="multipart/form-data">
	        	<input type="hidden" value="awafawf" name="form_sub">
	        	
			  <div class="form-group">
			    <label for="formGroupExampleInput">Name</label>
			    <input type="text" name="name" class="form-control" id="formGroupExampleInput" >
			  </div>
			  <div class="form-group">
			    <label for="formGroupExampleInput1">Price</label>
			    <input type="number" name="price" class="form-control" id="formGroupExampleInput1" >
			  </div>
			  <div class="form-group">
			    <label for="formGroupExampleInput2">Size</label>
			    <input type="number" name="size" class="form-control" id="formGroupExampleInput2" >
			  </div>
			  <div class="form-group">
			    <label for="formGroupExampleInput3">Brand</label>
			    <input type="text" name="brand" class="form-control" id="formGroupExampleInput3">
			  </div>
			  <div class="form-group">
			    <label for="formGroupExampleInput4">Description</label>
			    <textarea type="text" name="description" class="form-control" rows="10" cols="20" id="formGroupExampleInput4"></textarea>
			  </div>
			  <div class="form-group">
			    <label for="formGroupExampleInput5">Sale</label>
			    <input type="number" value="0" name="sale" class="form-control" id="formGroupExampleInput5">
			  </div>
			  <div class="form-group">
			    <label for="formGroupExampleInput6">Image</label>
			    <input type="file" name="prod_img" class="form-control" id="formGroupExampleInput6">
			  </div>
			  <div class="form-group">
			    <input type="submit" name="prod_submit" class="btn btn-primary form-control">
			  </div>

	        </form>
	      </div>
	    </div>
	  </div>
	</div>


	
	<!-- Modal -->
	<div class="modal fade" id="readModal" tabindex="-1" role="dialog" aria-labelledby="readModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="readModalLabel">More Information</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	      	<img src="" id="read_image" alt="">
	      	<h5>Name</h5>
	      	<div id="read_name"></div><hr>
	      	<h5>Price</h5>
	      	<div id="read_price"></div><hr>
	      	<h5>Desctription</h5>
	      	<div id="read_desctription"></div><hr>
	      	<h5>Size</h5>
	      	<div id="read_size"></div><hr>
	      	<h5>Brand</h5>
	      	<div id="read_brand"></div><hr>
	      	<h5>Sale</h5>
	      	<div id="read_sale"></div><hr>
	      </div>
	    </div>
	  </div>
	</div>

	

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="../js/mainAjax.js"></script>
	<script>
		let nav_btns = document.querySelectorAll('.users_nav_link');
		let nav_content = document.querySelectorAll('.nav-content');

		nav_btns.forEach(function(element){
			element.onclick = function(){
				for (let i = 0; i < nav_content.length;i++){
					nav_content[i].style.display = 'none'
				}

				for (let j = 0; j < nav_btns.length;j++){
					nav_btns[j].classList.remove('active')
				}

				nav_content[this.id].style.display = 'block';
				nav_btns[this.id].classList.add('active')
			}
		});

		let dropdownBtn = document.getElementById('hk-dropdown-btn')
		let dropdown = document.getElementById('hk-dropdown')

		dropdownBtn.addEventListener('click',function(){
			dropdown.classList.toggle('show')
		})

		let modal = document.getElementById("hk-modal")
		function openModal(){
			modal.classList.toggle("d-block");
		}

	</script>
</body>
</html>


<?php
	unset($_SESSION['sort_type']);
?>