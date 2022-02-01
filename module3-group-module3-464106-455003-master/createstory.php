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
    <title>Create Story</title>
</head>
<body>
    <!-- <a class = "navlabel" href="recently-deleted.php">Recently Deleted</a> -->
  <?php
  if(!isset($_SESSION["user_id"])){
    header('Location: index.php');
  }
  ?>
<nav class="navbar navbar-dark bg-dark">
<a class="navbar-brand" href="index.php" >News Site</a>
  <a class = "navlabel" href="createstory.php">Add Story</a>
  <!-- <a class = "navlabel" href="recently-deleted.php">Recently Deleted</a> -->
  <a class = "navlabel" href="logout.php">Logout</a>
</nav>



<h1> Add Story </h1>

<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="POST">
<h2> Title: </h2> <p><input type="text" size="50"name="title" required/> </p>
<!-- <h2> Credit: <input type="text" name="credit" required> </h2> -->
<h2> Body: </h2><p><textarea type="text" rows="4" cols="100" name="body" placeholder="Add text"></textarea></p>
 <h2> Link: </h2><p><textarea type="text" rows="1" cols="100" name="link" placeholder="Add link"></textarea></p>

 <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" /> 
<input type="submit" value="Submit"/>
</form> 

<!-- story:
        -id
        -associated userid 
        -title
        -body/text
        -date published
         -->

         <?php
require 'database.php';
date_default_timezone_set('America/Chicago');
if(isset($_POST["title"]) && isset($_POST["body"]) && isset($_POST["link"]) ) {
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
$new_story = $mysqli->prepare("insert into stories (title, body, user_id,date, link) values (?,?,?,?,?)
        ");
          if(!$new_story){
            printf("Failed: %s \n", $mysqli->error);
                exit;
        }
        
        $new_story->bind_param('sssss', $title, $body, $user_id, $date, $link);
                $title = $_POST["title"];
                $body = $_POST["body"];
                $user_id = $_SESSION["user_id"];
                $date = date("Y-m-d g:i:s A");
                $link = $_POST["link"];

            $new_story->execute();

            $new_story->close();

            header("Location: http://ec2-18-222-144-80.us-east-2.compute.amazonaws.com/~nathankatz11/module3-group-module3-464106-455003/index.php");
    }
      
    
    
?>

</body>
</body>
</html>