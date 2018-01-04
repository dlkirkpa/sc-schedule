<!DOCTYPE html>
<html>
	<head>
		<title>Attendance Instance Submitted</title>
		<link rel="stylesheet" type="text/css" href="attendanceinstance.css">
	</head>
	<body>
<?php

//Unvalidated variables.  One thing to note in the HTML file, 
//if the name of the variable has bracket (e.g. campus[]) then 
//it is a data type array that needs to be parsed into a string.
//This is done farther down in the code.

$campus = $_POST["campus"];
$assignee = $_POST["assignee"];
$date = $_POST["instanceDate"];
$instance_event = $_POST["instanceEvent"];
$no_call = $_POST["nocall"];
$description = $_POST["description"];
$submitted_by = $_POST["submittedby"];
$tardies = $_POST["tardies"];
$absences = $_POST["absences"];
$no_call_int = $_POST["nocallint"];

//Validated variables, the foreach loops run the validation functions for the arrays
$assignee = validate_text($assignee);
$campus = validate_campus($campus);
$description = validate_text($description);
$instance_event = validate_instance_event($instance_event);
$no_call = validate_no_call($no_call);
$submitted_by = validate_text($submitted_by);
$tardies = validate_text($tardies);
$absences = validate_text($absences);
$no_call_int = validate_text($no_call_int);

//Convert the date/time into easier to read date time formats

$date = date_create($date);
$date = date_format($date, "m/d/Y");

//Valdation Functions, scrubs inputs to make sure they're secure.

function validate_campus($data) {
	if ($data == "IUB" || "IUPUI"){
		return $data;
	} else {
		echo("Invalid Campus Input");
		trigger_error("Invalid Input", E_USER_ERROR);
	}
}

function validate_text($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function validate_instance_event($data) {
	if ($data == "Absence" || "Tardy") {
		return $data;
	} else {
		echo("Invalid Instance Event");
		trigger_error("Invalid Input", E_USER_ERROR);
	}
}

function validate_no_call($data) {
	if ($data == "Yes" || "No") {
		return $data;
	} else {
		echo("Invalid No Call");
		trigger_error("Invalid Input", E_USER_ERROR);
	}
}

//email to
$bl_supervisors = "BL-SCFL-Supervisor@exchange.iu.edu";
$in_supervisors = "IN-SCFL-Supervisor@exchange.iu.edu";

$iub_scheduler = "Schedule <schedule@iu.edu>";
$in_scheduler = "IN Sched <insched@iu.edu>";

$campus_supervisor = '';
$campus_scheduler = '';

if (in_array('IUB', $campus)) {
	$campus_supervisor = $bl_supervisors;
	$campus_scheduler = $iub_scheduler;
} elseif (in_array('IUPUI', $campus)) {
	$campus_supervisor = $in_supervisors;
	$campus_scheduler = $in_scheduler;
}

//Create string variables for the arrays to be printed
$campus_string = '';
$instance_event_string = '';
$no_call_string = '';

foreach ($campus as $value) {
	$campus_string .= '' . $value;
}

foreach ($instance_event as $value) {
	$instance_event_string .= '' . $value;
}

foreach ($no_call as $value) {
	$no_call_string .= '' . $value;
}

//This is the message text.  Anything changed here will modify the text the recipient sees.
$msg = "An attendance instance has been recorded as follows:

Assignee: 		$assignee
Date:			$date
Campus:			$campus_string
Event:			$instance_event_string
No Call?:		$no_call_string
Description:	$description
Submitted By: 	$submitted_by\n

Attendance Summary
Total Tardy:	$tardies
Total Absence:	$absences
Total No Call:	$no_call_int\n

To appeal this instance, REPLY-ALL to this email message with an explanation of why you are appealing it.

-UITS Support Center"

$headers= "From: $campus_scheduler" . "@iu.edu" . "\r\n" .
	'CC: ' . $campus_string;

$msg = wordwrap($msg, 70);

//send email
mail($assignee, "Attendance Notification: " . "$instance_event_string", $msg, $headers);


?>

<div type="flex-container" align="center">
	<form>
		<h2>Attendance Instance Submitted</h2>
		You have successfully submitted an attendance instance.  Please check your inbox to confirm that you have received the attendance instance.  If you have not received the instance within 5 minutes, please email me.<br><br>

		To make another instance <a href="attendanceinstance.html">click here</a>.
	</form>

</body>
</html>