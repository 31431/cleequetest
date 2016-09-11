<?php 
	include("groupFunction.php");
	include("main_ics_processer.php");
	$postedInfo = $_POST['value'];
	$value = explode(" ", $postedInfo);
	$daySelected = $value[0];
	$timeSelected = $value[1];
	$groupMemberArray = gettingGroupMember($_SESSION['groupID']);

	//Busy people IDs => Will be parsed back.
	$busyPeople = "";

	//Looping through everybody in the group 
	foreach ($groupMemberArray as $key => $value) {
		foreach ($value as $subkey => $subvalue) {
			$filename=gettingFilename($subvalue); // Getting their serialised ics array
			$userTimeSlotArray = unserialize($filename); //Unserialised the code
			if($userTimeSlotArray[$daySelected][$timeSelected] == 1){
				$busyPeople .= $subvalue."N";
			};
		}
	}

	if($busyPeople == ""){
		echo json_encode(array("busyPeople" => "none!"));
	} else {
	//Encode for sending back to addmember.php AJAX code
	echo json_encode(array("busyPeople" => $busyPeople));
	};
?>
