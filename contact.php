 <?php include('includes/header.php'); 
 
 if(isset($_POST['sub_bt']))
 {
	 
	 $message = '<html><body>';
			$message .= '<table rules="all" style="border-color: #666; width:40%;" border="1" cellpadding="10">';
			$message .= "<tr><td><strong>Name:</strong> </td><td>" . strip_tags($_POST['c_name']) . "</td></tr>";
			$message .= "<tr><td><strong>Email:</strong> </td><td>" . strip_tags($_POST['email']) . "</td></tr>";
			$message .= "<tr><td><strong>Phone:</strong> </td><td>" . strip_tags($_POST['phone']) . "</td></tr>";
			$message .= "<tr><td><strong>Message:</strong> </td><td>" . htmlentities($_POST['message']) . "</td></tr>";
			$message .= "</table>";
			$message .= "</body></html>";
			echo $message;
			
			
			$pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i"; 
            if (preg_match($pattern, trim(strip_tags($_POST['email'])))) { 
                $cleanedFrom = trim(strip_tags($_POST['email'])); 
            } /*else { 
                return "The email address you entered was invalid. Please try again!"; 
            } */
			
	 $to = 'electricitydepartment@yahoo.in';
			
			$subject = 'Contact Message From Website';
			
			$headers = "From: " . $cleanedFrom . "\r\n";
			$headers .= "Reply-To: ". strip_tags($_POST['email']) . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            if (mail($to, $subject, $message, $headers)) {
              echo 'Your message has been sent.';
            } else {
              echo 'There was a problem sending the email.';
            }
 }
 
 
 ?>
 <!-- Start Page Banner -->
    <div class="page-banner">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <h2>Contact Us</h2>
            <p>TCED Contact Page</p>
          </div>
          <div class="col-md-6">
            <ul class="breadcrumbs">
              <li><a href="index.php">Home</a></li>
              <li>TCED Contact Page</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- End Page Banner -->
	<div class="container">
		<div class="row">		
			<div class="col-md-6">
				<div class="content">
					<h1>Contact Info</h1>
					<p>
						<h3 style="color:#2A6298">Electricity Department ,<br>
						Thrissur Corporation,<br>
						Thrissur,kerala.</h3>
						<h3 style="color:#2A6298">
						HELP LINE : 0487 - 2422950<br>
						Email : electricitydepartment@yahoo.in<br>
						</h3>
						<br>
						
					</p>
		 
				</div>
			</div>
			<div class="col-md-6">
		<div class="content" style="padding:10px;">
		<h1>Contact Form</h1>
	 <form name="bill_det" method="post" action="contact.php">
	 
	 <div class="form-group">
		<div class="signleftbox">Your Name:</div>
		<div class="signrightbox">		
				<input class="form-control" type="text" name="c_name" id="c_name" value="" required>	
		</div>
		<div class="signleftbox">E-mail: </div>
		<div class="signrightbox">
			<input class="form-control" type="text" name="email" id="email" value="" >
		</div>
		<div class="signleftbox">Phone: </div>
		<div class="signrightbox">
			<input class="form-control" type="text" name="phone" id="phone"  value="" style="width:59%;" >
		</div>
		<div class="signleftbox">Message: </div>
		<div class="signrightbox">
		<textarea name="message" id="message" style=" height: 100px;width: 100%;"></textarea>
		</div>
		
		<div style="float:left;text-align:right;width: 100%; margin-bottom:10px;">
		<input type="submit" name="sub_bt" id="sub_bt" value="Send"  class="btn btn-info" >
		</div>
		</div>
	 </form>
	 
	 </div>
	</div>
		</div>
	</div>
 <?php include('includes/footer.php'); ?>