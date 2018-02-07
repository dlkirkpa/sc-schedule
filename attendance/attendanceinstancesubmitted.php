<?php include("cas_with_ldap_auth.php"); ?>
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
//I also set the constants here, they are the all-caps variables.  These are what the validated variables check against.  If one of the inputs has to change (e.g. a new walk-in location) then edit the constant arrays to add the new input.

$VALID_CAMPUS = array('IUB', 'IUPUI');
$VALID_INSTANCE_EVENT = array('Absence', 'Tardy');
$VALID_NO_CALL = array('Yes', 'No');

$assignee = validate_text($assignee);
$campus = validate_array($campus, $VALID_CAMPUS);
$description = validate_text($description);
$instance_event = validate_array($instance_event, $VALID_INSTANCE_EVENT);
$no_call = validate_array($no_call, $VALID_NO_CALL);
$submitted_by = validate_text($submitted_by);
$tardies = validate_text($tardies);
$absences = validate_text($absences);
$no_call_int = validate_text($no_call_int);

//Convert the date/time into easier to read date time formats

$date = date_create($date);
$date = date_format($date, "m/d/Y");

//Valdation Functions, scrubs inputs to make sure they're secure.

function validate_array($needles, $haystack) {
	if(!!array_intersect($needles, $haystack) == false) {
		echo("Invalid Input");
	} else {
		return $needles;
	}
}

function validate_text($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

//email to and CC variables getting set
$mail_to = "$assignee" . "@iu.edu";

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
$msg = "An attendance instance has been recorded as follows:\n

Assignee:	$assignee
Date:		$date
Campus:	$campus_string
Event:		$instance_event_string
No Call?:	$no_call_string
Description:	$description
Submitted By:	$submitted_by\n

Attendance Summary
Total Tardy:	$tardies
Total Absence:	$absences
Total No Call:	$no_call_int\n

To appeal this instance, REPLY-ALL to this email message with an explanation of why you are appealing it.

-UITS Support Center";

$headers= "From: $campus_scheduler" . "\r\n" .
	'CC: ' . "$campus_supervisor";

$msg = wordwrap($msg, 70);

//send email
mail($mail_to, "Attendance Notification: " . "$instance_event_string", $msg, $headers);

//creates a pseudo-log file for sub plea requests

$timestamp = date('r');

$append_to_log = fopen("attendancelog.txt", "a") or die("couldn't open");
flock($append_to_log, LOCK_EX);
$log_text = "\n$timestamp
	$assignee
	$date
	$campus_string
	$instance_event_string
	$no_call_string
	$description
	$submitted_by
	tardies: $tardies
	absences: $absences
	no call: $no_call_int\n";
fwrite($append_to_log, $log_text);
flock($append_to_log, LOCK_UN);
fclose($append_to_log);

?>

<div type="flex-container" align="center">
	<form>
		<h2>Attendance Instance Submitted</h2>
		You have successfully submitted an attendance instance.  Please check your inbox to confirm that you have received the attendance instance.  If you have not received the instance within 5 minutes, please email me.<br><br>

		To make another instance <a href="attendanceinstance.php">click here</a>.
	</form>
</div>

</body>
</html>