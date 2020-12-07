<?php 
include('includes/db.php'); 
if(isset($_GET['type']))
{
	$type= $_GET['type'];
	$stid = oci_parse($conn, "SELECT * FROM complaint_type");
	oci_execute($stid);
	while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
	print_r($row['TYPE_ID']);
}
}

?>