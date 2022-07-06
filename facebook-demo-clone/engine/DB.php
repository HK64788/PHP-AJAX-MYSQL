<?php
require_once 'User.php';
require_once 'Image.php';
require_once 'Comment.php';
require_once 'Help.php';
require_once 'Cart.php';



class DB{

	private const _HOST = 'localhost';
	private const _USER_NAME = 'root';
	private const _USER_PASS = '';
	private const _DB_NAME = 'hkbase';

	private $mysql;
	
	public function __construct(){
		$this->mysql = new mysqli(self::_HOST, self::_USER_NAME, self::_USER_PASS, self::_DB_NAME);
	}

	public function __destruct(){
		$this->mysql->close();
	}
	public function add_data($name,$surname,$login,$password,$age,$main__img,$rights,$admin_rights,$bg__img,$gallery){
		$sql = "INSERT INTO `user`(`id`, `name`, `surname`, `login`, `password`,`age`,`main__img`,`rights`,`admin_rights`,`bg__img`,`gallery`) VALUES(NULL, ?, ?, ?,?,?,?,?,?,?,?)";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param('ssssisssss', $name,$surname,$login,$password,$age,$main__img,$rights,$admin_rights,$bg__img,$gallery);
		$stmt->execute();
	}

	public function get_data(){
		$buffer = $this->mysql->query("SELECT * FROM `user`");
		$obj_arr = [];


		while ($obj = $buffer->fetch_object()) {
			$obj_arr[] = new User($obj->id,$obj->name,$obj->surname,$obj->login,$obj->password,$obj->age,$obj->main__img,$obj->rights,$obj->admin_rights,$obj->bg__img,$obj->gallery);
		}

		$buffer->free_result();
		return $obj_arr;
	}

	public function delete_user($login){
		$sql = "DELETE FROM `user` WHERE `login` = ?";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param('s', $login);
		$stmt->execute();
	}

	public function block_user($login,$right){
		$sql = "UPDATE `user` SET `rights`= ? WHERE `login` = ?";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param('ss',$right,$login);
		$stmt->execute();
	}

	public function update_user($update_login,$name,$surname,$new_password,$main__img){
		$sql = "UPDATE `user` SET `name`= ?,`surname` = ?,`password` = ?,`main__img` = ? WHERE `login` = ?";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param('sssss',$name,$surname,$new_password,$main__img,$update_login);
		$stmt->execute();
	}

	public function delete_avatar($login,$main__img){
		$sql = "UPDATE `user` SET `main__img`= ? WHERE `login` = ?";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param('ss',$main__img,$login);
		$stmt->execute();
	}

	public function admin_user($login,$right){
		$sql = "UPDATE `user` SET `admin_rights`= ? WHERE `login` = ?";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param('ss',$right,$login);
		$stmt->execute();
	}

	public function update_bg__img($login,$bg__img){
		$sql = "UPDATE `user` SET `bg__img`= ? WHERE `login` = ?";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param('ss',$bg__img,$login);
		$stmt->execute();
	}
	public function update_gallery($login,$gallery){
		$sql = "UPDATE `user` SET `gallery`= ? WHERE `login` = ?";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param('ss',$gallery,$login);
		$stmt->execute();
	}

	public function like_photo($login,$who_liked,$image){
		$sql = "INSERT INTO `like`(`user_login`,`liked_users`,`image`) VALUES(?,?,?)";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param('sss', $login,$who_liked,$image);
		$stmt->execute();
	}

	public function get_like_data($login){
		// $buffer = $this->mysql->query("SELECT * FROM `like` WHERE `user_login` = $login");
		// $obj_arr = [];


		// while ($obj = $buffer->fetch_object()) {
		// 	$obj_arr[] = new Image($obj->id,$obj->user_login,$obj->liked_users,$obj->image);
		// }

		// $buffer->free_result();
		// return $obj_arr;

		$sql = "SELECT * FROM `like` WHERE `user_login` = ?";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param("s", $login);
		$stmt->execute();

		$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
		
		return $result;

	}


	public function like_image($login,$liked_users,$image){
		$sql = "UPDATE `like` SET `liked_users`= ? WHERE `user_login` = ? AND `image` = ?";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param('sss',$liked_users,$login,$image);
		$stmt->execute();
	}


	public function comment_photo($login,$image,$comment_content,$comment_user){
		$sql = "INSERT INTO `comment`(`id`,`user_login`,`image`,`comment_content`,`comment_user`) VALUES(NULL,?,?,?,?)";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param('ssss', $login,$image,$comment_content,$comment_user);
		$stmt->execute();
	}

	public function get_comment_data($login,$comment_image){
		// $buffer = $this->mysql->query("SELECT * FROM `comment` WHERE `user_login` = $login");
		// $obj_arr = [];


		
		// while ($obj = $buffer->fetch_object()) {
		// 	$obj_arr[] = new Comment($obj->id,$obj->user_login,$obj->image,$obj->comment_content,$obj->comment_user);
		// }

		// $buffer->free_result();
		// return $obj_arr;

		$sql = "SELECT * FROM `comment` WHERE `user_login` = ?";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param("s", $login);
		$stmt->execute();

		$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
		
		return $result;
	}

	public function get_comment_data_ajax($login){
		$buffer = $this->mysql->query("SELECT * FROM `comment` WHERE `user_login` = $login");
		$result = $buffer->fetch_all(MYSQLI_ASSOC);
		$buffer->free_result();
		
		return json_encode($result);
	}

	public function get_help_data(){
		$buffer = $this->mysql->query("SELECT * FROM `help`");
		$obj_arr = [];


		
		while ($obj = $buffer->fetch_object()) {
			$obj_arr[] = new Help($obj->id,$obj->user_login,$obj->question,$obj->admin_login,$obj->admin_answer);
		}

		$buffer->free_result();
		return $obj_arr;
	}

	public function help_question($login,$question){
		$sql = "INSERT INTO `help`(`id`,`user_login`,`question`,`admin_login`,`admin_answer`) VALUES(NULL,?,?,'none','none')";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param('ss', $login,$question);
		$stmt->execute();
	}


	public function help_answer($admin_login,$admin_answer,$id){
		$sql = "UPDATE `help` SET `admin_login`= ?, `admin_answer` = ? WHERE `id` = ?";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param('sss',$admin_login,$admin_answer,$id);
		$stmt->execute();
	}

	public function add_product_data($name,$price,$size,$brand,$description,$sale,$prod__img){
		$sql = "INSERT INTO `product`(`id`,`name`,`price`,`size`,`brand`,`description`,`sale`,`image`) VALUES(NULL,?,?,?,?,?,?,?)";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param('siissis', $name,$price,$size,$brand,$description,$sale,$prod__img);
		$stmt->execute();
	}


	public function get_all_products(){
		$buffer = $this->mysql->query("SELECT * FROM `product`");
		$result = $buffer->fetch_all(MYSQLI_ASSOC);
		$buffer->free_result();
		
		return json_encode($result);
	}

	public function delete_product($id){
		$sql = "DELETE FROM `product` WHERE `id` = ?";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param('s', $id);
		$stmt->execute();
	}


	public function search_product($searchVal){
		$searchVal = "%$searchVal%";
		$sql = "SELECT * FROM `product` WHERE `name` LIKE ?";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param("s", $searchVal);
		$stmt->execute();

		$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
		
		return json_encode($result);
	}


	public function read_product($id){
		$sql = "SELECT * FROM `product` WHERE `id` = ?";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param("s", $id);
		$stmt->execute();

		$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
		
		return json_encode($result);
	}


	public function update_product_data($name,$price,$size,$brand,$description,$sale,$prod__img,$id){
		$sql = "UPDATE `product` SET `name`=?,`price`=?,`size`=?,`brand`=?,`description`=?,`sale`=?,`image`=? WHERE `id` = ?";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param('siississ', $name,$price,$size,$brand,$description,$sale,$prod__img,$id);
		$stmt->execute();
	}

	public function add_cart_item($login,$id){
		$sql = "INSERT INTO `cart`(`id`,`user_login`,`product_id`,`count`) VALUES(NULL,?,?,1)";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param('si',$login,$id);
		$stmt->execute();
	}

	public function get_cart_products($login){

		// $buffer = $this->mysql->query("SELECT * FROM `cart` WHERE `user_login` = $login");
		// $obj_arr = [];


		
		// while ($obj = $buffer->fetch_object()) {
		// 	$obj_arr[] = new Cart($obj->id,$obj->user_login,$obj->product_id,$obj->count);
		// }

		// $buffer->free_result();
		// return $obj_arr;

		$sql = "SELECT * FROM `cart` WHERE `user_login` = ?";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param("s", $login);
		$stmt->execute();

		$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
		
		return json_encode($result);

	}



	public function update_cart_item($login,$product_id,$count){
		$sql = "UPDATE `cart` SET `count`=? WHERE `user_login` = ? AND `product_id` = ?";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param('isi', $count,$login,$product_id);
		$stmt->execute();
	}


	public function delate_cart_item($login,$product_id){
		$sql = "DELETE FROM `cart` WHERE  `user_login` = ? AND `product_id` = ?";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param('si', $login,$product_id);
		$stmt->execute();
	}

	public function user_search($searchVal){
		$searchVal = "%$searchVal%";
		$sql = "SELECT * FROM `user` WHERE `name` LIKE ?";
		$stmt = $this->mysql->prepare($sql);
		$stmt->bind_param("s", $searchVal);
		$stmt->execute();

		$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
		
		return json_encode($result);
	}

}


$_DB = new DB;