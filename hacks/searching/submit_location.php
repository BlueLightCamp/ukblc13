<?php

// NB: This requires the following variables to be set in config.php:
// $host
// $username
// $password
// $db_name

require 'config.php';

// Connect to server and select database.
mysql_connect("$host", "$username", "$password")or die("cannot connect");
mysql_select_db("$db_name")or die("cannot select DB");

// Get values passed in...

// Action can be one of:
// register
// locate
// chat
// 
$action=$_POST[action];


// They're registering as a new searcher - add them into the database, and return an id
if($action=='register'){
	$forename=$_POST['forename'];
	$surname=$_POST['surname'];
	$mobile=$_POST['mobile'];

	$sql="INSERT INTO searchprofile(forename, surname, mobile)VALUES('$forename', '$surname', '$mobile');";
	$output = mysql_insert_id();
}

// They've just sent their latest location - save theirs, then return a collection of other people
if($action=='locate') {
	$lat=$_POST['lat'];
	$long=$_POST['long'];
	$id=$_POST['id'];
	
	// To simplify the INSERT or UPDATE complexity, we'll simply do a delete, immediately followed by an insert.
	$sql="DELETE FROM locations WHERE userid = $id;";
	$result=mysql_query($sql);
	
	$sql="INSERT INTO locations(lat, long, userid)VALUES($lat, $long, $id);";
	$result=mysql_query($sql);
	
	// If successfully insert data into database, we now get all the locations (except our own)
	if($result){
		$sth = mysql_query("SELECT lat, long FROM locations WHERE userid <> $id;");
		$rows = array();
		while($r = mysql_fetch_assoc($sth)) {
			$rows[] = $r;
		}
		$output = json_encode($rows);
	}
}

// They've just sent in a 'chat' message. Store it into the database - we'll worry about what to do with it from there.
if($action=='chat'){
	$chat=$_POST['chat']; // Text of the chat
	$lat=$_POST['lat'];
	$long=$_POST['long'];
	$id=$_POST['id'];    // User's ID number

	$sql="INSERT INTO chat(chat, userid, lat, long )VALUES('$chat', $id, $lat, $long);";
	$output = "Success"
}

$result=mysql_query($sql);

// if successfully insert data into database, displays message "Successful". 
if($result){
	echo $output;
}

mysql_close();
?> 
