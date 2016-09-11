<?php
	session_start();
	$_POST['']
	$groupID = $_SESSION['groupID'];
	$username = $_SESSION['username'];
	include("groupFunction.php");
	include("main_ics_processer.php");


	foreach($_POST['userChosen'] as $key=>$userID){
		$username = gettingUsernameFromID($userID);
		changePendingToZero ($groupID,$username);
	}

	header('Location: https://cleequetest.herokuapp.com/addmember.php?groupNameSelected='.$groupID.'&submit=Go%21');
?>