<?php
include "../secure/talk2db.php";
if(isset($_GET['from'])){
global $couchUrl;
global $facilityId;
global $config;
$groups = new couchClient($couchUrl, "groups");
$assignments = new couchClient($couchUrl, "assignments");
$resources = new couchClient($couchUrl, "resources");
$viewResults = $groups->include_docs(TRUE)->key($facilityId)->getView('api', 'allGroupsInFacility');
echo '<table width="550" border="1" align="center">
        <tr>
          <td style="font-weight: bold">No</td>
          <td style="font-weight: bold">Resource Title</td>
		  <td style="font-weight: bold">Date Assigned</td>
          <td style="font-weight: bold">Level/Group</td>
		  <td style="font-weight: bold">Action</td>
        </tr>';
$cnt=1;
$sign="";
foreach($viewResults->rows as $row){
	$key = array($facilityId,$row->doc->_id);
	$assignment_viewResults = $assignments->include_docs(TRUE)->key($key)->getView('api', 'facilityGroupIdAll');
	foreach($assignment_viewResults->rows as $assignment_row){
		if($assignment_row->doc->context->use =="stories for the week"){
				$sign="+ ";
			}else{
				$sign="";
			}
		if(strtotime($_GET['from']) <= ($assignment_row->doc->endDate) && ($assignment_row->doc->endDate)<=strtotime($_GET['to'])){
			if($assignment_row->doc->context->use=="word formation"){
				echo '
				<tr>
				  <td>'.$cnt.'</td>
				  <td> Form words from a given word : <br />
					<span style="font-weight: bold">'.implode(' , ',$assignment_row->doc->words).'</span></td>
				 <td >'.date('Y-m-d',$assignment_row->doc->startDate).' - '.date('Y-m-d',$assignment_row->doc->endDate).'</td>
				  <td  align="center">'.$row->doc->name.'</td>
				  <td><a href="ready2Sync.php?assigmentId='.$assignment_row->doc->_id.'">Remove</a></td>
				</tr>';
				$cnt++;
			}else if($assignment_row->doc->context->use=="fill blanks"){
				echo '
				<tr>
				  <td>'.$cnt.'</td>
				  <td> Fill in the blank Spaces : <br />
					<span style="font-weight: bold">'.implode(' , ',$assignment_row->doc->words).'</span></td>
				 <td >'.date('Y-m-d',$assignment_row->doc->startDate).' - '.date('Y-m-d',$assignment_row->doc->endDate).'</td>
				  <td  align="center">'.$row->doc->name.'</td>
				  <td><a href="ready2Sync.php?assigmentId='.$assignment_row->doc->_id.'">Remove</a></td>
				</tr>';
				$cnt++;
			}else if($assignment_row->doc->context->use=="listen and find" || $assignment_row->doc->context->use=="listern and find"){
				echo '
				<tr>
				  <td>'.$cnt.'</td>
				  <td> Listen and find spaces : <br />
					<span style="font-weight: bold">'.$assignment_row->doc->imageSetting.'</span></td>
				 <td >'.date('Y-m-d',$assignment_row->doc->startDate).' - '.date('Y-m-d',$assignment_row->doc->endDate).'</td>
				  <td  align="center">'.$row->doc->name.'</td>
				  <td><a href="ready2Sync.php?assigmentId='.$assignment_row->doc->_id.'">Remove</a></td>
				</tr>';
				$cnt++;
			}else if($assignment_row->doc->context->use=="word power"){
				echo '
				<tr>
				  <td>'.$cnt.'</td>
				  <td> Word Power : <br />
					<span style="font-weight: bold">'.$assignment_row->doc->wordCode.'</span></td>
				 <td >'.date('Y-m-d',$assignment_row->doc->startDate).' - '.date('Y-m-d',$assignment_row->doc->endDate).'</td>
				  <td  align="center">'.$row->doc->name.'</td>
				  <td><a href="ready2Sync.php?assigmentId='.$assignment_row->doc->_id.'">Remove</a></td>
				</tr>';
				$cnt++;
			}else if($assignment_row->doc->context->use=="unjumble words"){
				echo '
				<tr>
				  <td>'.$cnt.'</td>
				  <td> Unjumble Words : <br />
					<span style="font-weight: bold">'.$assignment_row->doc->jumbledWord.'</span></td>
				 <td >'.date('Y-m-d',$assignment_row->doc->startDate).' - '.date('Y-m-d',$assignment_row->doc->endDate).'</td>
				  <td  align="center">'.$row->doc->name.'</td>
				  <td><a href="ready2Sync.php?assigmentId='.$assignment_row->doc->_id.'">Remove</a></td>
				</tr>';
				$cnt++;
			}else{
				$resource_viewResults = $resources->getDoc($assignment_row->doc->resourceId);
				echo '
				<tr>
				  <td>'.$cnt.'</td>
				  <td>'.$sign.$resource_viewResults->title.'</td>
				 <td >'.date('Y-m-d',$assignment_row->doc->startDate).' - '.date('Y-m-d',$assignment_row->doc->endDate).'</td>
				  <td  align="center">'.$row->doc->name.'</td>
				  <td><a href="ready2Sync.php?assigmentId='.$assignment_row->doc->_id.'">Remove</a></td>
				</tr>';
				$cnt++;
			}
		}
	}
}
echo '</table>';
}
?>