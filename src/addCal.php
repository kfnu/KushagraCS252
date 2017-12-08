<?php
/**
 * Created by PhpStorm.
 * User: Kushagra
 * Date: 12/7/2017
 * Time: 2:15 AM
 */
session_start();
if(!isset($_SESSION["login"])){
    header("location: login.php");
}
//session_start(); // Starting Session
$error=''; // Variable To Store Error Message
$successMsg='';
//echo "username=".$_POST['username'];
if (isset($_POST['submit'])) {
    //echo "date = ".$_POST['caldate'];
    //echo "meal = ".$_POST['meal']."<br/>";
    //echo "item id = ".$_POST['item']."<br/>";
    //echo "quantity = ".$_POST['qua']."<br/>";
    if (empty($_POST['caldate']) || empty($_POST['meal']) || empty($_POST['qua']) || empty($_POST['item'])) {
        $error = "Date or Meal or Item or Quantity is invalid";
    }
    else
    {
// Define $username and $password
        $dateArray = explode("/",$_POST['caldate']);
        //$date = new DateTime("'".$dateArray[2].'-'.$dateArray[1].'-'.$dateArray[0]."'");
        $mydate = mktime(0, 0, 0, $dateArray[1], $dateArray[0], $dateArray[2]);
        $date = date('Y-m-d h:i:s',$mydate);
        //echo "mealdate=".$date;//.date("Y", $mydate) . "-" . date("m", $mydate). "-" . date("d", $mydate);;//$dateArray[2].'-'.$dateArray[1].'-'.$dateArray[0];
        $meal=$_POST['meal'];
        $item=$_POST['item'];
        $qua=$_POST['qua'];
        //require_once("model/config.php");
        $db_host = "us-cdbr-iron-east-05.cleardb.net";
        $db_user = "bf980adbdc50be";
        $db_pass = "867b73f4";
        $db_name = "heroku_5945d53bb9e3d51";
        $connection = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
        if (!$connection)
        {
            echo "Could not connect to database : ".$db_name." - ".mysqli_errno($connection)." - ".mysqli_error($connection);
            die('Database error');
        }//else{
        //echo "Connected to database : ";
        //}
        $query = "SELECT * FROM fooditem WHERE (id=$item);";
        $result = mysqli_query($connection,$query);
        if($result) {
            $rows = mysqli_num_rows($result);
            if ($rows == 1) {
                $row=mysqli_fetch_array($result);
                $unit=$row['unit'];
                $foodamount=$row['amount'];
                $foodprotein=$row['protein'];
                $foodcarb=$row['carbohydrate'];
                $foodfat=$row['fat'];
                $foodcalories=$row['calories'];
                $factor = floatval($qua)/floatval($foodamount);
                //echo "factor=".$factor."<br/>";
                $calcprotein=$factor*floatval($foodprotein);
                $calccarb=$factor*floatval($foodcarb);
                $calcfat=$factor*floatval($foodfat);
                $calccalories=$factor*floatval($foodcalories);
                $query = "INSERT INTO userfood (Guid, login, itemid, mealdate, meal, quantity, unit, calories, protein, carbohydrate, fat)
                  VALUES(uuid(), '".("".$_SESSION['login'])."', ".$item.", '".$date."', '".$_POST['meal']."', ".
                    $qua.", '".$unit."', ".$calccalories.", ".$calcprotein.", ".$calccarb.", ".$calcfat.");";
                $result = mysqli_query($connection,$query);
                if($result) {
                    //$_SESSION['login'] = $username; // Initializing Session
                    //header("location: login.php"); // Redirecting To Other Page
                    $successMsg = "Data saved";
                }else{
                    echo $query."<br/>";
                    echo "Sql query failed : ".$db_name." - ".mysqli_errno($connection)." - ".mysqli_error($connection);
                }
            }
        }
        mysqli_close($connection); // Closing Connection
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
    <link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"
          rel = "stylesheet">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="http://code.jquery.com/jquery-3.2.1.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/calorie.css">
    <title>Add Calorie</title>
    <script>
        $(function() {
            $("#caldate").datepicker({changeMonth: true, changeYear: true, defaultDate: new Date(), dateFormat: 'mm/dd/yy' });
        });
    </script>
</head>

<body>
<!--<div ng-app="">
    <p>Name : <input type="text" ng-model="name"></p>
    <h1>Hello {{name}}</h1>
</div>-->

<div class="header">
    <?php include("headeruser.php");?>
</div>

<div class="login-page">
    <p style="color:white;font-family: Roboto, sans-serif; font-size: 14px;height:20px;">
        &nbsp;
    </p>


    <div class="form">


        <form class="login-page" method="post"> <!--<div class = "datepickerdemo" ng-controller = "dateController as ctrl"
                 layout = "column" ng-cloak>-->
            <table style="align: center;">
                <tr>
                    <td style="align-content: center">
                        <select name="meal" value="<?php echo $_POST["meal"]; ?>">
                            <option value="">Select...</option>
                            <option value="B">Breakfast</option>
                            <option value="L">Lunch</option>
                            <option value="D">Dinner</option>
                            <option value="O">Other</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>
                        <?php
                        $db_host = "us-cdbr-iron-east-05.cleardb.net";
                        $db_user = "bf980adbdc50be";
                        $db_pass = "867b73f4";
                        $db_name = "heroku_5945d53bb9e3d51";
                        $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
                        $query = "SELECT id, CONCAT(item, ' (',unit,')') AS itemMod FROM fooditem ORDER By item ASC;";
                        //$result = conn($query);
                        //if (($result)||(mysql_errno == 0))
                        $result = mysqli_query($conn,$query);
                        //$result = $mysqli->query($query);
                        if ($result) {
                            echo "<select name='item' id='item' value=".$_POST["item"].">";
                            echo "<option value='" . "0" . "' selected=\"selected\"'>" . "Select" . "</option>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<option value='" . $row['id'] . "'>" . $row['itemMod'] . "</option>";
                            }
                            echo "</select>";
                        }
                        mysqli_close($conn);
                        ?>
                    </td>
                </tr>
                    <tr>
                    <td>
                        <input type="text" style='width:108px;background-color: #fffbcc;' name="caldate" value="<?=$_POST['caldate']?>" id="caldate">&nbsp;(mm/dd/yyyy)
                    </td>
                </tr>
                <tr>
                    <td>
                        <input style="border-color: #3B0B17; border-width: thick;" type="text" name="qua" value="<?php echo $_POST["qua"]; ?>" placeholder="quantity"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input name="submit" type="submit" value=" Add ">
                    </td>
                </tr>

                <!--</br></br>-->



            </table>

        </form>
        <!--<input type="text" name="username" placeholder="name"/>
        <input type="password" name="password" placeholder="password"/>
        <input type="text" name="email" placeholder="email address"/>
        <input name="submit" type="submit" value=" Create ">
        <p class="message">Already registered? <a href="login.php">Sign In</a></p>
    </form>
    <!--<form class="login-form">
        <input type="text" placeholder="username"/>
        <input type="password" placeholder="password"/>
        <button>login</button>
        <p class="message">Not registered? <a href="#">Create an account</a></p>
    </form>-->
    </div>
    <div style="width: 100%; text-align:center;">
        <span name="spMsg" style="color: #FFFFFF;"><?php echo $error; ?></span>
    </div>
    <div style="width: 100%; text-align:center;">
        <span name="spSuccessMsg" style="color: #FFFFFF;"><?php echo $successMsg; ?></span>
    </div>
</div>

</body>
</html>