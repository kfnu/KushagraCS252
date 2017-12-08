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
  <p style="color:white;font-family: Roboto, sans-serif; font-size: 16px;">
    Welcome to Calorie Counter. This project is made by Kushagra for CS252 Lab6. This web app is used to track calorie intake by different users assigned for different dates based on various food items saved in a MySQL Database.
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
      <button><span  style="font-family: 'American Typewriter'" size="9px"><a href="login.php">Login</a></span></button>
      <br/><br/>
      <button><span style="font-family: 'American Typewriter'" size="9px"><a href="createaccount.php">Create Account</a></span></button>
      <br/><br/>
      <button><span style="font-family: 'American Typewriter'" size="9px"><a href="contact.html">Contact</a></span></button>
      <!--<p class="message">Not registered? <a href="createaccount.html">Create an account</a></p>-->
    </form>
  </div>
</div>

</body>
</html>