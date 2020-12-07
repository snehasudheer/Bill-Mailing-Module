<?php 
include('includes/db.php'); 
if(isset($_POST['login']) && $_POST['login']!=''){
	$username = $_POST['username'];
	$pass = $_POST['pass'];
	$stid = oci_parse($conn, "SELECT * FROM user_details where username='$username' AND password ='$pass'");
oci_execute($stid);

$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
//print_r($row);
if (!empty($row))
{
	session_start();
	$_SESSION['cno'] = $row['CNO'];
	$_SESSION['uname'] = $row['USERNAME'];
	$_SESSION['cname'] = $row['CNAME'];
	$_SESSION['bill_type'] = $row['BILL_TYPE'];
	header('location:index.php');
}
else
{
	header('location:admin.php?msg=error');
}
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