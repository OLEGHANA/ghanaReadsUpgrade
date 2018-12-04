<?php
//include "../secure/talk2db.php";
 
if(isset($_POST['dateFrom'])){
	
global $studentIDs;
global $MasterstudentIDs;
global $resourceArray;
global $videoBookResArray;
global $couchUrl;
$resourceArray = array();
$MasterstudentIDs =array();
$videoBookResArray= array();
$file = "../transferData/schoolBell.db";
if (!unlink($file))
{
  echo ("Error deleting $file");
}
else
  {
  echo ("Cleared Old Sync Data");
  }
  // remove current sqllite database file -> schoolBell.db
echo "<br /> *************************************************************** <br />
Preparing System for Sync Process<br />";
$files = glob('../transferData/*'); // get all file names
foreach($files as $file){ 
  if(is_file($file))
    unlink($file); // delete file
}
// Delet all files from the resources folder
$files = glob('../resources/*'); // get all file names
foreach($files as $file){ 
  if(is_file($file))
    unlink($file); // delete file
}
 /**************************************/

  // Set default timezone
  date_default_timezone_set('UTC');
  
  try {
    /**************************************
    * Create databases and                *
    * open connections                    *
    **************************************/
  	// Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:../transferData/schoolBell.db');
	// Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, 
                            PDO::ERRMODE_EXCEPTION);
 
    // Create new database in memory
    $memory_db = new PDO('sqlite::memory:');
    // Set errormode to exceptions
    $memory_db->setAttribute(PDO::ATTR_ERRMODE, 
                              PDO::ERRMODE_EXCEPTION);

    /**************************************
    * Create tables                       *
    **************************************/
 	// Create table members
	///implode(', ',role)
    $file_db->exec("CREATE TABLE IF NOT EXISTS members (
                    id TEXT PRIMARY KEY, 
					facilityId TEXT, 
					groupId TEXT, 
					roles TEXT,
                    memberName TEXT, 
                    pass TEXT)");
	
	// Create table resources i.e Audio,Readable or video book as category
    $file_db->exec("CREATE TABLE IF NOT EXISTS resources (
					facilityId TEXT, 
					memberId TEXT, 
					assignmentId TEXT, 
					category TEXT, 
					res_id TEXT,
					res_file_name TEXT, 
					res_file_ext TEXT, 
					res_title TEXT,
					res_description TEXT)");
					
	/*				
    $file_db->exec("CREATE TABLE IF NOT EXISTS resources (
                    id TEXT,
					facilityId TEXT, 
					memberId TEXT, 
					assignmentId TEXT, 
					category TEXT, 
					type TEXT, 
					title TEXT,
                    description TEXT)");
					*/
					
					
	// Create table video_book question
    $file_db->exec("CREATE TABLE IF NOT EXISTS vb_questions (
                    facilityId TEXT,
					memberId TEXT, 
					assignmentId TEXT,
					question TEXT,
					ans TEXT, 
					pos1 TEXT, 
					pos2 TEXT,
                    pos3 TEXT, 
                    pos4 TEXT,
					attempted TEXT)");
					
	// Create table groups question
    $file_db->exec("CREATE TABLE IF NOT EXISTS groups (
					groupId TEXT PRIMARY KEY, 
					groupName TEXT,
                    facilityId TEXT)");
	
	 $file_db->exec("CREATE TABLE IF NOT EXISTS wordsFormation (
                    facilityId TEXT,
					memberId TEXT, 
					assignmentId TEXT,
					word TEXT,
					duration TEXT)");
					
	 $file_db->exec("CREATE TABLE IF NOT EXISTS fillTheBlanks (
                    facilityId TEXT,
					memberId TEXT, 
					assignmentId TEXT,
					word TEXT,
					duration TEXT, 
					blanks TEXT)");
					
	 $file_db->exec("CREATE TABLE IF NOT EXISTS listenAndFind (
                    facilityId TEXT,
					memberId TEXT, 
					assignmentId TEXT,
					imageSetting TEXT,
					duration TEXT)");
					
	$file_db->exec("CREATE TABLE IF NOT EXISTS unjumble_words (
                    facilityId TEXT,
					memberId TEXT, 
					assignmentId TEXT,
					jumbledWord TEXT,
					duration TEXT)");
					
	$file_db->exec("CREATE TABLE IF NOT EXISTS wordPower (
                    facilityId TEXT,
					memberId TEXT, 
					assignmentId TEXT,
					wordCode TEXT,
					duration TEXT)");
	/*
	db.execute('CREATE TABLE IF NOT EXISTS members (id TEXT, facilityId TEXT, groupId TEXT,roles TEXT,memberName TEXT, pass TEXT)');
		db.execute('CREATE TABLE IF NOT EXISTS resources (facilityId TEXT, memberId TEXT, assignmentId TEXT, category TEXT, res_id TEXT,res_file_name TEXT, res_file_ext TEXT, res_title TEXT,res_description TEXT)');
		db.execute('CREATE TABLE IF NOT EXISTS vb_questions (facilityId TEXT,memberId TEXT, assignmentId TEXT, question TEXT,ans TEXT, pos1 TEXT, pos2 TEXT,pos3 TEXT, pos4 TEXT)');
		db.execute('CREATE TABLE IF NOT EXISTS form_words (facilityId TEXT,memberId TEXT, assignmentId TEXT, ass_word TEXT, ass_score TEXT)');
		db.execute('CREATE TABLE IF NOT EXISTS groups (groupId TEXT PRIMARY KEY,groupName TEXT,facilityId TEXT)');
		Ti.API.info("Completed creating Databases");
		*/
	///compileClass('P1',$file_db);
 	
	$groups = new couchClient($couchUrl, "groups");
	$viewResults = $groups->include_docs(TRUE)->key($facilityId)->getView('api', 'allGroupsInFacility');
	$wCnt=0;
	while($wCnt<sizeof($viewResults->rows)){
		$gpName = $viewResults->rows[$wCnt]->doc->name;
		$gpID = $viewResults->rows[$wCnt]->doc->_id;
		$MasterstudentIDs = array_merge($MasterstudentIDs,compileClass($gpName,$file_db,$gpID));
		$wCnt++;
		////echo $wCnt.'<br />';
		if( trim($gpName) !="JHS"){
			$insert_stmt = $file_db->prepare("Insert into groups (groupId, groupName, facilityId) 
							values (:groupId, :groupName, :facilityId)");
			$insert_stmt->bindValue(':groupId',$gpID);
			$insert_stmt->bindValue(':groupName', $gpName);
			$insert_stmt->bindValue(':facilityId',$facilityId );
			$insert_stmt->execute();
		}
	}
			
 	echo "Gathering audio and readable resource data assigned to students <br />";
	foreach($MasterstudentIDs as $stuID){
		try{
			compileResources($stuID,$file_db);
		}catch(Exception $Err){
			
		}
	}
	
	echo "Gathering video book resource data assigned to students <br />";
	foreach($MasterstudentIDs as $stuID){
		compile_VideoBook_Resources($stuID,$file_db);
	}
	
	
	echo "Gathering video book Questions <br />";
	foreach($MasterstudentIDs as $stuID){
		compile_VideoBook_Questions($stuID,$file_db);
	}
 	
	echo "Gathering word formation data <br />";
	foreach($MasterstudentIDs as $stuID){
		
		compile_WordFormation($stuID,$file_db);
	}
	
	echo "Gathering fill blank space data <br />";
	foreach($MasterstudentIDs as $stuID){
		
		compile_FillBlanks($stuID,$file_db);
	}
	
	
	echo "Gathering listen and Find data <br />";
	foreach($MasterstudentIDs as $stuID){
		compile_listenAndFind($stuID,$file_db);
	}
	
	echo "Gathering Unjumble Word data <br />";
	foreach($MasterstudentIDs as $stuID){
		compile_UnjumbleWords($stuID,$file_db);
	}
	
	
	echo "Gathering Word Power data <br />";
	foreach($MasterstudentIDs as $stuID){
		compile_WordPower($stuID,$file_db);
	}
	
	
 	////////////////pullResourceFromCouch($resourceArray);
	
	
	
 
 	/*$id = 'tryrt';
	$facilityId = '224324234';
	$roles ='student';
	$name = 'Leonard Maximus';
	$pass ='password';
	$groupId = 'P1';
	$insert_stmt = $file_db->prepare("insert into members (id,facilityId,group,roles,name,pass) 
	values (:id, :facilityId, :group, :roles, :name, :pass)");
	$insert_stmt->bindValue(':id', $id);
	$insert_stmt->bindValue(':facilityId', $facilityId);
	$insert_stmt->bindValue(':groupId', $groupId);
	$insert_stmt->bindValue(':roles', $roles);
	$insert_stmt->bindValue(':name', $name);
	$insert_stmt->bindValue(':pass', $pass);
	$insert_stmt->execute();*/
	
	/*
	$result = $file_db->query("SELECT * FROM members ORDER BY memberName");
	$ccn =1;
    foreach($result as $row) {
		  echo $ccn.'<br />';
		  echo "Id: " . $row['id'] . "<br />";
		  echo "Name: " . $row['memberName'] . "<br />";
		  echo "Pass: " . $row['pass'] . "<br />";
		  echo "Level: " . $row['groupId'] . "<br />";
		  echo "Roles: " . $row['roles'] . "<br />";
		  echo "<br />";
		  $ccn++;
    }
	
	$result = $file_db->query("SELECT * FROM resources where category='video lesson'");
	$ccn =1;
    foreach($result as $row) {
		  echo $ccn.'<br />';
		  echo "Id: " . $row['id'] . "<br />";
		  echo "Title: " . $row['title'] . "<br />";
		  echo "Category: " . $row['category'] . "<br />";
		  echo "Description: " . $row['description'] . "<br />";
		  echo "MemberId: " . $row['memberId'] . "<br />";
		  echo "Type: " . $row['type'] . "<br />";
		  echo "<br />";
		  $ccn++;
    }
	
	$result = $file_db->query("SELECT * FROM vb_questions");
	$ccn =1;
    foreach($result as $row) {
		  echo $ccn.'<br />';
		  ////echo "colNum: " . $row['colNum'] . "<br />";
		  echo "MemberID: " . $row['memberId'] . "<br />";
		  echo "AssignmentID: " . $row['assignmentId'] . "<br />";
		  echo "Question: " . $row['question'] . "<br />";
		  echo "Answer: " . $row['ans'] . "<br />";
		  echo "Pos1: " . $row['pos1'] . "<br />";
		  echo "Pos2: " . $row['pos2'] . "<br />";
		  echo "Pos3: " . $row['pos3'] . "<br />";
		  echo "Pos4: " . $row['pos4'] . "<br />";
		  echo "<br />";
		  $ccn++;
    }
	
	$result = $file_db->query("SELECT * FROM groups");
	$ccn =1;
    foreach($result as $row) {
		  echo $ccn.'<br />';
		  ////echo "colNum: " . $row['colNum'] . "<br />";groupId, :groupName, :facilityId
		  echo "GroupID: " . $row['groupId'] . "<br />";
		  echo "GroupName: " . $row['groupName'] . "<br />";
		  echo "FacilityID: " . $row['facilityId'] . "<br />";
		  echo "<br />";
		  $ccn++;
    }*/
    // Close file db connection
    $file_db = null;
    // Close memory db connection
    $memory_db = null;
  }
  catch(Exception $e) {
    // Print PDOException message
    echo $e->getMessage();
  }
 
}

 /*******************************************
  	create functions to handle data
		 inseaction into database
      ****************************/
function compileClass($theClass,$file_db,$theGroupsID){	
	echo "Processing student details for ".$theClass." <br />";
	global $couchUrl;
	global $facilityId;
	global $config;
	$arymemeberIDs= array();
	$members = new couchClient($couchUrl, "members");
	$start_key = array($facilityId,$theClass,"A");
	$end_key = array($facilityId,$theClass,"Z");
	$viewResults = $members->include_docs(TRUE)->startkey($start_key)->endkey($end_key)->getView('api', 'facilityLevelActive_allStudent_sorted');
	$docCounter=1;
	$memberCode ="";
	foreach($viewResults->rows as $row) {
		if(trim($row->doc->pass)==""){
			$memberCode = "000";
		} else {
			$memberCode = $row->doc->pass;
		}
		$memberRole = implode(',',$row->doc->roles);
		$memberName = $row->doc->lastName.' '.$row->doc->middleNames.' '.$row->doc->firstName;
		$memberPass = $memberCode ;
		$insert_stmt = $file_db->prepare("insert into members (id,facilityId,groupId,roles,memberName,pass) 
		values (:id, :facilityId, :groupId, :roles, :memberName, :pass)");
		$insert_stmt->bindValue(':id', $row->doc->_id);
		$insert_stmt->bindValue(':facilityId', $facilityId);
		$insert_stmt->bindValue(':groupId', $theGroupsID);
		$insert_stmt->bindValue(':roles', $memberRole);
		$insert_stmt->bindValue(':memberName', ucwords(trim($memberName)));
		$insert_stmt->bindValue(':pass', $memberPass);
		$insert_stmt->execute();
		array_push($arymemeberIDs,$row->doc->_id);
	}
	echo "Successfully processed data for  ".$theClass." <br />";
	return $arymemeberIDs;
}

///// begin function for readable resources////
function compileResources($memberID,$file_db)
{	
	global $couchUrl;
	global $facilityId;
	global $config;
	$groups = new couchClient($couchUrl, "groups");
	$assignments = new couchClient($couchUrl, "assignments");
	$resources = new couchClient($couchUrl, "resources");
	$start_key = array($facilityId,$memberID);
	$viewResults = $groups->key($start_key)->getView('api', 'facilityWithMemberID');
	foreach($viewResults->rows as $row){///
		if(count($row) > 0){///
			$sign="";
			$start_key = array($facilityId,$row->id);
			
			//// Audio Resources
			$assaign_viewResults = $assignments->include_docs(TRUE)->key($start_key)->getView('api', 'facilityGroupID');
			foreach($assaign_viewResults->rows as $assignRow){////
				if($assignRow->doc->context->use=="stories for the week"){
					$sign="+ ";
				}else{
					$sign="";
				}
			if($assignRow->doc->context->use !="word formation" 
			&& $assignRow->doc->context->use !="fill blanks" 
			&& $assignRow->doc->context->use !="decodeable reading video strips"
			&& $assignRow->doc->context->use !="listen and find"
			&& $assignRow->doc->context->use !="listern and find"
			&& $assignRow->doc->context->use !="unjumble words"
			&& $assignRow->doc->context->use !="word power"){
					
					if(strtotime($_POST['dateFrom']) <= ($assignRow->doc->endDate) 
						&& ($assignRow->doc->endDate)<=strtotime($_POST['dateTo'])){/////
							$resDoc = $resources->getDoc($assignRow->value);
							if($resDoc->type!="video lesson"){
								$insert_stmt = $file_db->prepare("insert into resources 
								(facilityId, memberId, assignmentId, category, res_id,res_file_name, res_file_ext, res_title,res_description) 
							values(:facilityId,:memberId,:assignmentId, :category, :res_id, :res_file_name,:res_file_ext, :res_title,:res_description)");
								/*$insert_stmt = $file_db->prepare("insert into resources 
								(id, facilityId, memberId, assignmentId, category, type, title, description) 
								values (:id, :facilityId, :memberId, :assignmentId, :category, :type, :title, :description )");*/
								$insert_stmt->bindValue(':facilityId', $facilityId);
								$insert_stmt->bindValue(':memberId',$memberID );
								$insert_stmt->bindValue(':assignmentId',$assignRow->doc->_id);
								$insert_stmt->bindValue(':category', $assignRow->doc->context->use);
								//$insert_stmt->bindValue(':category', $resDoc->type);
								$attachementFileString = key($resDoc->_attachments);
								$attachementFileName = explode('.',$attachementFileString);
								$insert_stmt->bindValue(':res_id',$resDoc->_id);
								$insert_stmt->bindValue(':res_file_name',$attachementFileName[0]);
								$insert_stmt->bindValue(':res_file_ext', $attachementFileName[1]);
								$insert_stmt->bindValue(':res_title', $sign.$resDoc->title);
								$insert_stmt->bindValue(':res_description', $resDoc->description);
								$insert_stmt->execute();
								//Save Resources to Array for downloading
								global $resourceArray;
								$foundDuplicateInArray = false;
								for($cnt=0;$cnt<sizeof($resourceArray);$cnt++){
									if($resourceArray[$cnt]==$resDoc->_id){
										$foundDuplicateInArray =true;
									}
								}
								if(!$foundDuplicateInArray){
									array_push($resourceArray,$resDoc->_id);
								}
						}
					}/////
				}
			}////
		//////////////
		}///
		
	}//
  }
  
  
  function compile_VideoBook_Resources($memberID,$file_db){	
	global $couchUrl;
	global $facilityId;
	global $config;
	$groups = new couchClient($couchUrl, "groups");
	$assignments = new couchClient($couchUrl, "assignments");
	$resources = new couchClient($couchUrl, "resources");
	$start_key = array($facilityId,$memberID);
	$viewResults = $groups->key($start_key)->getView('api', 'facilityWithMemberID');
	foreach($viewResults->rows as $row){///
		if(count($row) > 0){///
			$sign="";
			$start_key = array($facilityId,$row->id);
			//// Video Resources
			if($assignRow->doc->context->use !="word formation" 
			&& $assignRow->doc->context->use !="fill blanks" 
			///&& $assignRow->doc->context->use !="decodeable reading video strips"
			&& $assignRow->doc->context->use !="listen and find"
			&& $assignRow->doc->context->use !="listern and find"
			&& $assignRow->doc->context->use !="unjumble words"
			&& $assignRow->doc->context->use !="word power"){
			$assaign_viewResults = $assignments->include_docs(TRUE)->key($start_key)->getView('api', 'facilityGroupIdVideoBook');
			foreach($assaign_viewResults->rows as $assignRow){////
				if(strtotime($_POST['dateFrom']) <= ($assignRow->doc->endDate) 
					&& ($assignRow->doc->endDate)<=strtotime($_POST['dateTo'])){/////
						$resDoc = $resources->getDoc($assignRow->value);
						if($resDoc->type=="video lesson" || $resDoc->type=="decodeable reading video strips"){
							$insert_stmt = $file_db->prepare("insert into resources 
							(facilityId, memberId, assignmentId, category, res_id,res_file_name, res_file_ext, res_title,res_description) 
							values (:facilityId,:memberId,:assignmentId, :category, :res_id, :res_file_name, :res_file_ext, :res_title,:res_description)");
							/*$insert_stmt = $file_db->prepare("insert into resources 
							(id, facilityId, memberId, assignmentId, category, type, title, description) 
							values (:id, :facilityId, :memberId, :assignmentId, :category, :type, :title, :description )");*/
							$insert_stmt->bindValue(':facilityId', $facilityId);
							$insert_stmt->bindValue(':memberId',$memberID );
							$insert_stmt->bindValue(':assignmentId',$assignRow->doc->_id);
							$insert_stmt->bindValue(':category', $resDoc->type);
							$insert_stmt->bindValue(':res_id',$resDoc->_id);
							$insert_stmt->bindValue(':res_file_name',$resDoc->legacy->id);
							$insert_stmt->bindValue(':res_file_ext', $resDoc->legacy->type);
							$insert_stmt->bindValue(':res_title', $sign.$resDoc->title);
							$insert_stmt->bindValue(':res_description', $resDoc->description);
							$insert_stmt->execute();
							//Save Resources to Array for downloading
							global $resourceArray;
							$foundDuplicateInArray = false;
							for($cnt=0;$cnt<sizeof($resourceArray);$cnt++){
								if($resourceArray[$cnt]==$resDoc->_id){
									$foundDuplicateInArray =true;
								}
							}
							if(!$foundDuplicateInArray){
								array_push($resourceArray,$resDoc->_id);
							}
					}
				}/////
				
			}////
			}
		//////////////
		}///
		
	}//
  }
  
  /////////////////////////////////////////////////////////////
  /////////////////////////////////////////////////////////////
  
  function compile_WordFormation($memberID,$file_db){	
	global $couchUrl;
	global $facilityId;
	global $config;
	
	$groups = new couchClient($couchUrl, "groups");
	$assignments = new couchClient($couchUrl, "assignments");
	///$resources = new couchClient($couchUrl, "resources");
	$start_key = array($facilityId,$memberID);
	$viewResults = $groups->key($start_key)->getView('api', 'facilityWithMemberID');
	foreach($viewResults->rows as $row){///
		if(count($row) > 0){///
			$sign="";
			$start_key = array($facilityId,$row->id);
			///
			$assaign_viewResults = $assignments->include_docs(TRUE)->key($start_key)->getView('api', 'facilityGroupIdWordFormAssignment');
			foreach($assaign_viewResults->rows as $assignRow){////
				if(strtotime($_POST['dateFrom']) <= ($assignRow->doc->endDate) 
					&& ($assignRow->doc->endDate)<=strtotime($_POST['dateTo'])){
						if($assignRow->doc->context->use =="word formation" ){
							$wordList ="";
							$insert_stmt = $file_db->prepare("insert into wordsFormation (facilityId,memberId,assignmentId ,word ,duration) 
							values (:facilityId, :memberId, :assignmentId , :word , :duration)");
							$insert_stmt->bindValue(':facilityId', $facilityId);
							$insert_stmt->bindValue(':memberId',$memberID );
							$insert_stmt->bindValue(':assignmentId',$assignRow->doc->_id);
							$insert_stmt->bindValue(':word',implode(',',$assignRow->doc->words));
							$insert_stmt->bindValue(':duration',implode(",",$assignRow->doc->duration));
							$insert_stmt->execute();
						
					}
				}/////
				
			}////
		//////////////
		}///
		
	}//
  }
  
  /////////////////////////////////////////////////////////////
  
  function compile_FillBlanks($memberID,$file_db){	
	global $couchUrl;
	global $facilityId;
	global $config;
	
	$groups = new couchClient($couchUrl, "groups");
	$assignments = new couchClient($couchUrl, "assignments");
	///$resources = new couchClient($couchUrl, "resources");
	$start_key = array($facilityId,$memberID);
	$viewResults = $groups->key($start_key)->getView('api', 'facilityWithMemberID');
	foreach($viewResults->rows as $row){///
		if(count($row) > 0){///
			$sign="";
			$start_key = array($facilityId,$row->id);
			///
			$assaign_viewResults = $assignments->include_docs(TRUE)->key($start_key)->getView('api', 'facilityGroupIdFillBlankAssignment');
			foreach($assaign_viewResults->rows as $assignFillRow){////
				if(strtotime($_POST['dateFrom']) <= ($assignFillRow->doc->endDate) 
					&& ($assignFillRow->doc->endDate)<=strtotime($_POST['dateTo'])){
						if($assignFillRow->doc->context->use =="fill blanks" ){
							$wordList ="";
							$insert_stmt = $file_db->prepare("insert into fillTheBlanks (facilityId,memberId,assignmentId ,word ,duration,blanks) 
							values (:facilityId, :memberId, :assignmentId , :word , :duration,:blanks)");
							$insert_stmt->bindValue(':facilityId', $facilityId);
							$insert_stmt->bindValue(':memberId',$memberID );
							$insert_stmt->bindValue(':assignmentId',$assignFillRow->doc->_id);
							$insert_stmt->bindValue(':word',implode(',',$assignFillRow->doc->words));
							$insert_stmt->bindValue(':duration',implode(",",$assignFillRow->doc->duration));
							$insert_stmt->bindValue(':blanks',implode(":",$assignFillRow->doc->blanks));
							$insert_stmt->execute();
						
					}
				}/////
				
			}////
		//////////////
		}///
		
	}//
  }
  
  function compile_listenAndFind($memberID,$file_db){	
	global $couchUrl;
	global $facilityId;
	global $config;
	
	$groups = new couchClient($couchUrl, "groups");
	$assignments = new couchClient($couchUrl, "assignments");
	///$resources = new couchClient($couchUrl, "resources");
	$start_key = array($facilityId,$memberID);
	$viewResults = $groups->key($start_key)->getView('api', 'facilityWithMemberID');
	foreach($viewResults->rows as $row){///
		if(count($row) > 0){///
			$sign="";
			$start_key = array($facilityId,$row->id);
			///
			$assaign_viewResults = $assignments->include_docs(TRUE)->key($start_key)->getView('api', 'facilityGroupIdlistenAndFind');
			foreach($assaign_viewResults->rows as $assignRow){////
				if(strtotime($_POST['dateFrom']) <= ($assignRow->doc->endDate) 
					&& ($assignRow->doc->endDate)<=strtotime($_POST['dateTo'])){
						if($assignRow->doc->context->use =="listen and find" || $assignRow->doc->context->use =="listern and find"){
							$wordList ="";
							$insert_stmt = $file_db->prepare("insert into listenAndFind (facilityId,memberId,assignmentId ,imageSetting ,duration) 
							values (:facilityId, :memberId, :assignmentId , :imageSetting , :duration)");
							$insert_stmt->bindValue(':facilityId', $facilityId);
							$insert_stmt->bindValue(':memberId',$memberID );
							$insert_stmt->bindValue(':assignmentId',$assignRow->doc->_id);
							$insert_stmt->bindValue(':imageSetting',$assignRow->doc->imageSetting);
							$insert_stmt->bindValue(':duration',$assignRow->doc->duration);
							$insert_stmt->execute();
							///echo $assignRow->doc->imageSetting;
						
					}
				}/////
				
			}////
		//////////////
		}///
		
	}//
  }

  function compile_UnjumbleWords($memberID,$file_db){	
	global $couchUrl;
	global $facilityId;
	global $config;
	
	$groups = new couchClient($couchUrl, "groups");
	$assignments = new couchClient($couchUrl, "assignments");
	///$resources = new couchClient($couchUrl, "resources");
	$start_key = array($facilityId,$memberID);
	$viewResults = $groups->key($start_key)->getView('api', 'facilityWithMemberID');
	foreach($viewResults->rows as $row){///
		if(count($row) > 0){///
			$sign="";
			$start_key = array($facilityId,$row->id);
			///
			$assaign_viewResults = $assignments->include_docs(TRUE)->key($start_key)->getView('api', 'facilityGroupIdUnjumbleWordsAssignment');
			foreach($assaign_viewResults->rows as $assignRow){////
				if(strtotime($_POST['dateFrom']) <= ($assignRow->doc->endDate) 
					&& ($assignRow->doc->endDate)<=strtotime($_POST['dateTo'])){
						if($assignRow->doc->context->use =="unjumble words" ){
							$wordList ="";
							$insert_stmt = $file_db->prepare("insert into unjumble_words (facilityId,memberId,assignmentId ,jumbledWord ,duration) 
							values (:facilityId, :memberId, :assignmentId , :jumbledWord , :duration)");
							$insert_stmt->bindValue(':facilityId', $facilityId);
							$insert_stmt->bindValue(':memberId',$memberID );
							$insert_stmt->bindValue(':assignmentId',$assignRow->doc->_id);
							$insert_stmt->bindValue(':jumbledWord',$assignRow->doc->jumbledWord);
							$insert_stmt->bindValue(':duration',$assignRow->doc->duration);
							$insert_stmt->execute();
							///echo $assignRow->doc->imageSetting;
					}
				}/////
				
			}////
		//////////////
		}///
		
	}//
  }
  
  function compile_WordPower($memberID,$file_db){	
	global $couchUrl;
	global $facilityId;
	global $config;
	
	$groups = new couchClient($couchUrl, "groups");
	$assignments = new couchClient($couchUrl, "assignments");
	///$resources = new couchClient($couchUrl, "resources");
	$start_key = array($facilityId,$memberID);
	$viewResults = $groups->key($start_key)->getView('api', 'facilityWithMemberID');
	foreach($viewResults->rows as $row){///
		if(count($row) > 0){///
			$sign="";
			$start_key = array($facilityId,$row->id);
			///
			$assaign_viewResults = $assignments->include_docs(TRUE)->key($start_key)->getView('api', 'facilityGroupIdWordPowerAssignment');
			foreach($assaign_viewResults->rows as $assignRow){////
				if(strtotime($_POST['dateFrom']) <= ($assignRow->doc->endDate) 
					&& ($assignRow->doc->endDate)<=strtotime($_POST['dateTo'])){
						if($assignRow->doc->context->use =="word power" ){
							$wordList ="";
							$insert_stmt = $file_db->prepare("insert into wordPower (facilityId,memberId,assignmentId ,wordCode ,duration) 
							values (:facilityId, :memberId, :assignmentId , :wordCode , :duration)");
							$insert_stmt->bindValue(':facilityId', $facilityId);
							$insert_stmt->bindValue(':memberId',$memberID );
							$insert_stmt->bindValue(':assignmentId',$assignRow->doc->_id);
							$insert_stmt->bindValue(':wordCode',$assignRow->doc->wordCode);
							$insert_stmt->bindValue(':duration',$assignRow->doc->duration);
							$insert_stmt->execute();
							///echo $assignRow->doc->imageSetting;
					}
				}/////
				
			}////
		//////////////
		}///
		
	}//
  }
  
   function compile_VideoBook_Questions($memberID,$file_db){	
	global $couchUrl;
	global $facilityId;
	global $config;
	$availableCnt =0;
	$groups = new couchClient($couchUrl, "groups");
	$assignments = new couchClient($couchUrl, "assignments");
	$resources = new couchClient($couchUrl, "resources");
	$start_key = array($facilityId,$memberID);
	$viewResults = $groups->key($start_key)->getView('api', 'facilityWithMemberID');
	foreach($viewResults->rows as $row){///
		if(count($row) > 0){///
			$sign="";
			$start_key = array($facilityId,$row->id);
			//// Audio Resources
			$assaign_viewResults = $assignments->include_docs(TRUE)->key($start_key)->getView('api', 'facilityGroupIdVideoBook');
			foreach($assaign_viewResults->rows as $assignRow){////
				if(strtotime($_POST['dateFrom']) <= ($assignRow->doc->endDate) 
					&& ($assignRow->doc->endDate)<=strtotime($_POST['dateTo'])){/////
						$resDoc = $resources->getDoc($assignRow->value);
						if($resDoc->type=="video lesson"){
							try{
								if(isset($resDoc->questions) > 0){
								foreach($resDoc->questions as $questn){
									$qunText = $qunAns = $qunPos1 = $qunPos2 = $qunPos3 = $qunPos4 ="";
									$qunText = key($questn);
									///$availableCnt = $availableCnt + 1;
									foreach($questn as $answer){
										$qunAns = key ($answer);
										foreach($answer as $possibles){
												for($counter = 0; $counter<4;$counter++){
													switch($counter){
														case 0:
															$qunPos1 = $possibles[$counter];
														break;
														case 1:
															$qunPos2 = $possibles[$counter];
														break;
														case 2:
															$qunPos3 = $possibles[$counter];
														break;
														case 3:
															$qunPos4 = $possibles[$counter];
														break;
													}
												}
										}
									}
									
									$insert_stmt = $file_db->prepare("insert into vb_questions 
									(facilityId, memberId, assignmentId, question, ans, pos1, pos2,pos3,pos4,attempted) 
									values (:facilityId, :memberId, :assignmentId, :question, :ans, :pos1, :pos2,:pos3,:pos4,:attempted)");
									
									///$insert_stmt->bindValue(':colNum',$availableCnt);
									$insert_stmt->bindValue(':facilityId', $facilityId);
									$insert_stmt->bindValue(':memberId',$memberID );
									$insert_stmt->bindValue(':assignmentId',$assignRow->doc->_id);
									$insert_stmt->bindValue(':question', $qunText);
									$insert_stmt->bindValue(':ans', $qunAns);
									$insert_stmt->bindValue(':pos1', $qunPos1);
									$insert_stmt->bindValue(':pos2', $qunPos2);
									$insert_stmt->bindValue(':pos3', $qunPos3);
									$insert_stmt->bindValue(':pos4', $qunPos4);
									$insert_stmt->bindValue(':attempted',"false");
									$insert_stmt->execute();
								}
						}
					  }catch(Exception $error){
						  /////////
						  
					  }
					}
				}/////
				
			}////
			
		//////////////
		}///
		
	}//
  }
  
  /*******************************************/
  function pullResourceFromCouch($getResourceArray){
	global $couchUrl;
	global $facilityId;
	global $config;
	echo "Pulling resources together for distrubution unto tablet <br /><br />";
	$counter = 100/sizeof($getResourceArray);
	$percentage=0;
	foreach($getResourceArray as $link){
		$percentage = $percentage + $counter;
		$resources = new couchClient($couchUrl, "resources");
		$docToDownload = $resources->getDoc($link);
		$get_FileToDownload = $docToDownload->_attachments;
		echo $percentage."% complete <br />";
		foreach($get_FileToDownload as $key => $value){
		  $url = $couchUrl."/resources/".$link."/".urlencode($key)."";
		  ///$content = file_get_contents($url);
		 /// file_put_contents('../resources/'.$link.'.'.end(explode(".",strtolower($key))), $content);
			  ///array_push($arrayImage,$key);
		}
	}
}

  
?>