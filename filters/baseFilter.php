<?php

class BaseFilter extends BaseModel{
	public $user_class;
	public $user_device;
	public $user_auth;
	public $user_id;
	public $auth_rules;
	public __constructor(){
		$this->auth_rules = ['SU','FA','AD','ED','AG','US'];
		$this->tbls = ["user_list","","",""]
	}
	public function auth($intgr){
		//TODO: Implement
		$this->
		
	}
	protected function get_session(){
		$this->user_auth = filter_input(INPUT_SESSION,"user_key");
		$this->user_id = filter_input(INPUT_SESSION,"user_id");
	}
	public static function start(){
		session_start();
	}
	protected function get_useragent(){
		$this->user_device = filter_input(INPUT_SERVER,"HTTP_USER_AGENT");
	}
	protected function check_match(){
		
	}
}
