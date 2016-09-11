<?php
	session_start();
	$groupID = $_SESSION['groupID'];
	$username = $_SESSION['username'];
	include("groupFunction.php");
	include("main_ics_processer.php");

	requestToJoinGroup($groupID,$username);

	header('Location: https://cleequetest.herokuapp.com/addmember.php?groupNameSelected='.$groupID.'&submit=Go%21');
	echo json_encode('message'=>'ok');
?>