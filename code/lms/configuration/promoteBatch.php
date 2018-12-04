<?php include "secure/talk2db.php";?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Open Learning Exchange</title>
<?php
global $couchUrl;
if(isset($_POST['proPassword']) ){
	//md5($_POST['proPassword']) =='0398eba0f513504057d1777562b1b8c6')
	  $groups = new couchClient($couchUrl, "groups");
	 $array_studentsIDs =array();
	$viewResults = $groups->include_docs(TRUE)->key($facilityId)->getView('api', 'allGroupsInFacility');
	foreach($viewResults as $group){
		foreach($group as $gp){
			if($gp->doc->name=='KG1'){
				$KG1_members = $gp->doc->members;
				$KG1_ID = $gp->doc->_id;
			}else if($gp->doc->name=='KG2'){
				$KG2_members = $gp->doc->members;
				$KG2_ID = $gp->doc->_id;
			}else if($gp->doc->name=='P1'){
				$P1_members = $gp->doc->members;
				$P1_ID = $gp->doc->_id;
			}else if($gp->doc->name=='P2'){
				$P2_members = $gp->doc->members;
				$P2_ID = $gp->doc->_id;
			}else if($gp->doc->name=='P3'){
				$P3_members = $gp->doc->members;
				$P3_ID = $gp->doc->_id;
			}else if($gp->doc->name=='P4'){
				$P4_members = $gp->doc->members;
				$P4_ID = $gp->doc->_id;
			}else if($gp->doc->name=='P5'){
				$P5_members = $gp->doc->members;
				$P5_ID = $gp->doc->_id;
			}else if($gp->doc->name=='P6'){
				$P6_members = $gp->doc->members;
				$P6_ID = $gp->doc->_id;
			}else if($gp->doc->name=='JHS'){
				$JHS_members = $gp->doc->members;
				$JHS_ID = $gp->doc->_id;
			}
			$array_studentsIDs = array_merge($array_studentsIDs,$gp->doc->members);
		}
	}
	
	//// KG1
	try{
		$KG1_doc = $groups->getDoc($KG1_ID);
		$KG2_doc = $groups->getDoc($KG2_ID);
		$P1_doc = $groups->getDoc($P1_ID);
		$P2_doc = $groups->getDoc($P2_ID);
		$P3_doc = $groups->getDoc($P3_ID);
		$P4_doc = $groups->getDoc($P4_ID);
		$P5_doc = $groups->getDoc($P5_ID);
		$P6_doc = $groups->getDoc($P6_ID);
		$JHS_doc = $groups->getDoc($JHS_ID);
		$JHS_doc->members = array_merge($JHS_doc->members,$P6_doc->members);
		$P6_doc->members = $P5_doc->members;
		$P5_doc->members = $P4_doc->members;
		$P4_doc->members = $P3_doc->members;
		$P3_doc->members = $P2_doc->members;
		$P2_doc->members = $P1_doc->members;
		$P1_doc->members = $KG2_doc->members;
		$KG2_doc->members = $KG1_doc->members;
		$KG1_doc->members = array();
		
		$groups->storeDoc($JHS_doc);
		$groups->storeDoc($KG1_doc);
		$groups->storeDoc($KG2_doc);
		$groups->storeDoc($P1_doc);
		$groups->storeDoc($P2_doc);
		$groups->storeDoc($P3_doc);
		$groups->storeDoc($P4_doc);
		$groups->storeDoc($P5_doc);
		$groups->storeDoc($P6_doc);
	}catch(Exception $error){
		//echo 'Error 1 '.$error;
	}
/// Change members class status

	$members = new couchClient($couchUrl, "members");
	for($cnt=0;$cnt<sizeof($array_studentsIDs);$cnt++){
		try{
			$memDoc = $members->getDoc($array_studentsIDs[$cnt]);
			switch($memDoc->levels[0]){
				case 'KG1':
				$memDoc->levels = array('KG2');
				break;
				case 'KG2':
				$memDoc->levels = array('P1');
				break;
				case 'P1':
				$memDoc->levels = array('P2');
				break;
				case 'P2':
				$memDoc->levels = array('P3');
				break;
				case 'P3':
				$memDoc->levels = array('P4');
				break;
				case 'P4':
				$memDoc->levels = array('P5');
				break;
				case 'P5':
				$memDoc->levels = array('P6');
				break;
				case 'P6':
				$memDoc->levels = array('JHS');
				break;
			}
			$members->storeDoc($memDoc);
		}catch(Exception $err){
			///echo 'Error found in student details '. $err;
		}
	}
	echo 'Completed Class Promotion';
}else{
	/*if(isset($_POST['proPassword'])){
		die('Invalid Password');
	}else{
	}*/
}

?>
</head>

<body>
<p>&nbsp;</p>
<p style="font-size: 36px;text-align: center">System Management</p>
<form id="form1" name="form1" method="post" action="">
  <table width="395" height="119" border="0" align="center" cellpadding="2" cellspacing="2">
    <tr>
      <td width="387" align="center">Promotion Password</td>
    </tr>
    <tr>
      <td align="center"><label for="proPassword"></label>
      <input type="password" name="proPassword" id="proPassword" /></td>
    </tr>
    <tr>
      <td align="center">
	  <?php 
	  $groups = new couchClient($couchUrl, "groups");
	$viewResults = $groups->include_docs(TRUE)->key($facilityId)->getView('api', 'allGroupsInFacility');
	foreach($viewResults as $group){
		foreach($group as $gp){
			echo $gp->doc->name.' , ';
		}
	}
	  ?>&nbsp;</td>
    </tr>
    <tr>
      <td align="center"><input type="submit" name="button" id="button" value="Promote All Students" /></td>
    </tr>
  </table>
</form>
</body>
</html>
