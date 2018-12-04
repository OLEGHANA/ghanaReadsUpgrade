<?php
include "../secure/talk2db.php";
if(isset($_GET['lang'])){
	$language = $_GET['lang'];
	$numberOfChars = intval($_GET['charsLength']);
	$word = $_GET['word'];
	///$qn_number = $_GET['qn_num'];
	global $couchUrl;
	global $facilityId;
	$doc = new stdClass();
	$numberOfUsedRes=0;
	$gobackArr = array();
	$word_bank = new couchClient($couchUrl, "word_bank");
	$key = array($language,$numberOfChars,$word);
	$viewResults = $word_bank->include_docs(TRUE)->key($key)->getView('api', 'language_NoChar_WordID');
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
} 
?>