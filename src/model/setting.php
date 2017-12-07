<?php
/**
 * Created by PhpStorm.
 * User: Kushagra
 * Date: 12/3/2017
 * Time: 5:13 PM
 */

//General Settings
//--------------------------------------------------------------------------
date_default_timezone_set('America/Indiana/Indianapolis');

//Database Information
$dbtype = "mysql";
$db_host = "us-cdbr-iron-east-05.cleardb.net";
$db_user = "bf980adbdc50be";
$db_pass = "867b73f4";
$db_name = "heroku_5945d53bb9e3d51";
$db_port = "3306";
$db_table_prefix = "";

$sms=true;

$langauge = "en";

//Generic website variables
$websiteName = "Calorie Counter";
$websiteUrl = "http://cc.kushbiz.com"; //including trailing slash

//Do you wish Calorie Counter to send out emails for confirmation of registration?
//We recommend this be set to true to prevent spam bots.
//False = instant activation
//If this variable is falses the resend-activation file not work.
$emailActivation = false;

//In hours, how long before Calorie Counter will allow a user to request another account activation email
//Set to 0 to remove threshold
$resend_activation_threshold = 1;

//Tagged onto our outgoing emails
$emailAddress = "kfnu@purdue.edu";

//Date format used on email's
$emailDate = date("l \\t\h\e jS");

//Directory where txt files are stored for the email templates.
$mail_templates_dir = "models/mail-templates/";

$default_hooks = array("#WEBSITENAME#","#WEBSITEURL#","#DATE#");
$default_replace = array($websiteName,$websiteUrl,$emailDate);

//Display explicit error messages?
$debug_mode = false;

//---------------------------------------------------------------------------
?>