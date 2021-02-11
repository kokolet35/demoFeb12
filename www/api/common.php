<?php
//include("db.php");
$host = 'localhost';
$user = 'user';
$password = 'microsoft';
$db_name = 'xperthands';

$connection = @mysql_connect($host, $user, $password) or die(mysql_error());
$db = mysql_select_db($db_name, $connection) or die(mysql_error()." could not select databaseoo");

date_default_timezone_set("Africa/Lagos");
$ctime = date('Y-m-d H:i:s');


function clean_data( $input )
{
    $input = trim( htmlentities( strip_tags( $input,"," ) ) );

    if( get_magic_quotes_gpc() )
        $input = stripslashes( $input );

    $input = mysql_real_escape_string( $input );
    return $input;
}



function log_succ($uid,$det)
{
	$aquery = mysql_query("INSERT into tbl_log(event,det) VALUES('$uid','$det')") or die("get name query error ".mysql_error());
}

function chk_ext($uemail)
{
$aquery = mysql_query("Select * FROM tbl_reg WHERE email='$uemail'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$uid = $row['uid'];
if($nrow > 0)
{
	$upass = "True";
}
else
{
	$upass = "false";
}

return $upass;
}

function chkemail($uemail)
{
$aquery = mysql_query("Select * FROM tbl_reg WHERE email='$uemail'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$uid = $row['uid'];
if($nrow == 1)
{
	$upass = "correct";
}
else
{
	$upass = "Not Found";
}

return $upass;
}

function check_user($uname,$upass)
{
$aquery = mysql_query("Select * FROM tbl_user WHERE username='$uname' AND user_status='Active' AND password='$upass'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
//$uid = $row['uid'];

return $nrow;
}

//Verify User Exit
function check_phone($userPhone)
{
$aquery = mysql_query("Select * FROM tbl_user WHERE username='$userPhone'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
//$uid = $row['uid'];

return $nrow;
}

//Verify Provider Exit
function check_phoneprvd($userPhone)
{
$aquery = mysql_query("Select * FROM tbl_srvprovider WHERE username='$userPhone'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
//$uid = $row['uid'];

return $nrow;
}

//Verify Vendor
function check_phonevendor($userPhone)
{
$aquery = mysql_query("Select * FROM tbl_vendor WHERE username='$userPhone'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
//$uid = $row['uid'];

return $nrow;
}

//Check New Alert
function check_newalert($userPhone, $userAlertID)
{
$aquery = mysql_query("Select * FROM tbl_alert WHERE username='$userPhone' AND sno > $userAlertID") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
//$uid = $row['uid'];

return $nrow;
}

//Check New Alert for Provider
function check_newalertx($userPhone, $userAlertID)
{
$aquery = mysql_query("Select * FROM tbl_alertprvd WHERE username='$userPhone' AND sno > $userAlertID") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
//$uid = $row['uid'];

return $nrow;
}

//Get Username
function get_userid($uname)
{
$aquery = mysql_query("Select * FROM tbl_user WHERE username='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['sno'];

return $upass;
}

//Get City Name
function get_citynamedb($uname)
{
$aquery = mysql_query("Select * FROM tbl_city WHERE sno='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['cityname'];

return $upass;
}

//Get Service Name
function get_srvname($uname)
{
$aquery = mysql_query("Select * FROM tbl_cat WHERE sno='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['catname'];

return $upass;
}


//Get User Status
function get_user_stat($uname)
{
$aquery = mysql_query("Select * FROM tbl_user WHERE username='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['user_status'];

return $upass;
}

//Get Provider Address
function get_prvdadd($uname)
{
$aquery = mysql_query("Select * FROM tbl_srvprovider WHERE username='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['srv_add'];

return $upass;
}

//Get Provider Brief
function get_prvdabout($uname)
{
$aquery = mysql_query("Select * FROM tbl_srvprovider WHERE username='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['srv_des'];

return $upass;
}

//Get Provider City
function get_prvdcity($uname)
{
$aquery = mysql_query("Select * FROM tbl_srvprovider WHERE username='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['srv_city'];

return $upass;
}


//Check Provider City
function check_prvdcity($userPhone, $cityname)
{
//$usercity = get_prvdcity($userPhone);
/*
$usercity = "Marina, Ajah, Ikoyi";
//Extract Message Content
$messageArray = explode(",",$usercity);

//if (in_array($cityname, $messageArray))
if ((isset($messageArray[$cityname])))
{$upass = "Checked";}
else
{$upass = null;}


return $upass;
*/

$aquery = mysql_query("Select * FROM tbl_prvdcity WHERE username='$userPhone' AND cityname='$cityname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
if($nrow == 1)
{$upass = "Checked";}
else
{$upass = "";}

return $upass;
}

//Check Provider City
function check_prvdsrv($userPhone, $srvid)
{

$aquery = mysql_query("Select * FROM tbl_prvdsrv WHERE username='$userPhone' AND srvid='$srvid'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
if($nrow == 1)
{$upass = "Checked";}
else
{$upass = "";}

return $upass;
}

//Check Provider City Availabitily
function prvdcity_avl($userPhone)
{

$aquery = mysql_query("Select * FROM tbl_prvdcity WHERE username='$userPhone'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
if($nrow > 0)
{$upass = True;}
else
{$upass = False;}

return $upass;
}

//Check Provider Service Availabitily
function prvdsrv_avl($userPhone)
{

$aquery = mysql_query("Select * FROM tbl_prvdsrv WHERE username='$userPhone'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
if($nrow > 0)
{$upass = True;}
else
{$upass = False;}

return $upass;
}

//Check Provider Service List
function chkprvd_det($userPhone)
{

$aquery = mysql_query("Select * FROM tbl_srvprovider WHERE username='$userPhone'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
if(($row['srv_type'] == "") && ($row['srv_city'] == ""))
{$upass = False;}
else
{$upass = True;}

return $upass;
}

//Get Provider Profile Status
function prvdprofile($uname)
{
$aquery = mysql_query("Select * FROM tbl_srvprovider WHERE username='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['profile_comp'];

return $upass;
}
//Get Provider Profile Status
function prvdprofileimg($uname)
{
$aquery = mysql_query("Select * FROM tbl_srvprovider WHERE username='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['profile_img'];

return $upass;
}

//Get Provider Doc Status
function get_doc_status($uname)
{
$aquery = mysql_query("Select * FROM tbl_srvprovider WHERE username='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['doc_comp'];

return $upass;
}

//Get Provider Online Status
function get_prvd_onlinestatus($uname)
{
$aquery = mysql_query("Select * FROM tbl_srvprovider WHERE username='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['profile_status'];

return $upass;
}

//Get Provider Profile Status
function get_prof_status($uname)
{
$aquery = mysql_query("Select * FROM tbl_srvprovider WHERE username='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['profile_comp'];

return $upass;
}

//Get Provider City List
function prvdprofcitylist($userPhone)
{

$aquery = mysql_query("Select * FROM tbl_srvprovider WHERE username='$userPhone'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);

$upass = $row['srv_city'];

return $upass;
}

//Get Provider Name
function get_prvdname($sno)
{

$aquery = mysql_query("Select * FROM tbl_srvprovider WHERE sno='$sno'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);

$upass = $row['fullname'];

return $upass;
}

//Get Provider Number
function get_prvdnum($sno)
{

$aquery = mysql_query("Select * FROM tbl_srvprovider WHERE sno='$sno'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);

$upass = $row['username'];

return $upass;
}


//Get Provider Sno
function get_prvdsno($phone)
{

$aquery = mysql_query("Select * FROM tbl_srvprovider WHERE username='$phone'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);

$upass = $row['sno'];

return $upass;
}



//Get Provider Service List
function prvdprofsrvlist($userPhone)
{

$aquery = mysql_query("Select * FROM tbl_srvprovider WHERE username='$userPhone'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);

$upass = $row['srv_type'];

return $upass;
}

//Get User Email
function get_user_email($uname)
{
$aquery = mysql_query("Select * FROM tbl_user WHERE username='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['user_email'];

return $upass;
}

//Get User Alert
function get_user_alert($uname)
{
$aquery = mysql_query("Select * FROM tbl_alert WHERE username='$uname' AND status='New'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);

return $nrow;
}

//Get User Service Request
function get_user_srvreqs($uname)
{
$aquery = mysql_query("Select * FROM tbl_services WHERE user_id='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);

return $nrow;
}



//Get Provider Service Request
function get_prvd_srvreqs($uname)
{
$aquery = mysql_query("Select * FROM tbl_services WHERE prvd_id='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);

return $nrow;
}

//Get Provider Alert
function get_prvd_alert($uname)
{
$aquery = mysql_query("Select * FROM tbl_alertprvd WHERE username='$uname' AND status='New'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);

return $nrow;
}

//Get User Alert ID
function get_user_alertID($uname)
{
$aquery = mysql_query("Select * FROM tbl_alert WHERE username='$uname' ORDER BY sno DESC") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['sno'];

return $upass;
}

//Get Provider Alert ID
function get_prvd_alertID($uname)
{
$aquery = mysql_query("Select * FROM tbl_alertprvd WHERE username='$uname' ORDER BY sno DESC") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['sno'];

return $upass;
}

//Get Provider Status
function get_prvd_status($uname)
{
$aquery = mysql_query("Select * FROM tbl_srvprovider WHERE username='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['srv_status'];

return $upass;
}

//Get Service Status
function get_srvstatus($uname)
{
$aquery = mysql_query("Select * FROM tbl_services WHERE sno='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['service_status'];

return $upass;
}

//Get Service Provider Sno
function get_prvdid($uname)
{
$aquery = mysql_query("Select * FROM tbl_services WHERE sno='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['prvd_id'];

return $upass;
}

//Get Last number ID
function get_srvid()
{
$aquery = mysql_query("Select * FROM tbl_services ORDER BY sno DESC") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['srv_idn'];

if($nrow == 0)
{$upass = 1001;}

return $upass;
}

//Insert Notification
function new_notification($username, $msg)
{
date_default_timezone_set("Africa/Lagos");
$ctime = date('Y-m-d H:i:s');
$ctimefmt = date('F d, Y');
$timefmt = date('H:i');
$result = mysql_query("INSERT INTO tbl_alert (username, msg, dadd, daddfmt, timefmt) VALUES('$username', '$msg', '$ctime', '$ctimefmt', '$timefmt')") or die(mysql_error()."- Insert Alert error");

return null;
}


//Insert Provider Notification
function new_prvdnotification($username, $msg)
{
date_default_timezone_set("Africa/Lagos");
$ctime = date('Y-m-d H:i:s');
$ctimefmt = date('F d, Y');
$timefmt = date('H:i');
$result = mysql_query("INSERT INTO tbl_alertprvd (username, msg, dadd, daddfmt, timefmt) VALUES('$username', '$msg', '$ctime', '$ctimefmt', '$timefmt')") or die(mysql_error()."- Insert Alert error");

return null;
}

//Get User City
function get_city($uname)
{
$aquery = mysql_query("Select * FROM tbl_user WHERE username='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['city'];
if(empty($upass))
{$upass = "None";}
else
{$upass = get_cityname($upass);}

return $upass;
}

//Get City Name
function get_cityname($sno)
{
$aquery = mysql_query("Select * FROM tbl_city WHERE sno='$sno'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['cityname'];

return $upass;
}

//Get City ID
function get_citysno($cname)
{
$aquery = mysql_query("Select * FROM tbl_city WHERE cityname='$cname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['sno'];

return $upass;
}


//Get Full Name
function get_fullname($uname)
{
$aquery = mysql_query("Select * FROM tbl_user WHERE username='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['fullname'];

return $upass;
}

//Get Full Name
function get_user_address($uname)
{
$aquery = mysql_query("Select * FROM tbl_user WHERE username='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$upass = $row['address'];

return $upass;
}


function get_username($uname)
{
$aquery = mysql_query("Select * FROM tbl_member WHERE sno='$uname'") or die("user check error ".mysql_error());
$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);
$username = $row['sno'];

return $upass;
}


function send_sms($num, $msg)
{
	$message = urlencode($msg); 
	$sender= urlencode("XpertHands"); 
	$mobile = $num; 
	//$url = 'http://www.MultiTexter.com/tools/geturl/Sms.php?username=j.okodugha@gmail.com&password=divinelove&sender='.$sender.'&message='.$message.'&flash=0&forcednd=1&recipients='.$mobile; 
	$url = "http://www.MultiTexter.com/tools/geturl/Sms.php?username=j.okodugha@gmail.com&password=divinelove&sender=$sender&message=$message&flash=0&forcednd=1&recipients=$mobile";
	$ch = curl_init(); 
	curl_setopt($ch,CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($ch, CURLOPT_HEADER, 0); 
	$resp = curl_exec($ch); 
	curl_close($ch);
	
	return $resp;
	
}
?>