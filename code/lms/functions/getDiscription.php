<?php session_start();
require $_SERVER['DOCUMENT_ROOT'].'/lms/secure/talk2db.php';
use PHPOnCouch\CouchClient;
use PHPOnCouch\Exceptions;
use PHPOnCouch\CouchDocument;
global $couchUrl;
	global $facilityId;
	$numberOfUsedRes=0;
	$resources = new couchClient($couchUrl, "resources");
	$resDoc = $resources->getDoc($_GET['id']);
	echo $resDoc->description;
	   
?>