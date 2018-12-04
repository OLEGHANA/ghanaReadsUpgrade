<?php 
$v = "";

/*
 *
 * Setup dependencies and globals
 *
 */

echo "\033[32m";
print ("Setting up\n");
echo "\033[0m";

// MySQL
global $mysqli;
$mysqli = new mysqli("localhost", "root", "", "xmhghana_wcc_game");

// CouchDB
require_once 'PHP-on-Couch-master/lib/couch.php';
require_once 'PHP-on-Couch-master/lib/couchClient.php';
require_once 'PHP-on-Couch-master/lib/couchDocument.php';
global $couchUrl;
$couchUrl = 'http://pi:raspberry@127.0.0.1:5984';
global $couchClient;
$couchClient = new couchClient($couchUrl, "word_bank");
$result = $mysqli->query("SELECT * FROM en_words LIMIT 100000, 150000");
//$wordDetails = $result->fetch_object();
while($record = $result->fetch_object()) { 
	try{
		$doc = new stdClass();
		$doc->_id = $record->word;
		$doc->kind = "Word";
		$doc->code = $record->code;
		$doc->advance_code = $record->advance_code;
		$doc->language = "en";
		$couchClient->storeDoc($doc);
		echo $record->word."<br />";
	}catch(Exception $err){
		
	}
}
$result->close();

?>
