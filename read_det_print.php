<?php  
//include('includes/db.php');
session_start();	
if(isset($_POST['year']) && $_POST['year']!='')
{
require_once('tcpdf_include.php');
$year=$_POST['year'];
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('TCED REPORT');
$pdf->SetTitle('TCED REPORT');
$pdf->SetSubject('TCED REPORT');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 8);

// add a page
$pdf->AddPage();
$conn = oci_connect('GDNTCR_USR', 'GDNTCR_USR', '');
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
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
				$stid1 = oci_parse($conn, "SELECT CATEGORY,NAME,AD1,AD2,TARIFF FROM ".$tab." where CNO='$consumerno'");
				oci_execute($stid1);
				$row1 = oci_fetch_array($stid1, OCI_ASSOC+OCI_RETURN_NULLS);
				$cat = $row1['CATEGORY'];
				if($cat=='LT4')
				{
					$stid = oci_parse($conn, "SELECT MONTH as term,YEAR as year,BILLNO as bill_no,BILLDATE as bill_date,PREADING as PMR,CREADING as CMR,KWH as UNITS FROM LT4billfile where CNO='$consumerno' and YEAR = '$year' order by YEAR,MONTH");
				}
				else if($cat=='GT')
				{
					$stid = oci_parse($conn, "SELECT MONTH as term,YEAR as year,BILLNO as bill_no,BILLDATE as bill_date,PREADING as PMR,CREADING as CMR,UNITS as UNITS FROM GTbillfile where CNO='$consumerno' and YEAR = '$year' order by YEAR,MONTH");		
				}
				else if($cat=='HT')
				{ 
					//echo "SELECT nht.MONTH as term,nht.YEAR as year,nht.BILLNO as bill_no,nht.KWHNORMAL,nht.KWHPEAK,nht.KWHOFF,nht.KVANORMAL,nht.KVAPEAK,nht.KVAOFF,htr.PF,htr.CKWH,htr.CKVAH,htr.CKVARH1,htr.CKVARH2,htr.FREQUENCY FROM NEWHTbillfile nht,HTREADING htr where nht.CNO='$consumerno' and htr.CNO=nht.CNO and nht.YEAR = '$year' order by YEAR,MONTH";
					$stid = oci_parse($conn, "SELECT nht.MONTH as term,nht.YEAR as year,nht.BILLNO as bill_no,nht.KWHNORMAL,nht.KWHPEAK,nht.KWHOFF,nht.KVANORMAL,nht.KVAPEAK,nht.KVAOFF,htr.PF,htr.CKWH,htr.CKVAH,htr.CKVARH1,htr.CKVARH2,htr.FREQUENCY FROM NEWHTbillfile nht,HTREADING htr where nht.CNO='$consumerno' and htr.CNO=nht.CNO and nht.YEAR=htr.YOB and nht.MONTH=htr.MOB and nht.YEAR = '$year' order by YEAR,MONTH");
				}
			}
			elseif(trim($bill_type)=='Spot')
				{
					$cat='SPOT';
					$tab = "master";
					$stid1 = oci_parse($conn, "SELECT NAME,AD1,AD2,SECT,TARIFF FROM ".$tab." where CNO='$consumerno'");
					oci_execute($stid1);
					$row1 = oci_fetch_array($stid1, OCI_ASSOC+OCI_RETURN_NULLS);
					$yr = substr($year, -2)+1;
					$year = $year.'-'.$yr;
					$stid = oci_parse($conn, "SELECT TERM as term,YEAR as year,BNO as bill_no,BDATE as bill_date,PMR,CMR,UNITS FROM billfile_trans where CNO='$consumerno' and YEAR = '$year' order by BDATE DESC");
				}
		}
		
		
		oci_execute($stid);
}		
// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
if($cat!='HT') 
{ 

	$html = '<table style="width:100%;border-bottom-width:0.1px;">
<tr>
	<th style="border-bottom-width:0.1px; width:20%; padding:10px;"><img src="images/tced_logo.png" /></th>
	<th colspan="3" style="border-bottom-width:0.1px; width:60%;">
		<h1 align="center">THRISSUR CORPORATION</h1>
		<h2 align="center">ELECTRICITY DEPARTMENT</h2></th>
	<th align="right" style="border-bottom-width:0.1px; width:20%;">&nbsp;</th>
</tr>
</table>
			<h2 align="center"> Reading Details '.$year.'</h2>
			<h4>Consumer Name: '.$row1['NAME'].'</h4>
			<h4>Consumer No.: '.$_SESSION['cno'].'</h4>
			<h4>Ward/House No.: '.$row1['AD1'].'</h4>';
			if(trim($bill_type)=='Spot') { 
			$html.='<h4>Section: '.$row1['SECT'].'</h4>';
			}
			$html.='<h4>Tariff: '.$row1['TARIFF'].'</h4>
			<div class="col-md-12" id="bills">
			<table width="100%" cellspacing="0" cellpadding="5" border="1">
			<tbody>
			<tr>
			<th class="col-md-1" style="text-align:center;"><b>Term</b></th>
			<th class="col-md-1" style="text-align:center;"><b>Year</b></th>
			<th class="col-md-1" style="text-align:center;"><b>Bill No.</b></th>
			<th class="col-md-2" style="text-align:center;"><b>Bill Date</b></th>
			<th class="col-md-2" style="text-align:center;"><b>PMR</b></th>
			<th class="col-md-2" style="text-align:center;"><b>CMR</b></th>
			<th class="col-md-2" style="text-align:center;"><b>Units</b></th>
			</tr>
			';
	$count=0;
	$cur_date = date('d-M-y');
	while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false)
	{	
		$count=$count+1;
		 if(trim($bill_type)=='Spot')
	 {
	$monthName = $row['TERM']; 
	 }
	 else{
	$monthName = date('F', mktime(0, 0, 0, $row['TERM'], 10)); 
	 } 
	 if($row["PMR"]=="-1" || $row["PMR"]=="-2")
	 {
		 $style= 'style="color:#FF1A00;"';
		 if($row["PMR"]=="-1")
		 {
		 $pmr= "LM";
		 }
		 else
		 {
		 $pmr= "FM";
		 }
	}
	else{
		$style= 'style="color:#000000;"';
		$pmr= $row["PMR"];
	}
	if($row["CMR"]=="-1" || $row["CMR"]=="-2")
	 {
		 $style1= 'style="color:#FF1A00;"';
		 if($row["CMR"]=="-1")
		 {
		 $cmr= "LM";
		 }
		 else
		 {
		 $cmr= "FM";
		 }
	}
	else{
		$style1= 'style="color:#000000;"';
		$cmr= $row["CMR"];
	}
		$html.= '<tr>
		<td align="center">'.$monthName.'</td>
		<td align="center">'.$row['YEAR'].'</td>
		<td align="center">'.$row['BILL_NO'].'</td>
		<td align="center">'.$row['BILL_DATE'].'</td>
		<td align="center" '.$style.'>'.$pmr.'</td>
		<td align="center" '.$style1.'>'.$cmr.'</td>
		<td align="center">'.$row['UNITS'].'</td>
		</tr>';
		} 
		if($count==0) 
		{	
		$html.= '<tr>
		<td colspan="8" align="center" style="color:red; font-weight:bold; ">No result to show</td>
		</tr>';
		}  
		$html.= '<tr>
		</tr>	
		</tbody>
		</table></div>';
} 
else 
{
	$html = '<table style="width:100%;border-bottom-width:0.1px;">
<tr>
	<th style="border-bottom-width:0.1px; width:20%; padding:10px;"><img src="images/logo.png" /></th>
	<th colspan="3" style="border-bottom-width:0.1px; width:60%;">
		<h1 align="center">THRISSUR CORPORATION</h1>
		<h2 align="center">ELECTRICITY DEPARTMENT</h2></th>
	<th align="right" style="border-bottom-width:0.1px; width:20%;">&nbsp;</th>
</tr>
</table>
		<h2 align="center"> Reading Details '.$year.'</h2>
		<h4>Consumer Name: '.$row1['NAME'].'</h4>
		<h4>Consumer No.: '.$_SESSION['cno'].'</h4>
		<h4>Ward/House No.: '.$row1['AD1'].'</h4>
		<h4>Tariff: '.$row1['TARIFF'].'</h4>
		<h2 align="center">Reading Details '.$year.'</h2>
		<div class="col-md-12" id="bills">
	 <table width="100%" cellspacing="0" cellpadding="5" border="1">
	 <tr>
	 <th style="text-align:center; padding:0px; width:7%;"><b>Term</b></th>
	 <th style="text-align:center; padding:0px; width:6%;"><b>Year</b></th>
	 <th style="text-align:center; padding:0px; width:6%;"><b>Bill No.</b></th>
	 <th style="text-align:center; padding:0px; width:8%;"><b>KWH NORMAL</b></th>
	 <th style="text-align:center; padding:0px; width:7%;"><b>KWH PEAK</b></th>
	 <th style="text-align:center; padding:0px; width:8%;"><b>KWH OFF</b></th>
	 <th style="text-align:center; padding:0px; width:7%;"><b>KVA NORMAL</b></th>
	 <th style="text-align:center; padding:0px; width:7%;"><b>KVA PEAK</b></th>
	 <th style="text-align:center; padding:0px; width:7%;"><b>KVA OFF</b></th>
	 <th style="text-align:center; padding:0px; width:5%;"><b>PF</b></th>
	 <th style="text-align:center; padding:0px; width:8%;"><b>CKWH</b></th>
	 <th style="text-align:center; padding:0px; width:8%;"><b>CKVAH</b></th>
	 <th style="text-align:center; padding:0px; width:8%;"><b>CKVARH1</b></th>
	 <th style="text-align:center; padding:0px; width:5%;"><b>CKVARH2</b></th>
	 <th style="text-align:center; padding:0px; width:7%;"><b>FREQUENCY</b></th>
	 </tr>
	 <tbody>';
	  $count=0;
	 $cur_date = date('d-M-y');
	while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false)
		//while($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS))
		{	
	$count=$count+1;
	//$monthName = date('F', mktime(0, 0, 0, $row['TERM'], 10));	
//$monthName = date('M', strtotime($row['TERM'] . '01'));	
$timestamp = mktime(0, 0, 0, $row['TERM'], 1);
$monthName = date('M', $timestamp );
	 $html.= '<tr>
	 <td align="center">'.$monthName.'</td>
	 <td align="center">'.$row['YEAR'].'</td>
	 <td align="center">'.$row['BILL_NO'].'</td>
	 <td align="center">'.$row['KWHNORMAL'].'</td>
	 <td align="center">'.$row['KWHPEAK'].'</td>
	 <td align="center">'.$row['KWHOFF'].'</td>
	 <td align="center">'.$row['KVANORMAL'].'</td>
	 <td align="center">'.$row['KVAPEAK'].'</td>
	 <td align="center">'.$row['KVAOFF'].'</td>
	 <td align="center">'.$row['PF'].'</td>
	 <td align="center">'.$row['CKWH'].'</td>
	 <td align="center">'.$row['CKVAH'].'</td>
	 <td align="center">'.$row['CKVARH1'].'</td>
	 <td align="center">'.$row['CKVARH2'].'</td>
	 <td align="center">'.$row['FREQUENCY'].'</td>
	 </tr>';
	} if($count==0) {	
	 $html.= '<tr>
	 <td colspan="15" align="center" style="color:red; font-weight:bold; ">No result to show</td>
	 </tr>';
	 }  
	 $html.= '<tr>
	 </tr>	
	 </tbody>
	 </table></div>';
}
//echo $html;
//exit;
// output the HTML content
$pdf->Image('images/TC_1.png', 50, 50, 100, '', '', '', '', false, 300);
	$pdf->setPageMark();
$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();



//Close and output PDF document
$pdf->Output('reading_details'.$year.'.pdf', 'D');
}
$year = date("Y"); 
include('includes/header.php'); 
?>
<!-- Start Page Banner -->
    <div class="page-banner">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <h2>Reading Details</h2>
            <p>Reading Details</p>
          </div>
          <div class="col-md-6">
            <ul class="breadcrumbs">
              <li><a href="index.php">Home</a></li>
              <li>Reading Details</li>
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
        
		<h1> YOUR READING DETAILS </h1>
		<fieldset>
	 
	 <div style="margin-top:35px">
	 
	 <div style="margin-top:35px; font-size:16px; color:#000;">
	 <form name="bill_det" method="post" action="read_det_print.php">
	 
	 <div class="form-group">
		<div class="col-md-4" style="width:30%;">Year: </div>
		<div class="col-md-4">
			<select name="year" id="year" class="form-control" style="width:115px;">
			<option value="">Select Year</option>
			<?php if($_SESSION['bill_type']=='Spot') { 
			for($i=$year;$i>=2011;$i--) { ?>
			<option value="<?php echo $i;?>"><?php echo $i;?>-<?php echo $i+1;?></option>
			<?php } } else { 
			for($i=$year;$i>=2003;$i--) { ?>
			<option value="<?php echo $i;?>"><?php echo $i;?></option>
			<?php } } ?>
			</select>
		</div>
		<div class="col-md-4" style="float:left;">
		<input type="submit" name="sub_bt" id="sub_bt" value="Print"  class="btn btn-info" style="font-size:14px;" >
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