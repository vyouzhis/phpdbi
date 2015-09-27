<?php
include_once Module.'/horribilis/catch.php';
if(defined("INJECTION") == false){
	define("INJECTION", md5(time()));
	$catch = new CatchAction();
	$catch->init();	
}