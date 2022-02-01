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
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="login.css" rel="stylesheet">
</head> 
<body>
    

<!-- <div id="login" class="col-xs-1 text-center">	
<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="POST">
	<p>Username: <input type="text" name="username" required></p>
    <p>Password: <input type="password" name="password" required></p>
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
	<input type="submit" value="Submit">
</form>
<div class="container"> -->

      <form class="form-signin" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="POST">
        <h2 class="form-signin-heading">Login</h2>
        <input type="text" class="form-control" placeholder="Username" name="username" required/>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="password" required/>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        <br/>
        <a class="btn btn-lg btn-success btn-block" href="signup.php">Sign Up</a>
      </form>


<?php
require 'database.php';

if(isset($_POST['username']) && isset($_POST['password'])){
    
        $username=$_POST['username'];
        $password=$_POST['password'];
        $stmt = $mysqli->prepare("select user_id, pwd from users where username=?");

        if(!$stmt){
	        printf("Query Prep Failed: %s\n", $mysqli->error);
        	exit;
        }


$stmt->bind_param('s', $username);
$stmt->execute();

$stmt->bind_result($user_id, $password_hash);
$stmt->fetch();


if(password_verify($password, $password_hash)){

    $_SESSION["username"] = $username;
    $_SESSION["user_id"] = $user_id;
    $_SESSION['token'] = bin2hex(random_bytes(32));

    header("Location: http://ec2-18-222-144-80.us-east-2.compute.amazonaws.com/~nathankatz11/module3-group-module3-464106-455003/index.php");
}
else{
    echo "<a class='btn btn-lg btn-success' href='signup.php'>Sign Up</a> No account found <br/> or <br/> <a class='btn btn-sm btn-warning' href='index.php'>View site without registering</a>";
}

$stmt->close();
}
?>

</body>
</html>