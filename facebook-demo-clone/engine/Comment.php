<?php
	class Comment{
		public $id;
		public $user_login;
		public $image;
		public $comment_content;
		public $comment_user;



		public function __construct($id,$user_login,$image,$comment_content,$comment_user){
			$this->id = $id;
			$this->user_login = $user_login;
			$this->image = $image;
			$this->comment_content = $comment_content;
			$this->comment_user = $comment_user;
		}


		public function get_user_login(){
			return $this->user_login;
		}

		public function get_image(){
			return $this->image;
		}
		

		public function get_comment_content(){
			return $this->comment_content;
		}

		public function get_comment_user(){
			return $this->comment_user;
		}

		

	}
?>