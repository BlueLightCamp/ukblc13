<?php

$host="localhost"; // Host name
$username=""; // Mysql username 
$password=""; // Mysql password 
$db_name="blc_db"; // Database name 

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

	$sql="INSERT INTO searchprofile(forename, surname, mobile)VALUES('$forename', '$surname', '$mobile')";
	$id = mysql_insert_id();
}

// 
if($action=='locate') {
	$lat=$_POST['lat'];
	$long=$_POST['long'];
	$id=$_POST['id'];
	
	$sql="INSERT INTO locations(lat, long, userid)VALUES('$lat', '$long', '$id')";
}

if($action=='chat'){
	$chat=$_POST['chat'] // Text of the chat
	$lat=$_POST['lat'];
	$long=$_POST['long'];
	$id=$_POST['id'];    // User's ID number

	$sql="INSERT INTO chat(chat, userid, lat, long )VALUES('$chat', '$id', '$lat', '$long')";
}

$result=mysql_query($sql);

// if successfully insert data into database, displays message "Successful". 
if($result){
	echo $id;
}

mysql_close();
?> 
