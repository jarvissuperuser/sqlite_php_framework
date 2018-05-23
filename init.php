<?php
//require_once "classes/controller.php";

$ctrl = ["model","User","Controller","Profile"];
foreach ($ctrl as $take)
{
	require_once "./models/$take.php";
}
require_once "filters/baseFilter.php";
require_once "QueryBuild.php";
