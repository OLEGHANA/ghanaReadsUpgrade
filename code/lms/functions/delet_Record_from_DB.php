<title>Admin Updates</title>
<?php
include "../secure/talk2db.php";
global $couchUrl;

//global $levels;
$levels= array("KG1","KG2","P1","P2","P3","P4","P5","P6");
$members = new couchClient($couchUrl, "members");
$groups = new couchClient($couchUrl, "groups");
$actions = new couchClient($couchUrl, "actions");
$lesson_notes = new couchClient($couchUrl, "lesson_notes");
$exercises = new couchClient($couchUrl, "exercises");


$membersID = array("gh295c4f23f5b3e9da36e996824f04c351","gh59b758099002f75564e5f769d704fb7b","gh59b758099002f75564e5f769d7040f4f");


foreach($membersID as $mem){
//// REMOVE FROM EXCERCISES
$rem_mem_exercise_key = array($mem);
$exerciseResults = $exercises->include_docs(TRUE)->key($rem_mem_exercise_key)->getView('api', 'MemberId');

foreach($exerciseResults->rows as $row){
	$exercises->deleteDoc($row->doc);
	$cnt++;
echo $cnt.'<br />';
}
echo "Finnished Deleting all doc with memberId = ".$mem;

}



foreach($membersID as $mem){
//// REMOVE FROM ACTION
$rem_mem_actions_key = array($mem);
$actionsResults = $actions->include_docs(TRUE)->key($rem_mem_actions_key)->getView('api', 'MemberId');

foreach($actionsResults->rows as $row){
	$actions->deleteDoc($row->doc);
	$cnt++;
echo $cnt.'<br />';
}
echo "Finnished Deleting all doc with memberId = ".$mem;
}



?>