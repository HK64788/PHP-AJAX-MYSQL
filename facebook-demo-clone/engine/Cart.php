<?php
	class Cart{
		public $id;
		public $user_login;
		public $product_id;
		public $count;


		public function __construct($id,$user_login,$product_id,$count){
			$this->id = $id;
			$this->user_login = $user_login;
			$this->product_id = $product_id;
			$this->count = $count;
		}


		public function get_user_login(){
			return $this->user_login;
		}
		

		public function get_product_id(){
			return $this->product_id;
		}

		public function get_count(){
			return $this->count;
		}


	}
?>