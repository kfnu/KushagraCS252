<?php
/**
 * Created by PhpStorm.
 * User: kushagra
 * Date: 12/8/17
 * Time: 11:59 AM
 */

session_start();
//echo "".$_SESSION['login'];
if(!isset($_SESSION['login'])){
    header("location: login.php");
    //echo "hi";
}
$db_host = "us-cdbr-iron-east-05.cleardb.net";
$db_user = "bf980adbdc50be";
$db_pass = "867b73f4";
$db_name = "heroku_5945d53bb9e3d51";
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
    die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}
$sql = "SELECT Guid, DATE_FORMAT(userfood.mealdate, '%m/%d/%y') AS mealdate,
      SUM(userfood.calories) AS mycalories, SUM(userfood.protein) as myprotein,
      SUM(userfood.carbohydrate) AS mycarb, SUM(userfood.fat) AS myfat FROM userfood INNER JOIN fooditem
      ON userfood.itemid=fooditem.id WHERE(userfood.login='".$_SESSION['login']."') GROUP BY mealdate;";
$query = mysqli_query($conn, $sql);
if (!$query) {
    die ('SQL Database Error: ' . mysqli_error($conn));
}
?>
<html>
<head>
    <link rel="stylesheet" href="css/calorie.css">
    <title>Report</title>
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
<div class="header">
    <?php include("headeruser.php");?>
</div>
<h1 style="color: white">Date-wise Calorie Intake</h1>
<table class="data-table">
    <caption class="title"></caption>
    <thead>
    <tr>
        <th>Date</th>
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
					<td align="center">'.$row['mealdate'].'</td>	
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
</body>
</html>