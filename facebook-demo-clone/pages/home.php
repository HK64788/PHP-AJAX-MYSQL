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

	if ($_SESSION["user_login"] == 'admin') {
		unset($_SESSION["user_login"]);
		header("Location: ../index.php");
		die;
	}

	if (isset($_SESSION['shop_active'])) {
		unset($_SESSION['shop_active']);
	}


	$users = $_DB->get_data();

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



function return_image_info($image,$user_images){
	
	foreach ($user_images as $user_image) {
		if ($user_image['image'] == $image) {
			$liked_userss = explode('"["',$user_image['liked_users']);
			if ($user_image['liked_users'] != "none") {
				return count($liked_userss);
			} else {
				return 0;
			}
		}
	}
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
	<input type="hidden" id="user_login" value="<?=$_SESSION['user_login']?>">
	
	<div class="hk-modal dark-modal" id="hk-modal">
		<div class="content">
			<form action="../engine/requests.php" method="POST" class="edit-form" enctype="multipart/form-data">
				<span onclick="openModal()" class="close-modal text-white">X</span>
				<h4 class="text-white text-center font-bold fs-4 pt-3">Редактировать профиль</h4>		
				<hr>
				<div class="edit-content">
					<p class="error">
						<?php
							if (isset($_SESSION['edit-error'])) {
								echo $_SESSION['edit-error'];
								unset($_SESSION['edit-error']);
							}	
						?>
					</p>
					<div class="mb-3">
						<label class="text-white">Имя</label>
						<input type="text" name="name" value="<?=$name?>" class="form-control">
					</div>
					<div class="mb-3">
						<label class="text-white">Фамилия</label>
						<input type="text" name="surname" value="<?=$surname?>" class="form-control">
					</div>
					<div class="mb-3">
						<label class="text-white">Старый пароль</label>
						<input type="password" name="password" class="form-control">
					</div>
					<div class="mb-3">
						<label class="text-white">Новый пароль</label>
						<input type="password" name="password-new" class="form-control">
					</div>
					<div class="mb-3">
						<label class="text-white">Аватарка</label>
						<br>
						<input type="file" class="text-white" name="main__img">
						<input type="submit" value="Удалить Аватарку" name="delate_avatar" class="btn btn-primary">
					</div>
					<input type="submit" name="edit_submit" value="Сахранить" class="hk-sub-btn">
				</div>
			</form>
		</div>	
	</div>

	<div class="hk-modal dark-modal" id="help-modal">
		<div class="content">
			<form action="../engine/requests.php" method="POST" class="help-form">
				<span onclick="openHelpModal()" class="close-modal text-white">X</span>
				<h4 class="text-white text-center font-bold fs-4 pt-3">Свежитесь с нами</h4>		
				<hr>
				<div class="answers">
					<?php 
						$questions = $_DB->get_help_data();

						foreach ($questions as $quest){
							if ($quest->get_admin_login() != "none") {
								if ($quest->get_user_login() == $_SESSION['user_login']) {
									?>	
										<div class="answer text-white">
											<div class="answer_content">
												<a href="#" class="icon_author">
													<img src="<?=$main__img?>" alt="">
												</a>
												<div class="comment_content my-comment">
													<h6><?=$name?> <?=$surname?></h6>
													<p><?=$quest->get_question()?></p>
												</div>
											</div>
											<div class="answer_contents">
												<?php 	
													$logins = $quest->get_admin_login();
													$admin_answers = $quest->get_admin_answer();
													for ($i=0; $i < count($logins); $i++) { 
														if ($logins[$i] != "admin"){
															foreach ($users as $user) {
															 
																if ($user->get_login() == $logins[$i]) {
																		?> 
																		<div class="answer_content">
																			<a href="#" class="icon_author ">
																				<img src="<?=$user->get_main__img()?>" alt="">
																			</a>
																			<div class="comment_content">
																				<h6><?=$user->get_name()?> <?=$user->get_surname()?></h6>
																				<p><?=$admin_answers[$i]?></p>
																			</div>
																		</div>
																			

																		<?php
																}
															} 

														} else{
															?>	
																<div class="answer_content">
																	<a href="#" class="icon_author">
																		<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQGErvmomYWjrPiB5in9Kem7Acoi_m6607w2g&usqp=CAU" alt="">
																	</a>
																	<div class="comment_content">
																		<h6>Admin</h6>
																		<p><?=$admin_answers[$i]?></p>
																	</div>
																</div>
																
															<?php
														}
																													
														}
													


														
													?>
											</div>
										</div>	
									<?php
								}
							}
							
						}
					?>
				</div>
				<hr>
				<div class="d-flex justify-content-between align-items-center">
					<input type="text" name="help-message" class="help-input">
					<button type="submit" class="comment_submit help-submit" name="help_submit"><i class="fa-solid fa-paper-plane"></i></button>
				</div>
				
			</form>
		</div>	
	</div>


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
				<div class="cover_wrapper">

					<button class="cover_btn" id="bg__btn" type="submit"><i class="fa fa-camera"></i> 
					<?php 
						if ($bg__img == "none") {
							echo " Добавить фото обложки";
						} else {
							echo " Изменить фото обложки";
						}
					?>
					</button>
					<form action="../engine/requests.php" method="POST" enctype="multipart/form-data">
							<p class="error">
								<?php
									if (isset($_SESSION['error'])) {
										echo $_SESSION['error'];
										unset($_SESSION['error']);
									}	
								?>
							</p>
							<input type="file" name="bg__img" id="bg__img" style="display: none;">
							<input type="submit" name="change-bg-img" id="bg_submit" class="cover_btn" value="Сахранить" style="display: none;">
						
						
					</form>
					
				</div>
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
							<a onclick="openModal()" href="#" class="prof-btn"><i class="fa fa-pen"></i> Редактировать профиль</a>	
								<?php
									if ($admin_rights == "true") {
										?>
										<form action="../engine/requests.php" method="POST">
											<input type="submit" name="admin_panel" value="Режим админа" class="prof-btn"> 
										</form>	

										<?php
									}
								?>
								
						</div>
					</div>
				</div>

				<hr>
				<nav class="home_nav">
					<a href="#" id="0" class="users_nav_link active">Фото</a>
					<a href="#" id="1" class="users_nav_link">Пользователи</a>
				</nav>

				<div class="photos my-3 nav-content">
					<form action="../engine/requests.php" method="POST"  enctype="multipart/form-data">
						<input type="file" name="images[]" class="upload-btn" multiple/>
						<input type="submit" value="Добавить Фото" class="prof-btn" name="add_gallery_photo">
					</form>

					<div class="gallery mt-3">
						<?php
							if ($gallery != "none") {
								$k = 0;
								for ($i=0; $i < count($gallery); $i++) { 
									if ($gallery[$i] != 'delated') {
										?>
											<div class="gallery_item">
												<div class="post_header">
													<div class="post_title">
														<a href="#" class="icon_author">
															<img src="<?=$main__img?>" alt="">
														</a>
														<h6><?=$name?> <?=$surname?></h6>
													</div>
													<div  id="<?=$k?>" class="gear">
														<i class="fa-solid fa-gear"></i>
														<form action="../engine/requests.php" method="POST" class="gallery_form">
															<input type="hidden" name="gallery_img" value="<?=$gallery[$i]?>">
															<input type="submit" name="change_main__img" class="hk-btn-delate" value="Сделать автаркой">

															<input type="submit" name="delate_img" class="hk-btn-delate my-2" value="Удалить">
															<input type="submit" name="change_bg_img" class="hk-btn-delate" value="Сделать обложкой">
															
														</form>
													</div>
												</div>
												<div class="gallery_img">
													<img src="<?=$gallery[$i]?>" alt="">
												</div>
												
												<div class="post_content">
													<div class="d-flex justify-content-between align-items-center">
														<h5 class="likes text-white">
															<i class="fa-solid fa-thumbs-up"></i>
															<span>
															<?php 
																$user_images = $_DB->get_like_data($_SESSION['user_login']);
																echo return_image_info($gallery[$i],$user_images);
															?>	
															</span>
														</h5>
														
													</div>

													<hr>
													<div class="comments">
														
													
													<?php
														$comments = $_DB->get_comment_data($_SESSION['user_login'],$gallery[$i]);

														foreach ($comments as $comment) {
															if ($comment['image'] == $gallery[$i]){
																?>
																
																	<div class="comment_user">
																		<?php
																			$users = $_DB->get_data();
																			foreach($users as $user){
																				if ($user->get_login() == $comment['comment_user']) {
																					if($user->get_login() == $_SESSION['user_login']){
																					 	$myComment = "my-comment";
																					} else {
																						$myComment = "";
																					}
																					?>
																					<a href="#" class="icon_author">
																						<img src="<?=$user->get_main__img()?>" alt="">
																					</a>
																					<div class="comment_content <?=$myComment?>">
																						<h6><?=$user->get_name()?> <?=$user->get_surname()?></h6>
																						<p><?=$comment['comment_content']?></p>

																					</div>
																					
																					<?php
																				}
																			}
																		?>
																	</div>
																
																<?php
															}
															
														}

													
													?>
													</div>


													<form action="../engine/requests.php" id="<?=$k?>" class="comment_form" method="POST">
														<input type="hidden" value="<?=$gallery[$i]?>" name="gallery_img">
														<input type="hidden" value="<?=$login?>" name="login">

														<a href="#" class="icon_author">
															<img src="<?=$main__img?>" alt="">
														</a>
														<input type="text" class="comment_input" name="comment"  placeholder="Напишите комментарий…">
														<input type="hidden" name='comment_sub' value="submit">
														<br>
														<button type="submit" class="comment_submit" name="comment_submit"><i class="fa-solid fa-paper-plane"></i></button>

													</form>
												</div>

												
											</div>
										<?php
										$k+=1;
									}
								}
							}	
						?>
					</div>
				</div>


				<div class="hk-flex nav-content" style="display: none!important;">
					<?php 
						$users = $_DB->get_data();

						foreach ($users as $user) {
							if ($user->get_login() !=  $_SESSION["user_login"]) {
								$name = $user->get_name();
								$surname = $user->get_surname();
								$login = $user->get_login();

								$main_img = $user->get_main__img();

								if ($login != $_SESSION["user_login"]) {
									?>
									<form class="admin-form" action="../engine/requests.php" method="POST">
										<input type="hidden" name="see_user_login" value="<?=$login?>">
										<div class="user_img">
											<img src="<?=$main_img?>" alt="">
										</div>
										<div class="user_cont">
											<p><?=$name?> <?=$surname?></p>
											<input type="submit" class="hk-btn-delate mb-2" value="Посмотреть профиль" name="see_profil">	
										</div>									
									</form>
									<?php
								}
							}
						}
					?>
				</div>
			</div>
		</div>
	</section>

	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<script src="../js/main.js"></script>
	<script src="../js/mainAjax.js"></script>
	<script type="text/javascript">
		let modal = document.getElementById("hk-modal")
		function openModal(){
			modal.classList.toggle("d-block");
		}

		let gear = document.querySelectorAll(".gear");
		let gallery_form = document.querySelectorAll(".gallery_form");

		gear.forEach(function(element){
			element.onclick = () => {
				gallery_form[element.id].classList.toggle("gallery_show");
			}
			
		})

		let helpModal = document.getElementById("help-modal");
		function openHelpModal(){
			helpModal.classList.toggle("d-block");
		}
			

		let btn = document.getElementById("bg__btn");
		let input = document.getElementById("bg__img");
		let submit = document.getElementById("bg_submit");


		btn.addEventListener("click",function(){
			input.click()
			submit.style.display = "block"
		});
		
		
		
	</script>

</body>
</html>