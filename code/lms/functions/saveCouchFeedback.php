<?php
include "../secure/talk2db.php";
global $couchUrl;
global $facilityId;
$actionTrue = false;
$actions = new couchClient($couchUrl, "actions");
$assignments = new couchClient($couchUrl, "assignments");
$resources = new couchClient($couchUrl, "resources");
$groups = new couchClient($couchUrl, "groups");
$feedbacks = new couchClient($couchUrl, "feedback");
if(isset($_POST['score'])){
	$resultArr = Array();
	$doc = $assignments->getDoc($_POST['assignmentId']);
	if($doc->status =="taken"){
		//foreach($doc->participants as $listTakenAssignment){
			$itemFound = -1;
			$itemFound = array_search($_POST['memberId'],$doc->participants);
			if($itemFound >-1){ 
				if(intval($listTakenAssignment[$itemFound]) < intval($_POST['score'])){
					$doc->scores[$itemFound] = $_POST['score'];
					$doc->dateTaken[$itemFound] = $_POST['dateTaken']."";
					$doc->totalMarks = $_POST['totalMarks'];
				}
			}else{
				array_push($doc->scores,$_POST['score']);
				array_push($doc->participants,$_POST['memberId']);
				array_push($doc->dateTaken,$_POST['dateTaken']."");	
			}
		//}
		$resultArr[0]['result'] = "another";
	}else{
		$doc->status = "taken";
		$doc->scores = array();
		$doc->participants = array();
		$doc->dateTaken = array();
		
		array_push($doc->scores,$_POST['score']);
		array_push($doc->participants,$_POST['memberId']);
		array_push($doc->dateTaken,$_POST['dateTaken']."");
		$doc->totalMarks = $_POST['totalMarks'];
		$resultArr[0]['result'] = "true"; 
	}
	$assignments->storeDoc($doc);
	echo json_encode($resultArr);
	
}else if(isset($_POST['resourceId'])){
		$todayDate = date("Y-m-d H:i:s"); 
	 	$actions = new couchClient($couchUrl, "actions");
	  	$doc = new stdClass();
	 	$doc->kind ="Action";
	 	$doc->memberId = $_POST['memberId'];
	  	$doc->memberRoles = array('student');
	  	$doc->facilityId = $facilityId;
	  	$doc->action = "used resource on tablet";
	  	$doc->objectId= $_POST['resourceId'];
	  	$doc->timestamp= $_POST['dateTime'];
	  	$doc->context= "tablet";
	  //print_r($doc);
	  	$response = $actions->storeDoc($doc);
		$resultArr[0]['result'] = "true"; 
		/////$assignments->storeDoc($doc);
		echo json_encode($resultArr);
}

//submitStatusUnjumble

//
?>