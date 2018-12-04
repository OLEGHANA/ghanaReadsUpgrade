<?php
include "../secure/talk2db.php";
if(isset($_GET['lang2'])){
	$language = $_GET['lang2'];
	$level = $_GET['level2'];
	$dispCnt = $_GET['number'];
	global $couchUrl;
	global $facilityId;
	$resources = new couchClient($couchUrl, "resources");
	$viewResults = $resources->include_docs(TRUE)->getView('api', 'allResources');
	//echo '<table width="95%">';
	//for($dispCnt =1;$dispCnt<=4;$dispCnt++){
			echo ''.($dispCnt).'. &nbsp;<select name="story[]" id="story'.($dispCnt).'">
				<option value="none" selected >none</option>';
				for($rcnt=0;$rcnt<sizeof($viewResults->rows);$rcnt++){
					$doc = $viewResults->rows[$rcnt]->doc;
					if($doc->language==$language && in_array($level,$doc->levels)){
							echo '<option value="'.$doc->_id.'">'.$doc->title.'</option>';
					}
				}
		echo '</select>';
		//</td>
       // </tr>';
	//}
	//echo ' </table>';
} else if(isset($_GET['lang5'])){
	$language = $_GET['lang5'];
	$level = $_GET['level5'];
	global $couchUrl;
	global $facilityId;
	$resources = new couchClient($couchUrl, "resources");
	$viewResults = $resources->include_docs(TRUE)->getView('api', 'allResources');
	echo '<table width="95%">';
	for($dispCnt =1;$dispCnt<=4;$dispCnt++){
		echo ' <tr>
          		<td colspan="4" align="left"><b>'.($dispCnt).'. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
				<select name="story[]" id="story'.($dispCnt).'">
				<option value="none" selected >none</option>';
				for($rcnt=0;$rcnt<sizeof($viewResults->rows);$rcnt++){
					$doc = $viewResults->rows[$rcnt]->doc;
					if($doc->language==$language && in_array($level,$doc->levels)){
						if($doc->legacy->type=='mp3'||$doc->legacy->type=='wav'){
							echo '<option value="'.$doc->_id.'">'.$doc->title.'</option>';
						}
					}
				}
		echo '</select>
		</td>
        </tr>';
	}
	echo ' </table>';
	
} else if(isset($_GET['grade'])){
	global $couchUrl;
	global $facilityId;
	$groups = new couchClient($couchUrl, "groups");
	$doc = $groups->getDoc($_GET['grade']);
	///print_r($doc->level);
	$gobackArr = array();
	$gobackArr[] = array(
	'level'=>$doc->level
	);
	$obj['gobackArr'] = $gobackArr;
    echo json_encode($obj);
	
} else if(isset($_GET['lang'])){
	$language = $_GET['lang'];
	$level = $_GET['level'];
	$dispCnt = $_GET['number'];
	global $couchUrl;
	global $facilityId;
	$resources = new couchClient($couchUrl, "resources");
	$viewResults = $resources->include_docs(TRUE)->getView('api', 'allResources');
		echo ''.($dispCnt).'. &nbsp;<select name="story[]" id="story'.($dispCnt).'">
				<option value="none" selected >none</option>';
				for($rcnt=0;$rcnt<sizeof($viewResults->rows);$rcnt++){
					$doc = $viewResults->rows[$rcnt]->doc;
					if($doc->language==$language && in_array($level,$doc->levels)){
						if($doc->legacy->type!='mp4'&& $doc->legacy->type!='avi' && $doc->legacy->type!='flv'){
								if($doc->legacy->type!='ppt' && $doc->legacy->type!='pptx' && $doc->legacy->type!='doc' && $doc->legacy->type!='docx'){
									if($doc->legacy->type!='mp3' && $doc->legacy->type!='wav' && $doc->legacy->type!='midi'){
										echo '<option value="'.$doc->_id.'">'.$doc->title.'</option>';
									}
								}
							}
					}
				}
		echo '</select>';
}
?>