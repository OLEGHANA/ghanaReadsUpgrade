<?php
include "../secure/talk2db.php";
if(isset($_GET['wf_language'])){
	$language = $_GET['wf_language'];
	$numberOfChars = intval($_GET['wf_chars']);
	
	//$language = "en";
	//$numberOfChars = 5;
	
	global $couchUrl;
	global $facilityId;
	$numberOfUsedRes=0;
	$word_bank = new couchClient($couchUrl, "word_bank");
	$start_key = array($language,$numberOfChars,"A");
	$end_key = array($language,$numberOfChars,"Z");
	$viewResults = $word_bank->include_docs(TRUE)->startkey($start_key)->endkey($end_key)->getView('api', 'listWords_language');
	///$numberOfUsedRes=count($lessonDoc->resources);
	///print_r($viewResults);
	$mainNumbers =1;
	echo '<table width="95%"  align="center">';
	for($dispCnt =1;$dispCnt<=2;$dispCnt++){
		$key=-1;
		$found = false;
		echo '
                <td><b>'.($mainNumbers++).'. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
				<select name="word[]" id="word'.($dispCnt).'">
				<option value="---" >---</option>';
				for($rcnt=0;$rcnt<sizeof($viewResults->rows);$rcnt++){
					$doc = $viewResults->rows[$rcnt]->doc;
						echo '<option value="'.$doc->_id.'">'.$doc->_id.'</option>';
				}
				echo '</td>
                <td><b>'.($mainNumbers++).'. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
				<select name="word[]" id="word'.($dispCnt).'">
				<option value="---" >---</option>';
				for($rcnt=0;$rcnt<sizeof($viewResults->rows);$rcnt++){
					$doc = $viewResults->rows[$rcnt]->doc;
						echo '<option value="'.$doc->_id.'">'.$doc->_id.'</option>';
				}
				echo '</td>
		 <tr>
         ';
		$numberOfUsedRes--;
	}
	echo ' </table>';
	
} 
?>