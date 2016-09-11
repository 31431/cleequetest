<?php
	session_start();
	include("groupFunction.php");
	include("main_ics_processer.php");
	$groupID = $_SESSION['groupID'];
	$usernameSession = $_SESSION['username'];

	requestToJoinGroup($groupID,$username);

	header('Location: https://cleequetest.herokuapp.com/addmember.php');
?>