<?php
	class Image{
		public $id;
		public $user_login;
		public $liked_users;
		public $image;


		public function __construct($id,$user_login,$liked_users,$image){
			$this->id = $id;
			$this->user_login = $user_login;
			$this->liked_users = $liked_users;
			$this->image = $image;
		}


		public function get_user_login(){
			return $this->user_login;
		}
		

		public function get_liked_users(){
			if ($this->liked_users != "none") {
				return json_decode($this->liked_users);
			} else {
				return "none";
			}
		}

		public function get_image(){
			return $this->image;
		}

	}
?>