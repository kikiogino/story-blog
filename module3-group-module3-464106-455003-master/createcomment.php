<?php
session_start();
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require 'database.php';
date_default_timezone_set('America/Chicago');




if(isset($_POST["comment"])&& isset($_POST["comment_title"])) {
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    

$new_comment = $mysqli->prepare("insert into comments (user_id, story_id,date, comment, comment_title) values (?,?,?,?,?)");
        if(!$new_comment){
            printf("Failed: %s \n", $mysqli->error);
                exit;
        }
        $user_id = $_SESSION["user_id"];
        $story_id = $_SESSION['story_id'];
        $date = date("Y-m-d g:i:s A");
        $comment = $_POST["comment"];
        $comment_title = $_POST["comment_title"]; 
    
        $new_comment->bind_param('iisss', $user_id, $story_id, $date, $comment, $comment_title);
                

        $new_comment->execute();

      

        header('Location: http://ec2-18-222-144-80.us-east-2.compute.amazonaws.com/~nathankatz11/module3-group-module3-464106-455003/story.php?story_id='.$story_id.'');
        $new_comment->close();
    }
      
    
?>