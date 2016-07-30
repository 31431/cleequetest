<?php
	include('LightOpenID-master/openid.php');
	include("databaseconnection.php");
	$openid= new LightOpenID("https://cleequetest.herokuapp.com/nusnet.php");


function checkingUsernameExistInUserid($usernameInput){
	include("databaseconnection.php");
	$sql= "SELECT count(id) FROM userid WHERE username='$usernameInput'";
	$stmt = $database -> prepare($sql);
	$stmt->execute();
	$count = $stmt->fetchColumn();
	if($count!= 1){
		echo "No username found<br>";
		return 1;//exit();
	} else {
		return 0;
	}*/
}


	if($openid->mode){
		if($openid->mode == 'cancel'){
			echo "User has canceled authentication";
		} elseif ($openid->validate()){
			$data = $openid->getAttributes();
			$email = $data['contact/email'];
			$usernameInput = $data['namePerson/friendly'];
			$fullName = $data['namePerson'];
			print_r($data);
			echo "<br>Identity: $openid->identity <br>";
			session_start();
			$_SESSION['username'] = $usernameInput;
			$_SESSION['fullName'] = $fullName;
			$_SESSION['email']=$email;

			if(checkingUsernameExistInUserid($username)){
			$sql = "INSERT INTO userid(username, password, email, name)VALUES ('$usernameInput', 'NUSNET','$email', '$fullName')";
    		$database->exec($sql);
    		echo "Yeah"
    		;}

    		echo "Boo";
			header('Location:https://cleequetest.herokuapp.com/loginPage.php');
		} else {
			echo "The user hasn't logged in.";
		}
	} else {
		echo "Please login";
	}

?>

