 <?php include('includes/header.php'); 
 include('includes/db.php');
		$cond = '';
		//print_r($_SESSION);
		$start=0;
		$limit=20;
		$end = $limit;
		if(isset($_GET['page']))
		{
			$page=$_GET['page'];
			$start=($page-1)*$limit+1;
			$end=$page*$limit;
		}
		else
		{
			$page=1;
		}
		if(isset($_SESSION['cno'])) 
		{ 
			$consumerno = $_SESSION['cno'];
			if(isset($_SESSION['bill_type']) && $_SESSION['bill_type']!='')
			{
				$bill_type = $_SESSION['bill_type'];
				
				if(trim($bill_type)=='Monthly')
				{
					$tab = "newmaster";
					$stid1 = oci_parse($conn, "SELECT CATEGORY FROM ".$tab." where CNO='$consumerno'");
					oci_execute($stid1);
					$row1 = oci_fetch_array($stid1, OCI_ASSOC+OCI_RETURN_NULLS);
					$cat = $row1['CATEGORY'];
					//echo "SELECT CNO,BILLNO,BILLDATE,LASTDATE,DISDATE,TOTAL,PAID  FROM ".$cat."billfile where CNO='$consumerno'";
					if($cat=='LT4')
					{
						$stid = oci_parse($conn, "select * from ( select a.*, ROWNUM rnum from ( SELECT MONTH as term,YEAR as year,BILLNO as bill_no,BILLDATE as bill_date,PAMT as pamt,PAYDATE as pdate,RECEIPTNO as receipt FROM LT4billfile where CNO='$consumerno' and YEAR > '2002' order by YEAR DESC,MONTH DESC) a where ROWNUM <= $end )where rnum  >= $start");
						$rows=oci_parse($conn, "select count(*) as count from LT4billfile where CNO='$consumerno' and YEAR > '2002'");
					}
					else if($cat=='GT')
					{
						$stid = oci_parse($conn, "select * from ( select a.*, ROWNUM rnum from ( SELECT MONTH as term,YEAR as year,BILLNO as bill_no,BILLDATE as bill_date,PAMT as pamt,PAYDATE as pdate,RECEIPTNO as receipt FROM GTbillfile where CNO='$consumerno' and YEAR > '2002' order by YEAR DESC,MONTH DESC) a where ROWNUM <= $end )where rnum  >= $start");
						$rows=oci_parse($conn, "select count(*) as count from GTbillfile where CNO='$consumerno' and YEAR > '2002'");		
					}
					else if($cat=='HT')
					{
						$stid = oci_parse($conn, "select * from ( select a.*, ROWNUM rnum from ( SELECT MONTH as term,YEAR as year,BILLNO as bill_no,BILLDATE as bill_date,PAMT as pamt,PAYDATE as pdate,RECEIPTNO as receipt FROM NEWHTbillfile where CNO='$consumerno' and YEAR > '2002' order by YEAR DESC,MONTH DESC) a where ROWNUM <= $end )where rnum  >= $start");
						$rows=oci_parse($conn, "select count(*) as count from NEWHTbillfile where CNO='$consumerno' and YEAR > '2002'");
					}
				}
				elseif(trim($bill_type)=='Spot')
				{
					$stid = oci_parse($conn, "select * from ( select a.*, ROWNUM rnum from ( SELECT TERM,YEAR,BNO as bill_no,BDATE as bill_date,PAMT,PDATE,RECEIPT FROM billfile_trans where CNO='$consumerno' and YEAR > '2011' order by BDATE DESC) a where ROWNUM <= $end )where rnum  >= $start");
					$rows=oci_parse($conn, "select count(*) as count from billfile_trans where CNO='$consumerno' and YEAR > '2011'");
				}
			}
			
			
			oci_execute($stid);
			oci_execute($rows);
		}
$cnt = oci_fetch_array($rows, OCI_NUM);
$total=ceil($cnt[0]/$limit);
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
		td:nth-of-type(1):before { content: "TERM"; }
		td:nth-of-type(2):before { content: "YEAR"; }
		td:nth-of-type(3):before { content: "BILL NO"; }
		td:nth-of-type(4):before { content: "BILL DATE"; }
		td:nth-of-type(5):before { content: "PAID AMOUNT"; }
		td:nth-of-type(6):before { content: "PAID DATE"; }
		td:nth-of-type(7):before { content: "RECEIPT"; }
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
	</style>

<!-- Start Page Banner -->
    <div class="page-banner">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <h2>Payment Details</h2>
            <p>Payment Details</p>
          </div>
          <div class="col-md-6">
            <ul class="breadcrumbs">
              <li><a href="index.php">Home</a></li>
              <li>Payment Details</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- End Page Banner -->

  <div class="container" style="margin-top:2%;">
        <div class="row">
		<div class="col-md-8">
		<div align="right"><a href="payment_det_print.php" style="padding:5px; background-color:#2A6197; color:#fff;">Print Payment details</a></div>
		<div class="col-md-12" id="bills" style="margin-top:30px; margin-bottom:30px;" >
<table width="100%" cellspacing='2' cellpadding='10' border='1'>
	 <thead>
	 <tr>
	 <th class="col-md-1" style="text-align:center;">Term</th>
	 <th class="col-md-1" style="text-align:center;">Year</th>
	 <th class="col-md-2" style="text-align:center;">Bill No.</th>
	 <th class="col-md-2" style="text-align:center;">Bill Date</th>
	 <th class="col-md-2" style="text-align:center;">Paid Amount</th>
	 <th class="col-md-2" style="text-align:center;">Paid Date</th>
	 <th class="col-md-2" style="text-align:center;">Receipt No.</th>
	 </tr>
	 </thead>
	 <tbody>
	 <?php
	 $count=0;
	 $cur_date = date('d-M-y');
while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false)
		//while($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS))
		{	
	$count=$count+1;
	$monthName = date('F', mktime(0, 0, 0, $row['TERM'], 10));
		 ?>
	 <tr>
	 <?php if(trim($bill_type)=='Spot') {?>
	 <td align="center"><?php echo $row['TERM']; ?></td>
	 <?php } else { ?>
	 <td align="center"><?php echo $monthName; ?></td>
	 <?php } ?>
	 
	 <td align="center"><?php echo $row['YEAR']; ?></td>
	 <td align="center"><?php echo $row['BILL_NO']; ?></td>
	 <td align="center"><?php echo $row['BILL_DATE']; ?></td>
	 <td align="center"><?php if($row['PAMT']==0) echo "NA"; else echo $row['PAMT']; ?></td>
	 <td align="center"><?php if($row['PDATE']=='') echo "NA"; else echo $row['PDATE']; ?></td>
	 <td align="center"><?php if($row['RECEIPT']==0) echo "NA"; else echo $row['RECEIPT']; ?></td>
	 
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
	echo "<a href='?page=".($page-1)."' class='button'>PREVIOUS</a>";
}
if($page!=$total)
{
	echo "<a href='?page=".($page+1)."' class='button'>NEXT</a>";
}

echo "<ul class='page'>";
		for($i=1;$i<=$total;$i++)
		{
			if($i==$page) { echo "<li class='current'>".$i."</li>"; }
			
			else { echo "<li><a href='?page=".$i."'>".$i."</a></li>"; }
		}
echo "</ul>";
?>
	 </div>
	 </div>
	 </div>
		<div class="col-md-4">
		<?php include('sidemenu.php'); ?>
		</div>
		
		</div>
		</div>
		
<?php include('includes/footer.php'); ?>