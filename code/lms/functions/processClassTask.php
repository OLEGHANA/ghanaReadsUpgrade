<?php
use PHPOnCouch\CouchClient;
use PHPOnCouch\Exceptions;
use PHPOnCouch\CouchDocument;
global $couchUrl;
global $facilityId;
$actionTrue = false;
$assignments = new couchClient($couchUrl, "assignments");
$resources = new couchClient($couchUrl, "resources");
$groups = new couchClient($couchUrl, "groups");
$feedbacks = new couchClient($couchUrl, "feedback");
if(isset($_POST['vBook']))
{
	
		if($_POST['vBook']!="none")
		{
			$doc = new stdClass();
			$resID = $_POST['vBook'];
			$resDoc = $resources->getDoc($resID);
			$doc->kind = "Assignment";
			$doc->resourceId = $resID;
			$doc->startDate = strtotime($_POST['startDate']);
			$doc->endDate = strtotime($_POST['endDate']);
			//$doc->startDate = $_POST['startDate'];
			//$doc->endDate = $_POST['endDate'];
			$doc->memberId =$_SESSION['lmsUserID'];
			$chosenQuestions =array();
			foreach($_POST['vbQ'] as $vbQ) {
				array_push($chosenQuestions,$vbQ);
			}
			$doc->context = array(
			  "subject" => $resDoc->subject,
			  "use" => "video book task",
			  "groupId" => $_POST['level'],
			  "facilityId"=>$facilityId,
			  "questions"=>$chosenQuestions
			);
			$response = $assignments->storeDoc($doc);
		}
	///// save in feedback too
		if($_POST['vBook']!="none")
		{
			$doc = new stdClass();
			$resID = $_POST['vBook'];
			$resDoc = $resources->getDoc($resID);
			$groupDoc = $groups->getDoc($_POST['level']);
			$doc->kind ="Feedback";
			$doc->rating=0;
			$doc->comment="";
			$doc->facilityId=$facilityId;
			$doc->memberId =$_SESSION['lmsUserID'];
			$doc->resourceId = $resID;
			$doc->timestamp = strtotime($_POST['startDate']);
			///$doc->timestamp = $_POST['startDate'];
			$doc->context = array(
			  "subject" => $resDoc->subject,
			  "use" => "video book task",
			  "level" => $groupDoc->level[0]
			);
			$response = $feedbacks->storeDoc($doc);
		}
	echo "| Video |";
	$actionTrue = true;
	recordActionObject($_SESSION['lmsUserID'],"Assigned video book task for the week ",$_POST['level']);
 	echo '<script type="text/javascript">alert("Video book saved successfully");</script>';
}
if(isset($_POST['AStory']))
{
	for($cnt=0; $cnt<sizeof($_POST['story']);$cnt++){
		if($_POST['story'][$cnt]!="none")
		{
			$doc = new stdClass();
			$resID = $_POST['story'][$cnt];
			$resDoc = $resources->getDoc($resID);
			$doc->kind = "Assignment";
			$doc->resourceId = $_POST['story'][$cnt];
			$doc->startDate = strtotime($_POST['startDate']);
			$doc->endDate = strtotime($_POST['endDate']);
			//$doc->startDate = $_POST['startDate'];
			//$doc->endDate = $_POST['endDate'];
			$doc->memberId =$_SESSION['lmsUserID'];
			$doc->context = array(
			  "subject" => $resDoc->subject,
			  "use" => "stories for the week",
			  "groupId" => $_POST['level'],
			  "facilityId"=>$facilityId
			  
			);
			$response = $assignments->storeDoc($doc);
		}
	}
	///// save in feedback too
	for($cnt=0; $cnt<sizeof($_POST['story']);$cnt++){
		if($_POST['story'][$cnt]!="none")
		{
			$doc = new stdClass();
			$resID = $_POST['story'][$cnt];
			$resDoc = $resources->getDoc($resID);
			$groupDoc = $groups->getDoc($_POST['level']);
			$doc->kind ="Feedback";
			$doc->rating=0;
			$doc->comment="";
			$doc->facilityId=$facilityId;
			$doc->memberId =$_SESSION['lmsUserID'];
			$doc->resourceId = $_POST['story'][$cnt];
			$doc->timestamp = strtotime($_POST['startDate']);
			$doc->context = array(
			  "subject" => $resDoc->subject,
			  "use" => "stories for the week",
			  "level" => $groupDoc->level[0]
			);
			$response = $feedbacks->storeDoc($doc);
		}
	}
	echo "| Audio |";
	$actionTrue = true;
	recordActionObject($_SESSION['lmsUserID'],"Assigned audio story task for the week ",$_POST['level']);
 	echo '<script type="text/javascript">alert("Audio assignment saved successfully");</script>';
}
if(isset($_POST['Phons']))
{
	die("Phonics");
}
if(isset($_POST['WordP']))
{
	die("Word Power");
}
if($actionTrue){
	echo " assignment saved";
}

?>