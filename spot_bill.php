<?php

require_once('tcpdf_include.php');

session_start();	
// Extend the TCPDF class to create custom Header and Footer
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
		$img_file = K_PATH_IMAGES.'trissur_bill.jpg';
		$this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
		// restore auto-page-break status
		$this->SetAutoPageBreak($auto_page_break, $bMargin);
		// set the starting point for the page content
		$this->setPageMark();
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('TCED REPORT');
$pdf->SetTitle('TCED REPORT');
$pdf->SetSubject('TCED REPORT');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(false);

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
$pdf->SetFont('dejavusans', '', 9);


$conn = oci_connect('tcr', 'dits', '');
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
			
			if(trim($bill_type)=='Spot')
			{
				
				
					$tab = "master";
				$stid1 = oci_parse($conn, "SELECT * FROM ".$tab." where CNO='$consumerno'");
				oci_execute($stid1);
				$row1 = oci_fetch_array($stid1, OCI_ASSOC+OCI_RETURN_NULLS);
				$wotariff = $row1['WO'];
					$stid = oci_parse($conn, "SELECT b.TERM as term,b.YEAR as year,b.BNO as bill_no,b.BDATE as bill_date,b.LDATE,b.FINEDATE,b.UNITS,b.TOTAMT,b.RECEIPT as RECEIPTNO,b.INT AS INTEREST,b.RC,b.PAID,b.FC,b.EC,b.RC,b.INT,b.ADVANCE,b.ARREAR,b.PMR,b.CMR,b.PMRDATE,b.DUTY_ARREAR,b.TOTAL,b.duty,c.COUNTERNO FROM billfile_trans b left join cash c on (b.cno=c.cno and b.BNO=c.billno) where b.CNO='$consumerno' and b.BNO='$billno' order by b.BDATE DESC");
					
					//echo "SELECT TERM as term,YEAR as year,BNO as bill_no,BDATE as bill_date,LDATE,FINEDATE,UNITS,TOTAMT,RECEIPT as RECEIPTNO,INT AS INTEREST,RC,PAID FROM billfile_trans where CNO='$consumerno' and BNO='$billno'order by BDATE DESC";
					//exit;
				}
		}
		oci_execute($stid);
		//$row = oci_fetch($stid);
		//echo "Sss";
		$row = oci_fetch_array($stid, OCI_ASSOC);
		//print_r($row);
		//exit;
		
		$bdate = $row['BILL_DATE'];
		
		//$stid2 = oci_parse($conn, "SELECT PAMT,TOTAMT FROM BILLFILE_TRANS WHERE BDATE = (select max(bdate) as bdate from (SELECT bdate from billfile_trans where cno='$consumerno' and year > '2011' and bdate < '$bdate' order by bdate desc)) and cno='$consumerno'");
		$stid2 = oci_parse($conn, "SELECT PREVBILLAMT,PAIDAMT,SECURITYAMT,ARREAR,ADVANCE,AVGCONSUMPTION,VC_PREPAREDBY,NN_ASDAMT,NN_SDINTEREST,NN_ECARREAR,NN_DUTYARREAR FROM GEN_SPOTBILLFILE WHERE cno='$consumerno' and BILLNO='$billno'");
		oci_execute($stid2);
		$row2 = oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS);
		
		$stid3 = oci_parse($conn, "SELECT PAMT,TOTAMT FROM BILLFILE_TRANS WHERE BDATE = (select max(bdate) as bdate from (SELECT bdate from billfile_trans where cno='$consumerno' and year > '2011' and bdate < '$bdate' order by bdate desc)) and cno='$consumerno'");
		oci_execute($stid3);
		$row3 = oci_fetch_array($stid3, OCI_ASSOC+OCI_RETURN_NULLS);
		
		if($row2['PREVBILLAMT']!='')
		{
			$prevbill = $row2['PREVBILLAMT'];
		}
		elseif($row3['TOTAMT']!='')
		{
			$prevbill = $row3['TOTAMT'];
		}
		
		if($row2['PAIDAMT']!='')
		{
			$prevpayed = $row2['PAIDAMT'];
		}
		elseif($row3['PAMT']!='')
		{
			$prevpayed = $row3['PAMT'];
		}
		
		
		if(trim($bill_type)!='Spot')
		{
		$monthName = date('F', mktime(0, 0, 0, $row['TERM'], 10));
		}
		else{
			$monthName = $row['TERM'];
		}
		
		
}
//print_r($row);
//exit;
// add a page
$pdf->AddPage();

$words=convert_number_to_words($row['TOTAMT']);

// Print a text
$html = '
	<table style="width:100%;" cellspacing="7" cellpadding="7">
	<tr><th colspan="6" height="50">&nbsp;</th></tr>
<tr><td align="right" colspan="2">'.$row1['SECT'].'</td><td align="right" colspan="2">'.$row1['AD1'].'</td><td align="right" colspan="2">'.$row1['VC_TELENO'].'</td></tr>
<tr><td align="right" colspan="2">'.$consumerno.'</td><td align="center" colspan="4">'.$row1['NAME'].'</td></tr>
<tr><td align="right" colspan="2">'.$row1['TARIFF'].'</td><td align="right" colspan="4">'.$row1['VC_POSTNO'].'</td></tr>
<tr><td align="right" colspan="2">'.$row1['PHASE'].'</td><td align="center" colspan="2">'.$row1['CONLOAD'].'&nbsp;KW</td><td align="right" colspan="2">&nbsp;</td></tr>
<tr><td align="right" colspan="2">'.$row['BILL_NO'].'<br />'.$row['BILL_DATE'].'<br />'.$row1['WO'].'</td><td align="center" colspan="2"><br /><br /><br /><br />'.$row['LDATE'].'</td><td align="center" colspan="2"><br /><br /><br /><br />'.$row['FINEDATE'].'</td></tr>
<tr><td align="right" colspan="2">&nbsp;</td><td align="left" colspan="2">'.strtoupper(date( "d-M-y", strtotime($row['PMRDATE']) )).'</td></tr>
<tr><td align="right" colspan="2">&nbsp;</td><td align="left" colspan="2">&nbsp;</td><td align="right" colspan="2">'.number_format($row['FC'], 2, ".", "").'</td></tr>
<tr><td align="right" colspan="2">&nbsp;</td><td align="left" colspan="2">'.$prevbill.'</td><td align="right" colspan="2">'.number_format(round($row['EC']-$row['DUTY']), 2, ".", "").'</td></tr>
<tr><td align="right" colspan="2">&nbsp;</td><td align="left" colspan="2">'.$prevpayed.'</td><td align="right" colspan="2">'.number_format(round($row['DUTY']), 2, ".", "").'</td></tr>
<tr><td height="110" align="right">&nbsp;</td><td align="left">&nbsp;<br /><br /><br />'.$row['CMR'].'<br /><br />'.$row['PMR'].'<br /><br />'.$row['UNITS'].'<br /><br /><br />'.$row2['AVGCONSUMPTION'].'</td><td align="left" colspan="2">&nbsp;</td><td align="right" colspan="2">40.00<br /><br />&nbsp;<br /><br />&nbsp;<br />SD Int : '.number_format($row2['NN_SDINTEREST'], 2, ".", "").'<br /><br />EC Arrear : '.number_format($row2['NN_ECARREAR'], 2, ".", "").'<br /><br />Duty Arrear : '.number_format($row2['NN_DUTYARREAR'], 2, ".", "").'<br /><br />'.number_format($row['TOTAL'], 2, ".", "").'</td></tr>
<tr><td align="right" colspan="2">&nbsp;<br />Additional security deposit:</td><td align="left" colspan="2">'.number_format($row1['SECURITYAMT'], 2, ".", "").'<br /> '.number_format($row2['NN_ASDAMT'], 2, ".", "").'</td><td align="right" colspan="2">'.number_format($row['ARREAR'], 2, ".", "").'</td></tr>
<tr><td align="right" colspan="2">&nbsp;</td><td align="left" colspan="2">&nbsp;</td><td align="right" colspan="2">'.number_format($row['ADVANCE'], 2, ".", "").'</td></tr>
<tr><td align="right" colspan="2">&nbsp;</td><td align="left" colspan="2">&nbsp;</td><td align="right" colspan="2">'.number_format($row['TOTAMT'], 2, ".", "").'</td></tr>
<tr><td align="right" colspan="2">&nbsp;</td><td align="left" colspan="2">&nbsp;</td><td align="right" colspan="2">'.ucfirst($words).'</td></tr>
<tr><td align="right" colspan="2">&nbsp;</td><td align="left" colspan="2">&nbsp;</td><td align="right" colspan="2">&nbsp;<br />'.ucfirst($row2['VC_PREPAREDBY']).'</td></tr>
</table>';

$pdf->writeHTML($html, true, false, true, false, '');





// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('spot_bill.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+

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