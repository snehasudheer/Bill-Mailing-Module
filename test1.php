<!doctype html>
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><html lang="en" class="no-js"> <![endif]-->
<html lang="en">

<head>

  <!-- Basic -->
  <title>Trissur Corporation</title>

  <!-- Define Charset -->
  <meta charset="utf-8">

  <!-- Responsive Metatag -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- Page Description and Author -->
  <meta name="description" content="Margo - Responsive HTML5 Template">
  <meta name="author" content="iThemesLab">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript" src="script/delete_script.js"></script>
<div class="container">
<h2>Example: Delete Multiple Rows with Checkbox using jQuery, PHP & MySQL</h2>
<table id="employee_grid" class="table table-condensed table-hover table-striped bootgrid-table" width="60%" cellspacing="0">
<thead>
<tr>
<th><input type="checkbox" id="select_all"></th>
<th>Name</th>
<th>Salary</th>
<th>Age</th>
</tr>
</thead>
<tbody>

<?php
/* Database connection start */
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (mysqli_connect_errno()) {
printf("Connect failed: %s\n", mysqli_connect_error());
exit();
}
$sql = "SELECT phc_id, phc_name, phc_desc, type FROM submenus LIMIT 5";
$resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
while( $rows = mysqli_fetch_assoc($resultset) ) {
?>
<tr id="<?php echo $rows["id"]; ?>">
<td><input type="checkbox" class="emp_checkbox" data-emp-id="<?php echo $rows["phc_id"]; ?>"></td>
<td><?php echo $rows["phc_name"]; ?></td>
<td><?php echo $rows["phc_desc"]; ?></td>
<td><?php echo $rows["type"]; ?></td>
</tr>
<?php
}
?>
</tbody>
</table>
<div class="row">
<div class="col-md-2 well">
<span class="rows_selected" id="select_count">0 Selected</span>
<a type="button" id="delete_records" class="btn btn-primary pull-right">Delete</a>
</div>
</div>
</div>
</head>
</html>