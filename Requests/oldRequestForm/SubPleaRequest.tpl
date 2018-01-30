!debug 2
*define-variables* 
  [req-campus] = "Campus is required"
  [req-location] = "Location is required"
  [req-shiftposition] = "Shift Position is required"
  [req-startdate] = "Start Date is required"
  [req-starttime] = "Start time is required"
  [req-enddate] = "End Date is required"
  [req-endtime] = "End time is required"
  [req-email] = "Email address is required"
  [reason]
  [stPtsTest]
!show-sig = no
!use-linebreak-mode=[reason]
!trim = [email]
  
*email-response*
!use-if [Submit] eq "Submit Form" and [campus] eq "IUB" and [shiftposition] ne "SA"
To:BL-SCFL-Consultant@exchange.iu.edu,schedule@iu.edu
From:[email]
Subject:[startdate] Sub Plea

To claim this request, please click Reply, change the To: line to schedule@iu.edu and state:
  "I would like to claim this request"
  
  Location: [location]
  Shift Position: [shiftposition]
  Start: [startdate] [starttime]
  End: [enddate] [endtime]
  
  Submitted by: [!NETWORK_ID]
  Comments: [reason]

*email-response*
!use-if [Submit] eq "Submit Form" and [campus] eq "IUB" and [stPtsTest] == 10
To:BL-SCFL-CC@exchange.iu.edu,schedule@iu.edu
From:[email]
Subject:[startdate] Sub Plea   

To claim this request, please click Reply, change the To: line to schedule@iu.edu and state:
  "I would like to claim this request"
  
  Location: [location]
  Shift Position: [shiftposition]
  Start: [startdate] [starttime]
  End: [enddate] [endtime]
  
  Submitted by: [!NETWORK_ID]
  Comments: [reason]
 
*email-response*
!use-if [Submit] eq "Submit Form" and [campus] eq "IUPUI" and [shiftposition] ne "SA"
To:IN-SCFL-Consultant@exchange.iu.edu,insched@iu.edu
From:[email]
Subject:[startdate] Sub Plea   

To claim this request, please click Reply, change the To: line to insched@iu.edu and state:
  "I would like to claim this request"
  
  Location: [location]
  Shift Position: [shiftposition]
  Start: [startdate] [starttime]
  End: [enddate] [endtime]
  
  Submitted by: [!NETWORK_ID]
  Comments: [reason]

*email-response*
!use-if [Submit] eq "Submit Form" and [campus] eq "IUPUI" and [shiftposition] eq "SA"
To:IN-SCFL-CC@exchange.iu.edu,insched@iu.edu
From:[email]
Subject:[startdate] Sub Plea   

To claim this request, please click Reply, change the To: line to insched@iu.edu and state:
  "I would like to claim this request"
  
  Location: [location]
  Shift Position: [shiftposition]
  Start: [startdate] [starttime]
  End: [enddate] [endtime]
  
  Submitted by: [!NETWORK_ID]
  Comments: [reason]

*success-response*
!use-if [Submit] eq "Submit Form"
  <HTML><head><title>Sub Plea Successfully Submitted</title></head>
  <body bgcolor="#FFFFFF">
  <h2> <center> Thanks, your sub plea has been submitted.</center> </h2><p>
  <p>
  <p>
  <p> 
  The following information was emailed to your scheduler and coworkers:<br> 
  <hr> 
 
  Location: [location]<br>
  Shift Position: [shiftposition]<br>
  Start: [startdate] [starttime]<br>
  End: [enddate] [endtime]<br>
  
  Submitted by: [!NETWORK_ID]<br>
  Comments: [reason]<br>
  ShiftPoint: [stPtsTest]
  <center> 
<h3><a href="https://www.indiana.edu/~schedule/Requests/SubPleaRequest.html">Submit Another Request</a><h3>
</center>
  </body></html>
  
  *error-response*
!use-if [Submit] eq "Submit Form"
  <HTML> <head> <title>Request Error</title></head>
  <body bgcolor="#FFFFFF">
  <h2><center>Sorry, there was an error submitting your request.</center></h2>
  <p>You have not included the following required information in your request: </p>
!print-if [campus] eq ""
  <b>Campus:</b> <br>
!end-print-if
!print-if [email] eq ""
  <b>Email:</b> <br>
!end-print-if
!print-if [shiftposition] eq ""
  <b>Shift Position:</b> <br>
!end-print-if
!print-if [startdate] eq ""
  <b>Start Date:</b> <br>
!end-print-if
!print-if [starttime] eq ""
  <b>Start Time:</b> <br>
!end-print-if
!print-if [enddate] eq ""
  <b>End Date:</b> <br>
!end-print-if
!print-if [endtime] eq ""
  <b>End Time:</b> <br>
!end-print-if
!print-if [location] eq ""
  <b>Location:</b> <br>
!end-print-if
!end-print-if
  <p>  Please GO BACK (click back on your browser) to the Sub Plea Form and re-submit it with all of
  the required information. </p>
  </body>
  </html>
  
 *append-response*
!use-if [Submit] eq "Submit Form" 
!append-file-name = "/ip/schedule/wwws/Requests/RequestMaster.html"
!record-delimiter
!append-after = "<!-- IUB CIB -->"
<tr>
	<td>[campus]</td>
	<td>[location]</td>
	<td>[shiftposition]</td>
	<td>[startdate]</td>
	<td>[starttime]</td>
	<td>[enddate]</td>
	<td>[endtime]</td>
	<td>[!NETWORK_ID]</td>
	<td>[reason]</td>
	<td>[!DATE]</td>
</tr> 