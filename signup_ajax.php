<?php 
include('includes/db.php'); 
if(isset($_POST['consumerno']) && $_POST['consumerno']!=''){
	$consumerno = $_POST['consumerno'];
	$bill_type = $_POST['bill_type'];
			if(trim($bill_type)=='Monthly')
			{
				$tab = "newmaster";
			}
			elseif(trim($bill_type)=='Spot')
			{
				$tab = "master";
			}
		
		$stid1 = oci_parse($conn, "SELECT * FROM ".$tab." where CNO='$consumerno'");
		oci_execute($stid1);
		$row1 = oci_fetch_array($stid1, OCI_ASSOC+OCI_RETURN_NULLS);
		//$cat = $row1['CATEGORY'];
		
		// header('Content-Type: application/json');
        // echo json_encode(array('breadcrumb' => $breadcrumb , 'pageContent' => $row1, 'session' => true));	
		// exit;
	
//print_r($row);
if (!empty($row1))
{
	//echo "sss";
	//exit;
	if(trim($bill_type)=='Monthly')
			{
	$data = array(
            "name"     => $row1['NAME'],
            "category"  => $row1['CATEGORY'],
            "vc_teleno"   => $row1['VC_TELENO'],
			"email"   => $row1['EMAIL']
        );
			}
			elseif(trim($bill_type)=='Spot')
			{
				$data = array(
            "name"     => $row1['NAME'],
            "category"  => 'SPOT',
            "vc_teleno"   => $row1['VC_TELENO'],
			"email"   => ''
        );
			}
       
}
else
{
	$data = array();
}
 echo json_encode($data);
}
/*echo "<table border='1'>\n";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";
	
}*/


?>