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
    <title>Story</title>
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
    <?php
    require 'database.php';

    $story_id = $_GET['story_id'];
    $_SESSION["story_id"] = $story_id ;

//function to update like count:
if(isset($_POST['like'])){
    $_SESSION["story_id"] = $_POST["story_id"] ;
    $update = $_POST["like_count"] +1 ;
    $stmt = $mysqli->prepare("update stories set like_count = ? where story_id = ?");

    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    
    $stmt->bind_param('ii', $update, $story_id);
    $stmt->execute();

    $stmt->close();
}

$stmt = $mysqli->prepare("select users.first_name, users.last_name, users.user_id, stories.story_id, stories.date, stories.title, stories.body, stories.link, stories.like_count from stories join users on (users.user_id = stories.user_id) where story_id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('i', $story_id);
$stmt->execute();

$stmt->bind_result($first_name, $last_name, $user_id, $story_id, $date, $title, $body, $link, $like_count);


// echo "<ul>\n";
while($stmt->fetch()){
 //find out if registered user or not
 $edit_story_link = 'http://ec2-18-222-144-80.us-east-2.compute.amazonaws.com/~nathankatz11/module3-group-module3-464106-455003/editstory.php?story_id='.$story_id;
 $delete_story_link = 'http://ec2-18-222-144-80.us-east-2.compute.amazonaws.com/~nathankatz11/module3-group-module3-464106-455003/deletestory.php?story_id='.$story_id;
 if(isset($_SESSION["user_id"])){
    //If registered user, did they write story or not
    if($_SESSION["user_id"] == $user_id){
        //Yes writer, can view, delete or edit article
        //https://getbootstrap.com/docs/4.3/components/card/
     printf('<div class="card mx-5 my-5 py-3">
            <div class="card-body">
            <h1 class="card-title py-2">%s</h1>
            <h6 class="card-subtitle mb-2 text-muted py-2">By %s %s. Created on %s</h6>
            <p class="card-text">%s</p>
            <a href="%s" class="btn-sm btn-success" target="_blank">View Original Link</a>
            <a href="%s" class="btn-sm btn-warning ml-5" target="_blank">Edit Story</a>
            <a href="%s" class="btn-sm btn-danger mx-5">Delete Story</a>
            </div>
        </div>', $title, $first_name, $last_name, $date, $body, $link, $edit_story_link, $delete_story_link);
            }
         else{
            printf('<div class="card mx-5 my-5 py-3">
            <div class="card-body">
            <h1 class="card-title py-2">%s</h1>
            <h6 class="card-subtitle mb-2 text-muted py-2">By %s %s. Created on %s</h6>
            <p class="card-text">%s</p>
            <a href="%s" class="btn-sm btn-success" target="_blank">View Original Link</a>
            </div>
        </div>', $title, $first_name, $last_name, $date, $body, $link);
            }
     
 }
 else{
    printf('<div class="card mx-5 my-5 py-3">
    <div class="card-body">
    <h1 class="card-title py-2">%s</h1>
    <h6 class="card-subtitle mb-2 text-muted py-2">By %s %s. Created on %s</h6>
    <p class="card-text">%s</p>
    <a href="%s" class="btn-sm btn-success" target="_blank">View Original Link</a>
    </div>
</div>', $title, $first_name, $last_name, $date, $body, $link);
 } 
}
$_SESSION['like_count'] = $like_count;
$stmt->close();
?>

<div class="mx-5 my-3">
<form action = "<?php $story_id = $_GET['story_id']; echo 'http://ec2-18-222-144-80.us-east-2.compute.amazonaws.com/~nathankatz11/module3-group-module3-464106-455003/story.php?story_id='.$story_id; ?>" method = "POST">
<input type = "hidden" name = "like_count" value = "<?php  echo $_SESSION['like_count']; ?>">
<?php  echo $_SESSION['like_count']; ?>
<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
<input type = "hidden" value = "<?php  echo $_SESSION['story_id']; ?>" name = "story_id" />
<input class="btn-lg  btn-success" type = "submit" value="like" name = "like" >
</form>
</div>


<h1 class="mx-5"> Comments </h1>           

<?php
require 'database.php';

$load_comment = $mysqli->prepare("select users.user_id, users.first_name, users.last_name,comments.comment_id, comments.comment, date, comments.comment_title, comments.story_id from comments join users on (users.user_id = comments.user_id) where story_id=? order by date desc");
if(!$load_comment){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$load_comment->bind_param("i", $story_id);

$load_comment->execute();
$load_comment->bind_result($user_id, $first_name, $last_name,$comment_id,$comment,$date, $comment_title, $story_id);


while($load_comment->fetch()){
    $delete_comment_link = 'http://ec2-18-222-144-80.us-east-2.compute.amazonaws.com/~nathankatz11/module3-group-module3-464106-455003/deletecomment.php?comment_id='.$comment_id;
    $edit_comment_link = 'http://ec2-18-222-144-80.us-east-2.compute.amazonaws.com/~nathankatz11/module3-group-module3-464106-455003/editcomment.php?comment_id='.$comment_id;
    //find out if registered user or not
    if(isset($_SESSION["user_id"])){

//    echo"works";
       //If registered user, did they write story or not
       if($_SESSION["user_id"] == $user_id){
           //Yes writer, can view, delete or edit article
           //https://getbootstrap.com/docs/4.3/components/card/
        printf('<div class="card mx-5 my-1">
               <div class="card-body">
               <h4 class="card-title">%s</h4>
               <h6 class="card-subtitle mb-2 text-muted">By %s %s. Posted on %s</h6>
               <p class="card-text">%s</p>
               
               <a href="%s" class="btn-sm btn-warning" target="_blank">Edit Comment</a>
               <a href="%s" class="btn-sm btn-danger mx-5">Delete Comment</a>
               </div>
           </div>', $comment_title, $first_name, $last_name, $date, $comment, $edit_comment_link,$delete_comment_link);
               }
            else{
               printf('<div class="card mx-5 my-1">
               <div class="card-body">
               <h4 class="card-title ">%s</h4>
               <h6 class="card-subtitle mb-2 text-muted">By %s %s. Created on %s</h6>
               <p class="card-text">%s</p>
               </div>
           </div>', $comment_title, $first_name, $last_name, $date, $comment);
               }
        
    }
    else{
       printf('<div class="card mx-5 my-3">
       <div class="card-body">
       <h4 class="card-title ">%s</h4>
       <h6 class="card-subtitle mb-2 text-muted">By %s %s. Created on %s</h6>
       <p class="card-text">%s</p>
       </div>
   </div>', $comment_title, $first_name, $last_name, $date, $comment);
    }
   }
   $load_comment->close();
?>
 <h1 class="mx-5 mt-5">Add Comment</h1>
<div class="card mx-5  px-2 py-2">
            <form name="add_comment" action="createcomment.php" method="POST">
                       
                        <p><textarea rows="1" cols="70" name="comment_title" placeholder="Comment Title" required></textarea></p>
                        <p><textarea rows="3" cols="100" name="comment" placeholder="Comment Body" required></textarea></p>
                            <p>
                            
                                <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                                <?php 
                                 if(!isset($_SESSION["user_id"])){
                                    printf("Sign Up to post comment".'<br/>'.'<a class = "btn-sm btn-success" href="signup.php">Create Account</a>');
                                 }
                                 else{
                                    printf('<input type="submit" class="btn-lg  btn-success"value="Add Comment" />');
                                 }
                                ?>
                                <!-- <input type="submit" class="btn-sm btn-success"value="Add Comment" /> -->
                            </p>
                        </form>
           </div>'

      

</body>
</html>