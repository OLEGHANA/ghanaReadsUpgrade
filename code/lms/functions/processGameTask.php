<?php

global $couchUrl;
global $facilityId;
$actionTrue = false;
$assignments = new couchClient($couchUrl, "assignments");
$resources = new couchClient($couchUrl, "resources");
$groups = new couchClient($couchUrl, "groups");
$feedbacks = new couchClient($couchUrl, "feedback");
if(isset($_POST['submitStatus']))
{
	if($_SESSION['lmsUserID'] != null){
	//if($_POST['submitStatus']=='true'){
		$doc = new stdClass();
		$doc->words = array();
		$doc->duration = array();
		$doc->kind = "Assignment";
		for($cnt=0; $cnt<sizeof($_POST['word']);$cnt++){
			if($_POST['word'][$cnt]!="")
			{
				array_push($doc->words,$_POST['word'][$cnt]);
				array_push($doc->duration,$_POST['duration'][$cnt]);
			}
		}
		$doc->startDate = strtotime($_POST['startDate']);
		$doc->endDate = strtotime($_POST['endDate']);
		//$doc->startDate = $_POST['startDate'];
		//$doc->endDate = $_POST['endDate'];
		$doc->memberId =$_SESSION['lmsUserID'];
		$doc->context = array(
		  "subject" => 'english',
		  "use" => "word formation",
		  "groupId" => $_POST['level'],
		  "facilityId"=>$facilityId
		  
		);
		$response = $assignments->storeDoc($doc);
		///// save in feedback too
		echo $response->_id;
		die ("       Form words from a given word saved    ");
		$actionTrue = true;
		recordActionObject($_SESSION['lmsUserID'],"Assigned a task (Form words from a given word)",$_POST['level']);
	}else{
		die('Sorry, this session has ended. Please logout and login again');
	}
} 
else if(isset($_POST['submitFillBlanks'])){
	if($_SESSION['lmsUserID'] != null){
	//if($_POST['submitStatus']=='true'){
		$doc = new stdClass();
		$doc->words = array();
		$doc->duration = array();
		$doc->blanks = array();
		$doc->kind = "Assignment";
		for($cnt=0; $cnt<sizeof($_POST['word']);$cnt++){
			if($_POST['word'][$cnt]!="")
			{
				array_push($doc->words,$_POST['word'][$cnt]);
				array_push($doc->duration,$_POST['duration'][$cnt]);
				array_push($doc->blanks,$_POST['missing'][$cnt]);
			}
		}
		$doc->startDate = strtotime($_POST['startDate']);
		$doc->endDate = strtotime($_POST['endDate']);
		//$doc->startDate = $_POST['startDate'];
		//$doc->endDate = $_POST['endDate'];
		$doc->memberId =$_SESSION['lmsUserID'];
		$doc->context = array(
		  "subject" => 'english',
		  "use" => "fill blanks",
		  "groupId" => $_POST['level'],
		  "facilityId"=>$facilityId
		  
		);
		$response = $assignments->storeDoc($doc);
		///// save in feedback too
		echo $response->_id;
		die ("    Fill in the blanks saved    ");
		$actionTrue = true;
		recordActionObject($_SESSION['lmsUserID'],"Assigned a task (Fill in the blanks)",$_POST['level']);
	}else{
		die('Sorry, this session has ended. Please logout and login again');
	}
}else if(isset($_POST['submitlistenFind'])){
	if($_SESSION['lmsUserID'] != null){
	//if($_POST['submitStatus']=='true'){
		$doc = new stdClass();
		$doc->imageSetting = $_POST['setting'];
		$doc->duration =  $_POST['duration'];
		$doc->kind = "Assignment";
		$doc->startDate = strtotime($_POST['startDate']);
		$doc->endDate = strtotime($_POST['endDate']);
		$doc->memberId =$_SESSION['lmsUserID'];
		$doc->context = array(
		  "subject" => 'english',
		  "use" => "listen and find",
		  "groupId" => $_POST['level'],
		  "facilityId"=>$facilityId
		  
		);
		$response = $assignments->storeDoc($doc);
		///// save in feedback too
		echo $response->_id;
		die ("    listen and Find saved    ");
		$actionTrue = true;
		recordActionObject($_SESSION['lmsUserID'],"Assigned a task (listen and find blanks)",$_POST['level']);
	}else{
		die('Sorry, this session has ended. Please logout and login again');
	}
}
else if(isset($_POST['submitStatusUnjumble'])){
	if($_SESSION['lmsUserID'] != null){
	//if($_POST['submitStatus']=='true'){
		$doc = new stdClass();
		$doc->jumbledWord = $_POST['jumbledWord'];
		$doc->duration =  $_POST['duration'];
		$doc->kind = "Assignment";
		$doc->startDate = strtotime($_POST['startDate']);
		$doc->endDate = strtotime($_POST['endDate']);
		$doc->memberId =$_SESSION['lmsUserID'];
		$doc->context = array(
		  "subject" => 'english',
		  "use" => "unjumble words",
		  "groupId" => $_POST['level'],
		  "facilityId"=>$facilityId
		  
		);
		$response = $assignments->storeDoc($doc);
		///// save in feedback too
		echo $response->_id;
		die ("    Unjumble Words Saved    ");
		$actionTrue = true;
		recordActionObject($_SESSION['lmsUserID'],"Assigned a task (Unjumble words)",$_POST['level']);
	}else{
		die('Sorry, this session has ended. Please logout and login again');
	}
}
else if(isset($_POST['submitStatusWordPower'])){
	if($_SESSION['lmsUserID'] != null){
	//if($_POST['submitStatus']=='true'){
		$doc = new stdClass();
		$doc->wordCode = $_POST['char'].$_POST['numbOfLetters'];
		$doc->duration =  $_POST['duration'];
		$doc->kind = "Assignment";
		$doc->startDate = strtotime($_POST['startDate']);
		$doc->endDate = strtotime($_POST['endDate']);
		$doc->memberId =$_SESSION['lmsUserID'];
		$doc->context = array(
		  "subject" => 'english',
		  "use" => "word power",
		  "groupId" => $_POST['level'],
		  "facilityId"=>$facilityId
		);
		$response = $assignments->storeDoc($doc);
		///// save in feedback too
		echo $response->_id;
		die ("    Words Power Saved    ");
		$actionTrue = true;
		recordActionObject($_SESSION['lmsUserID'],"Assigned a task (Words Power)",$_POST['level']);
	}else{
		die('Sorry, this session has ended. Please logout and login again');
	}
}



//submitStatusUnjumble

//
?>