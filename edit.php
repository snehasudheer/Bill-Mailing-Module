	<?php include('includes/header.php'); 
	if(isset($_SESSION['cno']) && $_SESSION['cno']!=''){
		$username = $_SESSION['uname'];
		$consumer_no = $_SESSION['cno'];
	$stid = oci_parse($conn, "SELECT * FROM user_details where username='$username'");
	oci_execute($stid);
	$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
	
	if($row['BILL_TYPE']=='Spot')
	{
	$stid1 = oci_parse($conn, "SELECT TARIFF,WO,PHASE,METERNO,CONLOAD,CONDATE,SECURITYAMT,CONNECTED FROM MASTER where CNO='$consumer_no'");
	$stid2 = oci_parse($conn, "SELECT SUM(PAIDAMT) as sum FROM SPOT_SDCOLLECTION where CNO='$consumer_no'");
	}
	else if($row['BILL_TYPE']=='Monthly'){
	$stid1 = oci_parse($conn, "SELECT TARIFF,PHASE,METERNO,CONLOAD,CONDATE,SECURITYAMT,CONNECTED FROM NEWMASTER where CNO='$consumer_no'");
	//echo "SELECT SUM(PAIDAMT) as sum FROM MONTH_SDCOLLECTION where CNO='$consumer_no'";
	//exit;
	$stid2 = oci_parse($conn, "SELECT SUM(PAIDAMT) as sum FROM MONTH_SDCOLLECTION where CNO='$consumer_no'");
	}
	oci_execute($stid1);
	$consumer_det = oci_fetch_array($stid1, OCI_ASSOC+OCI_RETURN_NULLS);
	oci_execute($stid2);
	$paidamts = oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS);
	$security_amt = $consumer_det['SECURITYAMT']+$paidamts['SUM'];
	
	$security_amt = number_format($security_amt, 2, ".", "");
	if($consumer_det['CONNECTED']==0)
	{
		$status="TDC";
	}
	else if($consumer_det['CONNECTED']==1)
	{
		$status="CONNECTED";
	}
	else if($consumer_det['CONNECTED']==-1)
	{
		$status="CDC";
	}
	else
	{
		$status="NA";
	}
	
if(isset($_GET['msg']) && $_GET['msg']='success')
{
	$msg = "Successfully Updated";
}
else

	$msg = "";
	}
	?>
	<!-- Start Page Banner -->
    <div class="page-banner">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <h2>User Account</h2>
            <p>TCED</p>
          </div>
          <div class="col-md-6">
            <ul class="breadcrumbs">
              <li><a href="index.php">Home</a></li>
              <li>Edit User</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- End Page Banner -->
	<div class="container" style="margin-top:2%;">
	<div class="row">
		<div class="col-md-12" style="margin-bottom:2%;">
		
	<form method="post" action="edit_action.php" class="contact_form" id="form-new-reg1" onsubmit="return validate()">
		
				<div class="content">
			
				
					<div class="col-md-10">
						<div>
							<h2 style="color:#fff; background:#295E95;padding:4px;border-radius:10px;">
								Edit User
							</h2>
						</div>
					</div>
				</div>
				<div style="color: green; float: left;font-size: 22px;font-weight: bold;padding: 10px;width: 100%;"><?php echo $msg; ?></div>
				<div class="row">
					<div class="col-md-12">
					
						<div class="form-group">
						<div class="page-header PHmain">
							<h2>
								Consumer Details
							</h2>
							</div>
							<div class="col-md-6">
							
							<div class="signleftbox">
								<div class="secretQ">
									Electrical Section:
								</div>
							</div>
							<div class="signrightbox">
								<select class="form-control" disabled="true" tabindex="1" name="section" id="section" class="parsley-validated" required>

										<option value="">-- Select Section --</option>
										<option value="1" selected="selected">Thrissur</option>
									
								</select>
							</div>
							</div>
							<div class="col-md-6">
							<div class="signleftbox">
								<div class="secretQ">
									Consumer Number:
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" value="<?php echo $row['CNO']; ?>" placeholder="Consumer Number" tabindex="7" name="consumerNo" id="consumerNo" class="parsley-validated" required>
							</div>
							</div>
							<div class="col-md-6">
								<div class="signleftbox">
								<div class="secretQ">
									Bill Type:
								</div>
							</div>
							<div class="signrightbox">
								<select class="form-control" tabindex="1" name="bill_type" id="bill_type" class="parsley-validated" onblur="getdetails()" required>

										<option value="">-- Select Bill Type --</option>
										<option <?php if($row['BILL_TYPE']=='Monthly') { echo 'selected="selected"'; } ?> value="Monthly">Monthly</option>
										<option <?php if($row['BILL_TYPE']=='Spot') { echo 'selected="selected"'; } ?> value="Spot">Spot</option>
									
								</select>
							</div>
							</div>
							<div class="col-md-6">
								<div class="signleftbox">
								<div class="secretQ">
									Category:
								</div>
							</div>
							<div class="signrightbox">
								<select class="form-control" tabindex="1" name="category" id="category" class="parsley-validated" required>

										<option value="">-- Select Category --</option>
										<option <?php if($row['CATEGORY']=='HT') { echo 'selected="selected"'; } ?> value="HT">HT</option>
										<option <?php if($row['CATEGORY']=='GT') { echo 'selected="selected"'; } ?> value="GT">GT</option>
										<option <?php if($row['CATEGORY']=='LT4') { echo 'selected="selected"'; } ?> value="LT4">LT4</option>
										<option <?php if($row['CATEGORY']=='SPOT') { echo 'selected="selected"'; } ?> value="SPOT">SPOT</option>
									
								</select>
							</div>
							</div>
							<div class="col-md-12"><a class="btn btn-primary" style="padding: 2px;margin-bottom: 5px;" id="mre_detail" href="javascript:void(0);">More details</a></div>
							<div class="col-md-12 edit_slide" id="more_det">
							<div class="col-md-6">
								<div class="signleftbox">
								<div class="secretQ edit_slide_left">
									Tariff:
								</div>
							</div>
							<div class="signrightbox edit_slide_right">
							<?php if(trim($consumer_det['TARIFF'])!='') echo $consumer_det['TARIFF']; else echo "NA"; ?>
							</div>
							</div>
							<?php if($row['BILL_TYPE']=='Spot') { ?>
							<div class="col-md-6">
								<div class="signleftbox">
								<div class="secretQ edit_slide_left">
									WO:
								</div>
							</div>
							<div class="signrightbox edit_slide_right">
							<?php if(trim($consumer_det['WO'])!='') echo $consumer_det['WO']; else echo "NA"; ?>
							</div>
							</div>
							<?php } ?>
							<div class="col-md-6">
								<div class="signleftbox">
								<div class="secretQ edit_slide_left">
									Phase:
								</div>
							</div>
							<div class="signrightbox edit_slide_right">
							<?php if(trim($consumer_det['PHASE'])!='') echo $consumer_det['PHASE']; else echo "NA"; ?>	
							</div>
							</div>
							<div class="col-md-6">
								<div class="signleftbox">
								<div class="secretQ edit_slide_left">
									Meter No.:
								</div>
							</div>
							<div class="signrightbox edit_slide_right">
							<?php if(trim($consumer_det['METERNO'])!='') echo $consumer_det['METERNO']; else echo "NA"; ?>
							</div>
							</div>
							<div class="col-md-6">
								<div class="signleftbox">
								<div class="secretQ edit_slide_left">
									Connection Load:
								</div>
							</div>
							<div class="signrightbox edit_slide_right">
							<?php if(trim($consumer_det['CONLOAD'])!='') echo $consumer_det['CONLOAD']; else echo "NA"; ?>	
							</div>
							</div>
							<div class="col-md-6">
								<div class="signleftbox">
								<div class="secretQ edit_slide_left">
									Connection Date:
								</div>
							</div>
							<div class="signrightbox edit_slide_right">
							<?php if(trim($consumer_det['CONDATE'])!='') echo $consumer_det['CONDATE']; else echo "NA"; ?>
							</div>
							</div>
							<div class="col-md-6">
								<div class="signleftbox">
								<div class="secretQ edit_slide_left">
									Status:
								</div>
							</div>
							<div class="signrightbox edit_slide_right">
							<?php echo $status; ?>	
							</div>
							</div>
							<div class="col-md-6">
								<div class="signleftbox">
								<div class="secretQ edit_slide_left">
									Secuiry Amount:
								</div>
							</div>
							<div class="signrightbox edit_slide_right">
							<?php if(trim($security_amt)!='') echo $security_amt; else echo "NA"; ?>
							</div>
							</div>
							</div>
							
							<div class="page-header PHmain">
							<h2>
								Login Details
							</h2>
							</div>
							<div class="col-md-6">
							<div class="signleftbox">
								<div class="secretQ">
									User ID:
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" readonly type="text" value="<?php echo $row['USERNAME']; ?>" placeholder="User Id" tabindex="6" name="user_id" id="user_id" class="parsley-validated" required>
							</div>
							</div>
							
							<div class="col-md-6">
							<div class="signleftbox">
								<div class="secretQ">
									Current Password:
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="password" value="" placeholder="Password" tabindex="6" name="old_pass" id="old_pass" class="parsley-validated" >
							</div>
							</div>
							<div class="col-md-6">
							<div class="signleftbox">
								<div class="secretQ">
									New Password:
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="password" value="" placeholder="Password" tabindex="6" name="new_pass" id="new_pass" class="parsley-validated" >
							</div>
							</div>
							<div class="col-md-6">
							<div class="signleftbox">
								<div class="secretQ">
									Confirm Password:
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="password" value="" placeholder="Password" tabindex="6" name="cnfrm_pass" id="cnfrm_pass" class="parsley-validated" >
							</div>
							</div>
							<div class="page-header PHmain">
							<h2>
								User Details
							</h2>
							</div>
							
							<div class="fifty">
							<div class="col-md-12">
							<div class="signleftbox">
								<div class="secretQ">
									Name:
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" value="<?php echo $row['CNAME']; ?>" placeholder="Customer Name" tabindex="7" name="cust_name" id="cust_name" class="parsley-validated" required>
							</div>
							</div>
							<div class="col-md-12">
							<div class="signleftbox ">
								<div class="secretQ">
									Date of birth:
								</div>
							</div>
							<div class="signrightbox Connectioninput">
							<input class="form-control" type="text" placeholder="DD-MM-YY" value="<?php echo date('d-m-Y',strtotime($row['DOB'])); ?>" tabindex="3" name="dob" id="dob" class="parsley-validated" onkeyup="DateFormat(this,this.value,event,false,'3')" onkeydown="DateFormat(this,this.value,event,false,'3')" onblur="DateFormat(this,this.value,event,true,'3')" onfocus="javascript:vDateType='3'" required>
							</div>
							</div>
							<div class="col-md-12">
							<div class="signleftbox ">
								<div class="secretQ">
									Email Id:
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" value="<?php echo $row['EMAIL']; ?>" onblur="return validate();" class="parsley-validated" placeholder="Email Id" tabindex="4" name="email_id" id="email_id" required>
								<!-- <textarea rows="" cols="" name=""></textarea> -->
							</div>
							</div>
							<div class="col-md-12">
							<div class="signleftbox ">
								<div class="secretQ">
									Phone Number:
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" value="<?php echo $row['PHONE']; ?>" class="parsley-validated" placeholder="Phone Number" tabindex="4" name="phone_no" id="phone_no" required>
								<!-- <textarea rows="" cols="" name=""></textarea> -->
							</div>
							</div>
							<div class="col-md-12">
							<div class="signleftbox ">
								<div class="secretQ">
									Mobile Number: 
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" value="<?php echo $row['MOBILE']; ?>" class="parsley-validated" placeholder="Mobile Number" tabindex="4" name="mobile" id="mobile">
								<!-- <textarea rows="" cols="" name=""></textarea> -->
							</div>
							</div>
							</div>
							<div class="fifty">
							<div class="col-md-12">
							<div class="signleftbox">
								<div class="secretQ">
									House Number/House Name/Flat Number: 
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" value="<?php echo $row['HOUSE_NO']; ?>" placeholder="House Number/House Name/Flat Number" tabindex="3" name="house_no" id="house_no" class="parsley-validated" required>
							</div>
						    </div>
							<div class="col-md-12">
							<div class="signleftbox">
								<div class="secretQ">
									Street:
								</div>
							</div>
							<div class="signrightbox">
								<!-- <input type="text" id="focusedInput" /> -->
								<input class="form-control" type="text" value="<?php echo $row['STREET']; ?>" class="parsley-validated" placeholder="Street" tabindex="4" name="street" id="street" required>
							</div>
							</div>
							<div class="col-md-12">
							<div class="signleftbox">
								<div class="secretQ">
									<div class="secretQ">
										Area/Locality:
									</div>
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" value="<?php echo $row['AREA']; ?>" class="parsley-validated" placeholder="Area/Locality" tabindex="4" name="area" id="area" required>
							</div>
							</div>
							<div class="col-md-12">
							<div class="signleftbox">
								<div class="secretQ">
									<div class="secretQ">
										City/Village: 
									</div>
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" value="<?php echo $row['CITY']; ?>" class="parsley-validated" placeholder="City/Village" tabindex="4" name="city" id="city" required>
							</div>
							</div>
							<div class="col-md-12">
							<div class="signleftbox">
								<div class="secretQ">
									<div class="secretQ">
										Pincode: 
									</div>
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" value="<?php echo $row['PIN']; ?>" class="parsley-validated" placeholder="Pincode" tabindex="4" name="pin" id="pin" required>
							</div>
						</div>
							</div>
							
							
							
							
						
							
						
							
						<!--  -->
					
				</div>
				</div>
				</div>
				<div class="row registerFormBtnMain " style="margin-top:10px;">
					<div class="line_regbuttonboxlb">
						<div class="col-md-12 engbtn_mid_all " align="center">
							<input type="submit" name="sub_bt" id="sub_bt" value="Update"  class="btn btn-info" >
						</div>
					</div>
				</div>
			
		
	</form>
	</div>
     
    </div>
  </div>
  <script type="text/javascript">
  function getdetails()
  {
	   var con_no = $('#consumerNo').val();
	   var bill_type = $('#bill_type').val();
	   		   var data = {
       consumerno: con_no,
	   bill_type: bill_type
    }
	   $.ajax({
		   

            url: "signup_ajax.php",
            type: "POST",
			dataType: 'json',
			data: data,
			success: function (response) {	
                $("#cust_name").val(response.name);
				$("#category").val(response.category);
				$("#phone_no").val(response.vc_teleno);
				$("#email_id").val(response.email);
            }
        });
  }
  
  function validate()
  {
	  var result = true;
	  var email = $('#email_id').val();
	  var oldpass = $('#old_pass').val();
	  var pass = $('#new_pass').val();
	  var cnfrmpass = $('#cnfrm_pass').val();
	  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	 // alert($('#check').html());
		if(!regex.test(email))
		{
			alert('Enter a valid email id');
			return false;
		}
		if(pass!='')
		{
		if(oldpass=='')
		{
			alert('Enter Old Password');
			return false;
			
		}
		if(pass!=cnfrmpass)
		{
			alert('Password Missmatch');
			return false;
		}
		}
		
		
		
  }
  
  $(document).ready(function(){
    $("#mre_detail").click(function(){
        $("#more_det").slideToggle();
    });
});
  </script>
  <?php include('includes/footer.php'); ?>