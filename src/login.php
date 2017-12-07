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

        //Selecting Database

        require_once("model/config.php");
        $connection = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
        if (!$connection)
        {
            echo "Could not connect to database : ".$db_name." - ".mysqli_errno($connection)." - ".mysqli_error($connection);
            die('Database error');
        }
// To protect MySQL injection for Security purpose

        $username = stripslashes($username);
        $password = stripslashes($password);
        $username = mysql_real_escape_string($username);
        $password = mysql_real_escape_string($password);

// SQL query to fetch information of registerd users and finds user match.
        $query = "select * from webuser where (pwd='$password' AND login='$username');";
        $result = mysqli_query($connection,$query);
        if($result) {
            $rows = mysqli_num_rows($result);

            if ($rows == 1) {
                //$_SESSION['login'] = $username; // Initializing Session
                header("location: /src/foodlist.html"); // Redirecting To Other Page
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
        <form class="login-form" method="post">
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