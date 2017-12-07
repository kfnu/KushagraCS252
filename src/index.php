<?php
/**
 * Created by PhpStorm.
 * User: kushagra
 * Date: 12/6/17
 * Time: 11:39 PM
 */

//echo "Hello?";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/guest.css">
    <script src="js/login.js"></script>
    <title>Homepage</title>
</head>
<body>
<div class="header">
    <?php include("headerguest.php");?>
</div>
<div class="login-page">
    <p style="color:white;font-family: Roboto, sans-serif; font-size: 14px;">
        Welcome to Calorie Counter...
    </p>
    <div class="form">
        <!--<form class="register-form">
            <input type="text" placeholder="name"/>
            <input type="password" placeholder="password"/>
            <input type="text" placeholder="email address"/>
            <button>create</button>
            <p class="message">Already registered? <a href="#">Sign In</a></p>
        </form>-->
        <form class="login-form">
     <!--       <input type="text" placeholder="username"/>
            <input type="password" placeholder="password"/>-->
            <button><a href="login.html"><span  style="font-family: 'American Typewriter'" size="9px">Login</span></a></button>
            <br/><br/>
            <button><a href="createaccount.html"><span style="font-family: 'American Typewriter'" size="9px">Create Account</span></a></button>
            <br/><br/>
            <button><a href="contact.php"><span style="font-family: 'American Typewriter'" size="9px">Contact</span></a></button>
            <!--<p class="message">Not registered? <a href="createaccount.html">Create an account</a></p>-->
        </form>
    </div>
</div>

</body>
</html>