 <?php 
 include('includes/header.php'); 
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
						$stid = oci_parse($conn, "select * from ( select a.*, ROWNUM rnum from ( SELECT MONTH as term,YEAR as year,BILLNO as bill_no,BILLDATE as bill_date,LASTDATE as LDATE,DISDATE as FINEDATE,KWH as UNITS,TOTAL as TOTAMT FROM LT4billfile where CNO='$consumerno' and YEAR > '2002' order by YEAR DESC,MONTH DESC) a where ROWNUM <= $end )where rnum  >= $start");
						$rows=oci_parse($conn, "select count(*) as count from LT4billfile where CNO='$consumerno' and YEAR > '2002'");
					}
					else if($cat=='GT')
					{
						//echo "select * from ( select a.*, ROWNUM rnum from ( SELECT MONTH as term,YEAR as year,BILLNO as bill_no,BILLDATE as bill_date,LASTDATE as LDATE,DISDATE as FINEDATE,UNITS as UNITS,TOTAL as TOTAMT FROM GTbillfile where CNO='$consumerno' and YEAR > '2002' order by YEAR DESC,MONTH DESC) a where ROWNUM <= $end )where rnum  >= $start";

						$stid = oci_parse($conn, "select * from ( select a.*, ROWNUM rnum from ( SELECT MONTH as term,YEAR as year,BILLNO as bill_no,BILLDATE as bill_date,LASTDATE as LDATE,DISDATE as FINEDATE,UNITS as UNITS,TOTAL as TOTAMT FROM GTbillfile where CNO='$consumerno' and YEAR > '2002' order by YEAR DESC,MONTH DESC) a where ROWNUM <= $end )where rnum  >= $start");
						$rows=oci_parse($conn, "select count(*) as count from GTbillfile where CNO='$consumerno' and YEAR > '2002'");		
					}
					else if($cat=='HT')
					{ 
						$stid = oci_parse($conn, "select * from ( select a.*, ROWNUM rnum from ( SELECT MONTH as term,YEAR as year,BILLNO as bill_no,BILLDATE as bill_date,LASTDATE as LDATE,DISDATE as FINEDATE,KWHNORMAL,KWHPEAK,KWHOFF,BILLDEMAND,TOTBILLAMT as TOTAMT FROM NEWHTbillfile where CNO='$consumerno' and YEAR > '2002' order by YEAR DESC,MONTH DESC) a where ROWNUM <= $end )where rnum  >= $start");
						//echo "SELECT MONTH as term,YEAR as year,BILLNO as bill_no,BILLDATE as bill_date,LASTDATE as LDATE,DISDATE as FINEDATE,TOTBILLAMT as TOTAMT FROM NEWHTbillfile where CNO='$consumerno' order by YEAR,MONTH";
						$rows=oci_parse($conn, "select count(*) as count from NEWHTbillfile where CNO='$consumerno' and YEAR > '2002'");
					}
				}
				elseif(trim($bill_type)=='Spot')
				{
					$stid = oci_parse($conn, "select * from ( select a.*, ROWNUM rnum from ( SELECT TERM as term,YEAR as year,BNO as bill_no,BDATE as bill_date,LDATE,FINEDATE,UNITS,TOTAMT FROM billfile_trans where CNO='$consumerno' and YEAR > '2011' order by BDATE DESC) a where ROWNUM <= $end )where rnum  >= $start");
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
		td:nth-of-type(5):before { content: "LAST DATE"; }
		td:nth-of-type(6):before { content: "FINE DATE"; }
		td:nth-of-type(7):before { content: "UNITS"; }
		td:nth-of-type(8):before { content: "TOTAL AMOUNT"; }
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
            <h2>Bill Details</h2>
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
		<div align="right"><a href="bill_det_print.php" style="padding:5px; background-color:#2A6197; color:#fff;">Print Bill details</a></div>
		<div class="col-md-12" id="bills" style="margin-top:30px; margin-bottom:30px;" >
<table width="100%" cellspacing='2' cellpadding='10' border='1'>
	 <thead>
	 <tr>
	 <th class="col-md-1" style="text-align:center;color: white; border-width:0.5px; border-style:ridge; border-right: #ffffff">Term</th>
	 <th class="col-md-1" style="text-align:center;color: white; border-width:0.5px; border-style:ridge; border-right: #ffffff">Year</th>
	 <th class="col-md-1" style="text-align:center;color: white; border-width:0.5px; border-style:ridge; border-right: #ffffff">Bill No.</th>
	 <th class="col-md-2" style="text-align:center;color: white; border-width:0.5px; border-style:ridge; border-right: #ffffff">Bill Date</th>
	 <th class="col-md-2" style="text-align:center;color: white; border-width:0.5px; border-style:ridge; border-right: #ffffff">Last Date</th>
	 <th class="col-md-2" style="text-align:center;color: white; border-width:0.5px; border-style:ridge; border-right: #ffffff">Fine Date</th>
	 <th class="col-md-2" style="text-align:center;color: white; border-width:0.5px; border-style:ridge; border-right: #ffffff">Units</th>
	 <?php if($cat=='HT') { ?>
	  <th class="col-md-1" style="text-align:center;color: white; border-width:0.5px; border-style:ridge; border-right: #ffffff">Bill Demand</th>
	 <?php } ?>
	 <th class="col-md-2" style="text-align:center;color: white; border-width:0.5px; border-style:ridge; border-right: #ffffff">Total Amount</th>
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
	 <td align="center"><?php echo $row['LDATE']; ?></td>
	 <td align="center"><?php echo $row['FINEDATE']; ?></td>
	 <?php if($cat=='HT') { ?>
	 <td align="center"><?php echo $row['KWHNORMAL']+$row['KWHOFF']+$row['KWHPEAK']; ?></td>
	  <td align="center"><?php echo $row['BILLDEMAND']; ?></td>
	 <?php } else { ?>
	 <td align="center"><?php echo $row['UNITS']; ?></td>
	 <?php } ?>
	 <td align="center"><?php echo $row['TOTAMT']; ?></td>
	 
	 </tr>
	 <?php } if($count==0) { ?> 
		
		<tr>
	 <td colspan="8" align="center" style="color:red; font-weight:bold; ">No result to show</td>
	 </tr>
	 <?php } ?>	 
	 <tr>
	
	 </tr>
	 	
	 </tbody>
	 </table>
	 <div style="float:left;width:100%;padding:5px;">
                                    <?php

                                    $tpages=$total;
                                    $prevlabel = "Prev";
                                    $nextlabel = "Next";
                                    $adjacents =5;
                                    $reload=$_SERVER['PHP_SELF'] . "?" ;
                                    $out = "<div class=\"paginate paginate-dark wrapper\">\n";
                                    echo "<ul>";
                                    // previous
                                    if($page==1 ) {
                                        $out.= "<li><a href=\"" . $reload . "\" >" . $prevlabel . "</a></li>\n";
                                    }
                                    elseif($page==2) {
                                        $out.= "<a href=\"" . $reload . "\">" . $prevlabel . "</a>\n";
                                    }
                                    else {
                                        $out.= "<a href=\"" . $reload . "&amp;page=" . ($page-1) . "\">" . $prevlabel . "</a>\n";
                                    }
	
                                    // first
                                    if($page>($adjacents+1)) {
                                        $out.= "<a href=\"" . $reload . "\">1</a>\n";
                                    }
	
                                    // interval
                                    if($page>($adjacents+2)) {
                                        $out.= "...\n";
                                    }
	
                                    // pages
                                    $pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
                                    $pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
                                    for($i=$pmin; $i<=$pmax; $i++) {
                                    if($i==$page) {
                                        $out.= "<li><a href=\"" .$reload ."\" class=\"current\">" . $i . "</a></li>\n";
                                    }
                                    elseif($i==1) {
                                         $out.= "<a href=\"" . $reload . "\">" . $i . "</a>\n";
                                    }
                                    else {
                                        $out.= "<a href=\"" . $reload . "&amp;page=" . $i . "\">" . $i . "</a>\n";
                                    }
                                }
	
                                // interval
                                if($page<($tpages-$adjacents-1)) {
                                    $out.= "...\n";
                                }
	
                                // last
                                if($page<($tpages-$adjacents)) {
                                    $out.= "<a href=\"" . $reload . "&amp;page=" . $tpages . "\">" . $tpages . "</a>\n";
                                }
	
                                // next
                                if($page<$tpages) {
                                    $out.= "<a href=\"" . $reload . "&amp;page=" . ($page+1) . "\">" . $nextlabel . "</a>\n";
                                }
                                else {
                                    $out.= "<li>" . $nextlabel . "</li>\n";
                                }
	
                                $out.= "</div>";
                                $out.= "</ul>";
                                echo $out;
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