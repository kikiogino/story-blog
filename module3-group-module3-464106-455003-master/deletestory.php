
<?php
session_start();
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require 'database.php';

if(isset($_GET['story_id'])){
    $story_id = $_GET['story_id'];

$delete_comment = $mysqli->prepare("delete from comments where story_id=?");
if(!$delete_comment){
	printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
    
// if(!hash_equals($_SESSION['token'], $_POST['token'])){
//     die("Request forgery detected");
// }
$delete_comment->bind_param("i", $story_id);
$delete_comment->execute();
// header('Location: http://ec2-18-222-144-80.us-east-2.compute.amazonaws.com/~nathankatz11/module3-group-module3-464106-455003/story.php?story_id='.$story_id.'');
$delete_comment->close();


$delete_story = $mysqli->prepare("delete from stories where stories.story_id=?");
if(!$delete_story){
	printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
    

$delete_story->bind_param("i", $story_id);
$delete_story->execute();
header('Location: http://ec2-18-222-144-80.us-east-2.compute.amazonaws.com/~nathankatz11/module3-group-module3-464106-455003/index.php');
$delete_story->close();


}
?>
