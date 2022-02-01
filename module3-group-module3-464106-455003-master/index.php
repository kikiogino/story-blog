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
    <title>Home</title>
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
<div class="container my-5">
<h1> Sort </h1>
<form class="mx-2" action = "<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method = "POST" >
        <input class="ml-2" type = "radio" name ="sorter" value = "sortdate_asc">Date ascend
        <input class="ml-3" type = "radio" name ="sorter" value = "sortdate_desc">Date descend
        <input class="ml-3" type = "radio" name ="sorter" value = "sortuser_asc" >First name ascend
        <input class="ml-3" type = "radio" name ="sorter" value = "sortuser_desc" >First name descend
        <input class="ml-3" type = "radio" name ="sorter" value = "sortuser_l_asc" >Last name ascend
        <input class="ml-3" type = "radio" name ="sorter" value = "sortuser_l_desc" >Last name descend
        <input type="submit" class="btn btn-success" name="submit" value="Sort" />
    
    </form>
</div>
<!-- cards from: https://getbootstrap.com/docs/4.3/components/card/ -->
<div class="container">
    <?php
       
    require 'database.php';

    $get_story = $mysqli->prepare("select users.user_id, stories.story_id, stories.title, stories.body, stories.date, users.first_name, users.last_name 
          from stories join users on (users.user_id = stories.user_id) order by date desc");
    if(isset($_POST['sorter'])){
        if($_POST['sorter'] == "sortdate_asc"){
            $get_story = $mysqli->prepare("select users.user_id, stories.story_id, stories.title, stories.body, stories.date, users.first_name, users.last_name 
            from stories join users on (users.user_id = stories.user_id) order by date asc");
          }
        
          else if($_POST['sorter'] == 'sortuser_asc'){
            $get_story = $mysqli->prepare("select users.user_id, stories.story_id, stories.title, stories.body, stories.date, users.first_name, users.last_name 
            from stories join users on (users.user_id = stories.user_id) order by users.first_name asc");
          }
          else if($_POST['sorter'] == 'sortdate_desc'){
            $get_story = $mysqli->prepare("select users.user_id, stories.story_id, stories.title, stories.body, stories.date, users.first_name, users.last_name 
            from stories join users on (users.user_id = stories.user_id) order by date desc");
          }
          else if($_POST['sorter'] == 'sortuser_desc'){
            $get_story = $mysqli->prepare("select users.user_id, stories.story_id, stories.title, stories.body, stories.date, users.first_name, users.last_name 
            from stories join users on (users.user_id = stories.user_id) order by users.first_name desc");
          }
          else if($_POST['sorter'] == 'sortuser_l_asc'){
            $get_story = $mysqli->prepare("select users.user_id, stories.story_id, stories.title, stories.body, stories.date, users.first_name, users.last_name 
            from stories join users on (users.user_id = stories.user_id) order by users.last_name asc");
          }
          else if($_POST['sorter'] == 'sortuser_l_desc'){
            $get_story = $mysqli->prepare("select users.user_id, stories.story_id, stories.title, stories.body, stories.date, users.first_name, users.last_name 
            from stories join users on (users.user_id = stories.user_id) order by users.last_name desc");
          }
      
        
    }
    
    $get_story ->execute();
    $get_story->bind_result($user_id, $story_id, $title, $body, $date, $first_name, $last_name);
    //loop for getting all stories
    while ($get_story->fetch()){
        $link = 'http://ec2-18-222-144-80.us-east-2.compute.amazonaws.com/~nathankatz11/module3-group-module3-464106-455003/story.php?story_id='.$story_id;
        $caption = substr($body, 0, 200)."...";
        $delete_story_link = 'http://ec2-18-222-144-80.us-east-2.compute.amazonaws.com/~nathankatz11/module3-group-module3-464106-455003/deletestory.php?story_id='.$story_id;
        //find out if registered user or not
        if(isset($_SESSION["user_id"])){
          
            //If registered user, did they write story or not
            if($_SESSION["user_id"] == $user_id){
                //Yes writer, can view, delete or edit article
                //https://getbootstrap.com/docs/4.3/components/card/
                printf('<div class="card  my-5 text-center text-white bg-secondary mb-3">
                <div class="card-body">
                  <h5 class="card-title">%s</h5>
                  <p class="card-text">%s</p>
                  <p class="card-text">Posted on %s. By %s %s</p>
                  <a href="%s" class="btn btn-warning mx-5">Read/edit/delete article...</a>
                </div>
              </div>', $title, $caption,$date, $first_name, $last_name, $link);
            }
            //if not writer, but is registered, can only view and comment
            else{
                printf('<div class="card my-5 text-center text-white bg-dark mb-3">
                <div class="card-body">
                  <h5 class="card-title">%s</h5>
                  <p class="card-text">%s</p>
                  <p class="card-text">Posted on %s. By %s %s </p>
                  <a href="%s" class="btn btn-primary">Read Article...</a>
                </div>
              </div>', $title, $caption,$date, $first_name, $last_name, $link);
            }
        }
        //if not writer, can only view post and/or if registered comment
        else{
            printf('<div class="card my-5 text-center text-white bg-dark mb-3">
            <div class="card-body">
              <h5 class="card-title">%s</h5>
              <p class="card-text">%s</p>
              <br/>
              <p class="card-text">Posted on %s. By %s %s</p>
              <a href="%s" class="btn btn-primary">Read Article...</a>
            </div>
          </div>', $title, $caption,$date, $first_name, $last_name, $link);
        }
    }
    $get_story->close();
    ?>

</div>


</body>
</html>