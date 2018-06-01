<?php
/**
 * Created by PhpStorm.
 * User: timothy
 * Date: 2018/05/19
 * Time: 3:10 PM
 */
require_once "init.php";
BaseFilter::start();
//BaseFilter::init();
try 
{
	$db = new QueryBuild();
	$u = new User($db);
	$p = new Profile($db);
	
	Controller::process_users($u);
	Controller::process_profiles($p);
}
catch (PDOException $pe){
	switch(trim(filter_input(INPUT_POST,"debug"))){
		case '1':
			echo json_encode(["Error"=>$pe->getTraceAsString(),$pe->getTrace()]);
			break;
		default:
			echo json_encode(["Error"=>"Request Failed","success"=>false]);
	}
} 
catch (Exception $exc){
	switch(trim(filter_input(INPUT_POST,"debug"))){
		case '1':
			echo json_encode(["Error"=>$exc->getTraceAsString(),$exc->getTrace()]);
			break;
		default:
			echo json_encode(["Error"=>$exc->getMessage(),"success"=>false]);
	}
}
