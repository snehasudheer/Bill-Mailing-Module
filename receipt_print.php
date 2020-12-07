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
				$stid1 = oci_parse($conn, "SELECT TARIFF,CATEGORY FROM ".$tab." where CNO='$consumerno'");
				oci_execute($stid1);
				$row1 = oci_fetch_array($stid1, OCI_ASSOC+OCI_RETURN_NULLS);
				$cat = $row1['CATEGORY'];
				$wotariff = $row1['TARIFF'];
				if($cat=='LT4')
				{
					$stid = oci_parse($conn, "SELECT l.MONTH as term,l.YEAR as year,l.BILLNO as bill_no,l.BILLDATE as bill_date,l.LASTDATE as LDATE,l.DISDATE as FINEDATE,l.KWH as UNITS,l.TOTAL as TOTAMT,l.RECEIPTNO,l.RC,l.PAID,c.counterno FROM LT4billfile l left join cash c on (l.cno=c.cno and l.billno=c.billno) where l.CNO='$consumerno' and l.BILLNO='$billno'");
					//echo "SELECT l.MONTH as term,l.YEAR as year,l.BILLNO as bill_no,l.BILLDATE as bill_date,l.LASTDATE as LDATE,l.DISDATE as FINEDATE,l.KWH as UNITS,l.TOTAL as TOTAMT,l.RECEIPTNO,l.RC,l.PAID,c..counterno FROM LT4billfile l left join cash c on (l.cno=c.cno and l.billno=c.billno) where l.CNO='$consumerno' and l.BILLNO='$billno'";
					//exit;
				}
				else if($cat=='GT')
				{
					$stid = oci_parse($conn, "SELECT g.MONTH as term,g.YEAR as year,g.BILLNO as bill_no,g.BILLDATE as bill_date,g.LASTDATE as LDATE,g.DISDATE as FINEDATE,g.UNITS as UNITS,g.TOTAL as TOTAMT,g.RECEIPTNO,g.INTEREST,g.RC,g.PAID,c.counterno FROM GTbillfile g left join cash c on (g.cno=c.cno and g.billno=c.billno) where g.CNO='$consumerno' and g.BILLNO='$billno'");
					//echo "SELECT MONTH as term,YEAR as year,BILLNO as bill_no,BILLDATE as bill_date,LASTDATE as LDATE,DISDATE as FINEDATE,UNITS as UNITS,TOTAL as TOTAMT,RECEIPTNO,INTEREST,RC,PAID FROM GTbillfile where CNO='$consumerno' and BILLNO='$billno'";
					//exit;
				}
				else if($cat=='HT')
				{ 
					$stid = oci_parse($conn, "SELECT b.MONTH as term,b.YEAR as year,b.BILLNO as bill_no,b.BILLDATE as bill_date,b.LASTDATE as LDATE,b.DISDATE as FINEDATE,b.KWHNORMAL,b.KWHPEAK,b.KWHOFF,b.BILLDEMAND,b.TOTBILLAMT as TOTAMT,b.RECEIPTNO,b.INTEREST,b.RC,b.PAID,c.counterno FROM NEWHTbillfile b left join cash c on (b.cno=c.cno and b.billno=c.billno) where b.CNO='$consumerno' and b.BILLNO='$billno'");
					//echo "SELECT b.MONTH as term,b.YEAR as year,b.BILLNO as bill_no,b.BILLDATE as bill_date,b.LASTDATE as LDATE,b.DISDATE as FINEDATE,b.KWHNORMAL,b.KWHPEAK,b.KWHOFF,b.BILLDEMAND,b.TOTBILLAMT as TOTAMT,b.RECEIPTNO,b.INTEREST,b.RC,b.PAID,c.counterno FROM NEWHTbillfile b left join cash c on (b.cno=c.cno and b.BNO=c.billno) where CNO='$consumerno' and BILLNO='$billno'";
					//exit;
				}
			}
			elseif(trim($bill_type)=='Spot')
				{
					$tab = "master";
				$stid1 = oci_parse($conn, "SELECT WO FROM ".$tab." where CNO='$consumerno'");
				oci_execute($stid1);
				$row1 = oci_fetch_array($stid1, OCI_ASSOC+OCI_RETURN_NULLS);
				$cat = $row1['CATEGORY'];
				$wotariff = $row1['WO'];
					$stid = oci_parse($conn, "SELECT b.TERM as term,b.YEAR as year,b.BNO as bill_no,b.BDATE as bill_date,b.LDATE,b.FINEDATE,b.UNITS,b.TOTAMT,b.RECEIPT as RECEIPTNO,b.INT AS INTEREST,b.RC,b.PAID,c.COUNTERNO FROM billfile_trans b left join cash c on (b.cno=c.cno and b.BNO=c.billno) where b.CNO='$consumerno' and b.BNO='$billno' order by b.BDATE DESC");
					//echo "SELECT TERM as term,YEAR as year,BNO as bill_no,BDATE as bill_date,LDATE,FINEDATE,UNITS,TOTAMT,RECEIPT as RECEIPTNO,INT AS INTEREST,RC,PAID FROM billfile_trans where CNO='$consumerno' and BNO='$billno'order by BDATE DESC";
					//exit;
				}
		}
		
		
		oci_execute($stid);
		//$row = oci_fetch($stid);
		//echo "Sss";
		$row = oci_fetch_array($stid, OCI_ASSOC);
		if(trim($bill_type)!='Spot')
		{
		$monthName = date('F', mktime(0, 0, 0, $row['TERM'], 10));
		}
		else{
			$monthName = $row['TERM'];
		}
		$words=convert_number_to_words($row['TOTAMT']+$row["INTEREST"]+$row["RC"]);
		
}		
//echo $row['RECEIPTNO']."sss";
//exit;
	$html = '
	<table style="width:100%;border-bottom-width:0.1px;">
	<tr><th colspan="5">&nbsp;</th></tr>
<tr>
	<th style="border-bottom-width:0.1px; width:20%; padding:10px;"><img style="margin-top:10px;" src="images/tced_logo.png" /></th>
	<th colspan="3" style="border-bottom-width:0.1px; width:60%;">
		<h2 align="center">THRISSUR CORPORATION</h2>
		<h4 align="center">ELECTRICITY DEPARTMENT</h4>
		<h4 align="center">RECEIPT</h4></th>
	<th align="right" style="border-bottom-width:0.1px; width:20%;"><img src="images/phne.png" style="height:10px; width:10px; margin-right:5px;" />2423613</th>
</tr>
<tr><td colspan="4">&nbsp;</td></tr>
<tr>
	<td style="width:15%;">Received from </td>
	<td align="left" colspan="2">: <b>SECRETARY H.C</b></td>
	<td align="right" style="width:25%;">Receipt No. </td>
	<td style="width:20%;">: '.$row["RECEIPTNO"].'</td>
</tr>
<tr>
	<td style="width:15%;border-bottom-width:0.1px;">Consumer No. </td>
	<td align="left" style="border-bottom-width:0.1px;">: '.$consumerno.'</td>
	<td align="left" style="border-bottom-width:0.1px;">Counter No. : '.$row["COUNTERNO"].'</td>
	<td align="right" style="width:25%;border-bottom-width:0.1px;"> Date </td>
	<td style="width:20%;border-bottom-width:0.1px;">: '.$row["BILL_DATE"].'</td>
</tr>
<tr><td colspan="4" style="border-right-width:0.1px;">&nbsp;</td>
	<td style="width:20%;">&nbsp;</td></tr>
<tr>
	<td style="width:15%;">Bill No. </td>
	<td align="left" colspan="2">: '.$billno.'</td>
	<td style="width:25%;border-right-width:0.1px;" align="right">Bill Amount </td>
	<td style="width:20%;">'.number_format($row["TOTAMT"], 2, ".", "").'</td>
</tr>
<tr>
	<td style="width:15%;">Term </td>
	<td align="left" colspan="2">: '.$monthName.'</td>
	<td style="width:25%;border-right-width:0.1px;" align="right">Penal Interest</td>
	<td style="width:20%;">'.number_format($row["INTEREST"], 2, ".", "").'</td>
</tr>
<tr>
	<td style="width:15%;">WO/Tariff :</td>
	<td align="left" colspan="2">: '.$wotariff.'</td>
	<td style="width:25%;border-right-width:0.1px;" align="right"></td>
	<td style="width:20%;">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" style="width:60%;">&nbsp;</td>
	<td style="width:20%;border-right-width:0.1px;" align="right">RC Charge</td>
	<td style="width:20%;">'.number_format($row["RC"], 2, ".", "").'</td>
</tr>
<tr>
	<td style="width:15%;">Rupees</td>
	<td align="left" colspan="2">: '.ucfirst($words).'</td>
	<td style="width:15%;border-right-width:0.1px;"></td>
	<td style="width:20%;">&nbsp;</td>
</tr>
<tr><td colspan="4" style="border-right-width:0.1px;">&nbsp;</td>
	<td style="width:20%;">&nbsp;</td></tr>
<tr>
	<td style=" width:25%;border-bottom-width:0.1px;">towards energy charges</td>
	<td align="left" colspan="2" style="border-bottom-width:0.1px;">&nbsp;</td>
	<td style="width:5%;border-bottom-width:0.1px;border-right-width:0.1px;">&nbsp;</td>
	<td style="width:20%;border-bottom-width:0.1px;">&nbsp;</td>
</tr>
<tr><td colspan="5">&nbsp;</td></tr>
<tr>
	<td width="30%" style="background:#ccc;">CASHIER</td>
	<td width="30%">ASST. SECRETARY </td>
	<td width="20%" align="right"><b>TOTAL : </b></td>
	<td width="20%"><b>'.number_format($row["TOTAMT"]+$row["INTEREST"]+$row["RC"], 2, ".", "").'</b></td>
</tr>
<tr><td colspan="5">&nbsp;</td></tr>
</table>';
//echo $html;
//exit;
if($row["PAID"]==1)
{
$pdf->Image('images/paid12.png', 90, 50, 50, '', '', '', '', false, 300);
	$pdf->setPageMark();
}
}

// output the HTML content
$pdf->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 0, true, 'L', true);

// reset pointer to the last page
$pdf->lastPage();



//Close and output PDF document
$pdf->Output('receipt_details.pdf', 'D');

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
