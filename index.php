<?php
/**
 * Created by PhpStorm.
 * User: timothy
 * Date: 2018/05/19
 * Time: 3:10 PM
 */
require_once "init.php";

try 
{
	$db = new QueryBuild();
	$u = new User($db);
	$p = new Profile($db);
	switch (trim(filter_input(INPUT_SERVER, "serve"))) {
		case 'add_user':
			$u->add();
			break;
		case "search":
			$u->search();
			break;
		case "get_user":
			$u->get();
			break;
		case "add_profile":
			$p->app();
			break;
		case "get_profile":
			$p->get();
			break;
		case "update":
			$u->update_all();
			break;
//		case "login":
//		case "sign_in":
//			$u->login();
//			break;
		default:
			echo '["nada"]';
//		 $this->process_survey($pointer);
	}
} catch (Exception $exc){
	switch(trim(filter_input(INPUT_POST,"debug"))){
		case '1':
			echo json_encode(["Error"=>$exc->getTraceAsString()]);
			break;
		default:
			echo json_encode(["Error"=>$exc->getMessage()]);
	}
}
