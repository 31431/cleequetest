<?php
	include('databaseconnection.php');
	$sql= "SELECT * FROM groupmember WHERE userID= '$userID' "; 
	$stmt = $database->prepare($sql);
	$stmt->execute();
	$groupArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
	print_r($groupArray);
?>