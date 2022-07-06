<?php
	class User {

		public $id;
		public $name;
		public $surname;
		public $login;
		public $password;
		public $age;
		public $main__img;
		public $rights;
		public $admin_rights;
		public $bg__img;
		public $gallery;


		public function __construct ($id,$name,$surname,$login,$password,$age,$main__img,$rights,$admin_rights,$bg__img,$gallery){
			$this->id = $id;
			$this->name = $name;
			$this->surname = $surname;
			$this->login = $login;
			$this->password = $password;
			$this->age = $age;
			$this->main__img = $main__img;
			$this->rights = $rights;
			$this->admin_rights = $admin_rights;
			$this->bg__img = $bg__img;
			$this->gallery = $gallery;
		}


		public function get_login(){
			return $this->login;
		}

		public function get_password(){
			return $this->password;
		}


		public function get_name(){
			return $this->name;
		}

		public function get_surname(){
			return $this->surname;
		}

		public function get_age(){
			return $this->age;
		}

		public function get_main__img(){
			return $this->main__img;
		}

		public function get_id(){
			return $this->id;
		}

		public function get_rights(){
			return $this->rights;
		}

		public function get_admin_rights(){
			return $this->admin_rights;
		}

		public function get_bg__img(){
			return $this->bg__img;
		}

		public function get_gallery(){
			if ($this->gallery != "none") {
				return json_decode($this->gallery);
			} else{
				return "none";
			}
		}







	}
?>