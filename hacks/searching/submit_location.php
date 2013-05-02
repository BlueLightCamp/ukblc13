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
$sql = '';

// Action can be one of:
// register
// locate
// chat
// locations
// reply
$action=$_GET['action'];


// Here we respond to a plain old GET request, supplying the API description as a JSON response.
if(!isset($action))
{
	$options = [
		"register" => "Register a new searcher. Requires: forename | surname | mobile Returns: id",
		"locate" => "Record a searcher's location. Requires: lat | long | id (from register) Returns: JSON array of other searchers or ERROR.",
		"chat" => "Submit a chat message back to the coordinator. Requires: chat | lat | long | id (from register) Returns: OK or ERROR",
		"locations" => "List all searcher locations. Requires: [Nothing]. Returns: JSON array of all searchers or ERROR.",
		"reply" => "Reply to a chat from a searcher. Requires: chat | id (from register) Returns: OK or ERROR",
	];
	die(json_encode($options));
}

// They're registering as a new searcher - add them into the database, and return an id
if($action=='register'){
	$forename=$_GET['forename'];
	$surname=$_GET['surname'];
	$mobile=$_GET['mobile'];

	$sql="INSERT INTO searchprofile(forename, surname, mobile)VALUES('$forename', '$surname', '$mobile');";
	$result=mysql_query($sql);
	
	$response = [
		"id" => mysql_insert_id(),
	];
	mysql_close();
	die(json_encode($response));
}

// They've just sent their latest location - save theirs, then return a collection of other people
if($action=='locate') {
	$lat=$_GET['lat'];
	$long=$_GET['long'];
	$id=$_GET['id'];
	
	// To simplify the INSERT or UPDATE complexity, we'll simply do a delete, immediately followed by an insert.
	$sql="DELETE FROM locations WHERE userid = $id;";
	$result=mysql_query($sql);

	$sql="INSERT INTO blc_db.locations(userid, lat, lon) VALUES($id, $lat, $long);";
	
	$result=mysql_query($sql);
	
	// If successfully insert data into database, we now get all the locations (except our own)
	if($result){
		$sth = mysql_query("SELECT lat, long FROM locations WHERE userid <> $id;");
		$rows = array();
		while($r = mysql_fetch_assoc($sth)) {
			$rows[] = $r;
		}
		mysql_close();
		die(json_encode($rows));
	}
}

// They've just sent in a 'chat' message. Store it into the database - we'll worry about what to do with it from there.
if($action=='chat'){
	$chat=$_GET['chat']; // Text of the chat
	$lat=$_GET['lat'];
	$long=$_GET['long'];
	$id=$_GET['id'];    // User's ID number

	$sql="INSERT INTO chat(chat, userid, lat, long )VALUES('$chat', $id, $lat, $long);";
	$result=mysql_query($sql);
	mysql_close();

	// if successfully insert data into database, displays message "Successful". 
	if($result){
		$response = [
			"result" => "OK",
		];
	} else {
		$response = [
			"result" => "ERROR",
		];		
	}

	die(json_encode($response));
}

// This to get all searchers' locations...
if($action=='locations') {

// First we should clear out anyone who hasn't updated their location in the last 5 minutes?

	$sql = "SELECT lat, long FROM locations;";

	$sth = mysql_query($sql);
	$rows = array();
	while($r = mysql_fetch_assoc($sth)) {
		$rows[] = $r;
	}
	mysql_close();
	die(json_encode($rows));
}


// This to get all searchers' locations...
if($action=='reply') {

	$chat=$_GET['chat'];	// Text of the chat
	$id=$_GET['id'];	// User's ID number
	$sql = "INSERT INTO blc_db.chat(chat, userid) VALUES($chat, $id);";
	$sth = mysql_query($sql);
	mysql_close();
	
	
	// if successfully insert data into database, displays message "Successful". 
	if($result){
		$response = [
			"result" => "OK",
		];
	} else {
		$response = [
			"result" => "ERROR",
		];		
	}

	die(json_encode($response));
}
?>
