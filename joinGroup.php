<?php
	session_start();
	$_POST['']
	$groupID = $_SESSION['groupID'];
	$username = $_SESSION['username'];
	include("groupFunction.php");
	include("main_ics_processer.php");

	requestToJoinGroup($groupID,$username);

	echo json_encode('message'=>'ok');
?>