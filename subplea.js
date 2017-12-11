function validateDate() {
	var shiftStart = new Date(document.getElementById("shiftStart").value);
	var shiftEnd = new Date(document.getElementById("shiftEnd").value);
	var nextDay = new Date();
	var currentDate = new Date();

	nextDay.setTime(shiftStart.getTime() + 86400000);

	if (currentDate.getTime() > shiftStart.getTime()) {
		alert("You need to select a future date");
		return false;
	} else if(shiftStart.getTime() > shiftEnd.getTime()) {
		alert("You need to select a shift end time after the shift start time.");
		return false;
	} else if(shiftEnd.getTime() > nextDay.getTime()) {
		alert("Shift End needs to be within 24 hours of Shift Start");
		return false;
	} else {
		return true;
	}
}	