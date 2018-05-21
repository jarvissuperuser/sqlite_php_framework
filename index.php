<?php
/**
 * Created by PhpStorm.
 * User: timothy
 * Date: 2018/05/19
 * Time: 3:10 PM
 */
require_once "init.php";
$db = new QueryBuild();
$u = new User($db);
try {
	switch (filter_input(INPUT_POST, "submit")) {
		case 'add_user':
			$u->add();
			break;
		case "search":
			$u->search();
			break;
		case "get_user":
			$u->get();
			break;
//		case "get_users":
//			$u->get_all($pointer);
//			break;
//		case "update_user":
//			$u->update($pointer);
//			break;
//		case "login":
//		case "sign_in":
//			$u->login();
//			break;
		default:
			echo '["nada"]';
//		 $this->process_survey($pointer);
	}
} catch (Exception $exc){
	echo json_encode(["Error"=>$exc->getMessage()]);
}