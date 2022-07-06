<?php 
	session_start();

	if (isset($_SESSION["user_login"])) {
		if ($_SESSION["user_login"] != 'admin') {
			header("Location: pages/home.php");
			die;
		} else {
			header("Location: pages/admin.php");
			die;
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" href="css/style.css">
	<title>Facebook - Login</title>
</head>
<body style="background: #f1f1f1; height: 100vh; overflow: hidden;">
	<section class="bg-f1">
		<div class="container">
			<div class="intro">
				<div class="intro_content">
					<h1>facebook</h1>
					<p>Facebook помогает вам всегда оставаться на связи и общаться со своими знакомыми.</p>
				</div>
				<div class="intro_login">
					<div class="intro_login_form">
						<p class="error">
							<?php
								if (isset($_SESSION['error'])) {
									echo $_SESSION['error'];
									unset($_SESSION['error']);
								}
							?>
						</p>
						<form action="engine/requests.php" method="POST" enctype="multipart/form-data">
						  <div class="mb-3">
						    <input type="text" name="login" placeholder="Электронный адрес или номер телефона" class="form-control hk-input" id="login">
						  </div>
						  <div class="mb-3">
						    <input type="password" name="password" placeholder="Пароль" class="form-control hk-input" id="password">
						  </div>
						  <div class="my-3">
						    <input type="submit" name="login_submit" value="Вход" class="hk-sub-btn">
						  </div> 
						  <hr class="my-4">
						  <div class="new_account_btn" id="reg-btn" onclick="openModal()">
						  	<a href="#" >Создать новый аккаунт</a>
						  </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>


	<div class="hk-modal" id="hk-modal">
		<div class="content">
			<form class="registration-form" action="engine/requests.php" method="POST" enctype="multipart/form-data">
				<span onclick="openModal()" class="close-modal">X</span>
				<div class="registration-title">
					<h1>Создать аккаунт</h1>
					<p>Быстро и легко.</p>
				</div>
				<div class="pad">
					<p class="error">
						<?php
							if (isset($_SESSION['reg-error'])) {
								echo $_SESSION['reg-error'];
								unset($_SESSION['reg-error']);
							}
						?>
					</p>
					<div class="df">
						<input type="text" class="registration-input" name="name" placeholder="Имя">
						<input type="text" class="registration-input" name="surname" placeholder="Фамилия">
					</div>	
					<input type="text" class="registration-input" name="login" placeholder="Логин">
					
					<input type="password" class="registration-input" name="password" placeholder="Парол">
					
					<p class="tal">Дата рождения</p>
					<div class="date">
						<div class="date__input">
							<select name="day" id="day">
								
							</select>
						</div>
						<div class="date__input">
							<select name="mounth" id="mounth">
								<option value="January">янв</option>
								<option value="February">фев</option>
								<option value="March">мар</option>
								<option value="April">апр</option>
								<option value="May">мая</option>
								<option value="June">июн</option>
								<option value="July">июл</option>
								<option value="August">авг</option>
								<option value="September">сен</option>
								<option value="October">окт</option>
								<option value="November">ноя</option>
								<option value="December">дек</option>
							</select>
						</div>
						<div class="date__input">
							<select name="year" id="year">
								
							</select>
						</div>

					</div>
					<p class="tal">Аватар</p>
					<div class="checkboxs">
						<input type="file" name="main__img">
					</div>

					<br>
					<div class="tac">
						<input type="submit" value="Регистрация" class="form__submit" name="reg_submit">
					</div>
				</div>
				
			</form>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	
	<script>
		let day = document.getElementById("day");
		let year = document.getElementById("year");
		for(let i = 1; i <= 31; i++){
			day.innerHTML += `<option value="${i}">${i}</option>`
		}
		for(let i = 1970; i <= 2012; i++){
			year.innerHTML += `<option value="${i}">${i}</option>`
		}

		let modal = document.getElementById("hk-modal")
		function openModal(){
			modal.classList.toggle("d-block");
		};
	</script>
</body>
</html>