	<?php include('includes/header.php'); ?>
	<!-- Start Page Banner -->
    <div class="page-banner">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <h2>Register New User</h2>
            <p>TCED</p>
          </div>
          <div class="col-md-6">
            <ul class="breadcrumbs">
              <li><a href="index.php">Home</a></li>
              <li>Register New User</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- End Page Banner -->
	<div class="container" style="margin-top:2%;">
	<div class="row">
		<div class="col-md-12" style="margin-bottom:2%;">
		
	<form method="post" action="signup_action.php" class="contact_form" id="form-new-reg1" onsubmit="return validate()">
		
				<div class="content">
			
				
					<div class="col-md-10">
						<div>
							<h2 style="color:#fff; background:#295E95;padding:4px;border-radius:10px;">
								Register New User
							</h2>
						</div>
					</div>
				</div>
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
									Consumer Number: <span style="color:red;">*</span>
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" value="" placeholder="Consumer Number" tabindex="7" name="consumerNo" id="consumerNo" class="parsley-validated" required>
							</div>
							</div>
							<div class="col-md-6">
								<div class="signleftbox">
								<div class="secretQ">
									Bill Type: <span style="color:red;">*</span>
								</div>
							</div>
							<div class="signrightbox">
								<select class="form-control" tabindex="1" name="bill_type" id="bill_type" class="parsley-validated" onblur="getdetails()" required>

										<option value="">-- Select Bill Type --</option>
										<option value="Monthly">Monthly</option>
										<option value="Spot">Spot</option>
									
								</select>
							</div>
							</div>
							<div class="col-md-6">
								<div class="signleftbox">
								<div class="secretQ">
									Category: <span style="color:red;">*</span>
								</div>
							</div>
							<div class="signrightbox">
								<select class="form-control" tabindex="1" name="category" id="category" class="parsley-validated" required>

										<option value="">-- Select Category --</option>
										<option value="HT">HT</option>
										<option value="GT">GT</option>
										<option value="LT4">LT4</option>
										<option value="SPOT">SPOT</option>
									
								</select>
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
									User ID: <span style="color:red;">*</span>
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" onchange="checkuser(this.value);" value="" placeholder="User Id" tabindex="6" name="user_id" id="user_id" class="parsley-validated" required>
							</div>
							<div align="right" id="check">&nbsp;</div>
							</div>
							
							<div class="col-md-6">
							<div class="signleftbox">
							
								<div class="secretQ">
									Password: <span style="color:red;">*</span>
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="password" value="" placeholder="Password" tabindex="6" name="user_pass" id="user_pass" class="parsley-validated" required>
							</div>
							</div>
							<div class="col-md-6">
							<div class="signleftbox">
								<div class="secretQ">
									Confirm Password: <span style="color:red;">*</span>
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="password" value="" placeholder="Password" tabindex="6" name="cnfrm_pass" id="cnfrm_pass" class="parsley-validated" required>
							</div>
							<div align="right" style="color:#BC2B3B;">Must contain atleast one character, one number and one special character Eg: abc@123</div>
							</div>
							<div class="page-header PHmain">
							<h2>
								User Details
							</h2>
							</div>
							<div class="col-md-10">
								<div>
									<h4 style="color:#fff; background:#4EB1E5;padding:4px;border-radius:10px; margin-bottom:10px;">
								This section is to provide details of Registering User and not Consumer Details. 
									</h4>
								</div>
							</div>
							<div class="fifty">
							<div class="col-md-12">
							<div class="signleftbox">
								<div class="secretQ">
									Name: <span style="color:red;">*</span>
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" value="" placeholder="Customer Name" tabindex="7" name="cust_name" id="cust_name" class="parsley-validated" required>
							</div>
							</div>
							<div class="col-md-12">
							<div class="signleftbox ">
								<div class="secretQ">
									Date of birth:
								</div>
							</div>
							<div class="signrightbox Connectioninput">
							<input class="form-control" type="text" placeholder="DD-MM-YY" value="" tabindex="3" name="dob" id="dob" class="parsley-validated" onkeyup="DateFormat(this,this.value,event,false,'3')" onkeydown="DateFormat(this,this.value,event,false,'3')" onblur="DateFormat(this,this.value,event,true,'3')" onfocus="javascript:vDateType='3'">
							</div>
							</div>
							<div class="col-md-12">
							<div class="signleftbox ">
								<div class="secretQ">
									Email Id: <span style="color:red;">*</span>
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" value="" class="parsley-validated" placeholder="Email Id" tabindex="4" name="email_id" id="email_id" required>
								<!-- <textarea rows="" cols="" name=""></textarea> -->
							</div>
							</div>
							<div class="col-md-12">
							<div class="signleftbox ">
								<div class="secretQ">
									Phone Number: <span style="color:red;">*</span>
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" value="" class="parsley-validated" placeholder="Phone Number" tabindex="4" name="phone_no" id="phone_no" required>
								<!-- <textarea rows="" cols="" name=""></textarea> -->
							</div>
							</div>
							<div class="col-md-12">
							<div class="signleftbox ">
								<div class="secretQ">
									Mobile Number: <span style="color:red;">*</span>
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" value="" class="parsley-validated" placeholder="Mobile Number" tabindex="4" name="mobile" id="mobile" required>
								<!-- <textarea rows="" cols="" name=""></textarea> -->
							</div>
							</div>
							</div>
							<div class="fifty">
							<div class="col-md-12">
							<div class="signleftbox">
								<div class="secretQ">
									House No./House Name/Flat No.: <span style="color:red;">*</span>
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" value="" placeholder="House Number/House Name/Flat Number" tabindex="3" name="house_no" id="house_no" class="parsley-validated" required>
							</div>
						    </div>
							<div class="col-md-12">
							<div class="signleftbox">
								<div class="secretQ">
									Street: <span style="color:red;">*</span>
								</div>
							</div>
							<div class="signrightbox">
								<!-- <input type="text" id="focusedInput" /> -->
								<input class="form-control" type="text" value="" class="parsley-validated" placeholder="Street" tabindex="4" name="street" id="street" required>
							</div>
							</div>
							<div class="col-md-12">
							<div class="signleftbox">
								<div class="secretQ">
									<div class="secretQ">
										Area/Locality: <span style="color:red;">*</span>
									</div>
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" value="" class="parsley-validated" placeholder="Area/Locality" tabindex="4" name="area" id="area" required>
							</div>
							</div>
							<div class="col-md-12">
							<div class="signleftbox">
								<div class="secretQ">
									<div class="secretQ">
										City/Village: <span style="color:red;">*</span>
									</div>
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" value="" class="parsley-validated" placeholder="City/Village" tabindex="4" name="city" id="city" required>
							</div>
							</div>
							<div class="col-md-12">
							<div class="signleftbox">
								<div class="secretQ">
									<div class="secretQ">
										Pincode: <span style="color:red;">*</span>
									</div>
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" value="" class="parsley-validated" placeholder="Pincode" tabindex="4" name="pin" id="pin" required>
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
							<input type="submit" name="sub_bt" id="sub_bt" value="Submit"  class="btn btn-info" >
							<input type="reset" name="can_bt" id="can_bt" value="Cancel"  class="btn btn-info" >
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
  function checkuser(val)
  {
	   $.ajax({
		   

            url: "checkuser_ajax.php",
            type: "POST",
			dataType: 'json',
			data: 'userid='+val,
			success: function (response) {	
               if(response.result==0)
			   {
				  $("#check").html("Username Already Exists");  
				  $('#check').css('color', 'red');
			   }
			   else
			   {
				   $("#check").html("Username Available");
				   $('#check').css('color', 'green');
			   }
            }
        });
  }
  function validate()
  {
	  var result = true;
	  var email = $('#email_id').val();
	  var pass = $('#user_pass').val();
	  var cnfrmpass = $('#cnfrm_pass').val();
	  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	 // alert($('#check').html());
		if($('#check').html() == 'Username Already Exists')
		{
			alert('Username Already Exists');
			return false;
		}
		if(!regex.test(email))
		{
			alert('Enter a valid email id');
			return false;
		}
		if(pass!=cnfrmpass)
		{
			alert('Password Missmatch');
			return false;
		}
		var minNumberofChars = 6;
		var maxNumberofChars = 16;
		var regularExpression = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{6,}$/;
		if(pass.length < minNumberofChars || pass.length > maxNumberofChars){
		alert('Password length should be between 6 and 16');
        return false;
    }
    if(!regularExpression.test(pass)) {
        alert("Password should contain atleast one character, one number and one special character");
        return false;
    }
	//var regularExpression1 = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d][A-Za-z\d!@#$%^&*()_+]{6,16}$/;
	//if(!regularExpression1.test(pass)) {
    //    alert("Password should not start with a special character");
    //    return false;
    //}
			
  }
  </script>
  <?php include('includes/footer.php'); ?>