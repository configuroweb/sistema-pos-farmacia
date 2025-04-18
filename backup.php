<?php
include 'topbar.php'; 
	//include our function
	include 'function_backup.php';

	if(isset($_POST['btnbackup'])){
		//get credentails via post
		$servername_db = $_POST['txtservername'];
		$username_db = $_POST['txtusername'];
		$password_db = $_POST['txtpassword'];
		$dbname_db = $_POST['txtdbname'];

		//backup and dl using our function
		backDb($servername_db, $username_db, $password_db, $dbname_db);

		exit();





$task= $fullname.' '.'Backup database'.' '. 'On' . ' '.$current_date;
$query2 = "INSERT into `activity_log` (task)
VALUES ('$task')";
$result2 = mysqli_query($conn,$query2);

$_SESSION['success'] ="Database backup successfully";

}
?>
