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
	$delFaclity = $_POST['facilityDel'];
	// actions
	// members
	// groups
	// assignments
	$databaseLocation = new couchClient($couchUrl, "actions");
	$viewResults =  $databaseLocation ->include_docs(TRUE)->key($delFaclity)->getView('api', 'facility_AllActions');
	$cnt = 1;
	foreach($viewResults->rows as $record){
		$doc = $databaseLocation ->getDoc($record->doc->_id);
		$databaseLocation ->deleteDoc($doc);
		echo $cnt. ' ' .$record->doc->_id.' - Deleted <br />';
		$cnt++;
	}
	
	echo 'Completed Deletion from system ... Records with id :'. $delFaclity;
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
<p style="font-size: 36px;text-align: center">System Management - Facility Duplication</p>
<form id="form1" name="form1" method="post" action="">
  <table width="395" height="119" border="0" align="center" cellpadding="2" cellspacing="2">
    <tr>
      <td width="387" align="center">Remove Unwanted Facility Id From database</td>
    </tr>
    <tr>
      <td align="center"><label for="proPassword"></label>
      Password : 
      <input type="password" name="proPassword" id="proPassword" /></td>
    </tr>
    <tr>
      <td align="center"><label for="facilityDel"></label>
        Facility Id : 
        <input type="text" name="facilityDel" id="facilityDel" /></td>
    </tr>
    <tr>
      <td align="center"><label for="dbName"></label>
        Database Name : 
        <input type="text" name="dbName" id="dbName" /></td>
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
      <td align="center"><input type="submit" name="button" id="button" value="Remove All from this database" /></td>
    </tr>
  </table>
</form>
</body>
</html>
