<?php include("cas_with_ldap_auth.php"); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Sub Plea Submitted</title>
	<link rel="stylesheet" type="text/css" href="subplea.css">
<body>
<?php



//Unvalidated variables
$campus = $_POST["campus"];
$location = $_POST["location"];
$position = $_POST["position"]; //returns data type, need to make sure to extract values
$shift_start = $_POST["shiftStart"];
$shift_end = $_POST["shiftEnd"];
$username = $_POST["username"];
$comments = $_POST["comments"];
$supervisor_check = $_POST["supervisorCheck"];


//Validated variables, the foreach loops run the validation functions for the arrays
$username = validate_text($username);
$comments = validate_text($comments);
$campus = validate_campus($campus);
foreach ($position as $value) {
	validate_position($value);
}
foreach ($location as $value) {
	validate_location($value);
}
$supervisor_check = validate_supervisor_check($supervisor_check);

//Convert the date/time into easier to read date time formats


$shift_start = date_create($shift_start);
$date_request = date_format($shift_start, "m/d/Y l");
$shift_start = date_format($shift_start, "m/d/Y h:i A");

$shift_end = date_create($shift_end);
$shift_end = date_format($shift_end, "m/d/Y h:i A");



//Validation Functions, scrubs inputs to make sure they're secure. 

function validate_campus($data) {
	if ($data == "IUB" || "IUPUI"){
		return $data;
	} else {
		echo("Invalid Campus Input");
		trigger_error("Invalid Input", E_USER_ERROR);
	}
}

function validate_location($data) {
	if ($data == "CIB" || $data == "LC" || $data == "IMU" || $data == "ICTC" || $data == "CC") {
		return $data;
	} else {
		echo("Invalid Location Input");
		trigger_error("Invalid Input", E_USER_ERROR);
	}
}

function validate_position($data) {
	if ($data == "Phones" || $data == "Walk-in" || $data == "Crimson Card" || $data == "Carry-in" || $data == "Supervisor") {
		return $data;
	} else {
		echo("Invalid Position Input");
		trigger_error("Invalid Input", E_USER_ERROR);
	}
}

function validate_text($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}


function validate_supervisor_check($data) {
	if ($data !== "yes"){
		$data == "no";
		return $data;
	} else {
		return $data;
	}
}

//email to
$bl_consultants = "BL-SCFL-Consultant@exchange.iu.edu";
$bl_supervisors = "BL-SCFL-Supervisor@exchange.iu.edu";
$in_consultants = "IN-SCFL-Consultant@exchange.iu.edu";
$in_supervisors = "IN-SCFL-Supervisor@exchange.iu.edu";

//supervisor
$iub_scheduler = "Schedule <schedule@iu.edu>";
$in_scheduler = "IN Sched <insched@iu.edu>";

//This statement sets the appropriate campus information for the request

if (in_array('Supervisor', $position) && in_array('IUB', $campus)) {
	$mail_to = $bl_supervisors;
	$campus_scheduler = $iub_scheduler;
} elseif (in_array('Supervisor', $position) && in_array('IUPUI', $campus)) {
	$mail_to = $in_supervisors;
	$campus_scheduler = $in_scheduler;
} elseif (in_array('IUB', $campus)) {
	$mail_to = $bl_consultants;
	$campus_scheduler = $iub_scheduler;
} elseif (in_array('IUPUI', $campus)) {
	$mail_to = $in_consultants;
	$campus_scheduler = $in_scheduler;
}

if ($supervisor_check == 'yes' && in_array("IUB", $campus) && $mail_to !== $bl_supervisors) {
	$mail_to .= ', ' . $bl_supervisors;
} elseif ($supervisor_check == 'yes' && in_array("IUPUI", $campus) && $mail_to !== $in_supervisors) {
	$mail_to .= ', ' . $in_supervisors;
}


//Creates string variables for the arrays to be printed
$campus_string = '';
$location_string = '';
$position_string = '';

foreach ($campus as $value) {
	$campus_string .= ' ' . $value;
}

foreach ($location as $value) {
	$location_string .= ' ' . $value;
}

foreach ($position as $value) {
	$position_string .= ' ' . $value;
}

$msg = "To claim this request, please click Reply and state:
  'I would like to claim this request'\n

  Location: $location_string
  Shift Position: $position_string
  Start: $shift_start
  End: $shift_end

  Submitted by: $username
  Comments: $comments";

$headers= "From: $username" . "@iu.edu" . "\r\n" . 
	'Reply-To: ' . $campus_scheduler . "\r\n" . 
	'CC: ' . $campus_scheduler;

$msg = wordwrap($msg, 70);

//send email
mail($mail_to,  "$date_request" . " Sub Plea Request", $msg, $headers);

//creates a pseudo-log file for sub plea requests

$timestamp = date('r');

$append_to_log = fopen("subplealog.txt", "a") or die("couldn't open");
flock($append_to_log, LOCK_EX);
$log_text = "\n$timestamp
	$username
	$campus_string
	$location_string
	$position_string
	$shift_start
	$shift_end\n";
fwrite($append_to_log, $log_text);
flock($append_to_log, LOCK_UN);
fclose($append_to_log);


//echo(/*$username . "\r\n" . $msg  . "\r\n" . */$headers . "\r\n" . /*$date_request  . "\r\n" . */$mail_to);
  ?>


	<div type="flex-container" align="center">
	<form>
		<h2>Sub Plea Submitted</h2>
		You have successfully submitted a subplea request.  Please check your inbox to confirm that you have received the sub plea request (it may be in your sub plea folder).  If you have not received the request within 5 minutes, please consult with a supervisor.<br><br>

		To make another request <a href="subplea.html">click here</a>.</form>
</body>
</html>