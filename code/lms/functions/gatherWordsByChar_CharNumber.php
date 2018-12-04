<?php
include "../secure/talk2db.php";
if(isset($_GET['lang'])){
	
	$backData = new stdClass();
	$backData->words = array();
	$language = $_GET['lang'];
	$numberOfChars = $_GET['numbOfChars'];
	$char = $_GET['char'];
	global $couchUrl;
	global $facilityId;
	$numberOfUsedRes=0;
	$word_bank = new couchClient($couchUrl, "word_bank");
	$key = array($language,(int)$numberOfChars,$char);
	$viewResults = $word_bank->include_docs(TRUE)->key($key)->getView('api', 'language_NoChar_WordStartChar');
	if(sizeof($viewResults->rows) > 0){
		foreach($viewResults->rows as $row){
			array_push($backData->words,$row->id);
		}
		$backData->query = true;
		echo json_encode($backData);
	}else{
		$backData->query = false;
		echo json_encode($backData);
	}
	/*
	if(sizeof($viewResults->rows) > 0){
		//$aryObj = array('true',$word,$qn_number);
		$doc->query = true;
		$doc->word=$word;
		//$doc->qn_number=$qn_number;
		///$obj['goBack'] = $gobackArr;
    	echo json_encode($doc);
	}else{
		$doc->query = false;
		$doc->word=$word;
		//$doc->qn_number=$qn_number;
		///$obj['goBack'] = $gobackArr;
    	echo json_encode($doc);
		//print('false');
	}
	*/
} 
?>