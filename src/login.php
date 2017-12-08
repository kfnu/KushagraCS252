<?php
/**
 * Created by PhpStorm.
 * User: Kushagra
 * Date: 12/7/2017
 * Time: 3:54 AM
 */
session_start(); // Starting Session
$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $error = "Username or Password is invalid";
    }
    else
    {
        $username=$_POST['username'];
        $password=$_POST['password'];
        //echo $username;
        //Selecting Database
        //require_once("model/config.php");
        $db_host = "us-cdbr-iron-east-05.cleardb.net";
        $db_user = "bf980adbdc50be";
        $db_pass = "867b73f4";
        $db_name = "heroku_5945d53bb9e3d51";
        //echo $db_host;
        $connection = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
        //echo $username;
        if (!$connection)
        {
            echo "Could not connect to database : ".$db_name." - ".mysqli_errno($connection)." - ".mysqli_error($connection);
            die('Database error');
        }
// To protect MySQL injection for Security purpose
        $username = stripslashes($username);
        $password = stripslashes($password);
        //echo $username;
        //$username = mysql_real_escape_string($username);
        //$password = mysql_real_escape_string($password);
        //echo $username;
// SQL query to fetch information of registerd users and finds user match.
        $query = "select * from webuser where (pwd='$password' AND login='$username');";
        $result = mysqli_query($connection,$query);
        if($result) {
            $rows = mysqli_num_rows($result);
            if ($rows == 1) {
                //echo "qpwoeipqowe";
                $_SESSION['login'] = $username; // Initializing Session
                //echo $username." ".$_SESSION['login'];
                //session_regenerate_id(true);
                header("location: foodlist.php"); // Redirecting To Other Page
                //exit();
                //die();
                exit;
            } else {
                $error = "Username or Password is invalid";
            }
        }else{
            //echo $query."<br/>";
            echo "Sql query failed : ".$db_name." - ".mysqli_errno($connection)." - ".mysqli_error($connection);
        }
        mysqli_close($connection); // Closing Connection
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/login.css">
    <script src="js/login.js"></script>
    <title>Login</title>
</head>
<body>
<div class="header">
    <?php include("headerguest.php");?>
</div>
<div class="login-page">
    <p style="color:white;font-family: Roboto, sans-serif; font-size: 14px;height:20px;">
        &nbsp;
    </p>
    <div class="form">
        <!--<form class="register-form">
            <input type="text" placeholder="name"/>
            <input type="password" placeholder="password"/>
            <input type="text" placeholder="email address"/>
            <button>create</button>
            <p class="message">Already registered? <a href="#">Sign In</a></p>
        </form>-->
        <form class="login-form" method="post" action="">
            <input type="text" name="username" value="<?php echo $_POST["username"]; ?>" placeholder="username"/>
            <input type="password" name="password" placeholder="password"/>
            <input name="submit" type="submit" value=" Login ">
            <p class="message">Not registered? <a href="createaccount.php">Create an account</a></p>
        </form>
    </div>
    <div style="width: 100%; text-align:center;">
        <span name="spMsg" style="color: #FFFFFF;"><?php echo $error; ?></span>
    </div>
</div>

</body>
</html>