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
    <link href="signup.css" rel="stylesheet">
    <title>SignUp</title>
</head>
<body>


<!-- //file:///Users/nathankatz/Downloads/bootstrap-4.0.0-alpha.6/docs/examples/signin/index.html -->
<div class="container">

      <form class="form-signin" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="POST">
        <h2 class="form-signin-heading">Sign Up</h2>
        <input type="text" class="form-control" placeholder="Name" name="first_name" required autofocus>
        <input type="text" class="form-control" placeholder="Last Name" name="last_name" required>
        <input type="text" class="form-control" placeholder="Username" name="username" required>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="pwd" required>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign Up</button>
        <br/>
        <a class="btn btn-lg btn-success btn-block" href="login.php">I have an account...Login</a>
      </form>

    </div> <!-- /container -->

<!-- <div id="login" class="col-xs-1 text-center">	
<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="POST">
<p>First Name: <input type="text" name="first_name" required></p>
    <p>Last Name: <input type="text" name="last_name" required></p>
    <p>Username: <input type="text" name="username" required></p>
    <p>Password: <input type="password" name="pwd" required></p>
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
	<input type="submit" value="Submit">
</form> -->
<?php
require 'database.php';
if(isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["username"]) && isset($_POST["pwd"])) {

$new_user = $mysqli->prepare("insert into users (first_name,last_name,username,pwd) values (?,?,?,?)
        ");
        if(!$new_user){
            printf("Failed: %s \n", $mysqli->error);
                exit;
        }
        if(!hash_equals($_SESSION['token'], $_POST['token'])){
            die("Request forgery detected");
        }
        $new_user->bind_param('ssss', $first_name, $last_name, $username, $pwd_hash);
                $first_name = $_POST["first_name"];
                $last_name = $_POST["last_name"];
                $username = $_POST["username"];
                $pwd_hash = password_hash($_POST["pwd"], PASSWORD_DEFAULT);

            $new_user->execute();
 
            $new_user->close();

            header("Location: http://ec2-18-222-144-80.us-east-2.compute.amazonaws.com/~nathankatz11/module3-group-module3-464106-455003/login.php");
        }

        
?>


</body>
</html>