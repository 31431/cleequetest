<?php
	session_start();
	include("groupFunction.php");
	include("main_ics_processer.php");
	$groupID = $_SESSION['groupID'];
	$usernameSession = $_SESSION['username'];


	echo 'username: '.$usernameSession;
	echo 'userID: '.gettingUserID($usernameSession);

	//requestToJoinGroup($groupID,$username);

	//header('Location: https://cleequetest.herokuapp.com/addmember.php');
?>