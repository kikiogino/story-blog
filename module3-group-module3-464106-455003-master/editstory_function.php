<?php
session_start();
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require 'database.php';
if(isset($_GET['story_id'])){
    $story_id = $_GET['story_id'];
    $new_story_title = $_POST['new_story_title'];
    $new_story_body = $_POST['new_story_body'];
    
$edit_story = $mysqli->prepare("update stories set title=?, body=? where stories.story_id=?");
if(!$edit_story){
	printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

if(!hash_equals($_SESSION['token'], $_POST['token'])){
    die("Request forgery detected");
}
$story_id = $_SESSION['story_id'];

$edit_story->bind_param("ssi", $new_story_title, $new_story_body,$story_id);
$edit_story->execute();
 header('Location: http://ec2-18-222-144-80.us-east-2.compute.amazonaws.com/~nathankatz11/module3-group-module3-464106-455003/story.php?story_id='.$story_id.'');
$edit_story->close();
}
?>
