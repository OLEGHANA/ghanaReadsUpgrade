<?php
/*
$server ="localhost";
$username ="root";
$password ="oleole";
//$password =“oleole”;
date_default_timezone_set('UTC');
$dbhandle= mysql_connect($server,$username,$password) or die(mysql_error());
$selected = mysql_select_db("schoolBell",$dbhandle) or die (mysql_error());
*/
session_start();
global $couchUrl;
require $_SERVER['DOCUMENT_ROOT'].'/lms/vendor/autoload.php';
include $_SERVER['DOCUMENT_ROOT'].'/lms/secure/init.db.php';
use PHPOnCouch\CouchClient;
use PHPOnCouch\Exceptions;
use PHPOnCouch\CouchDocument;
global $facilityId;
global $config;
global $facility_data;
$facility_json = file_get_contents($couchUrl . '/whoami/facility'); 
$facility_data = json_decode($facility_json);
$config_json = file_get_contents($couchUrl . '/whoami/config'); 
$config = json_decode($config_json);
$facilityId = $facility_data->facilityId;

function recordAction($action_by,$save_data){
	global $facilityId;
	$todayDate = date("Y-m-d H:i:s"); 
 $actions = new couchClient($couchUrl, 'actions');
  $doc = new stdClass();
  $doc->kind ='Action';
  $doc->memberId = $_SESSION['lmsUserID'];
 $doc->facilityId = $facilityId;
  $doc->action = $save_data;
  $doc->objectId= "";
  $doc->timestamp= strtotime($_SESSION['dateTime']);
  $doc->context= "lms";
  $actions->storeDoc($doc);
}


function recordActionObject($userID,$action,$object){
	global $facilityId;
	global $couchUrl;
	$todayDate = date("Y-m-d H:i:s"); 
	$actions = new couchClient($couchUrl, "actions");
	  $doc = new stdClass();
	 $doc->kind ="Action";
	  $doc->memberId = $_SESSION['lmsUserID'];
	  $doc->memberRoles = $_SESSION['role'];
	  $doc->facilityId = $facilityId;
	  $doc->action = $action;
	  $doc->objectId= $object;
	  $doc->timestamp= strtotime($_SESSION['dateTime']);
	  $doc->context= "lms";
	  //print_r($doc);
	  $response = $actions->storeDoc($doc);
}

function recordActionObjectDate($userID,$action,$object,$systemDateForm){
	global $facilityId;
	global $couchUrl;
	$todayDate = date("Y-m-d H:i:s"); 
	 $actions = new couchClient($couchUrl, "actions");
	  $doc = new stdClass();
	 $doc->kind ="Action";
	  $doc->memberId = $_SESSION['lmsUserID'];
	  $doc->memberRoles = $_SESSION['role'];
	  $doc->facilityId = $facilityId;
	  $doc->action = $action;
	  $doc->objectId= $object;
	  $doc->timestamp= strtotime($_SESSION['dateTime']);
	  $doc->context= "lms";
	  //print_r($doc);
	  $response = $actions->storeDoc($doc);
}



?>
