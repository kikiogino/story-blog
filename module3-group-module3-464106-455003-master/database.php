<?php
// Content of database.php

$usr="newsadmin"; //username used to login to database
$pwd="gobears"; //password for username
$mysqli = new mysqli('localhost', $usr, $pwd, 'news');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>