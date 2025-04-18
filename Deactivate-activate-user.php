<?php
include('topbar.php');
if(empty($_SESSION['login_email']))
    {   
      header("Location: login.php"); 
    }
    else{
    $email = $_SESSION["login_email"];

//save activity log details
$task= 'Activated/Deacivated user'.' '. 'On' . ' '.$current_date;
$sql = 'INSERT INTO activity_log(task) VALUES(:task)';
$statement = $dbh->prepare($sql);
$statement->execute([
':task' => $task
]);


// for activate user   	
if(isset($_GET['id']))
{
$id=intval($_GET['id']);

$sql = "UPDATE users SET status=? where ID=?";
$stmt= $dbh->prepare($sql);
$stmt->execute(["1", $id]);
header("location: user-record.php");
}

// for Deactivate user
if(isset($_GET['did']))
{
$did=intval($_GET['did']);

$sql = "UPDATE users SET status=? where ID=?";
$stmt= $dbh->prepare($sql);
$stmt->execute(["0", $did]);
header("location: user-record.php");


}
}
?>