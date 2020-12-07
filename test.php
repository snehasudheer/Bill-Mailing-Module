<!DOCTYPE HTML5 PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="" content="">
<title>Form</title>
<link rel="stylesheet" type="text/css" href='asset/css/bootstrap.min.css'>
<link rel="stylesheet" type="text/css" href='css/style.css'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src='js/validation.js'></script>
<script src='asset/js/bootstrap.min.js'></script>
<script src='js/custom-scripts.js'></script>
<script src='js/jquery-2.2.4.min.js'></script>
<script>
 function showDiv() {
   var h1id = parseInt(document.getElementById('hid1').value);
    h1id++;
    var i=h1id;
    document.getElementById('hid1').value = i;
   //var s = $("#search span").size()+1;
   document.getElementById('search').style.display = "block";
   $('<span id="spn_srch_'+i+'"><input type="text" width="10%" class="field_name z" id="txt_sel'+i+'" name="txt_wt[]" readonly/><input type="text" class="value_name z" id="txt_value'+i+'" name="txt_value[]" readonly/><button type="button" id="btn_sdlt_'+i+'" onclick="dltSrch(spn_srch_'+i+')">X</button></br></span>').appendTo("#search");
   $('#txt_sel'+i).val($('#sel_search option:selected').text());
   $('#txt_value'+i).val($('input:text').val());
   document.getElementById('sel_search').value= "";
   document.getElementById('txt_svalue').value= "";
}
function dltSrch(a) 
{
        $(a).remove();
}
function crt() 
{
    var h2id = parseInt(document.getElementById('hid2').value);
    h2id++;
    var s=h2id;
    document.getElementById('hid2').value = s;
   //var i = $("#create span").size()+1;
   document.getElementById('create').style.display = "block";
   $('<span id="spn_crt_'+s+'"><input type="text" class="crtsel z" id="txt_crtfield'+s+'" name="txt_crtsel[]" readonly/><button type="button" id="btn_crt_'+s+'" onclick="dltcrt(spn_crt_'+s+')">X</button></br></span>').appendTo("#create");
   $('#txt_crtfield'+s).val($('#sel_crt option:selected').text());
   document.getElementById('sel_crt').value= "";
}
function dltcrt(a) {
        $(a).remove();
}
function getBaseURL() {
    var url = location.href;  // entire url including querystring - also: window.location.href;
    var baseURL = url.substring(0, url.indexOf('/', 14));


    if (baseURL.indexOf('http://localhost') != -1 || baseURL.indexOf('https://localhost') != -1 || baseURL.indexOf('http://127.0.0.1') != -1 || baseURL.indexOf('https://127.0.0.1') != -1) {
        // Base Url for localhost
        var url = location.href;  // window.location.href;
        var pathname = location.pathname;  // window.location.pathname;
        var index1 = url.indexOf(pathname);
        var index2 = url.indexOf("/", index1 + 1);
        var baseLocalUrl = url.substr(0, index2);

        return baseLocalUrl + "/";
    }
    else {
        // Root Url for domain name
        //return baseURL + "/";
        var url = location.href;  // window.location.href;
        var pathname = location.pathname;  // window.location.pathname;
        var index1 = url.indexOf(pathname);
        var index2 = url.indexOf("/", index1 + 1);
        var baseLocalUrl = url.substr(0, index2);

        return baseLocalUrl + "/";
    }+"/"+op+"/"+brcd
}
var base_url=getBaseURL();//base url for the application
alert(base_url);
function searchok(){
 var fname =$('input:text.field_name').serialize();
 var vname =$('input:text.value_name').serialize();
 var crtfield =$('input:text.crtsel').serialize();
 alert (fname);
  alert (vname);
   alert (crtfield);
 var dataString = fname+'&'+vname+'&'+crtfield;

            $.ajax({
            type:'POST',
            url:"ajax.php",
            data: dataString,
            });
            //alert (dataString);
}
</script>

<script>

    function scrolify(tblAsJQueryObject, height){
        var oTbl = tblAsJQueryObject;

        // for very large tables you can remove the four lines below
        // and wrap the table with <div> in the mark-up and assign
        // height and overflow property  
        var oTblDiv = $("<div/>");
        oTblDiv.css('height', height);
        oTblDiv.css('overflow','scroll');               
        oTbl.wrap(oTblDiv);

        // save original width
        oTbl.attr("data-item-original-width", oTbl.width());
        oTbl.find('thead tr td').each(function(){
            $(this).attr("data-item-original-width",$(this).width());
        }); 
        oTbl.find('tbody tr:eq(0) td').each(function(){
            $(this).attr("data-item-original-width",$(this).width());
        });                 


        // clone the original table
        var newTbl = oTbl.clone();

        // remove table header from original table
        oTbl.find('thead tr').remove();                 
        // remove table body from new table
        newTbl.find('tbody tr').remove();   

        oTbl.parent().parent().prepend(newTbl);
        newTbl.wrap("<div/>");

        // replace ORIGINAL COLUMN width                
        newTbl.width(newTbl.attr('data-item-original-width'));
        newTbl.find('thead tr td').each(function(){
            $(this).width($(this).attr("data-item-original-width"));
        });     
        oTbl.width(oTbl.attr('data-item-original-width'));      
        oTbl.find('tbody tr:eq(0) td').each(function(){
            $(this).width($(this).attr("data-item-original-width"));
        });                 
    }

    $(document).ready(function(){
        scrolify($('#tblNeedsScrolling'), 100); // 160 is height
    });


    </script>

</head>
</head>

<?php
include('includes/db.php');
$stid3 = oci_parse($conn, "SELECT * FROM newhtbillfile where rownum<10 and billdate>'01-JAN-2016'");
		oci_execute($stid3);
		while (($row3 = oci_fetch_array($stid3, OCI_ASSOC)) != false)
		//while($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS))
		{	
	//echo "<pre style='float:left;'>";
//	print_r($row3);
//	echo "</pre></br>";
		}
//exit;
?>
   




<body>
    <div class="container-fluid" id="searchpan" style="display: block;">
      <form class="" name="search" method="GET" action="">
        <div id="panel" width="" cellpadding="2" style="margin-top:10px">
          <div class="col-md-12">
            <div class="col-md-6">
              <div colspan="2" class="col-md-5 row" align="right">
<!--Hidden Input field to get the id-->
<input type="hidden" id="hid1" name="hid1" value="0"/>
                <select id="sel_search" class="form-control" onclick="rmvClass('sel_search')" name="sel_search">
                  <option value="">--Select--</option>
                  <option value="customer_unique_id">Kyc Id</option>
                  <option value="cust_name">Customer Name</option>
                  <option value="cust_type">Customer Type</option>
                  <option value="doj">Date Of Join</option>
                  <option value="branch_joined">Branch Joined</option>
                  <option value="grnder">Gender</option>
                  <option value="date_of_birth">DOB</option>
                  <option value="father_name">Father's Name</option>
                  <option value="mother_name">Mother's Name</option>
                  <option value="mob_no">Mobile no</option>
                  <option value="email1">Email1</option>
                  <option value="id_proof">ID Proof</option>
                  <option value="id_num">ID No</option>
                  <option value="per_house_name"></option>
                  <option value=""></option>
                  <option value=""></option>
                  <option value=""></option>
                  <option value=""></option>
                  <option value=""></option>
                  <option value=""></option>
                  <option value=""></option>
                </select>
              </div>
              <div colspan="2" class="col-md-5">
                <input type="text" class="form-control" id="txt_svalue" placeholder="Value" name="txt_svalue" required/>
              </div>
              <div class="col-md-2">
                <button id="build" style="float: right;" type="submit" form="master" class="btn btn-primary" onClick="showDiv()">Build</button>
              </div>
            </div>
            <div class="col-md-6">
              <div colspan="" class="col-md-3" align="right">
                <label for="field">Select Field</label>
              </div>
<!--Hidden Input field to get the id-->
<input type="hidden" id="hid2" name="hid2" value="0"/>
              <div colspan="2" class="col-md-5" align="left">
                <select id="sel_crt" class="form-control" onclick="rmvClass('sel_crt')" name="sel_crt">
                  <option value="">--Select--</option>
                  <option value="customer_unique_id">Kyc Id</option>
                  <option value="cust_name">Customer Name</option>
                  <option value="cust_type">Customer Type</option>
                  <option value="doj">Date Of Join</option>
                  <option value="branch_joined">Branch Joined</option>
                  <option value="grnder">Gender</option>
                  <option value="date_of_birth">DOB</option>
                  <option value="father_name">Father's Name</option>
                  <option value="mother_name">Mother's Name</option>
                  <option value="mob_no">Mobile no</option>
                  <option value="email1">Email1</option>
                  <option value="id_proof">ID Proof</option>
                  <option value="id_num">ID No</option>
                  <option value=""></option>
                  <option value=""></option>
                </select>
              </div>
              <div class="col-md-2" align="right">
                <button id="btn_crt" style="float: right;" type="submit" form="master" class="btn btn-primary" onClick="crt()">Build</button>
              </div>
              <div class="col-md-2" align="right">
                <button style="float: right;" type="submit" form="master" class="btn btn-primary" onClick="searchok()">Search</button>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="col-md-6" id="search"  style="display:none;" class="" >
            </div>
            <div class="col-md-6" id="create"  style="display:none;" class="" >
            </div>
          </div>
        </div>
      </form>
	<!--   <div style="width:300px;border:6px green solid;">
        <table border="1" width="100%" id="tblNeedsScrolling">
            <thead>
                <tr><th>Header 1</th><th>Header 2</th></tr>
            </thead>
            <tbody>
                <tr><td>row 1, cell 1</td><td>row 1, cell 2</td></tr>
                <tr><td>row 2, cell 1</td><td>row 2, cell 2</td></tr>
                <tr><td>row 3, cell 1</td><td>row 3, cell 2</td></tr>
                <tr><td>row 4, cell 1</td><td>row 4, cell 2</td></tr>           
                <tr><td>row 5, cell 1</td><td>row 5, cell 2</td></tr>
                <tr><td>row 6, cell 1</td><td>row 6, cell 2</td></tr>
                <tr><td>row 7, cell 1</td><td>row 7, cell 2</td></tr>
                <tr><td>row 8, cell 1</td><td>row 8, cell 2</td></tr>           
            </tbody>
        </table>
    </div> -->
	<?php
if($_POST['ss'])
{
	print_r($_POST['cars']);
}	


//$now = time(); // or your date as well
$now = strtotime("2016-01-13"); 
$your_date = strtotime("2016-01-01");
$datediff = $now - $your_date;

echo floor($datediff / (60 * 60 * 24));


	?>
	
	<form action="" method="post" onsubmit="return abcd()" id="asd">
<select name="cars[]" id="cars" size="5" tabindex="1" class="ssss">
 <option value="">--Select a Value--</option>
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="opel">Opel</option>
  <option value="audi">Audi</option>
  <option value="benz">Benz</option>
  <option value="bmw">Bmw</option>
  <option value="tata">Tata</option>
  <option value="fiat">Fiat</option>
</select>
<select name="cars[]" id="cars" size="5" tabindex="1" class="ssss">
 <option value="">--Select a Value--</option>
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="opel">Opel</option>
  <option value="audi">Audi</option>
  <option value="benz">Benz</option>
  <option value="bmw">Bmw</option>
  <option value="tata">Tata</option>
  <option value="fiat">Fiat</option>
</select>
<select name="cars[]" id="cars" size="5" tabindex="1" class="ssss">
 <option value="">--Select a Value--</option>
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="opel">Opel</option>
  <option value="audi">Audi</option>
  <option value="benz">Benz</option>
  <option value="bmw">Bmw</option>
  <option value="tata">Tata</option>
  <option value="fiat">Fiat</option>
</select>
<input type="submit" name="ss">
</form>
</select>
    </div>
	<div class="modal hide fade" id="myModal">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>Modal header</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn">Close</a>
    <a href="#" class="btn btn-primary">Save changes</a>
  </div>
</div>
<button name="ass" onclick="abc();" >
</body>
<script>
function abcd()
{
	//var www = $('#asd select').serializeArray();
	var www = $('select.ssss').serialize();
	//$('input:text.value_name').serialize();
	
//	var bad=$('#cars').val();
	alert (www);
	return false;
}
</script>
<script type="text/javascript">
    function abc(){
        $('#myModal').modal('show');
    };
</script>
</html>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            180,1         Bot


