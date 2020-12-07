<?php 
include('includes/db.php'); 
if(isset($_POST['sub_bt'])){
	
	//$section = $_POST['section'];
	$consumerno = $_POST['consumerNo'];
	$bill_type = $_POST['bill_type'];
	$category = $_POST['category'];
	$user_id = $_POST['user_id'];
	$user_pass = $_POST['user_pass'];
	$cnfrm_pass = $_POST['cnfrm_pass'];
	$cust_name = $_POST['cust_name'];
	$dob = $_POST['dob'];
	$dob = date('d-M-Y',strtotime($dob));
	$email_id = $_POST['email_id'];
	$phone_no = $_POST['phone_no'];
	$mobile = $_POST['mobile'];
	$house_no = $_POST['house_no'];
	$street = $_POST['street'];
	$area = $_POST['area'];
	$city = $_POST['city'];
	$pin = $_POST['pin'];
	
	
	
			if(trim($bill_type)=='Monthly')
			{
				$tab = "newmaster";
			}
			elseif(trim($bill_type)=='Spot')
			{
				$tab = "master";
			}
		$stid1 = oci_parse($conn, "INSERT INTO user_details values('$consumerno','$dob','$street','$area','$city','$user_id','$user_pass','$category','$cust_name','$email_id','$phone_no','$mobile','$house_no','$pin','$bill_type')");
		if(oci_execute($stid1))
		{
		session_start();
		$_SESSION['cno'] = $consumerno;
		$_SESSION['uname'] = $user_id;
		$_SESSION['cname'] = $cust_name;
		$_SESSION['bill_type'] = $bill_type;
		header('Location: index.php');
		}
	

}


?>