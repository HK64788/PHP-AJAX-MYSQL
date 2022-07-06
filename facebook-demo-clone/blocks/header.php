<header class="header">
	<div class="header__inner">
		<div class="logo hk-pr">
			<a href="#" class="logo_icon">
				<i class="fa-brands fa-facebook-f"></i>
			</a>
		
			<input placeholder="Поиск на Facebook" id="user_search" class="header_search" type="text">
			<div id="draw_user_search"></div>
			
		</div>
		<nav class="nav">
			<a href="#" class="nav_link">
				<i class="fa-solid fa-house"></i>
			</a>
			<a href="#" class="nav_link">
				<i class="fa fa-tv"></i>
			</a>
			<a href="#" class="nav_link">
				<i class="fa fa-gamepad"></i>
			</a>
		</nav>
		<nav class="menu_nav">
			<a href="#" class="menu_link_autor">
				<img src="<?=$main__img?>" alt="">
				<span><?=$name?></span>
			</a>
			<a href="#" class="menu_link">
				<i class="fa fa-bars"></i>
			</a>
			<a href="#" class="menu_link">
				<i class="fa-brands fa-facebook-messenger"></i>
			</a>
			<a href="#" class="menu_link">
				<i class="fa-regular fa-bell"></i>
			</a>
			<a href="#" class="menu_link" id="hk-dropdown-btn">
				<i class="fa fa-caret-down"></i>
				<form action="../engine/requests.php" method="POST" class="hk-dropdown" id="hk-dropdown">
					<button type="submit" name="login_exit" class="hk-dropdown-link"><span><i class="fa-solid fa-arrow-right-from-bracket"></i></span> Выйти</button>
					<?php
						if (isset($_SESSION["profil_login"])) {
							?>
								<button type="submit" name="profil_exit" class="hk-dropdown-link"><span><i class="fa-solid fa-house-user"></i></span> Дамой</button>
							<?php
						} else if (isset($_SESSION['shop_active'])){
							?>
								<button type="submit" name="profil_exit" class="hk-dropdown-link"><span><i class="fa-solid fa-house-user"></i></span> Дамой</button>
							<?php

						}
					?>

					<?php 
						if (isset($_SESSION['user_login']) && !isset($_SESSION['profil_login']) && $_SESSION['user_login'] != 'admin' && !isset($_SESSION['shop_active'])) {
							?>
								<button type="button" onclick="openHelpModal()" class="hk-dropdown-link"><span><i class="fa-solid fa-handshake-angle"></i></span> Помощь</button>
							<?php
						}
					?>

					<?php
						if (!isset($_SESSION['shop_active'])) {
							?>
								<button type="submit" name="shop_log" class="hk-dropdown-link"><span><i class="fa-solid fa-basket-shopping"></i></span> Магазин</button>
							<?php
						}
					?>

					
				</form>

			</a>
		</nav>
	</div>
</header>