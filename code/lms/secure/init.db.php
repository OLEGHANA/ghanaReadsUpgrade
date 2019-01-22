<?php

	use PHPOnCouch\CouchClient;
	use PHPOnCouch\Exceptions;
	use PHPOnCouch\CouchDocument;
	global $couchUrl;
//#Database list
	$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
	$hostname = $_SERVER['HTTP_HOST'];
	$port = "5984";
	$couchUrl = $protocol.$hostname;
	$urlParts = parse_url($couchUrl);
	$couchUrl = $protocol.$urlParts['host'].":".$port;
	$dbNames = array( 
      "facilities" => "facilities$v",
        "whoami" => "whoami$v",
          "resources" => "resources$v",
            "members" => "members$v",
              "assignments" => "assignments$v",
                "actions" => "actions$v",
                  "questions" => "questions$v",
                    "feedback" => "feedback$v",
                      "groups" => "groups$v",
                      "word_bank" => "word_bank$v",
                      "exercises" => "exercises$v",
                      "lesson_notes" => "lesson_notes$v"

                        );

//#Trigger Reset
	if(isset($_GET['resetSystem'])){
		// Delete prior databases
		    if($_GET['resetSystem']=="true"){
			    	foreach($dbNames as $key => $value) {
			        exec("curl -XDELETE $couchUrl/$value");
		    	}
		    	echo "system reset completed";
			}
	}

//#Check and Create Databases
	global $Members;
    $Members = new couchClient($couchUrl, $dbNames['members']); 
	if(!$Members->databaseExists()){
		// Create the Couch Databases
	    foreach($dbNames as $key => $value) {
	        exec("curl -XPUT $couchUrl/" . $value);
	    }
	    date_default_timezone_set('UTC'); 
//#Creat Database Views 
		//#Actions View 
		try{
		  $client = new couchClient($couchUrl, 'actions');
		  try{
		      $apiDoc = $client->getDoc("_design/api");
		      $client->deleteDoc($apiDoc);
		    }catch(Exception $delerr){
		    
		    }
		  $design_doc = new stdClass();
		  $design_doc->_id = '_design/api';
		  $design_doc->language = 'javascript';
		  $design_doc->views = array ( 'memIdFacilityIdActionTime'=> array ('map' =>'function(doc) {
		  emit([doc.memberId,doc.facilityId,doc.action], doc._id);
			}'),
		'MemberId'=> array('map'=>'function(doc) {
		  emit([doc.memberId], doc._id) }')
		);
		  $client->storeDoc($design_doc);
		}catch(Exception $err){
		 print("actions api exist");   
		}

		//#Assignments View 
		try{
		  $client = new couchClient($couchUrl, 'assignments'); 
		 try{
		     $apiDoc = $client->getDoc("_design/api");
		     $client->deleteDoc($apiDoc);
		    }catch(Exception $delerr){
		  }
		  $design_doc = new stdClass();
		$design_doc->_id = '_design/api';
		$design_doc->language = 'javascript';
		$design_doc->views = array ( 
		'facilityGroupID'=> array ('map' => 'function (doc) {
			if(doc.context["use"]!="video book task"){
		    	emit([doc.context["facilityId"], doc.context["groupId"]],doc.resourceId);
			}
		}'),
		'facilityIdLesson_noteId'=> array ('map' => 'function (doc) {
			emit([doc.context["facilityId"], doc.context["lesson_noteId"]],true);
		 }'),
		'facilityIdMemberId'=> array ('map' => 'function (doc) {
		  	emit([doc.context["facilityId"], doc.memberId], doc._id);
		}'),
		'facilityGroupIdVideoBook'=> array ('map' => 'function (doc) {
			if(doc.context["use"]=="video book task"){
		    	emit([doc.context["facilityId"], doc.context["groupId"]],doc.resourceId);
			}
		}'),
		'facilityGroupIdAll'=> array ('map' => 'function (doc) {
		    emit([doc.context["facilityId"], doc.context["groupId"]],doc.resourceId);
		}'),
		'facilityGroupIdWordFormAssignment'=> array ('map' => 'function (doc) {
			if(doc.context["use"]=="word formation"){
		    	emit([doc.context["facilityId"], doc.context["groupId"]],doc.kind);
			}
		}'),
		'facilityGroupIdFillBlankAssignment'=> array ('map' => 'function (doc) {
			if(doc.context["use"]=="fill blanks"){
		    	emit([doc.context["facilityId"], doc.context["groupId"]],doc.kind);
			}
		}'),
		'facilityGroupIdlistenAndFind'=> array ('map' => 'function (doc) {
			if(doc.context["use"]=="listen and find" || doc.context["use"]=="listern and find"){
		    	emit([doc.context["facilityId"], doc.context["groupId"]],doc.kind);
			}
		}'),
		'facilityGroupIdWordPowerAssignment'=> array ('map' => 'function (doc) {
			if(doc.context["use"]=="word power"){
		    	emit([doc.context["facilityId"], doc.context["groupId"]],doc.resourceId);
			}
		}'),
		'facilityGroupIdUnjumbleWordsAssignment'=> array ('map' => 'function (doc) {
			if(doc.context["use"]=="unjumble words"){
		    	emit([doc.context["facilityId"], doc.context["groupId"]],doc.resourceId);
			}
		}')
		);
		$client->storeDoc($design_doc);
		}catch(Exception $err){
		    print("Assignment api exist <br />");
		}
		//
		//#Exercises View 
		try{
		  $client = new couchClient($couchUrl, 'exercises');
		  try{
		      $apiDoc = $client->getDoc("_design/api");
		      $client->deleteDoc($apiDoc);
		    }catch(Exception $delerr){
		    }
		  $design_doc = new stdClass();
		  $design_doc->_id = '_design/api';
		  $design_doc->language = 'javascript';
		  $design_doc->views = array ( 'facilityIdAssgnmentID'=> array ('map' =>'function(doc) {
		  emit([doc.facilityId,doc.assignmentId], doc._id);
		}'),
		'MemberId'=> array('map'=>'function(doc) {
		  emit([doc.memberId], doc._id) }')
		);
		  $client->storeDoc($design_doc);
		}catch(Exception $err){
		 print("Exercises api exist");   
		}


		//#Facilities View 
		try{
		  $client = new couchClient($couchUrl, 'facilities');
		  try{
		      $apiDoc = $client->getDoc("_design/api");
		      $client->deleteDoc($apiDoc);
		    }catch(Exception $delerr){
		    }
		  $design_doc = new stdClass();
		  $view_fn="function(doc) {
		              emit([doc.context['facilityId'], doc.context['groupId']],doc.resourceId);
		                    }";
		  $design_doc->_id = '_design/api';
		  $design_doc->language = 'javascript';
		  $design_doc->views = array ( 'facilityGroupID'=> array ('map' => $view_fn ) );
		  $client->storeDoc($design_doc);
		}catch(Exception $err){
		 print("Facility api exist");   
		}

		//#Feedback View 
		try{
		  $client = new couchClient($couchUrl, 'feedback'); 
		 try{
		     $apiDoc = $client->getDoc("_design/api");
		     $client->deleteDoc($apiDoc);
		    }catch(Exception $delerr){
		  }
		  $design_doc = new stdClass();
		$design_doc->_id = '_design/api';
		$design_doc->language = 'javascript';
		$design_doc->views = array ( 
		'facilityIdMemberID'=> array ('map' => "function (doc) {
		  			emit(doc.facilityId + doc.memberId, doc._id);
				}"),
		'facilityIdLesson_noteId'=> array ('map' => 'function (doc) {
			emit([doc.facilityId, doc.context["lesson_noteId"]],true);
		 }'),
		'facilityIdMemberId'=> array ('map' => 'function (doc) {
		  	emit([doc.facilityId , doc.memberId], doc._id);
		}')
		);
		$client->storeDoc($design_doc);
		}catch(Exception $err){
		    print("Feedback api exist <br />");
		}



		//#Groups View 
		try{
		  $client = new couchClient($couchUrl, 'groups'); 
		 try{
		     $apiDoc = $client->getDoc("_design/api");
		     $client->deleteDoc($apiDoc);
		    }catch(Exception $delerr){
		  }
		  $design_doc = new stdClass();
		$design_doc->_id = '_design/api';
		$design_doc->language = 'javascript';
		$design_doc->views = array ( 
		'allGroupsInFacility'=> array ('map' => "function (doc) {
						 if(doc.level) {
						   emit(doc.facilityId, doc._id);
						 }
			}"),
		'facilityLevel'=> array ('map' => "function (doc) {
		 				if(doc.level) {
		   					emit(doc.facilityId + doc.level, doc._id);
		 				}
			}"),
		'facilityMemberID'=> array ('map' => "function (doc) {
		 			if(doc.level) {
		   				emit(doc.facilityId + doc.members, doc._id);
		 			}
			}"),
		'facilityOwners'=> array ('map' => "function (doc) {
		 				if(doc.level) {
		   					emit(doc.facilityId, doc._id);
		 				}
		 	}"),
		'facilityWithMemberID'=> array ('map' => "function (doc) {
					for(var cnt=0; cnt<doc.members.length; cnt++){
		   				emit([doc.facilityId,doc.members[cnt]],doc._id);
					}
			}"),
		'groupsByID'=> array ('map' => "function (doc) {
		 				if(doc.level) {
		   					emit(doc.facilityId, doc._id);
		 			}
		 }"),
		'facilityIdLevel'=> array ('map' => "function (doc) {
		 	for(var cnt=0;cnt<doc.level.length;cnt++){
		   		emit([doc.facilityId,doc.level[cnt]], doc._id);
			}
		 }")
		 
		);
		$client->storeDoc($design_doc);
		}catch(Exception $err){
		    print("Feedback api exist <br />");
		}



		//#Lesson_notes View 
		try{
		  $client = new couchClient($couchUrl, 'lesson_notes'); 
		 try{
		     $apiDoc = $client->getDoc("_design/api");
		     $client->deleteDoc($apiDoc);
		    }catch(Exception $delerr){
		  }
		  $design_doc = new stdClass();
		$design_doc->_id = '_design/api';
		$design_doc->language = 'javascript';
		$design_doc->views = array ( 
		'facilityIdGroupId'=> array ('map' => "function (doc) {
		      emit([doc.facilityId, doc.groupId],true);
		 }"),
		 'facilityIdMemberIdExecDate'=> array ('map' => "function (doc) {
		      emit([doc.facilityId, doc.memberId],true);
		 }")
		 
		);
		$client->storeDoc($design_doc);
		}catch(Exception $err){
		    print("lesson_notes api exist <br />");
		}


		//#Members View 
		try{
		  $client = new couchClient($couchUrl, 'members');
		  try{
		     $apiDoc = $client->getDoc("_design/api");
		     $client->deleteDoc($apiDoc);
		    }catch(Exception $delerr){
		  }
		  $design_doc = new stdClass();
		  $view_fn="function(doc) {
		                if(doc.login) {
		                emit(doc.facilityId + doc.login, doc._id);
		              }
		            }";
		  $design_doc->_id = '_design/api';
		  $design_doc->language = 'javascript';
		  $design_doc->views = array ( 'facilityLogin'=> array ('map' => $view_fn ),

		'findMemberWithID'=> array ('map' => 'function(doc) {
		var myBoolean = true;
		for(var cnt=0;cnt<doc.roles.length;cnt++){
		if(doc.roles[cnt]=="administrator"|| doc.roles[cnt]=="student"){
		myBoolean = false;
		return;
		}
		}
		if(myBoolean){
		emit(doc._id, doc.firstName);
		}}'),

		'listMemNotAdmin'=> array ('map' => 'function(doc) {
		var myBoolean = true;
		for(var cnt=0;cnt<doc.roles.length;cnt++){
		if(doc.roles[cnt]=="administrator"|| doc.roles[cnt]=="student"){
		myBoolean = false;
		return;
		}
		}
		if(myBoolean){
		emit(doc.facilityId, doc._id);
		}}'),

		'facilityLevel'=> array ('map' => 'function(doc) {
			if(doc.levels) {
				if(doc.roles=="student" && doc.status=="active" ){
					emit(doc.facilityId + doc.levels, doc.lastName);
				}
			}
			}'),


		'facilityLevelActive'=> array ('map' => 'function(doc) {
		if(doc.levels) {
		if(doc.roles=="student" && doc.status=="active" ){
		emit(doc.facilityId + doc.levels, doc.lastName);
		}
		}}'),

		'facilityLevelActive_allStudent_sorted'=> array ('map' => 'function(doc) {
		if(doc.levels) {
		if(doc.roles=="student" && doc.status=="active" ){
		for( var cnt=0; cnt<doc.levels.length; cnt++){
		emit([doc.facilityId, doc.levels[cnt], doc.lastName],true);
		}
		}
		}}'),

		'facilityLevelInactive_allStudent_sorted'=> array ('map' => 'function(doc) {
		if(doc.levels) {
		if(doc.roles=="student" && doc.status=="inactive" ){
		for( var cnt=0; cnt<doc.levels.length; cnt++){
		emit([doc.facilityId, doc.levels[cnt], doc.lastName],true);
		}
		}
		}
		}'),
		'facility_AllMembers'=> array ('map' => 'function(doc) {
			emit(doc.facilityId, doc.lastName);
		}'),
		);
		$client->storeDoc($design_doc);
		}catch(Exception $err){
		    print("Members api exist \n");
		}
		
		//#Resources View 
		try{
		  $client = new couchClient($couchUrl, 'resources'); 
		 try{
		     $apiDoc = $client->getDoc("_design/api");
		     $client->deleteDoc($apiDoc);
		    }catch(Exception $delerr){
		  }
		  $design_doc = new stdClass();
		 $view_fn="function(doc) {
		emit(null,doc._id);
		}";
		$design_doc->_id = '_design/api';
		$design_doc->language = 'javascript';
		$design_doc->views = array ( 
		'allResources'=> array ('map' => $view_fn ),
		'allTeacherTraining'=> array ('map' => 'function(doc) {
			for(var cnt=0;cnt<doc.audience.length;cnt++){
				if(doc.audience[cnt]=="teacher training"){
						emit(null,doc._id);
						  }
							 }
		}')
		);
		$client->storeDoc($design_doc);
		}catch(Exception $err){
		    print("Resources api exist <br />");
		}


		//#Word_bank View 
		try{
		  $client = new couchClient($couchUrl, 'word_bank'); 
		 try{
		     $apiDoc = $client->getDoc("_design/api");
		    }catch(Exception $delerr){
		  }
		  $design_doc = new stdClass();
		 $view_fn="function(doc) {
			if(doc.language) {
				emit([doc.language, (doc._id).length,doc._id],true);
			}
		}";
		$design_doc->_id = '_design/api';
		$design_doc->language = 'javascript';
		$design_doc->views = array ( 
			'listWords_language'=> array (
				'map' => $view_fn ),
			'language_NoChar_WordID'=> array ('map' => 'function(doc) {
				if(doc.language) {
					emit([doc.language, (doc._id).length,doc._id],true);
				}
			}'),
			'language_NoChar_WordStartChar'=> array ('map' => 'function(doc) {
				if(doc.language) {
					emit([doc.language, (doc._id).length,doc._id[0]],true);
				}
			}')
		);	
		$client->storeDoc($design_doc);
		}catch(Exception $err){
		    print("Word_bank api exist <br />");
		}

//#Create Facility

		$Facilities = new couchClient($couchUrl, $dbNames['facilities']);
		$facility = (object) array(
		  "kind"     => "Facility",
		  "type"     => "COBPS",
		  "GPS"      => array("", ""),
		  "phone"    => "",
		  "name"     => "oleschool",
		  "country"  => "gh",
		  "region"   => "NA",
		  "district" => "NA",
		  "area"     => "NA",
		  "street"   => "NA",
		  "dateEnrolled" => strtotime("now")
		);
		$facility = $Facilities->storeDoc($facility);
		global $facilityId;
		$facilityId = $facility->id;

//#Create the whoami for facility
		$whoami = new couchClient($couchUrl, $dbNames['whoami']);
		$whoamiFacility = new CouchDocument($whoami);
		$whoamiFacility->set(array(
		  "_id" => "facility",
		  "kind" => "system",
		  "facilityId" => $facilityId,
		));

		// Create the whoami/config doc
		$whoamiConfig = new CouchDocument($whoami);
		$whoamiConfig->set(array(
		  "_id" => "config",
		  "kind" => "system",
		  "timezone" => "GMT",
		  "language" => "EN",
		  "version" => "2.0",
		  "layout" => 1,
		  "subjects" => array("english", "math", "science"),
		  "levels" => array('KG1', 'KG2', 'P1', 'P2', 'P3', 'P4', 'P5', 'P6','JHS1','JHS2','JHS3')
		));
		$whoami->storeDoc($design_doc);

	//#Create default groups in Couch
		$groups = new couchClient($couchUrl, $dbNames['groups']);
		global $levelToGroupIdMap;
		$levelToGroupIdMap = array(
		  "KG1" => "",
		  "KG2" => "",
		  "P1" => "",
		  "P2" => "",
		  "P3" => "",
		  "P4" => "",
		  "P5" => "",
		  "P6" => "",
		  "JHS1"=>"",
		  "JHS2"=>"",
		  "JHS3"=>"",
		);

		$group = array();
		foreach($levelToGroupIdMap as $key => $id) {
		  $n = new stdClass();
		  //$n->_id = $couchClient->getUuids(1)[0];
		  $n->kind = "Group";
		  $n->name = $key;
		  $n->level = array($key);
		  $n->members = array();
		  $n->owners = array();
		  $n->facilityId = $facilityId;
		  $levelToGroupIdMap[$key] = $n->_id;
		  //$group[] = $n;
		  $groups->storeDoc($n);
		}

//#Create super admin account
		$members = new couchClient($couchUrl, $dbNames['members']);
		global $adminMember;
		$adminMember = new stdClass();
		$adminMember->kind = "Member";
		$adminMember->role = ["teacher","leadteacher","headteacher","coach"];
		$adminMember->pass = md5("ghanabell");
		$adminMember->login = "schoolbell";
		$adminMember->facilityId = $facilityId;
		$adminMember->firstName = "System";
		$adminMember->middleNames = " ";
		$adminMember->lastName = "Administrator";
		$members->storeDoc($adminMember);
	}
			






?>