 <?php 
 include('includes/header.php'); 
 include('includes/db.php');
  if(isset($_POST['sub_bt']))
	{
		$cond = '';
		
		if(isset($_POST['con_no']))
		{
			$consumerno = $_POST['con_no'];
			
		}
		if(isset($_POST['bill_no']) && $_POST['bill_no']!='')
		{
			$bill_no = $_POST['bill_no'];
			$cond = " and BILLNO='$bill_no'";
		}
		
		if(isset($_POST['bill_type']) && $_POST['bill_type']!='')
		{
			$bill_type = $_POST['bill_type'];
			if(trim($bill_type)=='Monthly')
			{
				$tab = "newmaster";
			}
			elseif($bill_type=='Spot')
			{
				$tab = "master";
			}
		}
		
		$stid1 = oci_parse($conn, "SELECT CATEGORY FROM ".$tab." where CNO='$consumerno'");
		//echo "SELECT CATEGORY FROM ".$tab." where CNO='$consumerno'";
		oci_execute($stid1);
		$row1 = oci_fetch_array($stid1, OCI_ASSOC+OCI_RETURN_NULLS);
		$cat = $row1['CATEGORY'];
		
		
		$stid = oci_parse($conn, "SELECT CNO,BILLNO,BILLDATE,LASTDATE,DISDATE,TOTAL,PAID  FROM ".$cat."billfile where CNO='$consumerno'".$cond);
		//echo "SELECT CNO,BILLNO,BILLDATE,LASTDATE,DISDATE,TOTAL,PAID  FROM ".$cat."billfile where CNO='$consumerno'".$cond;
		//exit;
		oci_execute($stid);

		$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
		
		if(!empty($row))
		{
		$cur_date = date('d-M-y');
		//echo strtoupper($cur_date);
		//echo $row['LASTDATE'];
		$lastdate = strtotime($row['LASTDATE']);
                
                
                
		$cur_date= strtotime($cur_date);
                //echo $row['DISDATE'];
                //$d1="31-APR-17";
                //$d2=strtotime($d1);
                
                
                $dis_date = strtotime($row['DISDATE']);
                
		if($row['PAID']!=0)
		{
		 header('Location:bill_pay.php?cno='.$consumerno.'&bill_no='.$bill_no.'&bill_type='.$bill_type.'&msg=paid');
		}
		if(($dis_date >= $cur_date) && ($lastdate < $cur_date))
		{
                      $day= ceil(abs($cur_date - $lastdate) / 86400);
                      $month = ($day / 30) ;
                      $month = floor($month); 
                      $days = ($day % 30);
                      $amt=$row['TOTAL'];
                      if($month<=1)
                      {
                          $fine=$amt*(1/100);
                      }
                      else 
                      {
                          $fine=$amt*(1.5/100);
                      }
                      $fine=  round($fine);
                      $pay=$amt+$fine;
                      $pay=  round($pay);
                      
		}
		elseif($lastdate >= $cur_date)
		{
			 $fine =  0.00;
			 $pay = $row['TOTAL'];
		}
                else {
                      header('Location:bill_pay.php?cno='.$consumerno.'&bill_no='.$bill_no.'&bill_type='.$bill_type.'&msg=contact');
                }  
		}
		else
		{
		 header('Location:bill_pay.php?cno='.$consumerno.'&bill_no='.$bill_no.'&bill_type='.$bill_type.'&msg=notfound');	
		}
		//print_r($row);
	}
 
 
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
	
	/* 
	Max width before this PARTICULAR table gets nasty
	This query will take effect for any screen smaller than 760px
	and also iPads specifically.
	*/
	@media 
	only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 768px)  {
	
		/* Force table to not be like tables anymore */
		table, thead, tbody, th, td, tr { 
			display: block; 
		}
		
		/* Hide table headers (but not display: none;, for accessibility) */
		thead tr { 
			position: absolute;
			top: -9999px;
			left: -9999px;
		}
		
		table tr th{
		height:35px;
		font-weight:bold;
		font-size:16px;
	}
		
		tr { border: 1px solid #ccc; }
		
		td { 
			/* Behave  like a "row" */
			border: none;
			border-bottom: 1px solid #eee; 
			position: relative;
			padding-left: 50%; 
		}
		
		td:before { 
			/* Now like a table header */
			position: absolute;
			/* Top/left values mimic padding */
			top: 6px;
			left: 6px;
			width: 45%; 
			padding-right: 10px; 
			white-space: nowrap;
		}
		
		/*
		Label the data
		*/
		td:nth-of-type(1):before { content: "BILL NO"; }
		td:nth-of-type(2):before { content: "BILL DATE"; }
		td:nth-of-type(3):before { content: "DUE DATE"; }
		td:nth-of-type(4):before { content: "PAID DATE"; }
		td:nth-of-type(5):before { content: "PAYMENT"; }
	}
	
	/* Smartphones (portrait and landscape) ----------- */
	@media only screen
	and (min-device-width : 320px)
	and (max-device-width : 480px) {
		bills { 
			padding: 0; 
			margin: 0; 
			width: 320px; }
		}
	
	/* iPads (portrait and landscape) ----------- */
	@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
		bills { 
			width: 495px; 
		}
	}
	
	table tr:nth-child(odd) td{
		background:#FEACAC repeat;
	}
	table tr:nth-child(even) td{
		background:#C6DEF4 repeat;
	}
	table tr td{
		height:35px;
	}
	table tr th{
		height:35px;
		font-weight:bold;
		font-size:16px;
	}
	</style>

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
        
		<h1> YOUR BILL DETAILS </h1>
		<fieldset>
	 
	 <div style="margin-top:35px">
	 
	 <div style="margin-top:35px; font-size:16px; color:#000;">
	 <form name="bill_det" method="post">
	 
	 <div class="form-group">
		<div class="signleftbox">Consumer No: </div>
		<div class="signrightbox">	
		<input class="form-control" type="text" name="con_no" id="con_no" value="<?php echo $row['CNO']; ?>" readonly >
		</div>
		<div class="signleftbox">Bill No: </div>
		<div class="signrightbox">
			<input class="form-control" type="text" name="bill_no" id="bill_no" value="<?php echo $row['BILLNO']; ?>" readonly >
		</div>
		<div class="signleftbox">Bill Date: </div>
		<div class="signrightbox">
			<input class="form-control" type="text" name="bill_dt" id="bill_dt" value="<?php echo $row['BILLDATE']; ?>" readonly >
		</div>
		<div class="signleftbox">Last Date: </div>
		<div class="signrightbox">
			<input class="form-control" type="text" name="bill_dt" id="bill_dt" value="<?php echo $row['LASTDATE']; ?>" readonly >
		</div>
		<div class="signleftbox">Disconnection Date: </div>
		<div class="signrightbox">
			<input class="form-control" type="text" name="dis_dt" id="dis_dt" value="<?php echo $row['DISDATE']; ?>" readonly >
		</div>
		<div class="signleftbox">Amount: </div>
		<div class="signrightbox">
		<input class="form-control" type="text" name="amnt" id="amnt" value="<?php echo $row['TOTAL']; ?>" readonly>
		</div>
                <div class="signleftbox">Fine Amount: </div>
		<div class="signrightbox">
		<input class="form-control" type="text" name="amnt" id="amnt" value="<?php echo number_format((float)$fine, 2, '.', ''); ?>" readonly>
		</div>                
		<div class="signleftbox">Payable Amount: </div>
		<div class="signrightbox">
		<input class="form-control" type="text" name="amnt" id="amnt" value="<?php echo  number_format((float)$pay, 2, '.', ''); ?>" readonly>
		</div>
		<div style="text-align:right;">
		<input type="submit" name="sub_bt" id="sub_bt" value="Pay Bill"  class="btn btn-info" >
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