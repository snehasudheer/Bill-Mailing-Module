 <?php include('includes/header.php'); 
 ?>
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
            <h2>Online Bill Payment</h2>
            <p>Bill Details</p>
          </div>
          <div class="col-md-6">
            <ul class="breadcrumbs">
              <li><a href="index.php">Home</a></li>
              <li>Bill Details</li>
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
        
		<h1> GET YOUR BILL DETAILS </h1>
		<fieldset>
		
	

	    <!--<legend> <font color="black" size="4"> SEARCH YOUR BILL DETAILS BY</legend></font>
		<center><div id="flip">
		<button type="button" style="height:30px;" onclick="fun1()" >CONSUMER NO</button></div>
        <div id="panel">
        <form name="form1" action="condetail.php" method="post">
		<input type="text" id="cno" name="cno" autofocus size="20"><br><br> 
		<input type="submit" value="SEARCH" style="height:30px;" onclick=" return valiform1();">
		<pre></pre>
		 
		</form> </div>
	
          
	
	    <center><font color="BLACK" size="4"><b> OR </b></center></font>
	

	
	
	    <div id="flip1">
		<center>
     	<button type="button" style="height:30px;" onclick="fun2()">BILL NO</center></button></div>
        <div id="panel1"> 
        <form  name="form2" action="billdetail.php" method="post">
		<input type="text" name="bno"  autofocus id="bno" size="20"> 
		<input type="submit" value="SEARCH" style="height:30px;" onclick="return valiform2();"> 
		</form></div>
	    </center>--> 
	  
	 
	 <div style="margin-top:35px">
	 <a style="font-size:25px; padding:5px;" href="bill_det.php" class="btn btn-system" >Bill Details</a>	
	 <a style="font-size:25px; padding:5px;" href="bill_pay.php" class="btn btn-system">Quick Pay</a>
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