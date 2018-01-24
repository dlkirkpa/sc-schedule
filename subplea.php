<?php include("cas_with_ldap_auth.php"); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Sub Plea Requests</title>
		<link rel="stylesheet" type="text/css" href="subplea.css">
		<script src="subplea.js"></script>
	</head>
	<body>
		<div type="flex-container" align="center">
		<form name="subPlea" method="post" onsubmit="return validateDate()" action="subpleasubmitted.php">
			<h2>Sub Plea Request Form</h2><label>Campus:</label>
				<select id="campus" name="campus[]" required>
				<option value="" selected disabled hidden>Select Location</option>
				<option value="IUB">IUB</option>
				<option value="IUPUI">IUPUI</option>
			</select>
			<br>
			<label>Location: (select all that apply)</label>
				<select id="location" name="location[]" multiple required>
					<option value="CIB">CIB</option>
					<option value="LC">LC</option>
					<option value="IMU">IMU</option>
					<option value="ICTC">ICTC</option>
					<option value="CC">CC</option>
				</select>
			<br>
			<label>Shift Position: (select all that apply)</label>
				<select id="position" name="position[]" multiple required>
					<option value="Phones">Phones/Chat/Email</option>
					<option value="Walk-in">Walk-in</option>
					<option value="Crimson Card">Crimson Card</option>
					<option value="Carry-in">Carry-in</option>
					<option value="Supervisor">SA/Dispatcher/Project</option>
				</select>
			<br>
			<label>Shift Start:</label>
				<input type="datetime-local" id="shiftStart" name="shiftStart" pattern="[0-9]{2}/[0-9]{2}/[0-9]{4} [0-9]{2}:[0-9]{2} [A|P]M" placeholder= "MM/DD/YYYY hh:mm AM/PM" required><br>
			<label>Shift End:</label>
				<input type="datetime-local" id="shiftEnd" name="shiftEnd" pattern="[0-9]{2}/[0-9]{2}/[0-9]{4} [0-9]{2}:[0-9]{2} [A|P]M" placeholder= "MM/DD/YYYY hh:mm AM/PM" required><br>
			<label>Username: (Check the box if you are a supervisor)</label>
				<input type="text" id="username" name="username" placeholder="Your IU username" required>
			<input type="checkbox" id="supervisorCheck" name="supervisorCheck" value="yes"><br> 
			<label>Comments:</label>
				<textarea type="textarea" id="comments" name="comments" rows="3" placeholder="Reason you need a sub"></textarea><br>
			<input type="submit" name="submit" id="submit" value="submit" class="buttons">
		</form>
		</div>
	</body>
</html>