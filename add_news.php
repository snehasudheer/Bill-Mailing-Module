 <?php include('includes/header.php'); ?>
 <script src="tinymce/tinymce.min.js"></script>
 <script>tinymce.init({
  selector: 'textarea',
  height: 200,
  menubar: false,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table contextmenu paste code'
  ],
  toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  content_css: '//www.tinymce.com/css/codepen.min.css'
});</script>
<!-- Start Page Banner -->
    <div class="page-banner">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <h2>Add news</h2>
            <p>News</p>
          </div>
          <div class="col-md-6">
            <ul class="breadcrumbs">
              <li><a href="index.php">Home</a></li>
              <li>News</li>
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
        
		<h1> Add News </h1>
		<fieldset>
	 
	 <div style="margin-top:35px">
	 
	 <div style="margin-top:35px; font-size:16px; color:#000;">
	 <?php if(isset($_GET['msg'])) { 
	 if($_GET['msg']=='expired') {
		 $msg = "Due Date Over!";
		 $color = "red";
	 }
	 elseif($_GET['msg']=='paid') {
		 $msg = "Already Paid!";
		 $color = "green";
	 }
	 elseif($_GET['msg']=='notfound') {
		 $msg = "Record Not Found!";
		 $color = "red";
	 }
	 ?>
	 <div align="center" style="color:<?php echo $color; ?>;font-size: 24px;"><?php echo $msg; ?></div>
	 <?php } ?>
	 <form name="add_news" method="post" action="news_list.php">
	 
	 <div class="form-group">	
<div class="signleftbox">Title :</div>
		<div class="signrightbox">
		<input type="text" name="title" id="news_title" value="" style="width:86%;"/>
		</div>	 
		<div class="signleftbox">Description :</div>
		<div style="float:left;">
		<textarea >Easy (and free!) You should check out our premium features.</textarea>
		</div>
		<div style="float:right; margin-top:10px;">
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
		
		</div>
		</div>
		
<?php include('includes/footer.php'); ?>