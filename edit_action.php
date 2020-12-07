<?php 
session_start();
include('includes/db.php');
if(isset($_POST['sub_bt'])){
	print_r($_POST);
	
	//$section = $_POST['section'];
	$consumerno = $_POST['consumerNo'];
	$bill_type = $_POST['bill_type'];
	$category = $_POST['category'];
	$user_id = $_POST['old_pass'];
	$user_pass = $_POST['new_pass'];
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
		$stid1 = oci_parse($conn, "UPDATE user_details set CNO='".$consumerno."',DOB='".$dob."',STREET='".$street."',AREA='".$area."',CITY='".$city."',CATEGORY='".$category."',CNAME='".$cust_name."',EMAIL='".$email_id."',PHONE='".$phone_no."',MOBILE='".$mobile."',HOUSE_NO='".$house_no."',PIN='".$pin."',BILL_TYPE='".$bill_type."' WHERE USERNAME='".$_SESSION['uname']."'");
		//echo "UPDATE user_details set CNO='".$consumerno."',DOB='".$dob."',STREET='".$street."',AREA='".$area."',CITY='".$city."',CATEGORY='".$category."',CNAME='".$cust_name."',EMAIL='".$email_id."',PHONE='".$phone_no."',MOBILE='".$mobile."',HOUSE_NO='".$house_no."',PIN='".$pin."',BILL_TYPE='".$bill_type."' WHERE USERNAME='".$_SESSION['uname']."'";
		//exit;
		if(oci_execute($stid1))
		{
		$_SESSION['cname'] = $cust_name;
		$_SESSION['bill_type'] = $bill_type;
		header('Location: edit.php?msg=success');
		}
	

}


?>