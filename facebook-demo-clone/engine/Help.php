<?php
class Help{
		public $id;
		public $user_login;
		public $question;
		public $admin_login;
		public $admin_answer;



		public function __construct($id,$user_login,$question,$admin_login,$admin_answer){
			$this->id = $id;
			$this->user_login = $user_login;
			$this->question = $question;
			$this->admin_login = $admin_login;
			$this->admin_answer = $admin_answer;
		}

		public function get_id(){
			return $this->id;
		}

		public function get_user_login(){
			return $this->user_login;
		}

		public function get_question(){
			return $this->question;
		}
		

		public function get_admin_login(){
			if ($this->admin_login != "none") {
				return json_decode($this->admin_login);
			} else {
				return "none";
			}
		}

		public function get_admin_answer(){
			if ($this->admin_answer != "none") {
				return json_decode($this->admin_answer);
			} else {
				return "none";
			}
		}

		

	}
?>