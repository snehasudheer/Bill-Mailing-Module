 <?php include('includes/header.php'); ?>
<!-- Start Page Banner -->
    <div class="page-banner">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <h2>Online Bill Payment</h2>
            <p>Bill Payment</p>
          </div>
          <div class="col-md-6">
            <ul class="breadcrumbs">
              <li><a href="index.php">Home</a></li>
              <li>Bill Payment</li>
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
        
		<h1> BILL PAYMENT </h1>
		<fieldset>
	 
	 <div style="margin-top:35px">
	 
	 <div style="margin-top:35px; font-size:16px; color:#000;">
	 <?php if(isset($_GET['msg'])) { 
	 if($_GET['msg']=='expired') {
		 $msg = "Due Date Over!";
		 $color = "red";
	 }
	 elseif($_GET['msg']=='paid') {
		 $msg = "Already Paid!";
		 $color = "green";
	 }
	 elseif($_GET['msg']=='notfound') {
		 $msg = "Record Not Found!";
		 $color = "red";
	 }
         elseif($_GET['msg']=='contact') {
		 $msg = "Contact office for payment!";
		 $color = "red";
         }
	 ?>
	 <div align="center" style="color:<?php echo $color; ?>;font-size: 24px;"><?php echo $msg; ?></div>
	 <?php } ?>
	 <!--<h2 align="center" style="color:red;">Online Payment Coming Soon</h2>-->
	 <form name="bill_pay" method="post" action="payment.php">
	 
	 <div class="form-group">
		<div class="signleftbox">Consumer No: </div>
		<div class="signrightbox">
			<?php if(isset($_SESSION['cno'])) { ?>
				<input class="form-control" type="text" name="con_no" id="con_no" value="<?php if(isset($_POST['con_no'])) { echo $_POST['con_no']; } else { echo $_SESSION['cno']; } ?>" required >
				<?php } else if(isset($_GET['cno'])) { ?>
				<input class="form-control" type="text" name="con_no" id="con_no" value="<?php if(isset($_POST['con_no'])) { echo $_POST['con_no']; } else { echo $_GET['cno']; } ?>" required >
				<?php } else { ?>
				<input class="form-control" type="text" name="con_no" id="con_no" value="<?php if(isset($_POST['con_no'])) { echo $_POST['con_no']; } ?>" required >
				<?php } ?>
		</div>
		<div class="signleftbox">Bill No: </div>
		<div class="signrightbox">
		<?php if(isset($_GET['bill_no'])) { ?>
		<input class="form-control" type="text" name="bill_no" id="bill_no" value="<?php if(isset($_POST['con_no'])) { echo $_POST['con_no']; } else { echo $_GET['bill_no']; } ?>" required >
		<?php } else { ?>
		<input class="form-control" type="text" name="bill_no" id="bill_no" value="<?php if(isset($_POST['bill_no'])) { echo $_POST['bill_no']; } ?>" required >
		<?php } ?>
		</div>
		<div class="signleftbox">Bill Type: </div>
		<div class="signrightbox">
			<?php if(isset($_SESSION['cno'])) { ?>
			<label style="font-size:18px;"> Monthly </label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="bill_type" value="Monthly" <?php if($_SESSION['bill_type']=='Monthly') { echo 'checked="checked"'; } ?>required>&nbsp;&nbsp;&nbsp;&nbsp;
			<label style="font-size:18px;"> Spot </label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="bill_type" value="Spot" <?php if($_SESSION['bill_type']=='Spot') { echo 'checked="checked"'; } ?> required>
			
		<?php } else if(isset($_GET['bill_type'])) { ?>
			<label style="font-size:18px;"> Monthly </label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="bill_type" value="Monthly" <?php if($_GET['bill_type']=='Monthly') { echo 'checked="checked"'; } ?>required>&nbsp;&nbsp;&nbsp;&nbsp;
			<label style="font-size:18px;"> Spot </label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="bill_type" value="Spot" <?php if($_GET['bill_type']=='Spot') { echo 'checked="checked"'; } ?> required>
		<?php } else { ?>
			<label style="font-size:18px;"> Monthly </label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="bill_type" value="Monthly" required>&nbsp;&nbsp;&nbsp;&nbsp;
			<label style="font-size:18px;"> Spot </label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="bill_type" value="Spot" required>
		<?php } ?>
		</div>
		<div style="float:right; margin-top:10px;">
		<input type="submit" name="sub_bt" id="sub_bt" value="submit"  class="btn btn-info" >
		<a class="main-button" href="support.php">Back</a>
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