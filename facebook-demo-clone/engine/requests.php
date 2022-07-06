<?php 

	session_start();

	require_once 'DB.php';

	if (isset($_POST["login_exit"])) {
		if (isset($_SESSION["user_login"])) {
			unset($_SESSION["user_login"]);
			header("Location: ../index.php");
			die;
		}
	}


	if (isset($_POST["reg_submit"])) {
		$name = $_POST["name"];
		$surname = $_POST["surname"];
		$login = $_POST["login"];
		$password = $_POST["password"];
		$year = $_POST["year"];

		$age = date("Y")-$year;


		if (strlen(trim($name)) > 32 || strlen(trim($name))<= 1) {
			$_SESSION['reg-error'] = "Your name is invalid";
			header("Location: ../index.php");
			die;
		}else if (strlen(trim($surname)) > 32 || strlen(trim($surname))<= 1) {
			$_SESSION['reg-error'] = "Your surname is invalid";
			header("Location: ../index.php");
			die;
		}else if (strlen(trim($password)) > 32 || strlen(trim($password))<= 1) {
			$_SESSION['reg-error'] = "Your password is invalid";
			header("Location: ../index.php");
			die;
		}else if (strlen(trim($login)) > 32 || strlen(trim($login))<= 1) {
			$_SESSION['reg-error'] = "Your login is invalid";
			header("Location: ../index.php");
			die;
		} else if (strlen(trim($age)) > 3 || strlen(trim($age)) < 1) {
			$_SESSION['reg-error'] = "Your age is invalid";
			header("Location: ../index.php");
			die;
		}

		if ($login == "admin") {
			$_SESSION['reg-error'] = "Your login is invalid";
			header("Location: ../index.php");
			die;
		}

		if (!$_FILES['main__img']["error"]) {

			$img__name = $_FILES['main__img']["name"];
			$img__temp = $_FILES['main__img']["tmp_name"];

			$valid__img = ["jpg","png","jpeg","webp","gif"];

			$file__type = pathinfo($img__name,PATHINFO_EXTENSION);

			if (!in_array($file__type,$valid__img)) {

			 $_SESSION['reg-error'] = "Invalid Image Type ($file__type)";
			 header("Location: ../index.php");
			 die;
			}
			if (!file_exists("../uploads")) {
			 mkdir("../uploads");
			}
			$final__directory = "../uploads/".uniqid("main__img__",true).".".$file__type;

			move_uploaded_file($img__temp,$final__directory);
			$main__img = $final__directory;
	    }

	    if (!isset($main__img)) {
			$final__direction = "../uploads/author__img.png";
			$main__img = $final__direction;
		}

		$users = $_DB->get_data();

		foreach ($users as $user) {
			if ($user->get_login() == $login) {
				$_SESSION['reg-error'] = "Your login is repeated";	
				header('Location: ../index.php');
				die;
			}
		}



	    $_DB->add_data($name,$surname,$login,md5($password),$age,$main__img,"true","false","none","none");

		$_SESSION["user_login"] = $login;
		header('Location: ../pages/home.php');
		die;

	}


	if (isset($_POST["login_submit"])) {
		$login = $_POST["login"];
		$password = $_POST["password"];
		$users = $_DB->get_data();
		$log_helper = true;


		if ($login == "admin" && $password == "admin") {
			$_SESSION["user_login"] = $login;
			header('Location: ../pages/admin.php');
			die;
		}

		foreach ($users as $user) {
			if ($user->get_login() == $login && $user->get_password() == md5($password)) {
				if ($user->get_rights() == "false") {
					$_SESSION['error'] = "Your account is blocked";	
					header('Location: ../index.php');
					die;
				}
				$_SESSION["user_login"] = $login;
				$log_helper = false;
				header('Location: ../pages/home.php');
				die;
			}
		}

		if ($log_helper) {
			$_SESSION['error'] = "Your login or password is not right";
			header('Location: ../index.php');
			die;
		}
	}



	if (isset($_POST["delate_user"])) {
		$login = $_POST["delate_user_login"];

		$_DB->delete_user($login);
		header('Location: ../pages/admin.php');
		die;
	}


	if (isset($_POST["block_user"])){
		$login = $_POST["delate_user_login"];

		$users = $_DB->get_data();
		foreach ($users as $user) {
			if ($user->get_login() == $login) {
				if ($user->get_rights() == "true") { 
					$_DB->block_user($login,"false");
				} else {
					$_DB->block_user($login,"true");
				}
			}
		}

		header('Location: ../pages/admin.php');
		die;
	}

	if (isset($_POST["edit_submit"])) {
		$name = $_POST["name"];
		$surname = $_POST["surname"];
		$password = $_POST["password"];
		$new_password = $_POST["password-new"];

		
		$users = $_DB->get_data();
		foreach ($users as $user) {
			if ($user->get_login() == $_SESSION["user_login"]) {
				if (!$_FILES['main__img']["error"]) {

					$img__name = $_FILES['main__img']["name"];
					$img__temp = $_FILES['main__img']["tmp_name"];

					$valid__img = ["jpg","png","jpeg","webp","gif"];

					$file__type = pathinfo($img__name,PATHINFO_EXTENSION);

					if (!in_array($file__type,$valid__img)) {

					 $_SESSION['reg-error'] = "Invalid Image Type ($file__type)";
					 header("Location: ../pages/home.php");
					 die;
					}
					if (!file_exists("../uploads")) {
					 mkdir("../uploads");
					}
					$final__directory = "../uploads/".uniqid("main__img__",true).".".$file__type;

					move_uploaded_file($img__temp,$final__directory);
					$main__img = $final__directory;
			    }

			    if (!isset($main__img)) {
					$main__img = $user->get_main__img();
				}


			  	if (isset($name) && strlen(trim($name)) > 1) {
					if (strlen(trim($name)) > 32 || strlen(trim($name))<= 1) {
						$_SESSION['edit-error'] = "Your name is invalid";
						header("Location: ../pages/home.php");
						die;
					}
				} else {
					$name = $user->get_name();
				}

				if (isset($surname) && strlen(trim($surname)) > 1) {
					if (strlen(trim($surname)) > 32 || strlen(trim($surname))<= 1) {
						$_SESSION['edit-error'] = "Your surname is invalid";
						header("Location: ../pages/home.php");
						die;
					}
				} else {
					$surname = $user->get_surname();
				}

				if (strlen(trim($password)) > 1 && strlen(trim($new_password)) > 1) {
					if (md5($password) == $user->get_password()) {
						if (strlen(trim($password)) > 32 || strlen(trim($password))<= 1) {
							$_SESSION['edit-error'] = "Your password is invalid";
							header("Location: ../pages/home.php");
							die;
						} else if (strlen(trim($new_password)) > 32 || strlen(trim($new_password))<= 1) {
							$_SESSION['edit-error'] = "Your new password is invalid";
							header("Location: ../pages/home.php");
							die;
						}

						$new_password = md5($new_password);
					} else {
						$_SESSION['edit-error'] = "Your old password is not right";
						header("Location: ../pages/home.php");
						die;
					}
				} else {	
					$new_password = $user->get_password();
				}


				$_DB->update_user($_SESSION["user_login"],$name,$surname,$new_password,$main__img);
				header("Location: ../pages/home.php");
				die;

			}
		}
	}

	if (isset($_POST["delate_avatar"])) {
		$login = $_SESSION["user_login"];
		
		$main__img = "../uploads/author__img.png";
		$_DB->delete_avatar($login,$main__img);
		

		header('Location: ../pages/home.php');
		die;
	}


	if (isset($_POST["see_profil"])) {
		$_SESSION["profil_login"] = $_POST["see_user_login"];
		header('Location: ../pages/profil.php');
		die;
	}

	if (isset($_POST["profil_exit"])) {
		unset($_SESSION["profil_login"]);
		header('Location: ../pages/home.php');
		die;
	}


	if (isset($_POST["admin_user"])){
		$login = $_POST["delate_user_login"];

		$users = $_DB->get_data();
		foreach ($users as $user) {
			if ($user->get_login() == $login) {
				if ($user->get_admin_rights() == "true") { 
					$_DB->admin_user($login,"false");
				} else {
					$_DB->admin_user($login,"true");
				}
			}
		}

		header('Location: ../pages/admin.php');
		die;
	}

	if (isset($_POST["admin_panel"])) {
		header('Location: ../pages/admin.php');
		die;
	}

	if (isset($_POST["change-bg-img"])) {
		if (!$_FILES['bg__img']["error"]) {

			$img__name = $_FILES['bg__img']["name"];
			$img__temp = $_FILES['bg__img']["tmp_name"];

			$valid__img = ["jpg","png","jpeg","webp","gif"];

			$file__type = pathinfo($img__name,PATHINFO_EXTENSION);

			if (!in_array($file__type,$valid__img)) {

			 $_SESSION['error'] = "Invalid Image Type ($file__type)";
			 header("Location: ../pages/home.php");
			 die;
			}
			if (!file_exists("../uploads")) {
			 mkdir("../uploads");
			}
			$final__directory = "../uploads/".uniqid("bg__img__",true).".".$file__type;

			move_uploaded_file($img__temp,$final__directory);
			$bg__img = $final__directory;
	    }

	    if (!isset($bg__img)) {
			$bg__img = "none";
		}

		$_DB->update_bg__img($_SESSION["user_login"],$bg__img);
		header('Location: ../pages/home.php');
		die;

	}


	if (isset($_POST['add_gallery_photo'])) {
		
		$arr = [];
		$gallery;

		$users = $_DB->get_data();
		foreach ($users as $user) {
			if ($user->get_login() == $_SESSION["user_login"]) {
				$gallery = $user->get_gallery();
			}
		}



		if ($gallery != "none") {
			for ($i=0; $i < count($gallery); $i++) { 
				array_push($arr,$gallery[$i]);
			}
		} 


		foreach ($_FILES['images']['name'] as $key => $imageName) {

			if ($_FILES['images']['error'][$key] > 0) {
				continue;
			}

			$validExtension = ['jpg','jpeg','png','gif'];

			$extension = pathinfo($imageName,PATHINFO_EXTENSION );


			if (!in_array($extension, $validExtension)) {
				$_SESSION['error'] = "Invalid Image Type ($extension)";
				header("Location: ../pages/home.php");
				die;
			}

			if (!file_exists('../uploads')) {
				mkdir('../uploads');
			}

			$newImageSrc = '../uploads/'. uniqid('new__image',true) .'.'.$extension;

			$imageTemp = $_FILES['images']['tmp_name'][$key];
			array_push($arr, $newImageSrc);

			move_uploaded_file($imageTemp, $newImageSrc);

			$main__array = json_encode($arr);
			$_DB->like_photo($_SESSION['user_login'],"none",$newImageSrc);
		}	

		if (!isset($main__array)) {
			$main__array = "none";
		}

		

		$_DB->update_gallery($_SESSION['user_login'],$main__array);
		header('Location: ../pages/home.php');
		die;


	}


	if (isset($_POST['change_main__img'])) {
		$main__img = $_POST['gallery_img'];

		$users = $_DB->get_data();
		foreach ($users as $user) {
			if ($user->get_login() == $_SESSION['user_login']) {
				$_DB->update_user($_SESSION["user_login"],$user->get_name(),$user->get_surname(),$user->get_password(),$main__img);
				header('Location: ../pages/home.php');
				die;
			}
		}
	}

	if (isset($_POST['delate_img'])) {
		$img = $_POST['gallery_img'];
		

		$users = $_DB->get_data();
		foreach ($users as $user) {
			if ($user->get_login() == $_SESSION["user_login"]) {
				$gallery = $user->get_gallery();
			}
		}
		

		if ($gallery != "none") {
			for ($i=0; $i < count($gallery); $i++) { 
				if ($gallery[$i] == $img) {
					$gallery[$i] = 'delated';
				}
			}
		} 
		
		if (count($gallery) == 0) {
			$main__array = "none";
		} else {
			$main__array = json_encode($gallery);
		}

	

		$_DB->update_gallery($_SESSION['user_login'],$main__array);
		header('Location: ../pages/home.php');
		die;
	}


	if (isset($_POST['change_bg_img'])) {
		$img = $_POST['gallery_img'];
		
		$_DB->update_bg__img($_SESSION["user_login"],$img);
		header('Location: ../pages/home.php');
		die;
	}


	// if (isset($_POST['like_submit'])) {
	// 	$img = $_POST['gallery_img'];

	// 	
		
		
	// }


	if (isset($_POST['like_btn'])) {
		$img = $_POST['gallery_img'];

		$like_helper = false;
		$unset_user;
		$arr = [];



		$liked_users = $_DB->get_like_data($_SESSION["profil_login"]);

				

				

		foreach($liked_users as $liked_user){
			if ($liked_user['image'] == $img) {

				if ($liked_user["liked_users"] != 'none') {
					$users_list = json_decode($liked_user['liked_users']);

					foreach($users_list as $user){
						array_push($arr,$user);
					}

					foreach($users_list as $user){
						if ($user == $_SESSION['user_login']) {
							$like_helper = true;
							$unset_user = $user;
						}
					}

					if (!$like_helper) {
						array_push($arr,$_SESSION['user_login']);

						$main__arr = json_encode($arr);
						$_DB->like_image($_SESSION["profil_login"],$main__arr,$img);
						
					} else {
						for($i = 0; $i < count($arr);$i++){
							if ($arr[$i] == $unset_user) {
								unset($arr[$i]);
								sort($arr);
							}

						}

						$main__arr = json_encode($arr);

						if ($arr == "") {
							$main__arr = "none";
						}


						$_DB->like_image($_SESSION["profil_login"],$main__arr,$img);
					}

					



				} else {
					array_push($arr,$_SESSION['user_login']);

					$main__arr = json_encode($arr);

					$_DB->like_image($_SESSION["profil_login"],$main__arr,$img);
				}



				


				echo count($arr);
				die;

			}
		}


	}





	if (isset($_POST['help_submit'])) {
		$message = $_POST['help-message'];
		
		if (strlen(trim($message)) > 255 || strlen(trim($message))<= 5) {
			$_SESSION['error'] = "Your message is invalid";
			header("Location: ../pages/home.php");
			die;
		} 


		$_DB->help_question($_SESSION['user_login'],$message);
		header('Location: ../pages/home.php');
		die;
		

	}

	if (isset($_POST['help_answer_submit'])) {
		$id = $_POST['user_id'];
		$admin_answer = $_POST['admin_answer'];
		$login = $_POST['question_login'];
		$answer_arr = [];
		$login_arr = [];

		if (strlen(trim($admin_answer)) > 255 || strlen(trim($admin_answer))<= 5) {
			$_SESSION['error'] = "Your answer is invalid";
			header("Location: ../pages/admin.php");
			die;
		} 


		$questions = $_DB->get_help_data();

		foreach($questions as $quest){
			if ($quest->get_id() == $id) {
				$admin_logins = $quest->get_admin_login();
				$admin_answers = $quest->get_admin_answer();
			}
		}

		if ($admin_answers != "none" && $admin_logins != "none") {
			for ($i=0; $i < count($admin_logins); $i++) { 
				array_push($login_arr,$admin_logins[$i]);
				array_push($answer_arr,$admin_answers[$i]);
			}
		} 


		array_push($login_arr,$_SESSION['user_login']);
		array_push($answer_arr,$admin_answer);

		$login_arr = json_encode($login_arr);
		$answer_arr = json_encode($answer_arr);



		$_DB->help_answer($login_arr,$answer_arr,$id);
		header('Location: ../pages/admin.php');
		die;

	}


	if (isset($_POST['sort_users'])) {
		$sort_type = $_POST['users_filter'];
		if ($sort_type == 'admins') {
			$_SESSION['sort_type'] = 'admins';
		} else if ($sort_type == 'users') {
			$_SESSION['sort_type'] = 'users';	
		} else {
			$_SESSION['sort_type'] = 'all';
		}
		header('Location: ../pages/admin.php');
		die;

	}	



	if (isset($_POST['shop_log'])) {
		$_SESSION['shop_active'] = true;
		header('Location: ../pages/shop.php');
		die;
	}


	if (isset($_POST['form_sub'])) {
		$name = $_POST["name"];
		$price = $_POST["price"];
		$description = $_POST["description"];
		$size = $_POST["size"];
		$brand = $_POST["brand"];
		$sale = $_POST["sale"];


		if (!$_FILES['prod_img']["error"]) {

			$img__name = $_FILES['prod_img']["name"];
			$img__temp = $_FILES['prod_img']["tmp_name"];

			$valid__img = ["jpg","png","jpeg","webp","gif"];

			$file__type = pathinfo($img__name,PATHINFO_EXTENSION);

			if (!in_array($file__type,$valid__img)) {

			 echo "Invalid Image Type ($file__type)";
			 die;
			}

			if (!file_exists("../uploads")) {
			 mkdir("../uploads");
			}

			$final__directory = "../uploads/".uniqid("prod__img__",true).".".$file__type;

			move_uploaded_file($img__temp,$final__directory);
			$prod__img = $final__directory;
	    }

	    if (!isset($prod__img)) {
			$prod__img = "../uploads/default-product.jpg";
		}


		if (strlen(trim($name)) > 32 || strlen(trim($name))<= 1) {
			echo "Product name is invalid";
			die;
		} else if (strlen(trim($price)) > 10 || strlen(trim($price))<= 1) {
			echo "Product price is invalid";
			die;
		} else if (strlen(trim($size)) > 10 || strlen(trim($size))< 1) {
			echo "Product size is invalid";
			die;
		} else if (strlen(trim($description)) > 655 || strlen(trim($description))<= 4) {
			echo "Product description is invalid";
			
		} else if (strlen(trim($sale)) > 3 || strlen(trim($sale))< 1) {
			echo "Product sale is invalid";
			die;
		} else if (strlen(trim($brand)) > 32 || strlen(trim($brand))<= 1) {
			echo "Product brand is invalid";
			die;
		}

		$_DB->add_product_data($name,$price,$size,$brand,$description,$sale,$prod__img);


	}


	if (isset($_POST['get_all_products'])) {
		echo($_DB->get_all_products());
	}

	if (isset($_POST['delete_product'])) {
		$_DB->delete_product($_POST['delete_product']);
	}



	if (isset($_POST['comment_sub'])) {
		$img = $_POST['gallery_img'];
		$comment = $_POST['comment'];
		$login = $_POST['login'];

		if (strlen(trim($comment)) > 255 || strlen(trim($comment))<= 5) {
			$_SESSION['error'] = "Your comment is invalid";
			die;
		} 

		$_DB->comment_photo($login,$img,$comment,$_SESSION['user_login']);

		$users = $_DB->get_data();
		foreach ($users as $user){
			if ($user->get_login() == $_SESSION['user_login']) {
				$main__img = $user->get_main__img();
				$name = $user->get_name();
				$surname = $user->get_surname();
			}
		}


		$arr = ['name'=> $name,'surname'=>$surname,'comment_content'=>$comment,'main__img'=>$main__img,'comment_login'=>$login,'comment_image'=>$img];


		echo json_encode($arr);
		die;
	}


	if (isset($_POST['reload_comments'])) {
		echo $_DB->get_comment_data_ajax($_POST['reload_comments']);
	}

	if (isset($_POST['search_product'])) {
		echo $_DB->search_product($_POST['search_product']);
	}

	if (isset($_POST['read_product'])) {
		echo $_DB->read_product($_POST['read_product']);
	}


	if (isset($_POST['product_edit_submit'])) {
		$name = $_POST["product_edit_name"];
		$price = $_POST["product_edit_price"];
		$description = $_POST["product_edit_description"];
		$size = $_POST["product_edit_size"];
		$brand = $_POST["product_edit_brand"];
		$sale = $_POST["product_edit_sale"];
		$id = $_POST['edit_product_id'];
		$num = (int) $sale;



		if (!$_FILES['edit_prod_img']["error"]) {

			$img__name = $_FILES['edit_prod_img']["name"];
			$img__temp = $_FILES['edit_prod_img']["tmp_name"];

			$valid__img = ["jpg","png","jpeg","webp","gif"];

			$file__type = pathinfo($img__name,PATHINFO_EXTENSION);

			if (!in_array($file__type,$valid__img)) {

			 echo "Invalid Image Type ($file__type)";
			 die;
			}

			if (!file_exists("../uploads")) {
			 mkdir("../uploads");
			}

			$final__directory = "../uploads/".uniqid("prod__img__",true).".".$file__type;

			move_uploaded_file($img__temp,$final__directory);
			$prod__img = $final__directory;
	    }

	    if (!isset($prod__img)) {
			$prod__img = $_POST['hidden_product_img'];
		}



		if (strlen(trim($name)) > 32 || strlen(trim($name))<= 1) {
			echo "Product name is invalid";
			die;
		} else if (strlen(trim($price)) > 10 || strlen(trim($price))<= 1) {
			echo "Product price is invalid";
			die;
		} else if (strlen(trim($size)) > 10 || strlen(trim($size))< 1) {
			echo "Product size is invalid";
			die;
		} else if (strlen(trim($description)) > 655 || strlen(trim($description))<= 4) {
			echo "Product description is invalid";	
		} else if ($num > 100) {
			echo "Product sale is invalid";
			die;
		} else if (strlen(trim($brand)) > 32 || strlen(trim($brand))<= 1) {
			echo "Product brand is invalid";
			die;
		}

		$_DB->update_product_data($name,$price,$size,$brand,$description,$sale,$prod__img,$id);


	}


	if (isset($_POST['add_cart_submit'])) {
		$id = $_POST['shop_product_id'];
		$helper = true;
	

		$products = $_DB->get_cart_products($_SESSION['user_login']);
		

		foreach ($products as $product) {
			if ($product->get_product_id() == $id) {
				if ($product->get_count() >= 1) {
					$k = $product->get_count()+1;
					$helper = false;
				} else{
					die;
				}
				
			} 

		}


		



		if (!$helper) {
			$_DB->update_cart_item($_SESSION['user_login'],$id,$k);
		}
		
		if ($helper) {
			$_DB->add_cart_item($_SESSION['user_login'],$id);
		}
	}


	if (isset($_POST['get_cart_products'])) {
		echo $_DB->get_cart_products($_SESSION['user_login']);
	}

	if (isset($_POST['minus_product_count'])) {
		$id = $_POST['minus_product_count'];

		$helper = true;
	

		$products = json_decode($_DB->get_cart_products($_SESSION['user_login']));
		

		foreach ($products as $product) {
			if ($product->product_id == $id) {
				if ($product->count >= 2) {
					$k = $product->count-1;
					$helper = false;
				} else{
					die;
				}
			} 

		}


		



		if (!$helper) {
			$_DB->update_cart_item($_SESSION['user_login'],$id,$k);
		}
	}

	if (isset($_POST['plus_product_count'])) {
		$id = $_POST['plus_product_count'];

		$helper = true;
	

		$products = json_decode($_DB->get_cart_products($_SESSION['user_login']));
	

		foreach ($products as $product) {
			if ($product->product_id == $id) {
				if ($product->count >= 1) {
					$k = $product->count+1;
					$helper = false;
				} else{
					die;
				}
			} 

		}


		



		if (!$helper) {
			$_DB->update_cart_item($_SESSION['user_login'],$id,$k);
		}
	}


	if (isset($_POST['delate_cart_product'])) {
		$_DB->delate_cart_item($_SESSION['user_login'],$_POST['delate_cart_product']);
	}



	if (isset($_POST['user_search'])) {
		echo $_DB->user_search($_POST['user_search']);
	}

	if (isset($_POST['get_all_cart_products'])) {
		echo $_DB->get_cart_products($_POST['get_all_cart_products']);
	}

