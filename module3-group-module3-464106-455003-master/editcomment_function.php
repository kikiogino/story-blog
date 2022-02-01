<?php
session_start();
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require 'database.php';

if(isset($_GET['comment_id'])){
    $comment_id = $_GET['comment_id'];
    $new_comment = $_POST['new_comment'];
    $new_comment_title = $_POST['new_comment_title'];
    
$edit_comment = $mysqli->prepare("update comments set comment=?, comment_title=? where comments.comment_id=?");
if(!$edit_comment){
	printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
if(!hash_equals($_SESSION['token'], $_POST['token'])){
    die("Request forgery detected");
}

$story_id = $_SESSION['story_id'];


$edit_comment->bind_param("ssi", $new_comment, $new_comment_title,$comment_id);
$edit_comment->execute();
 header('Location: http://ec2-18-222-144-80.us-east-2.compute.amazonaws.com/~nathankatz11/module3-group-module3-464106-455003/story.php?story_id='.$story_id.'');
$edit_comment->close();

}
?>
