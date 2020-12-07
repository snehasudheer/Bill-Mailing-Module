<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
//print_r($_POST);
 //$fname=$_POST['txt_hwt'];
         //$vname=$_POST['txt_value'];
        // $crtfield=$_POST['txt_hcrtsel'];
		 echo $sql = "select * from ss ";
		 $result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    $row = mysqli_fetch_assoc($result);
      print_r($row['0']);
   
} else {
    echo "0 results";
}
print_r($row);

?>