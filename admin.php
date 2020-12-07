 <?php include('includes/header.php'); ?>
 
 
 
 <style>
#panel, #flip {
    padding: 5px;
    text-align: left;
    background-color: #e5eecc;
    border: solid 1px #c3c3c3;
}

#panel {
    padding: 30px;
    display: none;
}
</style>
<style>
#panel1, #flip1 {
    padding: 5px;
    text-align: left;
    background-color: #e5eecc;
    border: solid 1px #c3c3c3;
}

#panel1 {
    padding: 30px;
    display: none;
}
</style>


<!-- Start Page Banner -->
    <div class="page-banner">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <h2>Login</h2>
            <p>User Login</p>
          </div>
          <div class="col-md-6">
            <ul class="breadcrumbs">
              <li><a href="index.php">Home</a></li>
              <li>User Login</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- End Page Banner -->

  <div class="container" style="margin-top:2%;">
        <div class="row">
		<div class="col-md-8">
		<div class="content">
        
		<h1 align="center"> User Login </h1>
		<fieldset>
	 
	 <div style="margin-top:35px">
	 
	 <div style="margin-top:35px; font-size:16px; color:#000;">
	 <form name ="login" action="adminauth.php" method="POST">
	 
	 <div class="form-group">
		<div class="signleftbox">User name: </div>
		<div class="signrightbox">
				<input class="form-control" type="text" name="username" size="20" onclick="clear_fields()">
		</div>
		<div class="signleftbox">Password: </div>
		<div class="signrightbox">
			<input class="form-control" type="password" name="pass" size="21" onclick="clear_fields()">
		</div>
		<div><div class="signleftbox"></div><div class="error signrightbox" ><span id="error_msg" <?php if(isset($_GET['msg']) && $_GET['msg']=='error') { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>Invalid Username Or Password</span></div></div>
		<div align="center">
		<input align="center" type="submit" name="login" value="Login" style="height:30px;" onclick="check()">
		<a href="signup.php"><input class="" align="center" type="button" value="Sign Up" style="height:30px;"></a>
		</div>		
		</div>
	 </form>
	 </div>
	 
	 </div>
	 </fieldset>

	 </div>
	 </div>
		<div class="col-md-4">
		<?php include('sidemenu.php'); ?>
		</div>
		
		</div>
		</div>
		
<?php include('includes/footer.php'); ?>
<script>
function clear_fields()
{
	$('#error_msg').hide();
	
}
</script>