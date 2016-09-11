<?php
	session_start();
	$groupID = $_SESSION['groupID'];
	$username = $_SESSION['username'];
	include("groupFunction.php");
	include("main_ics_processer.php");

	echo $username;
	echo gettingUserID($username);

	//requestToJoinGroup($groupID,$username);

	//header('Location: https://cleequetest.herokuapp.com/addmember.php');
?>