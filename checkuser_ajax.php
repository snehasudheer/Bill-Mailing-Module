<?php 
include('includes/db.php'); 
if(isset($_POST['userid']) && $_POST['userid']!=''){
	$userid = $_POST['userid'];
		
		$stid = oci_parse($conn, "SELECT count(*) as count FROM user_details where USERNAME='$userid'");
		//echo "SELECT count(*) as count FROM user_details where USERNAME='$userid'"; 
		oci_execute($stid);
		$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
		$count = $row['COUNT'];
		
		
if ($count>0)
{
	$data = array(
            "result"     => 0
        );   
}
else
{
	$data = array(
			"result"     =>	1
	);
}
 echo json_encode($data);
}



?>