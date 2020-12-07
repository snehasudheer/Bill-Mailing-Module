<?php 
include('includes/header.php'); 
include('includes/db.php'); 
$stid = oci_parse($conn, "SELECT * FROM complaint_type");
oci_execute($stid);
/*while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
	print_r($row['TYPE_ID']);
}*/
?>
	<!-- Start Page Banner -->
    <div class="page-banner">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <h2>Register complaint</h2>
            <p>Complaints</p>
          </div>
          <div class="col-md-6">
            <ul class="breadcrumbs">
              <li><a href="index.php">Home</a></li>
              <li>Register complaint</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- End Page Banner -->
	<div class="container" style="margin-top:2%;">
	<div class="row">
		<div class="col-md-8" style="margin-bottom:2%;">
		<div class="content">
	<form autocomplete="off" method="post" action="anonymousComplainRegister.do" class="contact_form" data-validate="parsley" id="form-new-reg1">
		
			
			
				
					<div class="col-md-8">
						<div class="page-header PHmain">
							<h2>
								File New Complaint
							</h2>
						</div>
					</div>
				</div>
				
				<div class="row errorMessageSitewide" id="outageMsgDiv" style="display: none;">
						<a href="javascript:void(0);" id="msg12"></a>
						<div class="col-md-12">
							<div class="errorMessageSitewideBG"></div>
							<div class="errorMessageSitewideMessage"><span id="outageMsgSpan"></span></div>
						</div>
				</div>
				<div class="row">
					<div class="col-md-8 ">
						<div class="form-group">
							<div class="signleftbox">
								<div class="secretQ">
									Complaint Type:
								</div>
							</div>
							<div class="signrightbox">
								<select class="form-control" onchange="updateSecondSelection(this.value);" data-required="true" tabindex="1" name="type" id="dropdown1" class="parsley-validated">

										<option value="">-- Select Complaint --</option>
										<?php while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) { ?>
										<option value="<?php echo $row['TYPE_ID']; ?>"><?php echo $row['TYPE_DESC']; ?></option>
										<?php } ?>
										<!--<option value="100-1">NO POWER SUPPLY</option>
									
										<option value="100-2">VOLTAGE RELATED</option>
									
										<option value="100-3">TRANSFORMER RELATED</option>
									
										<option value="100-4">LINE RELATED</option>
									
										<option value="100-5">POLE RELATED</option>
									
										<option value="100-6">SERVICE CONNECTION RELATED</option>
									
										<option value="100-7">BILLING/METER RELATED COMPLAINTS</option>-->
									
								</select>
							</div>
							<div class="signleftbox">
								<div class="secretQ">
									Landmark:
								</div>
							</div>
							<div class="signrightbox">
								<a class="tooltip_button" data-placement="bottom" data-tooltip="Please provide the landmark to identify the location of problem. Landmark must be between 5 and 100 characters." href="javascript:void(0);"><img alt="" src="/selfservices/resources/Images/icons/Tooltip_QIcon.png"></a>
								<input class="form-control" type="text" maxlength="100" value="" data-trigger="focusin focusout" data-maxlength="100" data-error-message="Please provide the landmark " placeholder="Landmark" data-minlength="5" tabindex="6" name="landmark" id="landmark" class="parsley-validated">
							</div>
							<div class="signleftbox">
								<div class="secretQ">
									Complaint Category:
								</div>
							</div>
							<div class="signrightbox">
								<select class="form-control" onchange="getConsumerIdIsRequired();" data-required="true" tabindex="2" name="subType" id="dropdown2" class="parsley-validated">
									<option value="100-42">POWER THEFT</option>
									<option value="100-40">ACCIDENTS</option>
									<option value="100-43">MISCONDUCT BY EMPLOYEE</option>
									<option value="100-44">OTHERS</option>
									<option value="100-41">STREETLIGHT</option>
									<option value="100-39">SAFETY</option>
								</select>
							</div>
							<div class="signleftbox">
								<div class="secretQ">
									Consumer Number:
								</div>
							</div>
							<div class="signrightbox">
								<a class="tooltip_button" data-placement="bottom" data-tooltip="Please provide your 13 digit consumer number" href="javascript:void(0);"><img alt="" src="/selfservices/resources/Images/icons/Tooltip_QIcon.png"></a>
								<input class="form-control" type="text" maxlength="13" value="" onblur="getDTROutageInformation(this.value)" data-trigger="focusin focusout" data-error-message="You must enter a valid Consumer Number" placeholder="Consumer Number" data-minlength="13" data-regexp="^[1][1-3][0-9]{11}$" tabindex="7" name="consumerNumber" id="conNo" class="parsley-validated">
								

							</div>
							<div class="signleftbox">
								<div class="secretQ">
									Contact Person:
								</div>
							</div>
							<div class="signrightbox">
								<input class="form-control" type="text" maxlength="40" value="" data-trigger="focusin focusout" data-maxlength="40" data-error-message="You must enter a valid Name" placeholder="Contact Person" data-minlength="3" data-regexp="^[a-zA-Z\s\.]{1,40}$" tabindex="3" name="contactPerson" id="cperson" class="parsley-validated">
							</div>
						</div>
							<div class="signleftbox ">
								<div class="secretQ">
									Problem Description:
								</div>
							</div>
							<div class="signrightbox Connectioninput">
								<!-- <textarea rows="" cols="" name=""></textarea> -->
								<textarea class="form-control" data-trigger="keyup change" data-maxlength="250" class="required parsley-validated" data-error-message="Please enter complaint description " placeholder="Problem Description" data-required="true" data-minlength="5" style="resize:none;height:70px" maxlength="250" tabindex="8" name="description" id="problemDesc"></textarea>
							</div>
							<div class="signleftbox">
								<div class="secretQ">
									Mobile Number:
								</div>
							</div>
							<div class="signrightbox">
								<!-- <input type="text" id="focusedInput" /> -->
								<input class="form-control" type="text" maxlength="10" value="" data-trigger="focusin focusout" class="required parsley-validated" data-error-message="You must enter a valid mobile number" placeholder="Mobile Number" data-required="true" data-minlength="10" data-regexp="^[0-9]{10}$" tabindex="4" name="mobileNumber" id="focusedInput">
							</div>
							<div class="signleftbox ">
								<div class="secretQ">
									Address:
								</div>
							</div>
							<div class="signrightbox Connectioninput">
								<textarea class="form-control" rows="1" data-maxlength="300" data-error-message="Please enter your address " placeholder="Address " data-minlength="5" maxlength="300" tabindex="8" name="addressLine1" id="addr" class="parsley-validated"></textarea>
								<!-- <textarea rows="" cols="" name=""></textarea> -->
							</div>
							<div class="signleftbox">
								<div class="secretQ">
									<div class="secretQ">
										
										District:
									</div>
								</div>
							</div>
							<div class="signrightbox">
								<select class="form-control" data-error-message="Please select a district" tabindex="5" name="districtId" id="districtId" class="parsley-validated">
									<option value="">
										-- Select District --
									</option>
									<option value="1">THIRUVANANTHAPURAM</option><option value="2">KOLLAM</option><option value="3">PATHANAMTHITTA</option><option value="4">ALAPUZHA</option><option value="5">KOTTAYAM</option><option value="6">IDUKKI</option><option value="7">ERNAKULAM</option><option value="8">THRISSUR</option><option value="9">PALAKKAD</option><option value="10">MALAPPURAM</option><option value="11">KOZHIKODE</option><option value="12">WAYANAD</option><option value="13">KANNUR</option><option value="14">KASARGODE</option>
								</select><input type="hidden" value="1" name="_districtId">
							</div>
						</div>
						<!--  -->
					
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
     <div class="col-md-4">
		<?php include('sidemenu.php'); ?>
		</div>
    </div>
  </div>
  <?php include('includes/footer.php'); ?>
  <script>
  function updateSecondSelection(val)
  {
	 
    $.ajax({
		url: "sub_type_ajax.php?type="+val, 
		success: function(result){
        //$("#div1").html(result);
    }});
  }
  </script>