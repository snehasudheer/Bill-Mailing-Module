<?php  
//include('includes/db.php');
session_start();	
//echo $ss."fhdf";
//exit;
if(isset($_GET['cno']) && $_GET['cno']!='')
{
require_once('tcpdf_include.php');
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
$conn = oci_connect('GDNTCR_USR', 'gdntcr_usr', '');
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
				$stid1 = oci_parse($conn, "SELECT * FROM ".$tab." where CNO='$consumerno'");
				oci_execute($stid1);
				$row1 = oci_fetch_array($stid1, OCI_ASSOC+OCI_RETURN_NULLS);
				$cat = $row1['CATEGORY'];
				$wotariff = $row1['TARIFF'];
				if($cat=='LT4')
				{
					$stid = oci_parse($conn, "SELECT l.MONTH as term,l.YEAR as year,l.BILLNO as bill_no,l.BILLDATE as bill_date,l.LASTDATE as LDATE,l.DISDATE as FINEDATE,l.KWH as UNITS,l.TOTAL as TOTAMT,l.RECEIPTNO,l.RC,l.PAID,l.LIGHTLOAD,l.PREADING as PMR,l.CREADING as CMR,l.CLKWHREADING as CLKWH,l.PLKWHREADING as PLKWH,l.FC,l.EC,l.MRENT as meterrent,l.conload,c.counterno FROM LT4billfile l left join cash c on (l.cno=c.cno and l.billno=c.billno) where l.CNO='$consumerno' and l.BILLNO='$billno'");
					//echo "SELECT l.MONTH as term,l.YEAR as year,l.BILLNO as bill_no,l.BILLDATE as bill_date,l.LASTDATE as LDATE,l.DISDATE as FINEDATE,l.KWH as UNITS,l.TOTAL as TOTAMT,l.RECEIPTNO,l.RC,l.PAID,l.PREADING as PMR,l.CREADING as CMR,l.CLKWHREADING as CLKWH,l.PLKWHREADING as PLKWH,l.FC,l.EC,l.METERRENT,g.conload,c.counterno FROM LT4billfile l left join cash c on (l.cno=c.cno and l.billno=c.billno) where l.CNO='$consumerno' and l.BILLNO='$billno'";
					//exit;
				}
				else if($cat=='GT')
				{
					$stid = oci_parse($conn, "SELECT g.MONTH as term,g.YEAR as year,g.BILLNO as bill_no,g.BILLDATE as bill_date,g.LASTDATE as LDATE,g.DISDATE as FINEDATE,g.UNITS as UNITS,g.TOTAL as TOTAMT,g.RECEIPTNO,g.INTEREST,g.RC,g.PAID,g.PREADING as PMR,g.CREADING as CMR,g.FC,g.EC,g.METERRENT,g.conload,c.counterno FROM GTbillfile g left join cash c on (g.cno=c.cno and g.billno=c.billno) where g.CNO='$consumerno' and g.BILLNO='$billno'");
					//echo "SELECT MONTH as term,YEAR as year,BILLNO as bill_no,BILLDATE as bill_date,LASTDATE as LDATE,DISDATE as FINEDATE,UNITS as UNITS,TOTAL as TOTAMT,RECEIPTNO,INTEREST,RC,PAID FROM GTbillfile where CNO='$consumerno' and BILLNO='$billno'";
					//exit;
				}
			}
			
		}
		
		
		oci_execute($stid);
		//$row = oci_fetch($stid);
		//echo "Sss";
		$row = oci_fetch_array($stid, OCI_ASSOC);
/* 		print_r($row);
exit; */
		if(trim($bill_type)!='Spot')
		{
		$monthName = date('F', mktime(0, 0, 0, $row['TERM'], 10));
		}
		else{
			$monthName = $row['TERM'];
		}
		$words=convert_number_to_words($row['TOTAMT']);
		
}		$tenpercntge = $row['EC']/10;
//echo $row['RECEIPTNO']."sss";
//exit;

	$html = '
	<table style="width:100%;">
	<tr><th colspan="6">&nbsp;</th></tr>
<tr>
	<th style="border-bottom-width:0.1px; width:20%; padding:10px;"><img style="margin-top:10px;" src="images/tced_logo.png" /></th>
	<th colspan="4" style="border-bottom-width:0.1px; width:60%;">
		<h2 align="center">THRISSUR CORPORATION</h2>
		<h4 align="center">ELECTRICITY DEPARTMENT</h4>
	</th>
	<th align="right" style="border-bottom-width:0.1px; width:20%;"><img src="images/phne.png" style="height:10px; width:10px; margin-right:5px;" />2423613</th>
</tr>
<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="6" align="left">Demand and Disconnection Notice Under Indian Electricity Act 2003-Section 56</td></tr>
<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="4" align="left">Last date for payment without fine : '.$row['LDATE'].'</td><td>&nbsp;</td></tr>
<tr><td colspan="5" align="left">Disconnection will be effected if the amount as per this bill is not paid on or before : </td><td>'.$row['FINEDATE'].'</td></tr>
<tr><td colspan="5">&nbsp;</td><td>Ledger : '.$row1['LEDGER'].'</td></tr>
<tr><td colspan="6">&nbsp;</td></tr>
<tr><td align="left">Bill no. : </td><td>'.$billno.'</td><td>Elec. Section : </td><td>Thrissur </td></tr>
<tr><td align="left">House no. : </td><td>'.$row1['AD1'].'</td><td>Dated : </td><td>'.$row['BILL_DATE'].'</td></tr>
<tr><td align="left">Consumer no. : </td><td>'.$consumerno.'</td><td>Tariff : </td><td>'.$row1['TARIFF'].'</td></tr>
<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="6" align="left">Details of amount to be collected by Thrissur corporation Electricity Dept. from &nbsp;&nbsp;&nbsp;&nbsp;<b>'.$row1['NAME'].'</b>&nbsp;&nbsp;&nbsp;&nbsp; for the distribution of electricity during the month of '.date('F', mktime(0, 0, 0, $row['TERM'], 10)).'&nbsp;'.$row['YEAR'].'</td></tr>
<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="2" align="left">Previous reading : </td><td>'.$row['PMR'].' </td><td colspan="2">Previous LKWH Reading : </td><td>'.$row['PLKWH'].'</td></tr>
<tr><td colspan="2" align="left">Present reading :</td><td>'.$row['CMR'].' </td><td colspan="2">Present LKWH Reading : </td><td>'.$row['CLKWH'].'</td></tr>
<tr><td colspan="6" style="border-bottom-width:2px; color:#ccc;">&nbsp;</td></tr>
<tr><td colspan="6">&nbsp;</td></tr>
<tr><td align="left" colspan="3">&nbsp;</td><td><b>Industrial:</b></td><td><b>Light:</b></td><td align="right"><b>RUPEES </b></td></tr>
<tr><td align="left" colspan="3">Connected Load : </td><td>'.$row['CONLOAD'].' KW</td><td>'.$row['LIGHTLOAD'].' KW</td><td>&nbsp; </td></tr>
<tr><td align="left" colspan="3">Fixed Charge :</td><td>&nbsp;</td><td>&nbsp; </td><td align="right">'.number_format($row['FC'], 2, ".", "").' </td></tr>
<tr><td colspan="6">&nbsp;</td></tr>
<tr><td align="left" colspan="3">Units Consumed :</td><td>'.$row['UNITS'].'</td><td>&nbsp; </td><td></td></tr>
<tr><td align="left" colspan="3">Energy Charge :</td><td>&nbsp;</td><td>&nbsp; </td><td align="right">'.number_format($row['EC'], 2, ".", "").'  </td></tr>
<tr><td colspan="6">&nbsp;</td></tr>
<tr><td align="left" colspan="4">Electricity Duty @ 10% of Energy Charge:</td><td>&nbsp; </td><td align="right">'.$tenpercntge.'  </td></tr>
<tr><td align="left" colspan="4">Meter rent :</td><td>&nbsp; </td><td align="right">'.number_format($row['METERRENT'], 2, ".", "").' </td></tr>
<tr><td colspan="4">&nbsp;</td><td>&nbsp; </td><td style="border-top-width:0.1px;">&nbsp; </td></tr>

<tr><td align="right" colspan="4">&nbsp;</td><td align="center"><b>Total </b></td><td align="right"><b>'.number_format($row['TOTAMT'], 2, ".", "").' </b></td></tr>
<tr><td colspan="6" style="border-bottom-width:2px; color:#ccc;">&nbsp;</td></tr>
<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="6"><b>Rupees '.ucfirst($words).' Only</b></td></tr>
<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="2">Sr. Asst.</td><td colspan="2">S.S</td><td colspan="2" align="left">Asst. Secretary</td></tr>
<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="6">Arrears of previous months not included in this bill<br />
Payment by Cheque/DD is allowed only up to Last date for payment with out fine.<br />
Complaints if any regarding this bill can be registered with CGRF of the TCED.<br />
Objections if any on the decision of the CGRF can be brought before the state Ombudsman.</td></tr>
<tr><td colspan="6">&nbsp;</td></tr>
</table>';
/* echo $html;
exit;
 */
}

// output the HTML content
$pdf->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 0, true, 'L', true);

// reset pointer to the last page
$pdf->lastPage();



//Close and output PDF document
$pdf->Output(strtolower($cat).'_bill_'.$billno.'_'.$row['BILL_DATE'].'.pdf', 'D');

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
