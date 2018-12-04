<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>
<?php
echo phpinfo();
  // remove current sqllite database file -> schoolBell.db
	$files = glob('../transferData/*');
	foreach($files as $file)
	{ 
	  if(is_file($file))
	  {
	  	if($file == '../transferData/schoolBell.db')
		{
			//echo $file;
			unlink($file);
		}
	  }
	}
 /**************************************/
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
					roles TEXT,
                    name TEXT, 
                    pass TEXT)");
	
	// Create table resources i.e Audio,Readable or video book as category
    $file_db->exec("CREATE TABLE IF NOT EXISTS resources (
                    id TEXT PRIMARY KEY,
					facilityId TEXT, 
					assignmentId TEXT, 
					category TEXT, 
					type TEXT, 
					title TEXT,
                    description TEXT)");
					
	// Create table video_book question
    $file_db->exec("CREATE TABLE IF NOT EXISTS vb_questions (
                    colNum INTEGER PRIMARY KEY, 
					facilityId TEXT,
					assignmentId TEXT,
					question TEXT,
					ans TEXT, 
					pos1 TEXT, 
					pos2 TEXT,
                    pos3 TEXT, 
                    pos4 TEXT)");
 
 	$id = 'tryrt';
	$facilityId = '224324234';
	$roles ='student';
	$name = 'Leonard Maximus';
	$pass ='password';
	$insert_stmt = $file_db->prepare("insert into members (id,facilityId,roles,name,pass) values (:id, :facilityId, :roles, :name, :pass)");
	$insert_stmt->bindValue(':id', $id);
	$insert_stmt->bindValue(':facilityId', $facilityId);
	$insert_stmt->bindValue(':roles', $roles);
	$insert_stmt->bindValue(':name', $name);
	$insert_stmt->bindValue(':pass', $pass);
	$insert_stmt->execute();
	
	
	
	
////////    // Create table messages
////////   /* $file_db->exec("CREATE TABLE IF NOT EXISTS messages (
////////                    id INTEGER PRIMARY KEY, 
////////                    title TEXT, 
////////                    message TEXT, 
////////                    time INTEGER)");
//////// 
////////    // Create table messages with different time format
////////    $memory_db->exec("CREATE TABLE messages (
////////                      id INTEGER PRIMARY KEY, 
////////                      title TEXT, 
////////                      message TEXT, 
////////                      time TEXT)");
//////// */
//////// 
////////    /**************************************
////////    * Set initial data                    *
////////    **************************************/
//////// 
////////    // Array with some test data to insert to database             
////////    $messages = array(
////////                  array('title' => 'Hello!',
////////                        'message' => 'Just testing...',
////////                        'time' => 1327301464),
////////                  array('title' => 'Hello again!',
////////                        'message' => 'More testing...',
////////                        'time' => 1339428612),
////////                  array('title' => 'Hi!',
////////                        'message' => 'SQLite3 is cool...',
////////                        'time' => 1327214268)
////////                );
//////// 
//////// 
////////    /**************************************
////////    * Play with databases and tables      *
////////    **************************************/
//////// 
////////    // Prepare INSERT statement to SQLite3 file db
////////    $insert = "INSERT INTO messages (title, message, time) 
////////                VALUES (:title, :message, :time)";
////////    $stmt = $file_db->prepare($insert);
//////// 
////////    // Bind parameters to statement variables
////////    $stmt->bindParam(':title', $title);
////////    $stmt->bindParam(':message', $message);
////////    $stmt->bindParam(':time', $time);
//////// 
////////    // Loop thru all messages and execute prepared insert statement
////////    foreach ($messages as $m) {
////////      // Set values to bound variables
////////      $title = $m['title'];
////////      $message = $m['message'];
////////      $time = $m['time'];
//////// 
////////      // Execute statement
////////      $stmt->execute();
////////    }
//////// 
////////    // Prepare INSERT statement to SQLite3 memory db
////////    $insert = "INSERT INTO messages (id, title, message, time) 
////////                VALUES (:id, :title, :message, :time)";
////////    $stmt = $memory_db->prepare($insert);
//////// 
////////    // Select all data from file db messages table 
////////    $result = $file_db->query('SELECT * FROM messages');
//////// 
////////    // Loop thru all data from messages table 
////////    // and insert it to file db
////////    foreach ($result as $m) {
////////      // Bind values directly to statement variables
////////      $stmt->bindValue(':id', $m['id'], SQLITE3_INTEGER);
////////      $stmt->bindValue(':title', $m['title'], SQLITE3_TEXT);
////////      $stmt->bindValue(':message', $m['message'], SQLITE3_TEXT);
//////// 
////////      // Format unix time to timestamp
////////      $formatted_time = date('Y-m-d H:i:s', $m['time']);
////////      $stmt->bindValue(':time', $formatted_time, SQLITE3_TEXT);
//////// 
////////      // Execute statement
////////      $stmt->execute();
////////    }
//////// 
////////    // Quote new title
////////    $new_title = $memory_db->quote("Hi''\'''\\\"\"!'\"");
////////    // Update old title to new title
////////    $update = "UPDATE messages SET title = {$new_title} 
////////                WHERE datetime(time) > 
////////                datetime('2012-06-01 15:48:07')";
////////    // Execute update
////////    $memory_db->exec($update);
//////// 
////////    // Select all data from memory db messages table 
////////    $result = $memory_db->query('SELECT * FROM messages');
//////// 
////////    foreach($result as $row) {
////////      echo "Id: " . $row['id'] . "\n";
////////      echo "Title: " . $row['title'] . "\n";
////////      echo "Message: " . $row['message'] . "\n";
////////      echo "Time: " . $row['time'] . "\n";
////////      echo "\n";
////////    }
//////// 
//////// 
////////    /**************************************
////////    * Drop tables                         *
////////    **************************************/
//////// 
////////    // Drop table messages from file db
////////    $file_db->exec("DROP TABLE messages");
////////    // Drop table messages from memory db
////////    $memory_db->exec("DROP TABLE messages");
//////// 
//////// 
////////    /**************************************
////////    * Close db connections                *
////////    **************************************/
//////// 
    // Close file db connection
    $file_db = null;
    // Close memory db connection
    $memory_db = null;
  }
  catch(Exception $e) {
    // Print PDOException message
    echo $e->getMessage();
  }
?>
<body>
</body>
</html>