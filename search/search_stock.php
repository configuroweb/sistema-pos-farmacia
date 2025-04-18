<?php
include '../database/connect.php';

$name = $_REQUEST['name'];
if ($name !== "") {
	
	$stmt = $dbh->query("SELECT * FROM tblproduct where product_name='$name'");
	$row = $stmt->fetch();
	$expirydate = $row["expirydate"];
	$stock = $row["qty"];
	$category = $row["category"];
	$productID = $row["productID"];

}
// Store it in a array
$result = array("$expirydate", "$stock","$category","$productID");
// Send in JSON encoded form
$myJSON = json_encode($result);
echo $myJSON;
?>
