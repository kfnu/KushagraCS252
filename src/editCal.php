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
    <title>Edit Calorie</title>
    <script>
        $(function() {
            $("#caldate").datepicker({changeMonth: true, changeYear: true, defaultDate: '1/1/2018', dateFormat: 'mm/dd/yy' });
        });
    </script>
    <style type="text/css">
        body {
            font-size: 15px;
            color: #000000;
            font-family: "segoe-ui", "open-sans", tahoma, arial;
            padding: 0;
        }
        table {
            margin: auto;
            font-family: "Lucida Sans Unicode", "Lucida Grande", "Segoe Ui";
            font-size: 12px;
        }
        h1 {
            margin: 25px auto 0;
            text-align: center;
            text-transform: uppercase;
            font-size: 17px;
        }
        table td {
            transition: all .5s;
        }
        /* Table */
        .data-table {
            border-collapse: collapse;
            font-size: 14px;
            min-width: 537px;
        }
        .data-table th,
        .data-table td {
            border: 1px solid #e1edff;
            padding: 7px 17px;
        }
        .data-table caption {
            margin: 7px;
        }
        /* Table Header */
        .data-table thead th {
            background-color: #83172e;
            color: #FFFFFF;
            border-color: #3B0B17 !important;
            /*text-transform: uppercase;*/
        }
        /* Table Body */
        .data-table tbody td {
            color: #353535;
        }
        .data-table tbody td:first-child,
        .data-table tbody td:nth-child(4),
        .data-table tbody td:last-child {
            text-align: right;
        }
        .data-table tbody tr:nth-child(odd) td {
            background-color: #ffdfdc;
        }
        .data-table tbody tr:nth-child(even) td {
            background-color: #fbf8ff;
        }
        .data-table tbody tr:hover td {
            background-color: #ffffa2;
            border-color: #ffff0f;
        }
        /* Table Footer */
        .data-table tfoot th {
            background-color: #e5f5ff;
            text-align: right;
        }
        .data-table tfoot th:first-child {
            text-align: left;
        }
        .data-table tbody td:empty
        {
            background-color: #ffcccc;
        }
    </style>
</head>

<body>
<!--<div ng-app="">
    <p>Name : <input type="text" ng-model="name"></p>
    <h1>Hello {{name}}</h1>
</div>-->

<div class="header">
    <?php include("headeruser.php");?>
</div>
<?php
$db_host = "us-cdbr-iron-east-05.cleardb.net";
$db_user = "bf980adbdc50be";
$db_pass = "867b73f4";
$db_name = "heroku_5945d53bb9e3d51";
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}
$sql = "SELECT Guid, DATE_FORMAT(userfood.mealdate, '%m/%d/%y') AS mealdate, meal, quantity, userfood.unit AS unit,
      userfood.calories AS mycalories, userfood.protein as myprotein,
      userfood.carbohydrate AS mycarb, userfood.fat AS myfat FROM userfood INNER JOIN fooditem
      ON userfood.itemid=fooditem.id WHERE(userfood.login='".$_SESSION['login']."');";
$query = mysqli_query($conn, $sql);
if (!$query) {
die ('SQL Database Error: ' . mysqli_error($conn));
}
?>

<h1 style="color: white">List of Food Items Consumed</h1>
<table class="data-table">
    <caption class="title"></caption>
    <thead>
    <tr>
        <th>Edit</th>
        <th>Date</th>
        <th>Meal</th>
        <th>Quantity</th>
        <th>Unit</th>
        <th>Calories</th>
        <th>Protein (g)</th>
        <th>Carbohydrate (g)</th>
        <th>Fat (g)</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $no 	= 1;
    $total 	= 0;
    while ($row = mysqli_fetch_array($query))
    {
        $amount  = $row['amount'] == 0 ? '' : number_format($row['amount']);
        echo '<tr>
					<td>'.'<a href='.'edituserfood.php?id='.$row['Guid'].'>Edit</a>'.'</td>
					<td align="center">'.$row['mealdate'].'</td>
					<td align="center">'.$row['meal'].'</td>
					<td align="right">'.$row['quantity'].'</td>
					<td align="left">'.$row['unit'].'</td>
					<td align="right">'.$row['mycalories'].'</td>
					<td align="right">'.$row['myprotein'].'</td>
					<td align="right">'.$row['mycarb'].'</td>
					<td align="right">'.$row['myfat'].'</td>
				</tr>';
        //$total += $row['amount'];
        //$no++;
    }?>
    </tbody>
    <!--<tfoot>
			<tr>
				<th colspan="4">TOTAL</th>
				<th><?/*=number_format($total)*/?></th>
			</tr>
		</tfoot>-->
</table>
<div class="login-page">
    <p style="color:white;font-family: Roboto, sans-serif; font-size: 14px;height:20px;">
        &nbsp;
    </p>
    
    <div style="width: 100%; text-align:center;">
        <span name="spMsg" style="color: #FFFFFF;"><?php echo $error; ?></span>
    </div>
    <div style="width: 100%; text-align:center;">
        <span name="spSuccessMsg" style="color: #FFFFFF;"><?php echo $successMsg; ?></span>
    </div>
</div>

</body>
</html>