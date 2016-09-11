<?php
//Connecting to SQL database.
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);
	$location = null;
	$error = false;
	
	try {
    $database = new PDO("mysql:host=$server;dbname=$db", $username, $password);
    // set the PDO error mode to exception
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
   			print($e->getMessage());
	}

function DBprocessing($sql){
	include('databaseconnection.php');
	$stmt =  $database->prepare($sql);
	$stmt -> execute();
}

function addingGroupMember($groupID, $usernameSession){
	include("databaseconnection.php");
	$userID = gettingUserID($usernameSession);
	//echo "UserID is $userID<br>";
	$sqlInsertingGroupMember = "INSERT INTO groupmember(groupID, userID, pending) VALUES ('$groupID', '$userID','0')";
	//echo "Username is $usernameSession <br> inside addingGroupMember<br>";
	$database->exec($sqlInsertingGroupMember);
}

function gettingUserID($usernameSession){
	include("databaseconnection.php");
	$sqlUserID= "SELECT id FROM userid WHERE username = '$usernameSession'";
	$stmtGetUserID= $database->prepare($sqlUserID);
	$stmtGetUserID->execute();
	return $stmtGetUserID->fetchColumn();
}

function gettingUsernameFromID($userID){
	include("databaseconnection.php");
	$sql= "SELECT username FROM userid WHERE id = '$userID'";
	$stmt= $database->prepare($sql);
	$stmt->execute();
	return $stmt->fetchColumn();
}

function gettingGroupID($groupName){
	include("databaseconnection.php");
	$sqlUserID= "SELECT id FROM groupid WHERE groupName = '$groupName'";
	$stmtGetUserID= $database->prepare($sqlUserID);
	$stmtGetUserID->execute();
	return $stmtGetUserID->fetchColumn();
}

function gettingGroupNameFromID($groupID){
	include("databaseconnection.php");
	$sql = "SELECT groupName FROM groupid WHERE id = '$groupID'";
	$stmt = $database->prepare($sql);
	$stmt->execute();
	return $stmt->fetchColumn();
}

function gettingNameFromUsername($usernameInput){
	include("databaseconnection.php");
	$sql = "SELECT name FROM userid WHERE username = '$usernameInput'";
	$stmt = $database->prepare($sql);
	$stmt->execute();
	return $stmt->fetchColumn();
}

//Function to create group
function creatingGroup($usernameSession, $groupName){
	include("databaseconnection.php");
	$sql= "INSERT INTO groupid( groupName) VALUES ( '$groupName')";
	//$stmt = $database->prepare($sql);
	$database->exec($sql);
	$lastgroupid = $database->lastInsertID();
	$_SESSION['groupID']=$lastgroupid;
	addingGroupMember($lastgroupid,$usernameSession);
	echo "<h1>'$groupName' has been created successfully!<br></h1>";
}

//function to check whether the username alr exists 
function checkingUsernameExistInUserid($usernameInput){
	include("databaseconnection.php");
	$sql= "SELECT count(id) FROM userid WHERE username='$usernameInput'";
	$stmt = $database -> prepare($sql);
	$stmt->execute();
	$count = $stmt->fetchColumn();
	if( $count != 1){
		return 1;//exist;
	} else {
		return 0;
	}
}

function checkingUsernameExistInGroup($groupID,$usernameInput){
	include("databaseconnection.php");
	$userID= gettingUserID($usernameInput);
	$sql1= "SELECT count(userid) FROM groupmember WHERE userID='$userID' AND groupID='$groupID' AND pending ='1' "; // For user pending approval.
	$sql0= "SELECT count(userid) FROM groupmember WHERE userID='$userID' AND groupID='$groupID' AND pending ='0' "; // For user alr inside.
	//For $sql1
	$stmt = $database -> prepare($sql1);
	$stmt->execute();
	$count1 = $stmt->fetchColumn();
	//For $sql2
	$stmt = $database -> prepare($sql0);
	$stmt->execute();
	$count0 = $stmt->fetchColumn();

	if($count0 >= 1){
		return 1; //exist and not pending;
	} elseif ($count1 >= 1) {
		return 2; //exist and pending
	} else {
		return 0; //no
	}
}

function gettingGroupMember($groupID){
	include("databaseconnection.php");
	$sql = "SELECT userID FROM groupmember WHERE groupID = '$groupID' AND pending = '0' ";
	$stmt =  $database->prepare($sql);
	$stmt -> execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function printingGroupMember($groupID){
	$userArray = gettingGroupMember($groupID);
	foreach($userArray as $key=>$value){
		foreach($value as $subkey=>$subvalue){
			$usernameFromID= gettingUsernameFromID($subvalue);
			$name = gettingNameFromUsername($usernameFromID);
			echo "<div class='memberList noPointer' id='$subvalue'>";
				echo "<p class='usernameDescription'>$usernameFromID</p>";
				echo "<p class='nameDescription'>$name</p>";
			echo "</div>";
		}
	}

}

function listingAllGroups($usernameSession){
	include("databaseconnection.php");
	$userID=gettingUserID($usernameSession);
	$sql= "SELECT groupID FROM groupmember WHERE userID= '$userID' ";
	$stmt = $database->prepare($sql);
	$stmt->execute();
	$groupArray=$stmt->fetchAll(PDO::FETCH_ASSOC);
	if(empty($groupArray)){
		echo "<p> You have no groups! Please create one!</p>";
	} else {
	echo "<form action='addmember.php' method='GET' id='choosingMember'>";
	foreach($groupArray as $key=>$value){
		foreach ($value as $subkey => $subvalue) {
			$groupName=gettingGroupNameFromID($subvalue);
			echo "<input type='radio' name='groupNameSelected' value='$subvalue' style='display: none;'></input>";
			echo "<label for='groupNameSelected' class='groupName'>$groupName</label>";
		}
	}
	echo " <input type='submit' value='Go!' name='submit' id='submitChosenGroup' style='display:none;'>";
	echo "</form>";
	};	
	unset($_SESSION['groupID']);
}

		
function gettingFilename($userID){
	include("databaseconnection.php");
	$sql= "SELECT filename FROM userid WHERE id='$userID' ";
	$stmt = $database->prepare($sql);
	$stmt->execute();
	return $stmt->fetchColumn();
}

function gettingFilenameWithUsername($username){
	include("databaseconnection.php");
	$sql= "SELECT filename FROM userid WHERE username='$username' ";
	$stmt = $database->prepare($sql);
	$stmt->execute();
	return $stmt->fetchColumn();
}

function gettingGroupMemberNumber($groupID){
	include('databaseconnection.php');
	$sql = "SELECT count(*) FROM groupmember WHERE groupID = '$groupID' ";
	$stmt = $database->prepare($sql);
	$stmt->execute();
	return $stmt->fetchColumn();
}

function changePendingToZero ($groupID,$username){
	include('databaseconnection.php');
	$userID = gettingUserID($username);
	$sql="UPDATE groupmember SET pending = '0' WHERE groupID = '$groupID' AND userID = '$userID' ";
	$database->exec($sql);
}

function requestToJoinGroup($groupID,$username){
	include('databaseconnection.php');
	$userID = gettingUserID($username);
	$sqlInsertingGroupMember = "INSERT INTO groupmember(groupID, userID) VALUES ('$groupID', '$userID')";
	$database->exec($sqlInsertingGroupMember);
}

function deleteMemberFromGroup($groupID,$Username){
	include('databaseconnection.php');
	$userID = gettingUserID($username);
	$sql = "DELETE FROM groupmember WHERE groupID = '$groupID' AND userID = '$userID' ";
	$database->exec($sql);
}



?>
