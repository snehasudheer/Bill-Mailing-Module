 <?php 
 include('includes/header.php'); 
 include('includes/db.php');
  if(isset($_POST['sub_bt']) || isset($_GET['con_no'])) 
	{
		$start=0;
		$limit=20;
		$end = $limit;
		$page =1;
                
                $bill_no="";
                $from_dt="";
                $to_dt="";
                       
		if(isset($_GET['page']))
		{
			$page=$_GET['page'];
			$start=($page-1)*$limit+1;
			$end=$page*$limit;
		}
		$cond = '';
		if(isset($_POST['bill_type']) && $_POST['bill_type']!='')
			{
				$bill_type = $_POST['bill_type'];
			}
		elseif(isset($_GET['bill_type']) && $_GET['bill_type']!='')
			{
				$bill_type = $_GET['bill_type'];
			}
		
		if(isset($_POST['con_no']) && $_POST['con_no']!='')
			{
					$consumerno = $_POST['con_no'];
			}
		elseif(isset($_GET['con_no']) && $_GET['con_no']!='')
			{
					$consumerno = $_GET['con_no'];
			}
				
		if(trim($bill_type)=='Spot')
		{
			if(isset($_POST['bill_no']) && $_POST['bill_no']!='')
				{
					$bill_no = $_POST['bill_no'];
					$cond = " and BNO='$bill_no'";
				}
			elseif(isset($_GET['bill_no']) && $_GET['bill_no']!='')
				{
					$bill_no = $_GET['bill_no'];
					$cond = " and BNO='$bill_no'";
				}
			
			if(isset($_POST['from_dt']) && $_POST['from_dt']!='' && isset($_POST['to_dt']) && $_POST['to_dt']!='')
				{
					$from_dt = $_POST['from_dt'];
					$from_dt = date('d-M-Y',strtotime($from_dt));
					$to_dt = $_POST['to_dt'];
					$to_dt = date('d-M-Y',strtotime($to_dt));
					$cond.=" and BDATE between '$from_dt' and '$to_dt'";
				}
			elseif(isset($_GET['from_dt']) && $_GET['from_dt']!='' && isset($_GET['to_dt']) && $_GET['to_dt']!='')
				{
					$from_dt = $_GET['from_dt'];
					$from_dt = date('d-M-Y',strtotime($from_dt));
					$to_dt = $_GET['to_dt'];
					$to_dt = date('d-M-Y',strtotime($to_dt));
					$cond.=" and BDATE between '$from_dt' and '$to_dt'";
				}
		}
		else
		{
			if(isset($_POST['bill_no']) && $_POST['bill_no']!='')
				{
					$bill_no = $_POST['bill_no'];
					$cond = " and BILLNO='$bill_no'";
				}
			elseif(isset($_GET['bill_no']) && $_GET['bill_no']!='')
				{
					$bill_no = $_GET['bill_no'];
					$cond = " and BILLNO='$bill_no'";
				}
			
			if(isset($_POST['from_dt']) && $_POST['from_dt']!='' && isset($_POST['to_dt']) && $_POST['to_dt']!='')
				{
					$from_dt = $_POST['from_dt'];
					$from_dt = date('d-M-Y',strtotime($from_dt));
					$to_dt = $_POST['to_dt'];
					$to_dt = date('d-M-Y',strtotime($to_dt));
					$cond.=" and BILLDATE between '$from_dt' and '$to_dt'";
				}
			elseif(isset($_GET['from_dt']) && $_GET['from_dt']!='' && isset($_GET['to_dt']) && $_GET['to_dt']!='')
				{
					$from_dt = $_GET['from_dt'];
					$from_dt = date('d-M-Y',strtotime($from_dt));
					$to_dt = $_GET['to_dt'];
					$to_dt = date('d-M-Y',strtotime($to_dt));
					$cond.=" and BILLDATE between '$from_dt' and '$to_dt'";
				}	
		}
		
		if(isset($bill_type))
		{
			if(trim($bill_type)=='Monthly')
			{
				$tab = "newmaster";
				$stid1 = oci_parse($conn, "SELECT CATEGORY FROM ".$tab." where CNO='$consumerno'");
				oci_execute($stid1);
				$row1 = oci_fetch_array($stid1, OCI_ASSOC+OCI_RETURN_NULLS);
				$cat = $row1['CATEGORY'];
				if($cat=='LT4')
				{
				$print_page="gtnlt_bill.php";
				$stid = oci_parse($conn, "select * from ( select a.*, ROWNUM rnum from ( SELECT CNO,BILLNO,BILLDATE,LASTDATE,DISDATE,TOTAL,PAID  FROM LT4billfile where CNO='$consumerno'".$cond." and YEAR > '2002' order by billdate DESC) a where ROWNUM <= $end )where rnum  >= $start");
				$rows=oci_parse($conn, "select count(*) as count from LT4billfile where CNO='$consumerno'".$cond." and YEAR > '2002'");
				$paidcheck=oci_parse($conn, "select count(*) as count from LT4BILLFILE where billdate<(select max(billdate) from LT4BILLFILE where CNO='$consumerno'".$cond.") and CNO='$consumerno'".$cond." and YEAR > '2002' and paid='0'");
				}
				else if($cat=='GT')
				{
				$print_page="gtnlt_bill.php";
				$stid = oci_parse($conn, "select * from ( select a.*, ROWNUM rnum from ( SELECT CNO,BILLNO,BILLDATE,LASTDATE,DISDATE,TOTAL,PAID  FROM GTbillfile where CNO='$consumerno'".$cond." and YEAR > '2002' order by billdate DESC) a where ROWNUM <= $end )where rnum  >= $start");
				$rows=oci_parse($conn, "select count(*) as count from GTbillfile where CNO='$consumerno'".$cond." and YEAR > '2002'");	
				$paidcheck=oci_parse($conn, "select count(*) as count from GTbillfile where billdate<(select max(billdate) from GTbillfile where CNO='$consumerno'".$cond.") and CNO='$consumerno'".$cond." and YEAR > '2002' and paid='0'");				
				}
				else if($cat=='HT')
				{ 
				$print_page="ht_bill.php";
				$stid = oci_parse($conn, "select * from ( select a.*, ROWNUM rnum from (SELECT CNO,BILLNO,BILLDATE,LASTDATE,DISDATE,TOTBILLAMT as TOTAL,PAID  FROM NEWHTbillfile where CNO='$consumerno'".$cond." and YEAR > '2002' order by billdate DESC) a where ROWNUM <= $end )where rnum  >= $start");
				$rows=oci_parse($conn, "select count(*) as count from NEWHTbillfile where CNO='$consumerno'".$cond." and YEAR > '2002'");
				$paidcheck=oci_parse($conn, "select count(*) as count from NEWHTbillfile where billdate<(select max(billdate) from NEWHTbillfile where CNO='$consumerno'".$cond.") and CNO='$consumerno'".$cond." and YEAR > '2002' and paid='0'");
				}
			}
			elseif(trim($bill_type)=='Spot')
			{
				$print_page="spot_bill.php";
				$stid = oci_parse($conn, "select * from ( select a.*, ROWNUM rnum from (SELECT CNO,BNO as billno,BDATE as billdate,LDATE as lastdate,FINEDATE as disdate,totamt as TOTAL,PAID FROM billfile_trans where CNO='$consumerno'".$cond." and YEAR > '2011' order by BDATE DESC) a where ROWNUM <= $end )where rnum  >= $start");;
				$rows=oci_parse($conn, "select count(*) as count from billfile_trans where CNO='$consumerno'".$cond." and YEAR > '2011'");
				//echo "SELECT CNO,BNO as billno,BDATE as billdate,LDATE as lastdate,FINEDATE as disdate,totamt as TOTAL,PAID FROM billfile_trans where CNO='$consumerno'".$cond." and YEAR > '2011' order by BDATE DESC";
				$paidcheck=oci_parse($conn, "select count(*) as count from billfile_trans where BDATE<(select max(BDATE) from billfile_trans where CNO='$consumerno'".$cond.") and CNO='$consumerno'".$cond." and YEAR > '2011' and paid='0'");
				
			}
		}
		oci_execute($stid);
		oci_execute($rows);
		oci_execute($paidcheck);
		
$cnt = oci_fetch_array($rows, OCI_NUM);
$total=ceil($cnt[0]/$limit);
$paidcnt = oci_fetch_array($paidcheck, OCI_NUM);
$checkcnt = $paidcnt[0];
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
			top: 4px;
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
		td:nth-of-type(3):before { content: "LAST DATE"; }
		td:nth-of-type(4):before { content: "DISCONNECTION DATE"; }
		td:nth-of-type(5):before { content: "AMOUNT"; }
		td:nth-of-type(6):before { content: "BILL DETAILS"; }
		td:nth-of-type(7):before { content: "PRINT RECEIPT"; }
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
		background:#FFF repeat;
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
	<style type="text/css">
#content
{
	width: 900px;
	margin: 0 auto;
	font-family:Arial, Helvetica, sans-serif;
}
.page
{
float: right;
margin: 0;
padding: 0;
}
.page li
{
	list-style: none;
	display:inline-block;
}
.page li a, .current
{
display: block;
padding: 5px;
text-decoration: none;
color: #FFF;
 background: #4e8fc7 none repeat scroll 0 0;
}
.current
{
	/*font-weight:bold;*/
	color: #000;
}
.button
{
padding: 5px 15px;
text-decoration: none;
background: #333;
color: #F3F3F3;
font-size: 13PX;
border-radius: 2PX;
margin: 0 4PX;
display: block;
float: left;
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
        
		<h1> YOUR RECEIPT DETAILS </h1>
		<fieldset>
	 
	 <div style="margin-top:35px">
	 
	 <div style="margin-top:35px; font-size:16px; color:#000;">
	 <form name="bill_det" method="post" action="bill_det.php">
	 
	 <div class="form-group">
		<div class="signleftbox">Consumer No: </div>
		<div class="signrightbox">
				<?php if(isset($_SESSION['cno'])) { ?>
				<input class="form-control" type="text" name="con_no" id="con_no" value="<?php if(isset($_POST['con_no'])) { echo $_POST['con_no']; } else { echo $_SESSION['cno']; } ?>" required>
				<?php } else { ?>
				<input class="form-control" type="text" name="con_no" id="con_no" value="<?php if(isset($consumerno)) { echo $consumerno; } ?>" required>
				<?php } ?>
		</div>
		<div class="signleftbox">Bill No: </div>
		<div class="signrightbox">
			<input class="form-control" type="text" name="bill_no" id="bill_no" value="<?php if(isset($bill_no)) { echo $bill_no; } ?>" >
		</div>
		<div class="signleftbox">From Date: </div>
		<div class="signrightbox">
			<input class="form-control" type="text" name="from_dt" id="from_dt" onkeyup="DateFormat(this,this.value,event,false,'3')" onkeydown="DateFormat(this,this.value,event,false,'3')" onblur="DateFormat(this,this.value,event,true,'3')" onfocus="javascript:vDateType='3'"value="<?php if(isset($from_dt)) { echo $from_dt; } ?>" style="width:59%;" placeholder="DD-MM-YY">
		</div>
		<div class="signleftbox">To Date: </div>
		<div class="signrightbox">
		<input class="form-control" type="text" name="to_dt" id="to_dt" onkeyup="DateFormat(this,this.value,event,false,'3')" onkeydown="DateFormat(this,this.value,event,false,'3')" onblur="DateFormat(this,this.value,event,true,'3')" onfocus="javascript:vDateType='3'" value="<?php if(isset($to_dt)) { echo $to_dt; } ?>" style="width:59%;" placeholder="DD-MM-YY">
		</div>
		<div class="signleftbox">Bill Type: </div>
		<div class="signrightbox">
		<?php if(isset($_SESSION['cno'])) { ?>
			<label style="font-size:18px;"> Monthly </label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="bill_type" value="Monthly" <?php if($_SESSION['bill_type']=='Monthly') { echo 'checked="checked"'; } ?>required>&nbsp;&nbsp;&nbsp;&nbsp;
			<label style="font-size:18px;"> Spot </label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="bill_type" value="Spot" <?php if($_SESSION['bill_type']=='Spot') { echo 'checked="checked"'; } ?> required>
			
		<?php } else { ?>
			<label style="font-size:18px;"> Monthly </label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="bill_type" value="Monthly" <?php if(isset($bill_type) && $bill_type=='Monthly') { echo 'checked="checked"'; } ?> required>&nbsp;&nbsp;&nbsp;&nbsp;
			<label style="font-size:18px;"> Spot </label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="bill_type" value="Spot" <?php if(isset($bill_type) && $bill_type=='Spot') { echo 'checked="checked"'; } ?> required>
		<?php } ?>
		</div>
		<div style="float:left;">
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
		
		<?php if(isset($_POST['sub_bt']) || isset($_GET['con_no']))
	{  
?>
<div class="col-md-12" id="bills" style="margin-top:30px; margin-bottom:30px;" >
<table width="100%" cellspacing='2' cellpadding='10' border='1'>
	 <thead>
	 <tr>
	 <th class="col-md-1" style="text-align:center;">Bill No.</th>
	 <th class="col-md-2" style="text-align:center;">Bill Date</th>
	 <th class="col-md-2" style="text-align:center;">Last Date</th>
	 <th class="col-md-2" style="text-align:center;">Disconnection Date</th>
	 <th class="col-md-1" style="text-align:center;">Amount</th>
	 <th class="col-md-2" style="text-align:center;">Bill Details</th>
	 <th class="col-md-2" style="text-align:center;">Print Receipt</th>
	 </tr>
	 </thead>
	 <tbody>
	 <?php
	 $count=0;
	 $cur_date = date('d-M-y');
	 $cur_date = strtotime($cur_date);
while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false)
		//while($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS))
		{	
	$count=$count+1;
	//echo"ss";
	$lastdate = strtotime($row['LASTDATE']);
	$disdate = strtotime($row['DISDATE']);
	
	if($lastdate<=$cur_date) { 
	if($row['PAID']!=0) 
	{ 
		$style="style='color:green;'";
	} else 
	{ 
		$style="style='color:red;'";
	}
	}
	//$lastdate='17-AUG-2017';
	
		 ?>
	 <tr>
	 <td align="center"><?php echo $row['BILLNO']; ?></td>
	 <td align="center"><?php echo $row['BILLDATE']; ?></td>
	 <td align="center"><?php echo $row['LASTDATE']; ?></td>
	 <td align="center"><?php echo $row['DISDATE']; ?></td>
	 <td align="center"><?php echo $row['TOTAL']; ?></td>
	 <!--<td align="center" <?php if($lastdate<$cur_date) { echo $style; } ?>><?php if($lastdate<$cur_date) { if($row['PAID']!=0) { echo "PAID"; } else { if($checkcnt>0) { echo "CONTACT OFFICE FOR PAYMENT"; } else { echo "DUE DATE OVER"; }}} else if(($row['PAID']==0) && ($lastdate>$cur_date)){ if($checkcnt>0) { echo "CONTACT OFFICE FOR PAYMENT"; } else { ?><a href="bill_pay.php?cno=<?php echo $row['CNO']; ?>&bill_no=<?php echo $row['BILLNO']; ?>&bill_type=<?php echo $_POST['bill_type'];?>"><input type="button" class="btn btn-info" name="pay" id="pay" value="Need to Pay" style="padding:6px 2px;"><?php } } ?></a></td>-->
	 <td align="center" ><a href="<?php echo $print_page; ?>?cno=<?php echo $row['CNO']; ?>&bill_no=<?php echo $row['BILLNO']; ?>&bill_type=<?php echo $bill_type;?>"><input type="button" class="btn btn-info" name="print" id="print" value="Print Bill" style="padding:6px 2px;"></a></td>
	 <td align="center" <?php if($lastdate<=$cur_date) { echo $style; } ?>><?php if($row['PAID']!=0) { ?><a href="receipt_print.php?cno=<?php echo $row['CNO']; ?>&bill_no=<?php echo $row['BILLNO']; ?>&bill_type=<?php echo $bill_type;?>"><input type="button" class="btn btn-info" name="print" id="print" value="Print Receipt" style="padding:6px 2px;"></a><?php } else if(($disdate>=$cur_date) && $row['PAID']!=1) { ?> <a href="bill_pay.php?cno=<?php echo $row['CNO']; ?>&bill_no=<?php echo $row['BILLNO']; ?>&bill_type=<?php echo $_POST['bill_type'];?>"><input type="button" class="btn btn-info" name="pay" id="pay" value="Need to Pay" style="padding:6px 2px;"></a><?php } else {  echo "DUE DATE OVER"; } ?></td>
	 </tr>
	 <?php } if($count==0) { ?> 
		
		<tr>
	 <td colspan="7" align="center" style="color:red; font-weight:bold; ">No result to show</td>
	 </tr>
	 <?php } ?>	 
	 <tr>
	
	 </tr>
	 </tbody>
	 </table>
	 <div style="float:left;width:100%;padding:5px;">
	 <?php

if($page>1)
{
	echo "<a href='?page=".($page-1)."&con_no=".$consumerno."&bill_no=".$bill_no."&from_dt=".$from_dt."&to_dt=".$to_dt."&bill_type=".$bill_type."' class='button'>&lang;</a>";
}
if($page!=$total)
{
	echo "<a href='?page=".($page+1)."&con_no=".$consumerno."&bill_no=".$bill_no."&from_dt=".$from_dt."&to_dt=".$to_dt."&bill_type=".$bill_type."' class='button'>&rang;</a>";
}

echo "<ul class='page'>";
		for($i=1;$i<=$total;$i++)
		{
			if($i==$page) { echo "<li class='current'>".$i."</li>"; }
			
			else { echo "<li><a href='?page=".$i."&con_no=".$consumerno."&bill_no=".$bill_no."&from_dt=".$from_dt."&to_dt=".$to_dt."&bill_type=".$bill_type."'>".$i."</a></li>"; }
		}
echo "</ul>";
?>
	 </div> 
	 </div>
<?php } ?>
		
		</div>
		</div>
		
<?php include('includes/footer.php'); ?>