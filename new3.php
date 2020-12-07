


if(trim($bill_type)=='LT4')
			{
				$print_page="gtnlt_bill.php";
				if(isset($bill_month && $bill_year)
				{
					$cond .= " and MONTH='$bill_month' and YEAR='$bill_year'";
				}
				else{
					$cond .= "x.billdate=(select max(billdate) as date_max from LT4billfile)";
				}
				$stid = oci_parse($conn, "select * from ( select a.*, ROWNUM rnum from ( SELECT CNO,BILLNO,BILLDATE,LASTDATE,DISDATE,TOTAL,PAID  FROM LT4billfile where ".$cond." order by billdate DESC) a where ROWNUM <= $end )where rnum  >= $start");
				$rows=oci_parse($conn, "select count(*) as count from LT4billfile where ".$cond);
			}
			else if(trim($bill_type)=='GT')
			{
				$print_page="gtnlt_bill.php";
				if(isset($bill_month && $bill_year)
				{
					$cond .= " and MONTH='$bill_month' and YEAR='$bill_year'";
				}
				else{
					$cond .= "x.billdate=(select max(billdate) as date_max from GTbillfile)";
				}
				$stid = oci_parse($conn, "select * from ( select a.*, ROWNUM rnum from ( SELECT CNO,BILLNO,BILLDATE,LASTDATE,DISDATE,TOTAL,PAID  FROM GTbillfile where ".$cond." order by billdate DESC) a where ROWNUM <= $end )where rnum  >= $start");
				$rows=oci_parse($conn, "select count(*) as count from GTbillfile where ".$cond);	
			}
			elseif(trim($bill_type)=='Spot')
			{
				$print_page="spot_bill.php";
				if(isset($bill_month && $bill_year)
				{
					$cond .= " and TERM='$bill_month' and YEAR='$bill_year'";
				}
				else{
					$cond .= "x.bdate=(select max(bdate) as date_max from billfile_trans)";
				}
				$stid = oci_parse($conn, "select * from ( select a.*, ROWNUM rnum from (SELECT CNO,BNO as billno,BDATE as billdate,LDATE as lastdate,FINEDATE as disdate,totamt as TOTAL,PAID FROM billfile_trans where ".$cond." order by BDATE DESC) a where ROWNUM <= $end )where rnum  >= $start");;
				$rows=oci_parse($conn, "select count(*) as count from billfile_trans where ".$cond);
			}