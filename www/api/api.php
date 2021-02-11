<?php 
include("common.php");
//error_reporting(E_ALL ^ E_DEPRECATED);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
//REGISTRATION  
//http://myxpression.net/app/api.php?type=reg&name=Fola%20Daniel&password=8843jdsf&email=gt@gtbank.com
//http://localhost/xperthands/f7/core/api/api.php
//http://localhost/xperthands/f7/core/api/api?type=test - test

//API Test
if($_REQUEST['type'] == "test")
{
	$aresult['message'] = "success";
	$aresult['about'] = "Xperthands App API";
	$aresult['alist'] = [1,5,2,3,4,5,6,7];
	$aresult['city'] = array();
	$aquery = mysql_query("Select * FROM tbl_city") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_array($aquery)) {
	extract($row);
	$aresult['city'][] = $cityname;
	}
	//$aresult['userAlertDate'] = array();
	$aresult['userAlertMsg'] = array();
	$aquery = mysql_query("Select * FROM tbl_alert ORDER BY sno DESC LIMIT 10") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['userAlertMsg'][]=$row;
  }


	
echo json_encode($aresult);

}


//if((substr($_REQUEST['type'],0,6)) == "upload")
if($_REQUEST['type'] == "upload")
{
$mtype = $_REQUEST['type'];
$rand = $_REQUEST['rand'];
//$messageArray = explode("-",$mtype);

//$uid = $messageArray[1];
//$frmcode = $messageArray[2];

//Allow Headers
header('Access-Control-Allow-Origin: *');
//print_r(json_encode($_FILES));
$new_image_name = urldecode($_FILES["file"]["name"])."";
//Move your files into upload folder
move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/".$new_image_name);

//image compression and enhancements
$source_url = "uploads/".$new_image_name;
$destination_url = "uploads/".$new_image_name;
//$quality = "20";
//$durl = compress_image($source_url, $destination_url, $quality);

//move to database
//$aquery = mysql_query("INSERT into tbl_xpression(uid, img, fcode, odet) VALUES('$uid','$new_image_name', '$frmcode', '$mtype')") or die("get name query error ".mysql_error());

$query = "UPDATE tbl_company SET img_dump='$new_image_name' WHERE sno='1'";
$result = mysql_query($query) or die(mysql_error()."- Insert xpression error");

date_default_timezone_set("Africa/Lagos");
$ctime = date('Y-m-d H:i:s');

$msg = "I ran on $ctime with rand - $rand";
$result = mysql_query("UPDATE tbl_company SET rem_others='$msg' WHERE sno='1'") or die(mysql_error()."- Insert otp error");

$aresult['code'] = "0";
$aresult['message'] = "Success";

$aresult['type'] = $mtype;

echo json_encode($aresult);
}


if($_REQUEST['type'] == "fileupload")
{
$userPhone = $_REQUEST['userPhone'];
$fileType = $_REQUEST['fileType'];
$fileReq = $_REQUEST['fileReq'];

//Allow Headers
header('Access-Control-Allow-Origin: *');
//print_r(json_encode($_FILES));
$new_image_name = urldecode($_FILES["file"]["name"])."";
$new_image_name = "uploads/".$userPhone."_".$new_image_name;
//Move your files into upload folder
move_uploaded_file($_FILES["file"]["tmp_name"], "../../admin/".$new_image_name);

//image compression and enhancements
$source_url = "uploads/".$new_image_name;
$destination_url = "uploads/".$new_image_name;
//$quality = "20";
//$durl = compress_image($source_url, $destination_url, $quality);

//Delete Existing Records
$query = "DELETE FROM tbl_document WHERE doc_type='fileType' AND username='$userPhone'";
$result = mysql_query($query) or die(mysql_error()."- Insert xpression error");

date_default_timezone_set("Africa/Lagos");
$ctime = date('Y-m-d H:i:s');
$datefmt = date_create($ctime);
$datefmt = date_format($datefmt, "F d, Y");

//move to database
$aquery = mysql_query("INSERT into tbl_document(username, doc_type, doc_file, dateAdded, datefmt, docRequired) VALUES('$userPhone', '$fileType', '$new_image_name', '$ctime', '$datefmt', '$fileReq')") or die("get name query error ".mysql_error());



//Update photo
$result = mysql_query("UPDATE tbl_srvprovider SET profile_img='$new_image_name' WHERE username='$userPhone'") or die(mysql_error()."- Insert xpression error");

$aresult['code'] = "0";
$aresult['message'] = "Success";

$aresult['type'] = $mtype;

echo json_encode($aresult);
}
  
if($_REQUEST['type'] == "applogin")
{
	$userPhone = $_REQUEST['userName'];
	
	
	$aresult['status'] = "success";
	$aresult['message'] = "success";
	$aresult['uid'] = get_userid($_REQUEST['userName']);
	$aresult['fullname'] = get_fullname($_REQUEST['userName']);
	$aresult['userCity'] = get_city($_REQUEST['userName']);
	$aresult['userEmail'] = get_user_email($_REQUEST['userName']);
	$aresult['userAlert'] = get_user_alert($_REQUEST['userName']);
	$aresult['userAlertID'] = get_user_alertID($_REQUEST['userName']);
	$aresult['userSrvReqs'] = get_user_srvreqs($_REQUEST['userName']);
	
	date_default_timezone_set("Africa/Lagos");
	$ctime = date('Y-m-d H:i:s');
	
	//update mobile login
	$result = mysql_query("UPDATE tbl_user SET mobile_login = '$ctime' WHERE username = '$userPhone'") or die(mysql_error()."- Insert otp error");
	
	//Get User City
	$aresult['city'] = array();
	$aquery = mysql_query("Select * FROM tbl_city") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_array($aquery)) {
	extract($row);
	$aresult['city'][] = $cityname;
	}
	
	//Get User Alerts
	$aresult['userAlertMsg'] = array();
	$aquery = mysql_query("Select msg,status,daddfmt,timefmt FROM tbl_alert WHERE username='$userPhone' ORDER BY sno DESC LIMIT 20") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['userAlertMsg'][]=$row;
	}
	
	//Get Service Category except others
	$aresult['srvCategory'] = array();
	$aquery = mysql_query("Select sno,catname,app_img FROM tbl_cat WHERE catname !='Others' ORDER BY catname ASC") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['srvCategory'][]=$row;
	}
	
	//Get Others Service Category
	$aresult['srvCatOthers'] = array();
	$aquery = mysql_query("Select sno,catname,app_img FROM tbl_cat WHERE catname ='Others'") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['srvCatOthers'][]=$row;
	}
	
	//Get User Service Request List
	$aresult['userSrvReqList'] = array();
	$aquery = mysql_query("Select sno, catname, datefmt, service_time, service_status, service_request FROM tbl_services WHERE user_id ='$userPhone' ORDER BY service_date ASC") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['userSrvReqList'][]=$row;
	}
	
	

$aresult['type'] = $_REQUEST['type'];
	
echo json_encode($aresult);

}


if($_REQUEST['type'] == "login")
{
$userPhone = $_REQUEST['userName'];
	
$val = check_user($_REQUEST['userName'],md5($_REQUEST['userPass']));
	
$aresult['code'] = "0";
if($val != 1)
{$aresult['status'] = "failed"; $aresult['message'] = "Login Failed, Try again!"; $error_stat = 1;}

if (get_user_stat($_REQUEST['userName']) == "Unverified")
{
	$aresult['status'] = "incomplete"; 
	$otpcode = mt_rand(10001, 99999);
	$aresult['otpcode'] = $otpcode;  
	date_default_timezone_set("Africa/Lagos");
	$ctime = date('Y-m-d H:i:s');
	$result = mysql_query("INSERT INTO tbl_otp (otpcode, userphone, datesent) VALUES('$otpcode', '$userPhone', '$ctime')") or die(mysql_error()."- Insert otp error");
	$aresult['fullname'] = get_fullname($_REQUEST['userName']); 
	$aresult['message'] = "Complete your registration"; 
	$error_stat = 1;
 }

if(!isset($error_stat))
{
	$aresult['status'] = "success";
	$aresult['message'] = "success";
	$aresult['uid'] = get_userid($_REQUEST['userName']);
	$aresult['fullname'] = get_fullname($_REQUEST['userName']);
	$aresult['userCity'] = get_city($_REQUEST['userName']);
	$aresult['userEmail'] = get_user_email($_REQUEST['userName']);
	$aresult['userAlert'] = get_user_alert($_REQUEST['userName']);
	$aresult['userAlertID'] = get_user_alertID($_REQUEST['userName']);
	$aresult['userSrvReqs'] = get_user_srvreqs($_REQUEST['userName']);
	$aresult['userAddress'] = get_user_address($_REQUEST['userName']);
	
	date_default_timezone_set("Africa/Lagos");
	$ctime = date('Y-m-d H:i:s');
	
	//update mobile login
	$result = mysql_query("UPDATE tbl_user SET mobile_login = '$ctime' WHERE username = '$userPhone'") or die(mysql_error()."- Insert otp error");
	
	//Get User City
	$aresult['city'] = array();
	$aquery = mysql_query("Select * FROM tbl_city") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_array($aquery)) {
	extract($row);
	$aresult['city'][] = $cityname;
	}
	
	//Get User Alerts
	$aresult['userAlertMsg'] = array();
	$aquery = mysql_query("Select msg,status,daddfmt,timefmt FROM tbl_alert WHERE username='$userPhone' ORDER BY sno DESC LIMIT 20") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['userAlertMsg'][]=$row;
	}
	
	//Get Service Category except others
	$aresult['srvCategory'] = array();
	$aquery = mysql_query("Select sno,catname,app_img FROM tbl_cat WHERE catname !='Others' ORDER BY catname ASC") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['srvCategory'][]=$row;
	}
	
	//Get Others Service Category
	$aresult['srvCatOthers'] = array();
	$aquery = mysql_query("Select sno,catname,app_img FROM tbl_cat WHERE catname ='Others'") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['srvCatOthers'][]=$row;
	}
	
	//Get User Service Request List
	$aresult['userSrvReqList'] = array();
	$aquery = mysql_query("Select sno, catname, datefmt, service_time, service_status, service_request FROM tbl_services WHERE user_id ='$userPhone' ORDER BY service_date ASC") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['userSrvReqList'][]=$row;
	}
	
	
}

$aresult['type'] = $_REQUEST['type'];

echo json_encode($aresult);

//Add for unverified user to complete verification - done
}


if($_REQUEST['type'] == "reg")
{
$mtype = $_REQUEST['type'];
$userEmail = clean_data($_REQUEST['userEmail']); 
$inviteCode = clean_data($_REQUEST['inviteCode']);
$fullName = clean_data($_REQUEST['fullName']);
$userPhone = clean_data($_REQUEST['userPhone']);
$userPass = md5($userPhone);
date_default_timezone_set("Africa/Lagos");
$ctime = date('Y-m-d H:i:s');

//check if email exist
$val = check_phone($userPhone);

if ($val != 0)
{$aresult['message'] = "Phone Number is already registered!"; $error_stat = 1;}

if ((!isset($error_stat)) && (strlen($userPhone) != 11))
{$aresult['message'] = "Invalid Phone Number!"; $error_stat = 1;}


if(!isset($error_stat))
{
//Create user account
$query = "INSERT INTO tbl_user (fullname, username, user_email, password, ref_invitecode, dcreate, ucreate, reg_source, user_status) VALUES('$fullName', '$userPhone', '$userEmail', '$userPass', '$inviteCode', '$ctime', 'Self' , 'Mobile', 'Unverified')";
$result = mysql_query($query) or die(mysql_error()."- Insert user error");

//Create OTP
$otpcode = mt_rand(10001, 99999);
$result = mysql_query("INSERT INTO tbl_otp (otpcode, userphone, datesent) VALUES('$otpcode', '$userPhone', '$ctime')") or die(mysql_error()."- Insert otp error");

//Add Status Record
$result = mysql_query("INSERT INTO tbl_status (username, ustat, dadd) VALUES('$userPhone', 'Unverified', '$ctime')") or die(mysql_error()."- Insert otp error");


//send otpcode via sms - later (smslive247 and multitexter)
$msg = "Your Xperthands OTP Code is $otpcode";
send_sms($userPhone, $msg);
	
$aresult['code'] = "0";
$aresult['otpcode'] = $otpcode;
$aresult['message'] = "success";
}

$aresult['type'] = $mtype;

echo json_encode($aresult);

}


if($_REQUEST['type'] == "prvdreg")
{
$mtype = $_REQUEST['type'];
$srv_des = clean_data($_REQUEST['srv_des']); 
$srv_add = clean_data($_REQUEST['srv_add']);
$userPhone = clean_data($_REQUEST['userPhone']);
date_default_timezone_set("Africa/Lagos");
$ctime = date('Y-m-d H:i:s');

//check if user alread a provider
$val = check_phoneprvd($userPhone);

if ($val != 0)
{
	$aresult['message'] = "registered"; $error_stat = 1; new_notification($userPhone, "You are already Registered as a Provider");
	//get provider details
	$aresult['prvdadd'] = get_prvdadd($userPhone);
	$aresult['prvdabout'] = get_prvdabout($userPhone);
	
	$aresult['prvdprofile'] = prvdprofile($userPhone);
	$aresult['prvdprofcitylist'] = prvdprofcitylist($userPhone);
	$aresult['prvdprofsrvlist'] = prvdprofsrvlist($userPhone);
	
}


if(!isset($error_stat))
{
//Create user account
$query = "INSERT INTO tbl_srvprovider (username, srv_des, srv_add, dcreate) VALUES('$userPhone', '$srv_des', '$srv_add', '$ctime')";
$result = mysql_query($query) or die(mysql_error()."- Insert user error");
	
$aresult['code'] = "0";
$aresult['message'] = "success"; new_notification($userPhone, "Your Provider Registration was Successfully");
}

	$aquery = mysql_query("Select * FROM tbl_city") or die("city list error ".mysql_error());
	while ($row = mysql_fetch_array($aquery)) {
	extract($row);
	$uresult['cityname'] = $cityname;
	$uresult['citysno'] = $sno;
	$citychk =  check_prvdcity($userPhone, $sno);
	
	$uresult['hasPrvd'] = $citychk;

	$aresult['prvdcitylist'][]=$uresult;
	}

$aresult['type'] = $mtype;

echo json_encode($aresult);

}


if($_REQUEST['type'] == "saveprofile")
{
$mtype = $_REQUEST['type'];
$srv_des = clean_data($_REQUEST['srv_des']); 
$srv_add = clean_data($_REQUEST['srv_add']);
$userPhone = clean_data($_REQUEST['userPhone']);
$fullName = clean_data($_REQUEST['fullName']);
date_default_timezone_set("Africa/Lagos");
$ctime = date('Y-m-d H:i:s');


//Create user account
$query = "UPDATE tbl_srvprovider SET srv_des='$srv_des', fullname='$fullName', srv_add='$srv_add' WHERE username='$userPhone'";
$result = mysql_query($query) or die(mysql_error()."- Insert user error");
	
$aresult['code'] = "0";
$aresult['message'] = "success"; new_prvdnotification($userPhone, "Your Provider Profie was Updated!");


$aresult['type'] = $mtype;

echo json_encode($aresult);

}


if($_REQUEST['type'] == "prvdcheck")
{
$mtype = $_REQUEST['type'];
$userPhone = clean_data($_REQUEST['userPhone']);

//check if user alread a provider
$val = check_phoneprvd($userPhone);

if ($val != 0)
{
	$aresult['message'] = "true"; $error_stat = 1; new_notification($userPhone, "Access granted to Provider Dashboard");
	//get provider details
	$aresult['prvdadd'] = get_prvdadd($userPhone);
	$aresult['prvdabout'] = get_prvdabout($userPhone);
	$aquery = mysql_query("Select * FROM tbl_city") or die("city list error ".mysql_error());
	while ($row = mysql_fetch_array($aquery)) {
	extract($row);
	$uresult['cityname'] = $cityname;
	$uresult['citysno'] = $sno;
	$citychk =  check_prvdcity($userPhone, $sno);
	
	$uresult['hasPrvd'] = $citychk;
	

	$aresult['prvdcitylist'][]=$uresult;
	}
	
	
	$aresult['prvdAlert'] = get_prvd_alert($_REQUEST['userPhone']);
	$aresult['prvdAlertID'] = get_prvd_alertID($_REQUEST['userPhone']);
	$aresult['prvdStatus'] = get_prvd_status($_REQUEST['userPhone']);
	$aresult['prvdOnlineStatus'] = get_prvd_onlinestatus($_REQUEST['userPhone']);
	
	$prvdaltmsg = "Welcome to Provider Dashboard. Only Verified & Online Profile Status will get Service Request. ";

if(get_doc_status($_REQUEST['userPhone']) == "Incomplete")
{
	$prvdaltmsg .= "Upload required documents. ";
}
$aresult['prvdReqDocs'] = get_doc_status($_REQUEST['userPhone']);

if(get_prof_status($_REQUEST['userPhone']) == "False")
{
	$prvdaltmsg .= "Complete your Profile details. ";
}
	$aresult['prvdaltmsg'] = $prvdaltmsg;
//Get Provider Alerts
	$aresult['prvdAlertMsg'] = array();
	$aquery = mysql_query("Select msg,status,daddfmt,timefmt FROM tbl_alertprvd WHERE username='$userPhone' ORDER BY sno DESC LIMIT 20") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['prvdAlertMsg'][]=$row;
	}
	
	$prvd_id = get_prvdsno($userPhone);
	
	//Get Provider Service Request List
	$aresult['prvdSrvReqList'] = array();
	$aquery = mysql_query("Select tbl_services.sno, tbl_services.catname, tbl_services.datefmt, tbl_services.service_time, tbl_services.service_status, tbl_services.service_request, tbl_user.fullname  FROM tbl_services, tbl_user WHERE tbl_services.prvd_id ='$prvd_id' AND tbl_user.username=tbl_services.user_id ORDER BY service_date ASC") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['prvdSrvReqList'][]=$row;
	}
	
	$aresult['prvdSrvReqs'] = get_prvd_srvreqs($prvd_id);
	
	
}
else
{$aresult['message'] = "false"; $error_stat = 1; new_notification($userPhone, "Access denied to Provider Dashboard");}

$aresult['type'] = $mtype;
$aresult['prvdprofile'] = prvdprofile($userPhone);
$aresult['profile_img'] = prvdprofileimg($userPhone);
$aresult['prvdprofcitylist'] = prvdprofcitylist($userPhone);
$aresult['prvdprofsrvlist'] = prvdprofsrvlist($userPhone);
echo json_encode($aresult);

}


if($_REQUEST['type'] == "useralert")
{
$mtype = $_REQUEST['type'];
$userPhone = clean_data($_REQUEST['userPhone']);
$userAlertID = clean_data($_REQUEST['userAlertID']);
$userName = $userPhone;

$val = check_newalert($userPhone, $userAlertID);

if ($val != 0)
{
	$aresult['message'] = "true"; $error_stat = 1;
	$aresult['userAlert'] = get_user_alert($userPhone);
	$aresult['userAlertID'] = get_user_alertID($userPhone);

	//Get User Alerts
		$aresult['userAlertMsg'] = array();
		$aquery = mysql_query("Select msg,status,daddfmt,timefmt FROM tbl_alert WHERE username='$userPhone' ORDER BY sno DESC LIMIT 15") or die("user check error alert1".mysql_error());
		while ($row = mysql_fetch_assoc($aquery))  {
		$aresult['userAlertMsg'][]=$row;
	  }
		
		//$aquery = mysql_query("UPDATE tbl_alert SET status='Read' WHERE username='$userPhone'") or die("user check error ".mysql_error());
}
else
{$aresult['message'] = "false"; $error_stat = 1; }



//Get User Service Request List
	$aresult['userSrvReqList'] = array();
	$aquery = mysql_query("Select sno, catname, datefmt, service_time, service_status, service_request FROM tbl_services WHERE user_id ='$userPhone' ORDER BY service_date ASC") or die("user check error alert".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['userSrvReqList'][]=$row;
	}
	
$aresult['userSrvReqs'] = get_user_srvreqs($userPhone);

$aresult['type'] = $mtype;

echo json_encode($aresult);

}

if($_REQUEST['type'] == "useralertx")
{
$mtype = $_REQUEST['type'];
$userPhone = clean_data($_REQUEST['userPhone']);
$prvdAlertID = clean_data($_REQUEST['prvdAlertID']);
$userName = $userPhone;

$val = check_newalertx($userPhone, $prvdAlertID);

if ($val != 0)
{
	$aresult['message'] = "true"; $error_stat = 1;
	$aresult['prvdAlert'] = get_prvd_alert($userPhone);
	$aresult['prvdAlertID'] = get_prvd_alertID($userPhone);

	//Get Provider Alerts
	$aresult['prvdAlertMsg'] = array();
	$aquery = mysql_query("Select msg,status,daddfmt,timefmt FROM tbl_alertprvd WHERE username='$userPhone' ORDER BY sno DESC LIMIT 20") or die("user check error alertx1".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['prvdAlertMsg'][]=$row;
	}
		
}
else
{$aresult['message'] = "false"; $error_stat = 1; }

$prvd_id = get_prvdsno($userPhone);
	
	//Get Provider Service Request List
	$aresult['prvdSrvReqList'] = array();
	$aquery = mysql_query("Select tbl_services.sno, tbl_services.catname, tbl_services.datefmt, tbl_services.service_time, tbl_services.service_status, tbl_services.service_request, tbl_user.fullname  FROM tbl_services, tbl_user WHERE tbl_services.prvd_id ='$prvd_id' AND tbl_user.username=tbl_services.user_id ORDER BY service_date ASC") or die("user check error alertx".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['prvdSrvReqList'][]=$row;
	}
	
	$aresult['prvdSrvReqs'] = get_prvd_srvreqs($prvd_id);

$aresult['type'] = $mtype;

echo json_encode($aresult);

}

if($_REQUEST['type'] == "updateuseralert")
{
$mtype = $_REQUEST['type'];
$userPhone = clean_data($_REQUEST['userPhone']);
$userAlertID = clean_data($_REQUEST['userAlertID']);
$userName = $userPhone;

$userAlertID++;

$aquery = mysql_query("UPDATE tbl_alert SET status='Read' WHERE username='$userPhone' AND sno < $userAlertID") or die("user check error ".mysql_error());

	$aresult['userAlert'] = get_user_alert($userPhone);
	$aresult['userAlertID'] = get_user_alertID($userPhone);

	//Get User Alerts
		$aresult['userAlertMsg'] = array();
		$aquery = mysql_query("Select msg,status,daddfmt,timefmt FROM tbl_alert WHERE username='$userPhone' ORDER BY sno DESC LIMIT 15") or die("user check error ".mysql_error());
		while ($row = mysql_fetch_assoc($aquery))  {
		$aresult['userAlertMsg'][]=$row;
	  }

	  //Get User Service Request List
	$aresult['userSrvReqList'] = array();
	$aquery = mysql_query("Select sno, catname, datefmt, service_time, service_status, service_request FROM tbl_services WHERE user_id ='$userPhone' ORDER BY service_date ASC") or die("user check error alert".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['userSrvReqList'][]=$row;
	}
	
$aresult['userSrvReqs'] = get_user_srvreqs($userPhone);
$aresult['message'] = "success";
$aresult['type'] = $mtype;

echo json_encode($aresult);

}

if($_REQUEST['type'] == "updateuseralertx")
{
$mtype = $_REQUEST['type'];
$userPhone = clean_data($_REQUEST['userPhone']);
$prvdAlertID = clean_data($_REQUEST['prvdAlertID']);
$userName = $userPhone;

$prvdAlertID++;

$aquery = mysql_query("UPDATE tbl_alertprvd SET status='Read' WHERE username='$userPhone' AND sno < $prvdAlertID") or die("user check error ".mysql_error());

	$aresult['prvdAlert'] = get_prvd_alert($userPhone);
	$aresult['prvdAlertID'] = get_prvd_alertID($userPhone);

	//Get Provider Alerts
	$aresult['prvdAlertMsg'] = array();
	$aquery = mysql_query("Select msg,status,daddfmt,timefmt FROM tbl_alertprvd WHERE username='$userPhone' ORDER BY sno DESC LIMIT 20") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['prvdAlertMsg'][]=$row;
	}

$aresult['message'] = "success";
$aresult['type'] = $mtype;

echo json_encode($aresult);

}

if($_REQUEST['type'] == "getproviders")
{
$mtype = $_REQUEST['type'];
$userPhone = clean_data($_REQUEST['userPhone']);
$userCity = clean_data($_REQUEST['userCity']);
$catName = clean_data($_REQUEST['catName']);
$catSno = clean_data($_REQUEST['catSno']);
$citySno = get_citysno($userCity);

$userName = $userPhone;

//Check that userphone is not same - Provider cannot request themselves

//$aquery = mysql_query("SELECT tbl_srvprovider.prvd_rating, tbl_srvprovider.fullname, tbl_srvprovider.profile_img, tbl_city.cityname FROM tbl_srvprovider, tbl_city WHERE tbl_srvprovider.srv_city LIKE '%$userCity%' AND tbl_srvprovider.srv_status='Registered' AND tbl_city.sno = '$citySno'") or die("user check error ".mysql_error());
$aquery = mysql_query("SELECT tbl_srvprovider.prvd_rating, tbl_srvprovider.fullname, tbl_srvprovider.profile_img, tbl_srvprovider.srv_type, tbl_srvprovider.sno, tbl_srvprovider.srv_city, tbl_city.cityname FROM tbl_srvprovider, tbl_city WHERE tbl_srvprovider.srv_city LIKE '%$userCity%' AND tbl_srvprovider.srv_type LIKE '%$catName%' AND tbl_srvprovider.srv_status='Registered' AND tbl_srvprovider.username !='$userName' AND tbl_city.sno = '$citySno'") or die("user check error ".mysql_error());
//$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);

if ($nrow > 0)
{
	//Get Provider list
	$aresult['srvPrvdList'] = array();
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['srvPrvdList'][]=$row;
	}
	
	$aresult['message'] = "success";
}
else
{$aresult['message'] = "none";}


$aresult['type'] = $mtype;
$aresult['nrow'] = $nrow;

echo json_encode($aresult);

}


if($_REQUEST['type'] == "detailsprvd")
{
$mtype = $_REQUEST['type'];
$prvdsno = clean_data($_REQUEST['prvdsno']);

$userName = $userPhone;

$aquery = mysql_query("SELECT * FROM tbl_srvprovider WHERE sno='$prvdsno'") or die("user check error ".mysql_error());
//$row = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);


	//Get Provider list
	$aresult['srvPrvdDetails'] = array();
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['srvPrvdDetails'][]=$row;
	}
	
	$aresult['message'] = "success";



$aresult['type'] = $mtype;
$aresult['nrow'] = $nrow;

echo json_encode($aresult);

}

if($_REQUEST['type'] == "usrvdetails")
{
$mtype = $_REQUEST['type'];
$usrvsno = clean_data($_REQUEST['usrvsno']);

$userName = $userPhone;

$aquery = mysql_query("SELECT * FROM tbl_services WHERE sno='$usrvsno'") or die("user check error ".mysql_error());
//$rowstat = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);


	//Get Provider list
	$aresult['usrvDets'] = array();
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['usrvDets'][]=$row;
	}
	
	$aresult['usrvStats'] = get_srvstatus($usrvsno);
	$aresult['message'] = "success";



$aresult['type'] = $mtype;

echo json_encode($aresult);

}

if($_REQUEST['type'] == "usrvdetailsx")
{
$mtype = $_REQUEST['type'];
$usrvsno = clean_data($_REQUEST['usrvsno']);

$userName = $userPhone;

$prvd_id = get_prvdid($usrvsno);

if(empty($prvd_id))
{$prvd_id = "";}

$aquery = mysql_query("SELECT tbl_services.*, tbl_srvprovider.sno as prvdsno, tbl_srvprovider.fullname, tbl_srvprovider.username, tbl_srvprovider.profile_img, tbl_srvprovider.prvd_rating FROM tbl_services, tbl_srvprovider WHERE tbl_services.sno='$usrvsno' AND tbl_srvprovider.sno='$prvd_id'") or die("user check error ".mysql_error());
//$rowstat = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);


	//Get Provider list
	$aresult['usrvDets'] = array();
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['usrvDets'][]=$row;
	}
	
	$aresult['usrvStats'] = get_srvstatus($usrvsno);
	$aresult['message'] = "success";



$aresult['type'] = $mtype;

echo json_encode($aresult);

}

if($_REQUEST['type'] == "uprvdetailsx")
{
$mtype = $_REQUEST['type'];
$usrvsno = clean_data($_REQUEST['usrvsno']);

$userName = $userPhone;

$prvd_id = get_prvdid($usrvsno);

$aquery = mysql_query("SELECT tbl_services.*, tbl_srvprovider.sno as prvdsno, tbl_srvprovider.fullname, tbl_srvprovider.profile_img, tbl_srvprovider.prvd_rating FROM tbl_services, tbl_srvprovider WHERE tbl_services.sno='$usrvsno' AND tbl_srvprovider.sno='$prvd_id'") or die("user check error ".mysql_error());
//$rowstat = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);


	//Get Provider list
	$aresult['usrvDets'] = array();
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['usrvDets'][]=$row;
	}
	
	$aresult['usrvStats'] = get_srvstatus($usrvsno);
	$aresult['message'] = "success";



$aresult['type'] = $mtype;

echo json_encode($aresult);

}

if($_REQUEST['type'] == "editservice")
{
$mtype = $_REQUEST['type'];
$usrvsno = clean_data($_REQUEST['usrvsno']);
$userPhone = clean_data($_REQUEST['userPhone']);
$srvadd = clean_data($_REQUEST['srvadd']);
$srvdetails = clean_data($_REQUEST['srvdetails']);
$srvtime = clean_data($_REQUEST['srvtime']);
$srvdate = clean_data($_REQUEST['srvdate']);

$userName = $userPhone;

$axquery = mysql_query("SELECT * FROM tbl_services WHERE sno='$usrvsno'") or die("user check error ".mysql_error());
$rowstat = mysql_fetch_array($axquery);
$srv_id = $rowstat['service_id'];
$catName = $rowstat['catname'];
$userPhone = $rowstat['user_id'];
$usrvstats = get_srvstatus($usrvsno);

date_default_timezone_set("Africa/Lagos");
$ctime = date('Y-m-d H:i:s');

date_default_timezone_set("Africa/Lagos");
$curtime = date('Ymd');

$dcreate= date_create($srvdate);
$dcreate = date_format($dcreate, "Ymd");

if ($dcreate < $curtime)
{
	$aresult['message'] = "error";
	$aresult['errormsg'] = "Selected Date is in the Past. Please Change and Re-submit!";
}
else
{

//check service status
if (($usrvstats  == "Pending") || ($usrvstats  == "Assigned"))
{
	$datefmt = date_create($srvdate);
	$datefmt = date_format($datefmt, "F d, Y");
	
	//Update service schedule
	$srvquery = mysql_query("UPDATE tbl_services SET service_date='$srvdate', service_time='$srvtime', service_request='$srvdetails', service_add='$srvadd', datefmt='$datefmt' WHERE sno='$usrvsno'") or die("user check error ".mysql_error());
	
	//Notify User
	new_notification($userPhone,"Your Service Request ID: $srv_id has been Rescheduled.");
	
	//Notify Admin
	new_notification("Admin","The Service ID: $srv_id for request in $catName has been Rescheduled by User.");
	
	//Notify Prvd if Assigned or Selected
	if (!empty($rowstat['prvd_id']))
	{
		$prvd_number = get_prvdnum($rowstat['prvd_id']);
		new_prvdnotification($prvd_number,"The Service ID: $srv_id for request in $catName has been Rescheduled by User.");
		//Send sms
		send_sms($prvd_number, "The Service ID: $srv_id for request in $catName has been Rescheduled by User.");
	}
	
	$stat_des = "The Service ID: $srv_id for request in $catName has been Rescheduled by User";
	
	//Insert Status History
	$query = "INSERT INTO  tbl_servstatus(srv_id, srv_status, stat_des, dadd, ucreate) VALUES('$srv_id', 'Pending', '$stat_des', '$ctime', 'System')";
	$result = mysql_query($query) or die(mysql_error()."- Insert service error");
	
	
}


$aquery = mysql_query("SELECT * FROM tbl_services WHERE sno='$usrvsno' ORDER BY service_date") or die("user check error ".mysql_error());
//$rowstat = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);


	//Get User Service Request List
	$aresult['userSrvReqList'] = array();
	$aquery = mysql_query("Select sno, catname, datefmt, service_time, service_status, service_request FROM tbl_services WHERE user_id ='$userPhone' ORDER BY service_date ASC") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['userSrvReqList'][]=$row;
	}
	
	$aresult['userSrvReqs'] = get_user_srvreqs($userPhone);
	$aresult['message'] = "success";

}

$aresult['type'] = $mtype;

echo json_encode($aresult);

}

if($_REQUEST['type'] == "usrvcancel")
{
$mtype = $_REQUEST['type'];
$usrvsno = clean_data($_REQUEST['usrvsno']);

$axquery = mysql_query("SELECT * FROM tbl_services WHERE sno='$usrvsno'") or die("user check error ".mysql_error());
$rowstat = mysql_fetch_array($axquery);
$srv_id = $rowstat['service_id'];
$catName = $rowstat['catname'];
$userPhone = $rowstat['user_id'];
$usrvstats = get_srvstatus($usrvsno);

date_default_timezone_set("Africa/Lagos");
$ctime = date('Y-m-d H:i:s');

//check service status
if (($usrvstats  == "Pending") || ($usrvstats  == "Assigned"))
{
	

	//Update service to Cancelled
	$srvquery = mysql_query("UPDATE tbl_services SET service_status='Cancelled' WHERE sno='$usrvsno'") or die("user check error ".mysql_error());
	
	//Notify User
	new_notification($userPhone,"Your Service Request ID: $srv_id has been cancelled.");
	
	//Notify Admin
	new_notification("Admin","The Service ID: $srv_id for request in $catName has been cancelled by User.");
	
	//Notify Prvd if Assigned or Selected
	if (!empty($rowstat['prvd_id']))
	{
		$prvd_number = get_prvdnum($rowstat['prvd_id']);
		new_prvdnotification($prvd_number,"The Service ID: $srv_id for request in $catName has been cancelled by User.");
		//Send sms
		send_sms($prvd_number, "The Service ID: $srv_id for request in $catName has been cancelled by User.");
	}
	
	$stat_des = "The Service ID: $srv_id for request in $catName has been cancelled by User";
	
	//Insert Status History
	$query = "INSERT INTO  tbl_servstatus(srv_id, srv_status, stat_des, dadd, ucreate) VALUES('$srv_id', 'Cancelled', '$stat_des', '$ctime', 'System')";
	$result = mysql_query($query) or die(mysql_error()."- Insert service error");
	
	
	
}
$aquery = mysql_query("SELECT * FROM tbl_services WHERE sno='$usrvsno' ORDER BY service_date") or die("user check error ".mysql_error());
//$rowstat = mysql_fetch_array($aquery);
$nrow = mysql_num_rows($aquery);


	//Get User Service Request List
	$aresult['userSrvReqList'] = array();
	$aquery = mysql_query("Select sno, catname, datefmt, service_time, service_status, service_request FROM tbl_services WHERE user_id ='$userPhone' ORDER BY service_date ASC") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['userSrvReqList'][]=$row;
	}
	
	$aresult['userSrvReqs'] = get_user_srvreqs($userPhone);
	$aresult['message'] = "success";



$aresult['type'] = $mtype;

echo json_encode($aresult);

}

if($_REQUEST['type'] == "prvdaccept")
{
$mtype = $_REQUEST['type'];
$usrvsno = clean_data($_REQUEST['usrvsno']);

$axquery = mysql_query("SELECT * FROM tbl_services WHERE sno='$usrvsno'") or die("user check error ".mysql_error());
$rowstat = mysql_fetch_array($axquery);
$srv_id = $rowstat['service_id'];
$catName = $rowstat['catname'];
$userPhone = $rowstat['user_id'];
$usrvstats = get_srvstatus($usrvsno);

date_default_timezone_set("Africa/Lagos");
$ctime = date('Y-m-d H:i:s');

$prvd_number = get_prvdnum($rowstat['prvd_id']);
$prvd_name = get_prvdname($rowstat['prvd_id']);

//check service status
if (($usrvstats  == "Pending") || ($usrvstats  == "Assigned"))
{
	

	//Update service to Cancelled
	$srvquery = mysql_query("UPDATE tbl_services SET service_status='Assigned' WHERE sno='$usrvsno'") or die("user check error ".mysql_error());
	
	//Notify User
	new_notification($userPhone,"Provider - $prvd_name ($prvd_number) assigned/requested on your Service Request ID: $srv_id for $catName has accepted and will be contacting you soon.");
	
	//Notify Admin
	new_notification("Admin","The Service ID: $srv_id for request in $catName has been accepted by Provider ($prvd_name - $prvd_number)");
	
	//Notify User if Assigned or Selected
	if (!empty($rowstat['prvd_id']))
	{
		$prvd_number = get_prvdnum($rowstat['prvd_id']);
		new_prvdnotification($prvd_number,"Service ID: $srv_id for request in $catName was accepted by you");
		//Send sms to user
		send_sms($userPhone, "Provider - $prvd_name ($prvd_number) assigned/requested on your Service Request ID: $srv_id for $catName has accepted and will be contacting you soon.");
	}
	
	$stat_des = "The Service ID: $srv_id for request in $catName has been accepted by Provider ($prvd_name - $prvd_number)";
	
	//Insert Status History
	$query = "INSERT INTO  tbl_servstatus(srv_id, srv_status, stat_des, dadd, ucreate) VALUES('$srv_id', 'Assigned', '$stat_des', '$ctime', 'System')";
	$result = mysql_query($query) or die(mysql_error()."- Insert service error");
	
	
	
}
	$prvd_id = $rowstat['prvd_id'];
	
	//Get Provider Service Request List
	$aresult['prvdSrvReqList'] = array();
	$aquery = mysql_query("Select tbl_services.sno, tbl_services.catname, tbl_services.datefmt, tbl_services.service_time, tbl_services.service_status, tbl_services.service_request, tbl_user.fullname  FROM tbl_services, tbl_user WHERE tbl_services.prvd_id ='$prvd_id' AND tbl_user.username=tbl_services.user_id ORDER BY service_date ASC") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['prvdSrvReqList'][]=$row;
	}
	
	$aresult['prvdSrvReqs'] = get_prvd_srvreqs($prvd_id);
	
	$aresult['message'] = "success";



$aresult['type'] = $mtype;

echo json_encode($aresult);

}


if($_REQUEST['type'] == "prvdreject")
{
$mtype = $_REQUEST['type'];
$usrvsno = clean_data($_REQUEST['usrvsno']);
$rejectval = clean_data($_REQUEST['rejectval']);

$axquery = mysql_query("SELECT * FROM tbl_services WHERE sno='$usrvsno'") or die("user check error ".mysql_error());
$rowstat = mysql_fetch_array($axquery);
$srv_id = $rowstat['service_id'];
$catName = $rowstat['catname'];
$userPhone = $rowstat['user_id'];
$usrvstats = get_srvstatus($usrvsno);

date_default_timezone_set("Africa/Lagos");
$ctime = date('Y-m-d H:i:s');

$prvd_number = get_prvdnum($rowstat['prvd_id']);
$prvd_name = get_prvdname($rowstat['prvd_id']);

//check service status
if (($usrvstats  == "Pending") || ($usrvstats  == "Assigned"))
{
	

	//Update service to Cancelled
	$srvquery = mysql_query("UPDATE tbl_services SET service_status='Pending', prvd_id='' WHERE sno='$usrvsno'") or die("user check error ".mysql_error());
	
	//Notify User
	new_notification($userPhone,"Provider assigned/requested on your Service Request ID: $srv_id is not available at the moment. Our System will assigned another available provider.");
	
	//Notify Admin
	new_notification("Admin","The Service ID: $srv_id for request in $catName has been rejected by Provider ($prvd_name - $prvd_number) with reason - $rejectval");
	
	//Notify User if Assigned or Selected
	if (!empty($rowstat['prvd_id']))
	{
		$prvd_number = get_prvdnum($rowstat['prvd_id']);
		new_prvdnotification($prvd_number,"Service ID: $srv_id for request in $catName was rejected by you");
		//Send sms to user
		send_sms($userPhone, "Provider assigned/requested on your Service Request ID: $srv_id in $catName is not available at the moment. Our System will assigned another available provider.");
	}
	
	$stat_des = "The Service ID: $srv_id for request in $catName has been rejected by Provider ($prvd_name - $prvd_number) with reason - $rejectval";
	
	//Insert Status History
	$query = "INSERT INTO  tbl_servstatus(srv_id, srv_status, stat_des, dadd, ucreate) VALUES('$srv_id', 'Rejected', '$stat_des', '$ctime', 'System')";
	$result = mysql_query($query) or die(mysql_error()."- Insert service error");
	
	
	
}
	$prvd_id = $rowstat['prvd_id'];
	
	//Get Provider Service Request List
	$aresult['prvdSrvReqList'] = array();
	$aquery = mysql_query("Select tbl_services.sno, tbl_services.catname, tbl_services.datefmt, tbl_services.service_time, tbl_services.service_status, tbl_services.service_request, tbl_user.fullname  FROM tbl_services, tbl_user WHERE tbl_services.prvd_id ='$prvd_id' AND tbl_user.username=tbl_services.user_id ORDER BY service_date ASC") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['prvdSrvReqList'][]=$row;
	}
	
	$aresult['prvdSrvReqs'] = get_prvd_srvreqs($prvd_id);
	
	$aresult['message'] = "success";



$aresult['type'] = $mtype;

echo json_encode($aresult);

}

if($_REQUEST['type'] == "bookservice")
{
$mtype = $_REQUEST['type'];
$prvdsno = clean_data($_REQUEST['prvdsno']);
$userPhone = clean_data($_REQUEST['userPhone']);
$srvadd = clean_data($_REQUEST['srvadd']);
$srvdetails = clean_data($_REQUEST['srvdetails']);
$userCity = clean_data($_REQUEST['userCity']);
$srvtime = clean_data($_REQUEST['srvtime']);
$srvdate = clean_data($_REQUEST['srvdate']);
$catName = clean_data($_REQUEST['catName']);
$catSno = clean_data($_REQUEST['catSno']);
$recomprvd = clean_data($_REQUEST['recomprvd']);

$userName = $userPhone;

date_default_timezone_set("Africa/Lagos");
$curtime = date('Ymd');

$dcreate= date_create($srvdate);
$dcreate = date_format($dcreate, "Ymd");

if ($dcreate < $curtime)
{
	$aresult['message'] = "error";
	$aresult['errormsg'] = "Selected Date is in the Past. Please Change and Re-submit!";
}
else
{
	//action
	date_default_timezone_set("Africa/Lagos");
	$ctime = date('Y-m-d H:i:s');
	$datefmt = date_create($srvdate);
	$datefmt = date_format($datefmt, "F d, Y");
	$srv_idn = get_srvid();
	$srv_idn++;
	$srv_ida = mt_rand(1,26);
	$randalph = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
	$srv_id = "ID".$srv_idn.$randalph[$srv_ida];
	$srk1 = mt_rand(100001, 9999999);
	$srk2 = mt_rand(1,26);
	$srk3 = mt_rand(1,26);	
	$srk4 = mt_rand(1,26);
	$srv_key = $randalph[$srk2].$randalph[$srk4].$srk1.$randalph[$srk3];
	//Insert New Service Booking
	$query = "INSERT INTO tbl_services (user_id, prvd_id, service_id, service_date, date_added, service_status, service_request, service_add, service_time, service_key, srv_idn, datefmt, catname, srv_catid, service_location) VALUES('$userPhone', '$prvdsno', '$srv_id', '$srvdate', '$ctime', 'Pending', '$srvdetails', '$srvadd', '$srvtime', '$srv_key', '$srv_idn', '$datefmt', '$catName', '$catSno', '$userCity')";
	$result = mysql_query($query) or die(mysql_error()."- Insert service error");
	
	$prvd_name = get_prvdname($prvdsno);
	$prvd_number = get_prvdnum($prvdsno);
	
	if(!empty($prvdsno))
	{
		$stat_des = "Service Booked with Selected Provider - $prvd_name ($prvd_number)";
		//Notify Provider (Notifications)
		new_prvdnotification($prvd_number, "You have a New Service Request in Service Category: $catName for your Immediate Attention.");
		//send_sms
		send_sms($prvd_number, "You have a New Service Request in Service Category: $catName for your Immediate Attention.");
		
		//Insert Admin Notification
		new_notification("Admin", "A New Service ID: $srv_id has been generated for Booking from Userphone: $userPhone in Service Category: $catName and requested from Selected Provider - $prvd_name ($prvd_number)");
	}
	else
	{
		$stat_des = "Service Booked Pending Assignment of a Provider";
		
		//Notify Admin
		new_notification("Admin", "A New Service ID: $srv_id has been generated for Booking from Userphone: $userPhone in Service Category: $catName and Pending Assignment of a Provider");
		
	}

	//Insert Status History
	$query = "INSERT INTO  tbl_servstatus(srv_id, srv_status, stat_des, dadd, ucreate) VALUES('$srv_id', 'Pending', '$stat_des', '$ctime', 'System')";
	$result = mysql_query($query) or die(mysql_error()."- Insert service error");
	
	
	//Insert Admin Notification
	//new_notification("Admin", "A New Service ID: $srv_id has been generated for Booking from Userphone: $userPhone in Service Category: $catName");
	
	//Insert User Notification
	new_notification($userPhone, "The Service ID: $srv_id has been assigned to your service request in $catName. Provider will contact you.");
	
	$aresult['message'] = "success";
}

//Get User Service Request List
	$aresult['userSrvReqList'] = array();
	$aquery = mysql_query("Select sno, catname, datefmt, service_time, service_status, service_request FROM tbl_services WHERE user_id ='$userPhone' ORDER BY service_date ASC") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['userSrvReqList'][]=$row;
	}
	
	$aresult['userSrvReqs'] = get_user_srvreqs($userPhone);



$aresult['type'] = $mtype;

echo json_encode($aresult);

}


if($_REQUEST['type'] == "prvdcitylist")
{
	$mtype = $_REQUEST['type'];
	$userPhone = clean_data($_REQUEST['userPhone']);
	//$usercity = get_prvdcity($userPhone);
	//$usercity = "Marina, Ajah, Ikoyi";
	//Extract Message Content
	//$messageArray = explode(",",$usercity);
	
	$aquery = mysql_query("Select * FROM tbl_city") or die("city list error ".mysql_error());
	/*
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['userAlertMsg'][]=$row;
	}
	*/
	while ($row = mysql_fetch_array($aquery)) {
	extract($row);
	$uresult['cityname'] = $cityname;
	$uresult['citysno'] = $sno;
	$citychk =  check_prvdcity($userPhone, $sno);
	
	$uresult['hasPrvd'] = $citychk;
	//Check if it exist in exploded array	
	/*
	if (array_search($achk, $messageArray) !== FALSE)
	{$uresult['hasPrvd'] = "Checked";}
	else
	{$uresult['hasPrvd'] = null;}

	*/

	$aresult['prvdcitylist'][]=$uresult;
	
	$citychk = "";
	}
	
	
	//$aresult['atype'] = $messageArray;
	//$aresult['btype'] = $row;
	$aresult['type'] = $mtype;

	echo json_encode($aresult);

}


if($_REQUEST['type'] == "vendoreg")
{
$mtype = $_REQUEST['type'];
$biz_des = clean_data($_REQUEST['biz_des']); 
$biz_add = clean_data($_REQUEST['biz_add']);
$biz_name = clean_data($_REQUEST['biz_name']);
$userPhone = clean_data($_REQUEST['userPhone']);
date_default_timezone_set("Africa/Lagos");
$ctime = date('Y-m-d H:i:s');

//check if user alread a provider
$val = check_phonevendor($userPhone);

if ($val != 0)
{$aresult['message'] = "registered"; $error_stat = 1; new_notification($userPhone, "You are already Registered as a Vendor");}


if(!isset($error_stat))
{
//Create user account
$query = "INSERT INTO tbl_vendor (username, srv_desc, vendor_name, vendor_address, dcreate) VALUES('$userPhone', '$biz_des', '$biz_name', '$biz_add', '$ctime')";
$result = mysql_query($query) or die(mysql_error()."- Insert user error");
$aresult['code'] = "0";
$aresult['message'] = "success"; new_notification($userPhone, "Your Vendor Registration was Successfully");
}

$aresult['type'] = $mtype;

echo json_encode($aresult);

}

if($_REQUEST['type'] == "otp")
{
$mtype = $_REQUEST['type'];
$userPhone = clean_data($_REQUEST['userPhone']);
$otpcode = clean_data($_REQUEST['otpCode']);
date_default_timezone_set("Africa/Lagos");
$ctime = date('Y-m-d H:i:s');
$username = $userPhone;

//Update otp code
$result = mysql_query("UPDATE tbl_otp SET codestatus = 'Verified' WHERE otpcode='$otpcode'") or die(mysql_error()."- Insert otp error");

//Add Status Record
$result = mysql_query("INSERT INTO tbl_status (username, ustat, dadd) VALUES('$userPhone', 'Active', '$ctime')") or die(mysql_error()."- Insert otp error");

//update User Record
$result = mysql_query("UPDATE tbl_user SET user_status = 'Active', mobile_login = '$ctime' WHERE username='$userPhone'") or die(mysql_error()."- Insert otp error");

$aresult['userCity'] = get_city($_REQUEST['userPhone']);
$aresult['userEmail'] = get_user_email($_REQUEST['userPhone']);
$aresult['message'] = "success";
$aresult['fullname'] = get_fullname($_REQUEST['userPhone']);
$aresult['userCity'] = get_city($_REQUEST['userPhone']);
$aresult['userEmail'] = get_user_email($_REQUEST['userPhone']);
$aresult['userAlert'] = get_user_alert($_REQUEST['userPhone']);
$aresult['userAlertID'] = get_user_alertID($_REQUEST['userPhone']);
$aresult['userSrvReqs'] = get_user_srvreqs($_REQUEST['userPhone']);
	
	//Get User City
	$aresult['city'] = array();
	$aquery = mysql_query("Select * FROM tbl_city") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_array($aquery)) {
	extract($row);
	$aresult['city'][] = $cityname;
	}
	
	//Get User Alerts
	$aresult['userAlertMsg'] = array();
	$aquery = mysql_query("Select msg,status,daddfmt,timefmt FROM tbl_alert WHERE username='$userPhone' ORDER BY sno DESC LIMIT 20") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['userAlertMsg'][]=$row;
	}
	
	//Get Service Category except others
	$aresult['srvCategory'] = array();
	$aquery = mysql_query("Select sno,catname,app_img FROM tbl_cat WHERE catname !='Others' ORDER BY catname ASC") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['srvCategory'][]=$row;
	}
	
	//Get Others Service Category
	$aresult['srvCatOthers'] = array();
	$aquery = mysql_query("Select sno,catname,app_img FROM tbl_cat WHERE catname ='Others'") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['srvCatOthers'][]=$row;
	}
	
	//Get User Service Request List
	$aresult['userSrvReqList'] = array();
	$aquery = mysql_query("Select sno, catname, datefmt, service_time, service_status, service_request FROM tbl_services WHERE user_id ='$userPhone' ORDER BY service_date ASC") or die("user check error ".mysql_error());
	while ($row = mysql_fetch_assoc($aquery))  {
	$aresult['userSrvReqList'][]=$row;
	}

$aresult['type'] = $mtype;

echo json_encode($aresult);
}



if($_REQUEST['type'] == "phone")
{
$mtype = $_REQUEST['type'];
$userPhone = clean_data($_REQUEST['userPhone']);

//check if phone exist
$val = check_phone($userPhone);

if ($val == 1)
{
	$aresult['message'] = "success"; 
	$otpcode = mt_rand(10001, 99999);
	$aresult['otpcode'] = $otpcode;  
	date_default_timezone_set("Africa/Lagos");
	$ctime = date('Y-m-d H:i:s');
	$result = mysql_query("INSERT INTO tbl_otp (otpcode, userphone, datesent) VALUES('$otpcode', '$userPhone', '$ctime')") or die(mysql_error()."- Insert otp error");
	$aresult['fullname'] = get_fullname($userPhone); 
	
	//send otpcode via sms - later (smslive247 and multitexter)
	$msg = "Your Xperthands OTP Code is $otpcode";
	$sms_resp = send_sms($userPhone, $msg);
	
}
else
{$aresult['message'] = "Phone Number is not registered!"; }


$aresult['type'] = $mtype;

echo json_encode($aresult);
}


if($_REQUEST['type'] == "setcity")
{
$mtype = $_REQUEST['type'];
$userPhone = clean_data($_REQUEST['userPhone']);
$userCity = get_citysno(clean_data($_REQUEST['userCity']));

//Update User City Record
$result = mysql_query("UPDATE tbl_user SET city = '$userCity' WHERE username='$userPhone'") or die(mysql_error()."- Insert otp error");

$aresult['message'] = "success";
$aresult['type'] = $mtype;

echo json_encode($aresult);
}

//User Profile Update
if($_REQUEST['type'] == "profileupdate")
{
$mtype = $_REQUEST['type'];
$userPhone = clean_data($_REQUEST['userPhone']);
$fullName = clean_data($_REQUEST['fullName']);
$userEmail = clean_data($_REQUEST['userEmail']);
$userAddress = clean_data($_REQUEST['userAddress']);

//Update User Profile Record
$result = mysql_query("UPDATE tbl_user SET fullname = '$fullName', user_email='$userEmail', address='$userAddress'  WHERE username='$userPhone'") or die(mysql_error()."- Insert otp error");

$aresult['message'] = "success";
$aresult['type'] = $mtype;

echo json_encode($aresult);
}


//User Password Update
if($_REQUEST['type'] == "passwordupdate")
{
$mtype = $_REQUEST['type'];
$userPhone = clean_data($_REQUEST['userPhone']);
$userPass = md5(clean_data($_REQUEST['userPass']));

//Update User Password Record
$result = mysql_query("UPDATE tbl_user SET password = '$userPass' WHERE username='$userPhone'") or die(mysql_error()."- Insert otp error");

$aresult['message'] = "success";
$aresult['type'] = $mtype;

echo json_encode($aresult);
}

//Provider Online/Offline Status Update
if($_REQUEST['type'] == "chgOnlineStatus")
{
$mtype = $_REQUEST['type'];
$userPhone = clean_data($_REQUEST['userPhone']);
$newOnStat = clean_data($_REQUEST['onStatus']);

//Update User Password Record
$result = mysql_query("UPDATE tbl_srvprovider SET profile_status = '$newOnStat' WHERE username='$userPhone'") or die(mysql_error()."- Insert otp error");

$aresult['message'] = "success";
$aresult['type'] = $mtype;

echo json_encode($aresult);
}


//Update Provider Service Locations
if($_REQUEST['type'] == "updatecityprvd")
{
$mtype = $_REQUEST['type'];
$userPhone = clean_data($_REQUEST['userPhone']);
//$citylist = [];
//$citylist = array($_REQUEST['citylist']);
//$citylist = $_POST['citylist'];
//$citylist = json_decode($citylist,true);
$citystr = $_REQUEST['citystr'];

$citylist = explode(",",$citystr);

$clen = count($citylist);
//Update User Password Record
$result = mysql_query("DELETE FROM tbl_prvdcity WHERE username='$userPhone'") or die(mysql_error()."- Insert otp error");
date_default_timezone_set("Africa/Lagos");
$ctime = date('Y-m-d H:i:s');

for ($i = 1; $i < $clen-1; $i++)
	{
		$result = mysql_query("INSERT INTO tbl_prvdcity (username, cityname, dadd) VALUES('$userPhone', '$citylist[$i]', '$ctime')") or die(mysql_error()."- Insert otp error");
		$citystrdb .= get_citynamedb($citylist[$i]);
		
		if($i < $clen-2)
		{$citystrdb .= ", ";}
	}
	
	$result = mysql_query("UPDATE tbl_srvprovider SET srv_city='$citystrdb' WHERE username='$userPhone'") or die(mysql_error()."- Insert otp error");

$aresult['message'] = "success";
$aresult['clen'] = $clen;
$aresult['type'] = $mtype;

$aquery = mysql_query("Select * FROM tbl_city") or die("city list error ".mysql_error());
	while ($row = mysql_fetch_array($aquery)) {
	extract($row);
	$uresult['cityname'] = $cityname;
	$uresult['citysno'] = $sno;
	$citychk =  check_prvdcity($userPhone, $sno);
	
	$uresult['hasPrvd'] = $citychk;

	$aresult['prvdcitylist'][]=$uresult;
	}
	
	
	$aquery = mysql_query("Select * FROM tbl_cat") or die("city list error ".mysql_error());
	while ($row = mysql_fetch_array($aquery)) {
	extract($row);
	$uresult['catname'] = $catname;
	$uresult['catsno'] = $sno;
	$catchk =  check_prvdsrv($userPhone, $sno);
	
	$uresult['hasPrvd'] = $catchk;

	$aresult['prvdsrvlist'][]=$uresult;
	}

echo json_encode($aresult);
}


//Update Provider Services
if($_REQUEST['type'] == "updatesrvprvd")
{
$mtype = $_REQUEST['type'];
$userPhone = clean_data($_REQUEST['userPhone']);
$srvstr = $_REQUEST['srvstr'];

$srvlist = explode(",",$srvstr);

$clen = count($srvlist);
//Update User Password Record
$result = mysql_query("DELETE FROM tbl_prvdsrv WHERE username='$userPhone'") or die(mysql_error()."- Insert otp error");
date_default_timezone_set("Africa/Lagos");
$ctime = date('Y-m-d H:i:s');

for ($i = 1; $i < $clen-1; $i++)
	{
		$result = mysql_query("INSERT INTO tbl_prvdsrv (username, srvid, dadd) VALUES('$userPhone', '$srvlist[$i]', '$ctime')") or die(mysql_error()."- Insert otp error");
		
		$srvstrdb .= get_srvname($srvlist[$i]);
		
		if($i < $clen-2)
		{$srvstrdb .= ", ";}
		
	}

	$result = mysql_query("UPDATE tbl_srvprovider SET srv_type='$srvstrdb' WHERE username='$userPhone'") or die(mysql_error()."- Insert otp error");
	
$aresult['message'] = "success";
$aresult['clen'] = $clen;
$aresult['type'] = $mtype;

$aquery = mysql_query("Select * FROM tbl_cat") or die("city list error ".mysql_error());
	while ($row = mysql_fetch_array($aquery)) {
	extract($row);
	$uresult['catname'] = $catname;
	$uresult['catsno'] = $sno;
	$catchk =  check_prvdsrv($userPhone, $sno);
	
	$uresult['hasPrvd'] = $catchk;

	$aresult['prvdsrvlist'][]=$uresult;
	}
	
	//Check for Profile Completion
	if (!prvdcity_avl($userPhone)){$prof_comp = false;}
	if (!prvdsrv_avl($userPhone)){$prof_comp = false;}
	if (!chkprvd_det($userPhone)){$prof_comp = false;}
	
	if(!isset($prof_comp))
	{$result = mysql_query("UPDATE tbl_srvprovider SET profile_comp='True' WHERE username='$userPhone'") or die(mysql_error()."- Insert otp error");}
	else
	{
		$result = mysql_query("UPDATE tbl_srvprovider SET profile_comp='False' WHERE username='$userPhone'") or die(mysql_error()."- Insert otp error");
		$result = mysql_query("UPDATE tbl_srvprovider SET srv_status='Registered' WHERE username='$userPhone'") or die(mysql_error()."- Insert otp error");
		
		//send message to admin to verify
		
	}

	$aresult['prvdprofile'] = prvdprofile($userPhone);
	$aresult['prvdprofcitylist'] = prvdprofcitylist($userPhone);
	$aresult['prvdprofsrvlist'] = prvdprofsrvlist($userPhone);

echo json_encode($aresult);
}


if($_REQUEST['type'] == "supportmessage")
{
$mtype = $_REQUEST['type'];
$supportMsg = clean_data($_REQUEST['supportMsg']);
$supportCat = clean_data($_REQUEST['supportCat']);
$supportType = clean_data($_REQUEST['supportType']);
$userPhone = clean_data($_REQUEST['userPhone']);
date_default_timezone_set("Africa/Lagos");
$ctime = date('Y-m-d H:i:s');

//Create user account
$query = "INSERT INTO tbl_msg (username, msgcat, usermessage, dcreate, usrtype) VALUES('$userPhone', '$supportCat', '$supportMsg', '$ctime', '$supportType')";
$result = mysql_query($query) or die(mysql_error()."- Insert user error");
$aresult['code'] = "0";
$aresult['message'] = "success"; 
if ($supportType == "User")
{new_notification($userPhone, "Your inquiry has been logged. A representative will attend to it shortly and get back to you if necessary.");}
else
{new_prvdnotification($userPhone, "Your inquiry has been logged. A representative will attend to it shortly and get back to you if necessary.");}


$aresult['type'] = $mtype;

echo json_encode($aresult);

}

?>


 