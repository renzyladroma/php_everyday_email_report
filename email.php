<?php
set_time_limit(0);
//error_reporting(0);
require_once('E:/www/philippine_gateway/class/class.phpmailer.php'); 
require_once('E:/www/philippine_gateway/class/class.smtp.php'); 
require_once('E:/www/philippine_gateway/lib/gw-utility.php');

$con = mysqli_connect("52.220.52.227","cma_web","55yDEKwj","cma_web");

// Check connection
if (mysqli_connect_errno())
 {
  //echo "Failed to connect to MySQL: " . mysqli_connect_error();
 }
 
//$logfile = 'E:/log/philippine_gateway/other/report/';
//$logname = "philstar_daily_report";


$email_content = "";

/*----------------------------------------------------------START BACKEND REPORT-----------------------------------------------------------*/

$email_content .= error_report();



 


function error_report() {
global $con; //$logfile, $logname;

$current_date = date("Y-m-d");

//$email_content.= "<b> [CMA Running Campaign Ads] - Daily Report (".date("Y-m-d",strtotime($current_date)).") </b> <br /><br />";

	//$sql_per_status = "SELECT id, name AS ads_name FROM campaign_creative_info WHERE end_date >= CURDATE() AND status_id = '1' AND id NOT IN ('1420', '1451')";
	$sql_per_status = "SELECT ad_name AS ads_name FROM running_campaigns WHERE running_campaigns.date = CURDATE()";
	$result_per_status = mysqli_query($con,$sql_per_status) ; //(LogMsg($logfile,$logname,"SQL Error: ".mysqli_error($con)));
    $email_content.= 'Hi All,'; 
	$email_content.= "<p> </p>";
	$email_content.= "Please be informed of the following ads that you will receive today, ".date("M d, Y",strtotime($current_date)).":";
	$email_content.="<p> </p>";
	$email_content.= '<table border="1" border-color="#FFFFFF" cellspacing="0" cellpadding="5" border-style="solid">';
							$email_content.= "<tr bgcolor='#fcb088'>
												<td>Campaign Ads</td>
											  </tr>";

						foreach($result_per_status as $row){
							$status = $row['ads_name'];
							
							
							$email_content.= "<tr>";
								$email_content.= "<td>".$status."</td>";
							$email_content.= "<tr>";
						} 
	$email_content.= '</table>'; 
	$email_content.= "<p> </p>"; 
	$email_content.= 'Thank you.'; 
return $email_content;
} 
   
//var_dump($email_content);
sendMail($email_content);


function sendMail ($email_content) {
	global $logfile, $logname, $current_date, $email_content;
	
	$mail    = new PHPMailer;
$current_date = date("Y-m-d");
	$subject = "[CMA Running Campaign Ads] - Daily Report (".date("Y-m-d",strtotime($current_date)).")";

	$mail->IsSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'smtp.gmail.com;';                       // Specify host server
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'donotreply@cdu.com.ph';           // SMTP username
	$mail->Password = 'CMPa55w0rd';                      // SMTP password
	$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
	$mail->SMTPDebug  = 0;   

	$mail->From = 'donotreply@cdu.com.ph'; 
	$mail->FromName = 'donotreply'; 
	
	$mail->AddAddress('randy.tejada@cdu.com.ph');
	$mail->AddAddress('patrick.santos@cdu.com.ph');
	$mail->AddAddress('marc.reyman@cdu.com.ph');
	$mail->AddAddress('renz.ladroma@cdu.com.ph');
	$mail->AddAddress('irene.eusebio@cdu.com.ph'); 
  
	$mail->Port = 465; 

	$mail->IsHTML(true);                                  // Set email format to HTML

	$mail->Subject = $subject;
	$mail->Body    = $email_content;
	//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	if(!$mail->Send()) {
	   LogMsg($logfile,$logname,$mail->ErrorInfo);
	   LogMsg($logfile,$logname,'Message could not be sent.');
	   exit;
	}else{
		LogMsg($logfile,$logname,'Message has been sent');
	}
} 
 


mysqli_close($con);

?>