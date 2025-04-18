
<?php
session_start();
error_reporting(1);
//include('connect2.php');
include('database/connect.php');
include('database/connect2.php');

$fullname = $_SESSION['login_fullname'];



//$stmt = $dbh->query("SELECT * FROM websiteinfo");
//$row_website = $stmt->fetch();
//$logo="home/".$row_website['logo'] ;
//$favicon="home/".$row_website['logo'] ;

//$email=$row_website['email'] ;
//$website_name=$row_website['schoolname'] ;
//$url=$row_website['website'] ;
//$phone1=$row_website['phone1'] ;
//$phone2=$row_website['phone2'] ;
//$address=$row_website['address'] ;



date_default_timezone_set('America/Bogota');
$current_date = date('Y-m-d H:i:s');
?> 
