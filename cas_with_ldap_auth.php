<?php
 
session_save_path('/ip/schedule/sessions'); //UPDATE TO YOUR SESSIONS PATH
session_start();
 
 
//THIS FUNCTION GETS THE CURRENT URL
function curPageURL()
{
  $pageURL = 'http';
  if ($_SERVER["HTTPS"] == "on") {
    $pageURL .= "s://";
    if ($_SERVER["SERVER_PORT"] != "443") {
      $pageURL .= $_SERVER["HTTP_HOST"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
      $pageURL .= $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    }
  } else {
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
      $pageURL .= $_SERVER["HTTP_HOST"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
      $pageURL .= $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    }
  }
  return $pageURL;
}//END CURRENT URL FUNCTION

//THIS FUNCTION SENDS THE USER TO CAS AND THEN BACK
function cas_authenticate(){

  $sid = SID; //Session ID #

  //if the last session was over 15 minutes ago
  if (isset($_SESSION['LAST_SESSION']) && (time() - $_SESSION['LAST_SESSION'] > 900)) {
    $_SESSION['CAS'] = false; // set the CAS session to false
  }

  $authenticated = $_SESSION['CAS'];
  $casurl = curPageURL();
  $casurl = strtok($casurl, '?');

  //send user to CAS login if not authenticated
  if (!$authenticated) {
    $_SESSION['LAST_SESSION'] = time(); // update last activity time stamp
    $_SESSION['CAS'] = true;

    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=https://cas.iu.edu/cas/login?cassvc=IU&casurl='.$casurl.'">';
    //header("Location: https://cas.iu.edu/cas/login?cassvc=IU&casurl=$casurl");
    exit;
  }

  if ($authenticated) {
    if (isset($_GET["casticket"])) {

      //set up validation URL to ask CAS if ticket is good
      $_url = 'https://cas.iu.edu/cas/validate';
      $cassvc = 'IU'; //search kb.indiana.edu for "cas application code" to determine code to use here in place of "appCode"
#      $casurl = strtok($casurl, '?');
      $params = "cassvc=$cassvc&casticket=$_GET[casticket]&casurl=$casurl";

      $urlNew = "$_url?$params";

      //CAS sending response on 2 lines. First line contains "yes" or "no". If "yes", second line contains username (otherwise, it is empty).
      $ch = curl_init();
      $timeout = 5; // set to zero for no timeout
      curl_setopt ($ch, CURLOPT_URL, $urlNew);
      curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
      ob_start();
      curl_exec($ch);
      curl_close($ch);
      $cas_answer = ob_get_contents();
      ob_end_clean();
      //split CAS answer into access and user
      list($access,$user) = split("\n",$cas_answer,2);
      $access = trim($access);
      $user = trim($user);
      //set user and session variable if CAS says YES
      if ($access == "yes") {
        $_SESSION['user'] = $user;
      }//END SESSION USER
    } else if (!isset($_SESSION['user'])) { //END GET CAS TICKET
      echo '<META HTTP-EQUIV="Refresh" Content="0; URL=https://cas.iu.edu/cas/login?cassvc=IU&casurl='.$casurl.'">';
      exit;
    }
  }
}//END CAS FUNCTION

 
cas_authenticate();

//BEGIN LDAP AUTHENTICATION

function get_groups($user) { //source code here: https://samjlevy.com/php-ldap-membership/
    // Active Directory server
    $ldap_host = "ads.iu.edu";
 
    // Active Directory DN, base path for our querying user
    $ldap_dn = "ou=Accounts,dc=ads,dc=iu,dc=edu";
 
    // Active Directory user for querying
    $query_user = "schedule@".$ldap_host;
    $password = "SharePoint requests will be missed!";
 
    // Connect to AD
    $ldap = ldap_connect($ldap_host) or die("Could not connect to LDAP");
    ldap_bind($ldap,$query_user,$password) or die("Could not bind to LDAP");
 
    // Search AD
    $results = ldap_search($ldap,$ldap_dn,"(samaccountname=$user)",array("memberof","primarygroupid"));
    $entries = ldap_get_entries($ldap, $results);
    
    // No information found, bad user
    if($entries['count'] == 0) return false;
    
    // Get groups and primary group token
    $output = $entries[0]['memberof'];
    $token = $entries[0]['primarygroupid'][0];
    
    // Remove extraneous first entry
    array_shift($output);
    
    // We need to look up the primary group, get list of all groups
    $results2 = ldap_search($ldap,$ldap_dn,"(objectcategory=group)",array("distinguishedname","primarygrouptoken"));
    $entries2 = ldap_get_entries($ldap, $results2);
    
    // Remove extraneous first entry
    array_shift($entries2);
    
    // Loop through and find group with a matching primary group token
    foreach($entries2 as $e) {
        if($e['primarygrouptoken'][0] == $token) {
            // Primary group found, add it to output array
            $output[] = $e['distinguishedname'][0];
            // Break loop
            break;
        }
    }
 
    return $output;
}

//grab the username from the session
$CAS_username = $_SESSION['user'];

//query the groups, these are put into an array.  Create an empty array.
$LDAP_groups = get_groups("$CAS_username");
$groups_explode = array();

//This reduces the array down to the necessary components for authentication.
foreach($LDAP_groups as $a) {
  $r = explode(",",$a,2);
    $g = explode("=",$r[0]);
    $groups_explode[] = $g[1];
}

//these are the groups that are allowed to access the page.  You can find the groups in ADUC.  One thing that is important about these: you need to get the lowest group, higher level nested groups do not get pulled up in the groups query.  E.g. IU-SCFL-ALL-sec will not work, because its members are other groups.
$approved_groups = ["IU-SCFL-PA-sec", "IU-SCFL-SA-sec"];

//This function compares two arrays for intersection, returns true if intersection occurs.
function in_array_any($needles, $haystack) {
    return !!array_intersect($needles, $haystack);
}

//This checks if the arrays have intersection, if false then the page dies.
if(!in_array_any($approved_groups, $groups_explode) == true) {
    die("You are not authorized to access this page.");
}
 
  ?>