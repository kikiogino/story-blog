<?php
session_start();
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require 'database.php';

//liking stories:
// story table: story_id, like_count, user_id, 
//  
if(!hash_equals($_SESSION['token'], $_POST['token'])){
    die("Request forgery detected");
}
if(isset($_GET['story_id'])){
    $story_id = $_GET['story_id'];

    
     
}
?>
