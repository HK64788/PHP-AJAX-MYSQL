<?php
session_start();
require_once '../engine/DB.php.';

if (!isset($_SESSION["user_login"])) {
	header("Location: ../index.php");
	die;
}

if (!isset($_SESSION["profil_login"])) {
	header("Location: ../pages/home.php");
	die;
}

if ($_SESSION["user_login"] == 'admin') {
	unset($_SESSION["user_login"]);
	header("Location: ../index.php");
	die;
}


$users = $_DB->get_data();

foreach ($users as $user) {
	if ($user->get_login() == $_SESSION["profil_login"]){
		$login = $user->get_login();
		$password = $user->get_password();
		$user_name = $user->get_name();
		$surname = $user->get_surname();
		$age = $user->get_age();
		$user_main__img = $user->get_main__img();
		$bg__img = $user->get_bg__img();
		$gallery = $user->get_gallery();
	}
}

foreach ($users as $user) {
	if ($user->get_login() == $_SESSION["user_login"]){
		$name = $user->get_name();
		$user_surname = $user->get_surname();
		$user_login = $user->get_login();
		$main__img = $user->get_main__img();
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
<title><?=$user_name?> Page</title>
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
					<img src="<?=$user_main__img?>" alt="">
				</div>
				<div class="profile_info">
					<h1><?=$user_name?></h1>
					<h1><?=$surname?></h1>
					<a href="#" class="friends">Друзя: 0</a>
				</div>
			</div>
			<div class="profile_settings">
				<div class="btn-wrapper">
							
				</div>
			</div>
		</div>

		<hr>
		<nav class="home_nav">
			<a href="#" id="0" class="users_nav_link active">Фото</a>
			<a href="#" id="1" class="users_nav_link">Пользователи</a>
		</nav>

		<div class="photos my-3 nav-content">
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
													<img src="<?=$user_main__img?>" alt="">
												</a>
												<h6><?=$user_name?> <?=$surname?></h6>
											</div>
											
										</div>
										<div class="gallery_img">
											<img src="<?=$gallery[$i]?>" alt="">
										</div>
										
										<div class="post_content">
											<div class="d-flex justify-content-between align-items-center">
												<h5 class="likes text-white">
													<i class="fa-solid fa-thumbs-up"></i>
													<span class="like_count">
													<?php 
														$user_images = $_DB->get_like_data($_SESSION['profil_login']);
														echo return_image_info($gallery[$i],$user_images);
													?>	
													</span>
												</h5>
												<form id="<?=$k?>" class="like_form" action="../engine/requests.php" method="POST">
													<input type="hidden" class="get_id" value="<?=$k?>" name="get_id" >
													<input type="hidden" name="like_btn" value="aaaa">
													<input type="hidden" name="gallery_img" value="<?=$gallery[$i]?>">
													<button type="submit" class="like_btn" name="like_submit"><i class="fa-solid fa-thumbs-up"></i> Нравиться</button>
												</form>
											</div>
											<hr>
											
											<div class="comments">
											<?php
												$comments = $_DB->get_comment_data($_SESSION['profil_login'],$gallery[$i]);

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
						$password = $user->get_password();
						$age = $user->get_age();
						$id = $user->get_id();
						$main_img = $user->get_main__img();
						$rights = $user->get_rights();

						if ($login != $_SESSION["user_login"] && $login != $_SESSION["profil_login"]) {
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
</body>
</html>