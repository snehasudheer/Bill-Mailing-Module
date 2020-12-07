<?php  
//include('includes/db.php');				
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

require_once('tcpdf_include.php');

session_start();	

if(isset($_GET['con_no']) && $_GET['con_no']!='')
{

$conn = oci_connect('GDNTCR_USR', 'GDNTCR_USR', '');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
if(isset($_GET['con_no'])) {
$consumer_number = trim($_GET['con_no']);
$con_nos = explode(',',$consumer_number);

if(isset($_GET['bill_type']) && $_GET['bill_type']!='')
		{
			$bill_type = $_GET['bill_type'];
			
			if(trim($bill_type)=='HT')
			{
				foreach($con_nos as $con_no)
				{
					
				$res = explode('__',$con_no);
				$billno = $res[0];
				$consumerno = $res[1];
				$tab = "newmaster";
				
				$stid1 = oci_parse($conn, "SELECT nm.*,ht.KVACHARGE,ht.KWHCHARGE FROM ".$tab." nm, httariffnew ht where nm.CNO='$consumerno' and nm.TARIFF=ht.TARIFF AND ht.DT_WITHEFFECT IN (SELECT MAX(DT_WITHEFFECT) FROM HTTARIFFNEW WHERE TARIFF=nm.TARIFF)");
				oci_execute($stid1);
				$row1 = oci_fetch_array($stid1, OCI_ASSOC+OCI_RETURN_NULLS);
				$cat = $row1['CATEGORY'];
				$wotariff = $row1['TARIFF'];
				if($cat=='HT')
				{ 
					$stid = oci_parse($conn, "SELECT b.MONTH as term,b.YEAR as year,b.BILLNO as bill_no,b.BILLDATE as bill_date,b.LASTDATE as LDATE,b.DISDATE as FINEDATE,b.KWHNORMAL,b.KWHPEAK,b.KWHOFF,b.BILLDEMAND,b.TOTBILLAMT as TOTAMT,b.RECEIPTNO,b.INTEREST,b.RC,b.PAID,b.PF,b.EXDEMCHARGE,b.PFPENALTY,b.arrears,c.counterno FROM NEWHTbillfile b left join cash c on (b.cno=c.cno and b.billno=c.billno) where b.BILLNO='$billno'");
					oci_execute($stid);
					$row = oci_fetch_array($stid, OCI_ASSOC);
					$filename= 'HT_bill/ht_bill_'.$row['BILL_NO'].'_'.$row['BILL_DATE'].'.pdf';
				}
 $mail = new PHPMailer(true);  
 // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 3;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'sendemailtced@gmail.com';                 // SMTP username
    $mail->Password = 'tcedgmail';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 25;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('sendemailtced@gmail.com', 'Ebill from TCED');
    $mail->addAddress('sukhithsukesh@gmail.com', 'Recipient');     // Add a recipient
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Your Electricity Bill dated '.$row['BILL_DATE'].' is ready to view';
    $mail->Body    = 'Your Electricity Bill dated <b>'.$row['BILL_DATE'].'</b> is ready to view. Please find the attached file with this mail';
  //  $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	
	//Attachments
    $mail->addAttachment($filename);         // Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
   $bill_no = $row['BILL_NO'];
    if($mail->send())
	{
		$stid3 = oci_parse($conn, "update send_bill set mail_stat='1' where bill_type='HT' and bill_no='$bill_no'");
		oci_execute($stid3);
	}
   

} catch (Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}
					
				}
			}
		}
		
		}

}
?>
