<?php include("cas_with_ldap_auth.php"); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Attendance Instance</title>
		<link rel="stylesheet" type="text/css" href="attendanceinstance.css">

	</head>
	<body>
		<div type="flex-containter" align="center">
		<form name="attendanceInstance" method="post" action="attendanceinstancesubmitted.php">
			<h2>Attendance Instance Form</h2>
			<label>Campus:</label>
				<select id="campus" name="campus[]" required>
				<option value="" selected disabled hidden>Select Location</option>
				<option value="IUB">IUB</option>
				<option value="IUPUI">IUPUI</option>
				</select>
			<br>
			<label>Assignee:</label>
				<input type="text" id="assignee" name="assignee" pattern="[a-z0-9]{0,8}" maxlength="8"  required><br>
			<label>Date:</label>
				<input type="date" id="instanceDate" name="instanceDate" pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}" placeholder= "MM/DD/YYYY" required><br>
			<label>Event:</label>
				<select id="instanceEvent" name="instanceEvent[]" required>
					<option value="" selected disabled hidden>Type of Instance</option>
					<option value="Absence">Absence</option>
					<option value="Tardy">Tardy</option>
				</select>
			<br>
			<label>No Call?</label>
				<select id="nocall" name="nocall[]" required>
					<option value="" selected disabled hidden></option>
					<option value="Yes">Yes</option>
					<option value="No">No</option>
				</select>
			<br>
			<label>Description:</label>
				<textarea type="textarea" id="description" name="description" rows="3" placeholder="Reason person called out"></textarea>
			<label>Submitted By:</label>
				<input type="text" id="submittedby" name="submittedby" pattern="[a-z0-9]{0,8}" maxlength="8" required>
			<br>
			<label>Attendance Summary (after appeals):</label>
			<label>Tardies:</label>
				<input type="number" id="tardies" name="tardies" min="0" max="99" required><br>
			<label>Absences:</label>
				<input type="number" id="absences" name="absences" min="0" max="99" required><br>
			<label>No Calls:</label>
				<input type="number" id="nocallint" name="nocallint" min="0" max="99" required>
			<br>
			<br>
			<input type="submit" name="submit" id="submit" value="submit" class="buttons">
		</form>
	</div>
</body>
</html>