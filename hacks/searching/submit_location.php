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

$action=$_GET["action"];


// Here we respond to a plain old GET request, supplying the API description as a JSON response.
if(!isset($action))
{
	$options = [
		"create" => "Create a new search. Requires: name | coordinator | description - Returns: search_id or ERROR",
		"register" => "Register a new searcher. Requires: forename | surname | mobile | search_id - Returns: id or ERROR",
		"locate" => "Record a searcher's location. Requires: lat | long | id (from register) - Returns: JSON array of other searchers or ERROR.",
		"chat" => "Submit a chat message back to the coordinator. Requires: chat | lat | long | id (from register) - Returns: OK or ERROR",
		"locations" => "List all searcher locations. Requires: coordinator -  Returns: JSON array of all searchers or ERROR.",
		"reply" => "Reply to a chat from a searcher. Requires: chat | id (from register) - Returns: OK or ERROR",
		"broadcast" => "Broadcasts to all searchers in the search this co-ordinator is the co-ordinator of. - Returns: OK or ERROR",
	];
	die(json_encode($options));
}

// They're creating a new search - add it into the database, and return it's id
if($action=='create') {

	$name        = $_GET['name'];
	$coordinator = $_GET['coordinator'];
	$description = $_GET['description'];
	
	$sql="INSERT INTO blc_db.searchcase(name, coordinator, description) VALUES ('" . $name . "', " . $coordinator . ", '" . $description . "');";
	
	$result=mysql_query($sql);
	
	$response = [
		"id" => mysql_insert_id(),
	];
	mysql_close();
	die(json_encode($response));
}

// They're registering as a new searcher - add them into the database, and return an id
if($action=='register') {

	$forename = $_GET["forename"];
	$surname  = $_GET["surname"];
	$mobile   = $_GET["mobile"];
	$searchid = $_GET['search_id'];
	
	$sql="INSERT INTO blc_db.searchprofile(searchcase_id, forename, surname, mobile) VALUES (" . $searchid . ", '" . $forename . "', '" . $surname . "', '" . $mobile . "');";
	
	$result=mysql_query($sql);
	
	$response = [
		"id" => mysql_insert_id(),
	];
	mysql_close();
	die(json_encode($response));
}

// They've just sent their latest location - save theirs, then return a collection of other people
if($action=='locate') {

	$id   = $_GET['id'];
	$lat  = $_GET['lat'];
	$long = $_GET['long'];
	
	// To simplify the INSERT or UPDATE complexity, we'll simply do a delete, immediately followed by an insert.
	$sql="DELETE FROM blc_db.locations WHERE userid = $id;";
	$result=mysql_query($sql);

	$sql="INSERT INTO blc_db.locations(searchprofile_id, lat, lon) VALUES(" . $id . ", " . $lat . ", " . $long . ");";
	
	//die($sql);
	$result=mysql_query($sql);
	
	// If successfully insert data into database, we now get all the locations (except our own)
	if($result){
		$sth = mysql_query("SELECT lat, lon FROM locations WHERE searchprofile_id <> $id;");
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
	
	$chat = $_GET['chat'];  // Text of the chat
	$id   = $_GET['id'];    // User's ID number
	$lat  = $_GET['lat'];   // Current location is recorded too
	$long = $_GET['long'];

	$sql="INSERT INTO chat(chat, searchprofile_id, lat, lon) VALUES ('" . $chat . "', " . $id . ", " . $lat . ", " . $long . ");";
	$result=mysql_query($sql);
	mysql_close();

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

// First we should clear out anyone who hasn't updated their location in the last 5 ?? minutes...
	$sql = "DELETE FROM locations WHERE TIMESTAMPDIFF(MINUTE, recorded, CURRENT_TIMESTAMP) > 5;";
	$sth = mysql_query($sql);
	$coordinator = $_GET['coordinator'];

	$sql = "SELECT locations.lat, locations.lon, sp1.id FROM searchprofile sp1 JOIN searchcase ON sp1.searchcase_id = searchcase.id JOIN locations ON sp1.id = locations.searchprofile_id WHERE searchcase.coordinator = " . $coordinator. ";";

	$sth = mysql_query($sql);
	$rows = array();
	while($r = mysql_fetch_assoc($sth)) {
		$rows[] = $r;
	}
	mysql_close();
	die(json_encode($rows));
}


// This to send a chat message from co-ordinator to a searcher...
if($action=='reply') {

	$chat=$_GET['chat'];	// Text of the chat
	$id=$_GET['id'];	// User's ID number
	$coord=$_GET['coord'];	// User ID of the co-ordinator
	$sql = "INSERT INTO blc_db.chat(chat, userid, fromuser) VALUES($chat, $id, -1);";
	$sth = mysql_query($sql);
	mysql_close();
	
	
	if($sth){
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

// This allows the co-ordinator to send the same message to all searchers.
if($action=='broadcast'){

	$chat=$_GET['chat'];	// Text of the chat
	$sql = "INSERT INTO blc_db.chat(chat, fromuser) VALUES($chat, -1);";
	$sth = mysql_query($sql);
	mysql_close();
	
	if($sth){
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
