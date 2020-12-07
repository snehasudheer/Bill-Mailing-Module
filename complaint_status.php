 <?php include('includes/header.php'); ?>
<script language = "javascript"> 

function valiform1() 
{
    var x = document.getElementById('cno').value;
	if ( x == null || x == "" )
	
	{    alert(x);
        alert("ENTER YOUR CONSUMER NO, IT CAN'T BE EMPTY!!!");
		return false;
    }
	
	
	
}
function valiform2() 
{ 
    var y = document.getElementById('bno').value;
	if ( y == null || y == "" )
	
	{
        alert("ENTER YOUR BILL NO, IT CAN'T BE EMPTY!!!");
		return false ;
	
        
	}
	
	
}

  </script>
  
 
 
 
 
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
table td {word-wrap:break-word;}
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
 <script>
$(document).ready(function(){
    $("#flip1").click(function(){
        $("#panel1").slideToggle("slow");
    });
});
</script> 
<script>
$(document).ready(function(){
    $("#flip").click(function(){
        $("#panel").slideToggle("slow");
    });
});
</script>

<!-- Start Page Banner -->
    <div class="page-banner">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <h2>Complaint Status</h2>
            <p>Complaint Details</p>
          </div>
          <div class="col-md-6">
            <ul class="breadcrumbs">
              <li><a href="index.php">Home</a></li>
              <li>Complaint Details</li>
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
        
		<h1> Complaint Status </h1>
		<fieldset>
	 
	 <div style="margin-top:35px">
	 
	 <div style="margin-top:35px; font-size:16px; color:#000;">
	 <form name="bill_det" method="post">
	 
	 <div class="form-group">
		<div class="signleftbox">Complaint No: </div>
		<div class="signrightbox">
				<input class="form-control" type="text" name="cmp_no" id="cmp_no" value="<?php if(isset($_POST['cmp_no'])) { echo $_POST['cmp_no']; } ?>" >
		</div>
		<div style="margin-top:10px; float:right;">
		<input type="reset" name="res_bt" id="res_bt" value="Reset"  class="btn btn-info" >
		<input type="submit" name="sub_bt" id="sub_bt" value="submit"  class="btn btn-info" >
		</div>
		</div>
	 </form>
	 </div>
	 
	 </div>
	  <?php if(isset($_POST['sub_bt']))
	{ ?>
	<div id="cmplnts" style="margin-top:30px; float:left; padding:25px; border:1px solid #ccc; background: rgb(252,255,244); /* Old browsers */
background: -moz-linear-gradient(-45deg, rgba(252,255,244,1) 0%, rgba(223,229,215,1) 40%, rgba(179,190,173,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(-45deg, rgba(252,255,244,1) 0%,rgba(223,229,215,1) 40%,rgba(179,190,173,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(135deg, rgba(252,255,244,1) 0%,rgba(223,229,215,1) 40%,rgba(179,190,173,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fcfff4', endColorstr='#b3bead',GradientType=1 );" >
			<div class="signleftbox">
				<div class="secretQ">
					Complaint Type:
				</div>
			</div>
			<div class="signrightbox">
				<input class="form-control" disabled="disabled" type="text" maxlength="13" value="GENERIC COMPLAINTS" data-trigger="focusin focusout"  placeholder="Complaint Type" data-minlength="13" data-regexp="^[1][1-3][0-9]{11}$" tabindex="7" name="complaint_type" id="com_type" class="parsley-validated">								
			</div>
			<div class="signleftbox">
				<div class="secretQ">
					Land Mark:
				</div>
			</div>
			<div class="signrightbox">
				<input class="form-control" disabled="disabled" type="text" maxlength="13" value="Dinesh Auditorium" data-trigger="focusin focusout"  placeholder="Land Mark" data-minlength="13" data-regexp="^[1][1-3][0-9]{11}$" tabindex="7" name="land_mark" id="land_mark" class="parsley-validated">								
			</div>
			<div class="signleftbox">
				<div class="secretQ">
					Complaint Category:
				</div>
			</div>
			<div class="signrightbox">
				<input class="form-control" disabled="disabled" type="text" maxlength="13" value="ACCIDENTS" data-trigger="focusin focusout"  placeholder="Complaint Category" data-minlength="13" data-regexp="^[1][1-3][0-9]{11}$" tabindex="7" name="com_cat" id="com_cat" class="parsley-validated">								
			</div>
			<div class="signleftbox">
				<div class="secretQ">
					Consumer Number:
				</div>
			</div>
			<div class="signrightbox">
				<input class="form-control" disabled="disabled" type="text" maxlength="13" value="45555353" data-trigger="focusin focusout" placeholder="Consumer Number" data-minlength="13" data-regexp="^[1][1-3][0-9]{11}$" tabindex="7" name="consumerNumber" id="conNo" class="parsley-validated">								
			</div>
			<div class="signleftbox">
				<div class="secretQ">
					Contact Person:
				</div>
			</div>
			<div class="signrightbox">
				<input class="form-control" disabled="disabled" type="text" maxlength="40" value="Shyni" data-trigger="focusin focusout" data-maxlength="40" placeholder="Contact Person" data-minlength="3" data-regexp="^[a-zA-Z\s\.]{1,40}$" tabindex="3" name="contactPerson" id="cperson" class="parsley-validated">
			</div>
		
			<div class="signleftbox ">
				<div class="secretQ">
					Problem Description:
				</div>
			</div>
			<div class="signrightbox Connectioninput">
				<textarea class="form-control" disabled="disabled" type="text" maxlength="40" data-trigger="focusin focusout" data-maxlength="40" placeholder="Problem Description" data-minlength="3" data-regexp="^[a-zA-Z\s\.]{1,40}$" tabindex="3" name="contactPerson" id="cperson" class="parsley-validated">Some accidents occured during voltage fluctuation</textarea>
			</div>
			
			<div class="signleftbox">
				<div class="secretQ">
					Complaint Status:
				</div>
			</div>
			<div class="signrightbox">
				<input class="form-control" disabled="disabled" type="text" maxlength="40" value="Solving" data-trigger="focusin focusout" data-maxlength="40" placeholder="Contact Person" data-minlength="3" data-regexp="^[a-zA-Z\s\.]{1,40}$" tabindex="3" name="contactPerson" id="cperson" class="parsley-validated">
			</div>
			
			<div class="signleftbox ">
				<div class="secretQ">
					Comments:
				</div>
			</div>
			<div class="signrightbox Connectioninput">
				<textarea class="form-control" disabled="disabled" type="text" maxlength="40" data-trigger="focusin focusout" data-maxlength="40" placeholder="Comments" data-minlength="3" data-regexp="^[a-zA-Z\s\.]{1,40}$" tabindex="3" name="contactPerson" id="cperson" class="parsley-validated">Your complaint will be solved within 4 hours</textarea>
			</div>
	</div>
	<?php } ?>
	 </fieldset>
	 
	

	 </div>
	 </div>
		<div class="col-md-4">
		<?php include('sidemenu.php'); ?>
		</div>
		
		</div>
		</div>
		
<?php include('includes/footer.php'); ?>