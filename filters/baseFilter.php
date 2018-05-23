<?php

class BaseFilter extends BaseModel{
	public $user_class;
	public $user_device;
	public  $user_auth ;
	public $user_id;
	public $user_privilege;
	public static $auth_rules = ['SU','FA','AD','ED','AG','US'];
	public function __construct(){
		$this->auth_rules_can = ['SuperUser','FinAdmin','ADmin',
		'EDucator','AGent','USer'];
		$this->tbls = ["user_list","user_details","",""];
		$this->get_useragent();
		$this->get_session();
	}
	public function check_user($intgr){
		//TODO: Implement
		$count = $this->record_check(0,["email"])[0];
		if ($count<1)throw new Exception("User Not Found");
	}
	public function login(){
		$what = $this->multi_builder(["email","type"]);
		$user=$this->get_from_db($what,0,1,1);
		if(user["password"] != BaseFilter::phash("password"))
			throw new Exception("User Not Found");
		$count = count($user);
		if($count<1)throw new Exception("User Not Found");
		$user_key = ["id"=>$user["uid"],
			"user_agent"=>$this->user_device];
		$str_key = json_encode($user_key);
		session_register("user_id",$user["udid"]);
		session_register("user_privilage",$user["type"]);
		session_register("user_key",lock_key($str_key));
	}
	protected function get_session(){
		$this->user_auth = filter_input(INPUT_SESSION,"user_key");
		$this->user_id = filter_input(INPUT_SESSION,"user_id");
		$this->user_privilege = 
			filter_input(INPUT_SESSION,"user_privilege");
	}
	public static function start(){
		session_start();
	}
	protected function get_useragent(){
		$this->user_device = filter_input(INPUT_SERVER,"HTTP_USER_AGENT");
	}
	public static function auth($level_key){
		$level = BaseFilter::get_level($level_key);
		$user_level = BaseFilter::get_level(
			filter_input(INPUT_SESSION,"user_privilege"));
		return ($user_level>$level);
	}
	private function lock_key($str){
		$str1=base64_encode($str);
		$str2=str_rot13($str1);
		return $str2;
	}
	private function unlock_key($str){
		$str2=str_rot13($str);
		$str1=base64_decode($str2);
		return $str1;
	}
	public static function phash($key){
		return hash("SHA256",filter_input(INPUT_POST,"password"));
	}
	public static function get_level($key){
		for($l = 0;$l< count(BaseFilter::$auth_rules);$l++)
			if(BaseFilter::$auth_rules[$l] == $key)
				 return $l;
		return 5;
	}
}
