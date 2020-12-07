<?php  
//include('includes/db.php');
 error_reporting(E_ERROR);
require_once('tcpdf_include.php');

session_start();	
//echo $ss."fhdf";
//exit;
if(isset($_GET['cno']) && $_GET['cno']!='')
{

// create new PDF document

class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
       
        //$this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
}

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
$pdf->SetFont('dejavusans', '', 6);

// add a page
$pdf->AddPage();
//$img_file = K_PATH_IMAGES.'paid.png';
//$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
$conn = oci_connect('GDNTCR_USR', 'GDNTCR_USR', '');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

if(isset($_GET['cno'])) 
{ 
	$consumerno = $_GET['cno'];
	$billno = $_GET['bill_no'];

	if(isset($_GET['bill_type']) && $_GET['bill_type']!='')
		{
			$bill_type = $_GET['bill_type'];
			
			if(trim($bill_type)=='Monthly')
			{
				$tab = "newmaster";
				$stid1 = oci_parse($conn, "SELECT nm.*,ht.KVACHARGE,ht.KWHCHARGE FROM ".$tab." nm, httariffnew ht where nm.CNO='$consumerno' and nm.TARIFF=ht.TARIFF AND ht.DT_WITHEFFECT IN (SELECT MAX(DT_WITHEFFECT) FROM HTTARIFFNEW WHERE TARIFF=nm.TARIFF)");
				oci_execute($stid1);
				$row1 = oci_fetch_array($stid1, OCI_ASSOC+OCI_RETURN_NULLS);
				$cat = $row1['CATEGORY'];
				$wotariff = $row1['TARIFF'];
				if($cat=='HT')
				{ 
					$stid = oci_parse($conn, "SELECT b.MONTH as term,b.YEAR as year,b.BILLNO as bill_no,b.BILLDATE as bill_date,b.LASTDATE as LDATE,b.DISDATE as FINEDATE,b.KWHNORMAL,b.KWHPEAK,b.KWHOFF,b.BILLDEMAND,b.TOTBILLAMT as TOTAMT,b.RECEIPTNO,b.INTEREST,b.RC,b.PAID,b.PF,b.EXDEMCHARGE,b.PFPENALTY,b.arrears,c.counterno FROM NEWHTbillfile b left join cash c on (b.cno=c.cno and b.billno=c.billno) where b.CNO='$consumerno' and b.BILLNO='$billno'");
					oci_execute($stid);
					$row = oci_fetch_array($stid, OCI_ASSOC);
					$no = $row['TERM'];
					$prev = $row['TERM']-1;
					
					$stid2 = oci_parse($conn, "SELECT NORMALRDG".$no.",PEAKRDG".$no.",OFFPEAKRDG".$no.",KVANORMALRDG".$no.",KVAPEAKRDG".$no.",KVAOFFPEAKRDG".$no.",NORMALRDG".$prev.",PEAKRDG".$prev.",OFFPEAKRDG".$prev.",KVANORMALRDG".$prev.",KVAPEAKRDG".$prev.",KVAOFFPEAKRDG".$prev." from HTTRANS where CNO='$consumerno'");
					oci_execute($stid2);
					$row2 = oci_fetch_array($stid2, OCI_ASSOC);
					
					$stid3 = oci_parse($conn, "SELECT sum(paidamt) as paidamt from month_sdcollection where CNO='$consumerno'");
					oci_execute($stid3);
					$row3 = oci_fetch_array($stid3, OCI_ASSOC);
				}
			}
		
		}
		
		$normal = ($row2['NORMALRDG'.$no]-$row2['NORMALRDG'.$prev])*$row1['MULTFACTOR'];
		$peak = ($row2['PEAKRDG'.$no]-$row2['PEAKRDG'.$prev])*$row1['MULTFACTOR'];
		$offpeak = ($row2['OFFPEAKRDG'.$no]-$row2['OFFPEAKRDG'.$prev])*$row1['MULTFACTOR'];
		$totalkwh = $normal+$peak+$offpeak;
		
		$normalkva = $row2['KVANORMALRDG'.$no]*$row1['MULTFACTOR'];
		$peakkva = $row2['KVAPEAKRDG'.$no]*$row1['MULTFACTOR'];
		$offpeakkva =$row2['KVAOFFPEAKRDG'.$no]*$row1['MULTFACTOR'];
		$totalkva = $normalkva+$peakkva+$offpeakkva;
		//echo $a = 'NORMALRDG'.$no;
		
		//echo $row2['NORMALRDG'.$no]."ssds";
		//print_r($row2);
		//exit;
		
		if(trim($bill_type)!='Spot')
		{
		$monthName = date('F', mktime(0, 0, 0, $row['TERM'], 10));
		}
		else{
			$monthName = $row['TERM'];
		}
		$words=convert_number_to_words($row['TOTAMT']+$row["INTEREST"]+$row["RC"]);
		$consumption = $row['KWHNORMAL'] + $row['KWHPEAK'] + $row['KWHOFF'];
		$elec_sur = $normal+$peak+$offpeak;
		
		$pfdiff = 0.9 - $row['PF'];
		$t_en = $tot_en_chrge * $pfdiff/100 * 0.5/100;
		$t_en = $tot_en_chrge * $pfdiff/0.01 * 1/100;
		$perkva = $row1['CONTDEMAND']*75/100;
		
		$cash_avail = number_format($row3['PAIDAMT'] + $row1['SECURITYAMT'], 2, ".", "");
		$subtotalatof = (max($normalkva,$peakkva,$offpeakkva,$perkva)* $row1['KVACHARGE'])+($row1['KVACHARGE']/2*$row['EXDEMCHARGE']);
}		
//echo $row['RECEIPTNO']."sss";
//exit;
//print_r($row1);
//exit;
$tot_en_chrge = ($normal*$row1['KWHCHARGE']) + ($peak*$row1['KWHCHARGE']*1.5) + ($offpeak*$row1['KWHCHARGE']*.75);
$tot_chrge = $tot_en_chrge + $row['PFPENALTY'];
$tot = max($normalkva,$peakkva,$offpeakkva,$perkva)* $row1['KVACHARGE'] + $tot_chrge + ($elec_sur*0.025) + ($tot_en_chrge*0.1);
//echo  max($normalkva,$peakkva,$offpeakkva,$perkva)* $row1['KVACHARGE'].'_'.$elec_sur.'_'.$tot_en_chrge.'_'.$tot_chrge;
//exit;
		$html = '
	<table style="width:100%;border-bottom-width:0.1px;">
	<tr>
		<th colspan="8">&nbsp;</th>
	</tr>
	<tr>
		<th style="border-bottom-width:0.1px; width:20%; padding:10px;"><img style="margin-top:10px;" src="images/tced_logo.png" /></th>
		<th colspan="6" style="border-bottom-width:0.1px; width:60%;">
			<h2 align="center">THRISSUR CORPORATION</h2>
		<h4 align="center">ELECTRICITY DEPARTMENT</h4>
		</th>
		<th align="right" style="border-bottom-width:0.1px; width:20%;"><img src="images/phne.png" style="height:10px; width:10px; margin-right:5px;" />2423613</th>
	</tr>
	</table>
	<table style="width:100%;" border="1">
	<tr>
		<td>Con.Code</td><td>23432423432</td>
		<td>Bill Date</td>
		<td>'.$row['BILL_DATE'].'</td>
		<td>Due Date</td>
		<td>'.$row['FINEDATE'].'</td>
		<td>Bill. No</td>
		<td>'.$row['BILL_NO'].'</td>
	</tr>
	<tr>
		<td>Tariff</td>
		<td colspan="3">Licensee: Thrissur Corporation</td>
		<td>Last Date</td>
		<td>'.$row['LDATE'].'</td>
		<td>CD Available(cash)</td>
		<td align="right">'.$cash_avail.'</td>
	</tr>
	</table>
	<table>
	<tr><td colspan="8">&nbsp;</td></tr>
	<tr>
		<td colspan="6" align="left">
			<table border="1" style="padding:2px">
			<tr>
				<td>'.$row1['NAME'].'<br />
					'.$row1['AD1'].',<br />
					'.$row1['AD2'].',<br />
					'.$row1['AD3'].'<br /><br />
					LCN:
				</td>
			</tr>
			</table>
		</td>
		<td colspan="2">
			<table border="1" style="padding:2px">
			<tr>
				<td>SD (BG):-<br />
					51159240<br />
					Virtual Account No:- KSEB21CNC2008<br />
					<br /><br />
				</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="8">&nbsp;</td></tr>
	<tr>
		<td colspan="8">
		<table border="1" style="padding:2px">
			<tr>
				<td>
					Arrears : 
				</td>
				<td>
					'.$row['ARREARS'].'
				</td>
		
				<td colspan="2">
			
					Date of Previous Reading
				</td>
				<td>
					31-May-2016
				</td>
				<td colspan="2">	
					Date of Present Reading
				</td>
				<td>	
					30-Jun-2016
				</td>
				</tr>
				</table>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr><td colspan="8">&nbsp;</td></tr>
	<tr>
		<td colspan="3">
			<table border="1" style="padding:2px">
			<tr>
				<td>Contract<br /> Demand(KVA)</td>
				<td>75% of CD<br /> (KVA)</td>
				<td>130% of CD<br /> (KVA)</td>
				<td>Connected Load (KW)</td>
			</tr>
			<tr>
				<td>'.$row1['CONTDEMAND'].'</td>
				<td>'.$row1['CONTDEMAND']*75/100 .'</td>
				<td>'.$row1['CONTDEMAND']*130/100 .'</td>
				<td>'.$row1['CONLOAD'].'</td>
			</tr>
			<tr></tr>
			</table>
		</td>
		<td colspan="3">
			<table border="1" style="padding:2px">
			<tr>
				<td colspan="3" align="center">Average</td>
			</tr>
			<tr>
				<td>MD(KVA)</td>
				<td>Consumption(KWH)</td>
				<td>PF</td>
			</tr>
			<tr>
				<td>'.max($row1['CONTDEMAND']*75/100, $row['KVANORMAL'], $row['KVAPEAK'], $row['KVAOFF'] ).'</td>
				<td>'.$consumption.'</td>
				<td>'.$row['PF'].'</td>
			</tr>
			<tr></tr>
			</table>
		</td>
		<td colspan="2">
			<table border="1" style="padding:2px">
			<tr>
				<td width="40%">Supply Voltage</td><td width="40%"></td><td width="20%">EHT</td>
			</tr>
			<tr>
				<td>Billing Type</td>
				<td colspan="2">HT</td>
			</tr>
			<tr>
				<td>Section</td>
				<td colspan="2">TCED</td>
			</tr>
			<tr>
				<td>Circle</td>
				<td colspan="2">Thrissur</td>
			</tr>
			</table>
		</td>
		
	</tr>
	<tr><td colspan="8">&nbsp;</td></tr>
	</table>
	<table border="1" style="padding:2px">
		<tr>
			<td colspan="10" align="center"><b>Reading Details(KVA,KWh) for 06-2016</b></td>
		</tr>
		<tr>
			<td colspan="5"><b>1.Energy Consumption(KWh)</b></td>
			<td colspan="5"><b>2.Demand (KVA)</b></td>
		</tr>
		<tr>
			<td><b>Zone</b></td>
			<td align="center"><b>FR</b></td>
			<td align="center"><b>IR</b></td>
			<td align="center"><b>MF</b></td>
			<td align="center"><b>Units</b></td>
			<td colspan="2"><b>Zone</b></td>
			<td align="center"><b>Readings</b></td>
			<td align="center"><b>MF</b></td>
			<td align="center"><b>Units</b></td>
		</tr>
		<tr>
			<td>1</td>
			<td align="center">'.$row2['NORMALRDG'.$no].'</td>
			<td align="center">'.$row2['NORMALRDG'.$prev].'</td>
			<td align="center">'.$row1['MULTFACTOR'] .'</td>
			<td align="center">'.$normal.'</td>
			<td colspan="2">1</td>
			<td align="center">'.$row2['KVANORMALRDG'.$no].'</td>
			<td align="center">'.$row1['MULTFACTOR'] .'</td>
			<td align="center">'.$normalkva.'</td>
		</tr>
		<tr>
			<td>2</td>
			<td align="center">'.$row2['PEAKRDG'.$no].'</td>
			<td align="center">'.$row2['PEAKRDG'.$prev].'</td>
			<td align="center">'.$row1['MULTFACTOR'] .'</td>
			<td align="center">'.$peak.'</td>
			<td colspan="2">2</td>
			<td align="center">'.$row2['KVAPEAKRDG'.$no].'</td>
			<td align="center">'.$row1['MULTFACTOR'] .'</td>
			<td align="center">'.$peakkva.'</td>
		</tr>
		<tr>
			<td>3</td>
			<td align="center">'.$row2['OFFPEAKRDG'.$no].'</td>
			<td align="center">'.$row2['OFFPEAKRDG'.$prev].'</td>
			<td align="center">'.$row1['MULTFACTOR'] .'</td>
			<td align="center">'.$offpeak.'</td>
			<td colspan="2">3</td>
			<td align="center">'.$row2['KVAOFFPEAKRDG'.$no].'</td>
			<td align="center">'.$row1['MULTFACTOR'] .'</td>
			<td align="center">'.$offpeakkva.'</td>
		</tr>
		<tr>
		<td colspan="4" align="right"><b>Total</b></td>
		<td align="center">'.$totalkwh.'</td>
		<td colspan="4" align="right"><b>Total</b></td>
		<td align="center">'.$totalkva.'</td>
		</tr>
		<tr>
			<td colspan="4" align="right"></td>
			<td></td>
			<td colspan="4"><b>3.Factory Lighting</b></td>
			<td>0.0</td>
		</tr>
		<tr>
			<td colspan="4" align="right"></td>
			<td></td>
			<td colspan="4"><b>4.Colony Lighting</b></td>
			<td>0.0</td>
		</tr>
		<tr>
			<td colspan="2">Ave.PF=KWh/KVAh</td>
			<td colspan="3" align="left">0.93</td>
			<td colspan="4"><b>5.Generator</b></td>
			<td>0.0</td>
		</tr>
	</table>
	<table border="1" style="padding:2px">
	
		<tr>
			<td colspan="7" align="center"><b>INVOICE</b></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
			<td><b>Unit</b></td>
			<td><b>Rate(Rs)</b></td>
			<td><b>Amount(Rs)</b></td>
			<td>&nbsp;</td>
			<td><b>Amount</b></td>
		</tr>
		<tr>
			<td colspan="5">1.Total Demand Charge</td>
			<td colspan="2">9.Other Charges</td>
		</tr>
		<tr>
			<td colspan="2">a.Demand Charge</td>
			<td>'.max($normalkva,$peakkva,$offpeakkva,$perkva).'</td>
			<td align="right">'.number_format($row1['KVACHARGE'], 2, ".", "").'</td>
			<td align="right">'.number_format((max($normalkva,$peakkva,$offpeakkva,$perkva)* $row1['KVACHARGE']), 2, ".", "").'</td>
			<td>Interest</td>
			<td>0.00</td>
		</tr>
		<tr>
			<td colspan="2">b. </td>
			<td>0.0</td>
			<td align="right">'.number_format($row1['KVACHARGE'], 2, ".", "").'</td>
			<td align="right">0.00</td>
			<td colspan="2" rowspan="13">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">c. </td>
			<td>0.0</td>
			<td align="right">'.number_format($row1['KVACHARGE'], 2, ".", "").'</td>
			<td align="right">0.00</td>
		</tr>
		<tr>
			<td colspan="2">d. Excess Demand Charge</td>
			<td>'.$row['EXDEMCHARGE'].'</td>
			<td align="right">'.number_format($row1['KVACHARGE']/2, 2, ".", "") .'</td>
			<td align="right">'.number_format(($row1['KVACHARGE']/2*$row['EXDEMCHARGE']), 2, ".", "") .'</td>
		</tr>
		<tr>
			<td colspan="2">e. </td>
			<td>&nbsp;</td>
			<td align="right">'.number_format($row1['KVACHARGE']/2, 2, ".", "") .'</td>
			<td align="right">0.00</td>
		</tr>
		<tr>
			<td colspan="2">f. </td>
			<td>&nbsp;</td>
			<td align="right">'.number_format($row1['KVACHARGE']/2, 2, ".", "") .'</td>
			<td align="right">0.00</td>
		</tr>
		<tr>
			<td colspan="4"><b>Sub Total (a+b+c+d+e+f)</b></td>
			<td align="right"><b>'.number_format($subtotalatof, 2, ".", "").'</b></td>
		</tr>
		<tr>
			<td colspan="5">1.Total Energy Charges</td>
		</tr>
		<tr>
			<td colspan="2">a.Energy Charges</td>
			<td>'.$normal.'</td>
			<td align="right">'.number_format($row1['KWHCHARGE'], 2, ".", "").'</td>
			<td align="right">'.number_format(($normal*$row1['KWHCHARGE']), 2, ".", "").'</td>
		</tr>
		<tr>
			<td colspan="2">b. (rate * 1.5)</td>
			<td>'.$peak.'</td>
			<td align="right">'.number_format($row1['KWHCHARGE'], 2, ".", "").'</td>
			<td align="right">'.number_format(($peak*$row1['KWHCHARGE']*1.5), 2, ".", "").'</td>
		</tr>
		<tr>
			<td colspan="2">c. (rate * 0.75)</td>
			<td>'.$offpeak.'</td>
			<td align="right">'.number_format($row1['KWHCHARGE'], 2, ".", "").'</td>
			<td align="right">'.number_format(($offpeak*$row1['KWHCHARGE']*.75), 2, ".", "").'</td>
		</tr>
		<tr>
			<td colspan="4"><b>Sub Total (a+b+c)</b></td>
			<td align="right"><b>'.number_format($tot_en_chrge, 2, ".", "").'</b></td> 
		</tr>
		<tr>
			<td colspan="4">PF Incentives/Penalty</td>
			<td align="right">'.number_format($row['PFPENALTY'], 2, ".", "").'</td>
		</tr>
		<tr>
			<td colspan="4"><b>Total Energy Charge</b></td>
			<td align="right"><b>'.number_format($tot_chrge, 2, ".", "").'</b></td>
		</tr>
		<tr>
			<td colspan="5">&nbsp;</td>
			<td><b>10.Total(add 1 to 9)</b></td>
			<td align="right"><b>'.number_format($tot, 2, ".", "").'</b></td>
		</tr>
		<tr>
			<td colspan="5">&nbsp;</td>
			<td>Plus/minus (Round off)</td>
			<td align="right">0.00</td>
		</tr>
		<tr>
			<td colspan="4"><b>Sub Total(a+b)</b></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">5. Electricity Duty</td>
			<td>'.number_format($tot_en_chrge, 2, ".", "").'</td>
			<td>10%</td>
			<td align="right">'.number_format(($tot_en_chrge*0.1), 2, ".", "").'</td>
			<td rowspan="3" colspan="2">
				<table cellpadding="1" cellspacing="1">
					<tr>
					<td rowspan="3" width="15%">Less</td>
					<td colspan="2" width="45%" style="border-bottom-width:1px;">1.Advance at Credit</td>
					<td colspan="2" width="40%" style="border-bottom-width:1px;">&nbsp;</td>
					</tr>
					<tr>
					<td colspan="2" style="border-bottom-width:1px;">2.CD Interest</td>
					<td colspan="2" align="right" style="border-bottom-width:1px;">'.number_format($row['INTEREST'], 2, ".", "").'</td>
					</tr>
					<tr>
					<td colspan="2">3.CD Refund</td>
					<td colspan="2" align="right">0.00</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">6. Ele. Surcharge</td>
			<td>'.$elec_sur.'</td>
			<td>0.025</td>
			<td align="right">'.$elec_sur*0.025 .'</td>
		</tr>
		<tr>
			<td colspan="2">7. Duty on self generated energy</td>
			<td>0</td>
			<td>0.012</td>
			<td align="right">0.00</td>
		</tr>
		<tr>
			<td colspan="5">8. Penalty for non-segn. of light load</td>
			<td rowspan="2"><b>Net Payable</b></td>
			<td rowspan="2" align="right"><b>'.number_format($row['TOTAMT'], 2, ".", "").'</b></td>
		</tr>
		<tr>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="7"><b>( Rupees '.$words.')</b></td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
			<td colspan="2">Balance Advance at credit, if any</td>
			<td>&nbsp;</td>
		</tr>
		
		<tr>
			<td rowspan="2" colspan="7">E & O.E</td>
			
		</tr>
		<tr><td></td></tr>
		<tr>
			<td colspan="4" align="left">(instructions overleaf)</td>
			<td colspan="3" align="right">SPECIAL OFFICER(REVENUE)</td>
		</tr>
	</table>';
//echo $html;
//exit;

}

// output the HTML content
$pdf->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 0, true, 'L', true);
//$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();



//Close and output PDF document
$pdf->Output('ht_bill_'.$row['BILL_NO'].'_'.$row['BILL_DATE'].'.pdf', 'D');

function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}
?>
