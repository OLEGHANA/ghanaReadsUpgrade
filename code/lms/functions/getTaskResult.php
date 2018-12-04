<?php
include "../secure/talk2db.php";
	global $couchUrl;
	global $facilityId;
	$feedbacks = new couchClient($couchUrl, "feedback");
	$resources = new couchClient($couchUrl, "resources");
	$assignments = new couchClient($couchUrl, "assignments");
	$members = new couchClient($couchUrl, "members");
	$doc = $assignments->getDoc($_GET['assignmentId']);
	echo '<table width="500" border="1">
      <tr>
        <td width="200"  style="font-size: 12px; color: #009;">Student Name</td>
        <td width="40"  style="font-size: 13px; color: #009;"  align="center">Score </td>
        <td width="40"  style="font-size: 12px; color: #009;"  align="center">Out Of </td>
        <td width="60"  style="font-size: 12px; color: #009;"  align="center">Date Taken</td>
      </tr>';
	try{
		$displayCounter= 0;
		$arrayCounter = 0;
		foreach($doc->participants as $row){
			$memberDoc = $members->getDoc($row);
			if($displayCounter%2!=0){
			echo '<tr>
				<td  bgcolor="#FFFFCC">'.$memberDoc->lastName.' '.$memberDoc->middleNames.' '.$memberDoc->firstName.'</td>
				<td align="center" bgcolor="#FFFFCC">'.$doc->scores[$arrayCounter].'</td>
				<td align="center" bgcolor="#FFFFCC">'.$doc->totalMarks.'</td>
				<td  align="center"  bgcolor="#FFFFCC">'.$doc->dateTaken[$arrayCounter].'</td>
			  </tr>';
			}else{
				echo '<tr>
				<td >'.$memberDoc->lastName.' '.$memberDoc->middleNames.' '.$memberDoc->firstName.'</td>
				<td align="center" >'.$doc->scores[$arrayCounter].'</td>
				<td align="center">'.$doc->totalMarks.'</td>
				<td  align="center">'.$doc->dateTaken[$arrayCounter].'</td>
			  </tr>';
			}
			$displayCounter++;
			$arrayCounter++;
		}
	}catch(Exception $err){
					echo ''.$err.'';
	}
	echo '</table>';
?>