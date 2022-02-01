<?php
session_start();
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Edit </title>
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
<a class="navbar-brand" href="index.php" >News Site</a>
  
  <!-- <a class = "navlabel" href="recently-deleted.php">Recently Deleted</a> -->
  <?php
  if(isset($_SESSION["user_id"])){
    printf('<a class = "navlabel" href="createstory.php">Add Story</a>');
    printf('<a class = "navlabel" href="logout.php">Logout</a>');
  }
  else{
    printf('<a class = "navlabel" href="signup.php">Create Account</a>');
  }
  ?>
</nav>
<h1 class="mx-5 mt-5">Edit Comment</h1>
<div class="card mx-5  px-2 py-2">
    
            <form name="edit_comment" action="<?php $comment_id = $_GET['comment_id']; echo 'http://ec2-18-222-144-80.us-east-2.compute.amazonaws.com/~nathankatz11/module3-group-module3-464106-455003/editcomment_function.php?comment_id='.$comment_id; ?>" method="POST">
                       
                        <p><textarea rows="1" cols="70" name="new_comment_title" placeholder="Comment Title" required><?php
require 'database.php';

$comment_id = $_GET['comment_id'];

$stmt = $mysqli->prepare("select comment_title from comments where comment_id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('i', $comment_id);

$stmt->execute();

$stmt->bind_result($comment_title);

while($stmt->fetch()){
	echo $comment_title;
}

$stmt->close();

?></textarea></p>
                        <p><textarea rows="3" cols="100" name="new_comment" placeholder="Comment Body" required><?php
require 'database.php';

$comment_id = $_GET['comment_id'];

$stmt = $mysqli->prepare("select comment from comments where comment_id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('i', $comment_id);

$stmt->execute();

$stmt->bind_result($comment);

while($stmt->fetch()){
	echo $comment;
}

$stmt->close();

?></textarea></p>
                            <p>
                            
                                <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                                <?php 
                                 if(!isset($_SESSION["user_id"])){
                                    printf("Sign Up to edit comment".'<br/>'.'<a class = "btn-sm btn-success" href="signup.php">Create Account</a>');
                                 }
                                 else{
                                    printf('<input type="submit" class="btn-lg  btn-success"value="Submit Changes" />');
                                 }
                                ?>
                                <!-- <input type="submit" class="btn-sm btn-success"value="Add Comment" /> -->
                            </p>
                        </form>
           </div>
</body>
</html>