<?php
	$userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$station = $_SESSION["station"] ;
		$username = $_SESSION["username"];
	}
	if($adminstatus == 4){
		include_once('includes/header.php');
		?>
		<script type="text/javascript">
			document.location = "login.php";
		</script>
		<?php
	}
	else{
		$page_title = "Airtel Paybill C2B Statement Upload";
		include_once('includes/header.php');
	?>
	<!-- ####################################################################################################### -->
		<div id="page">
			<div id="content">
				<div class="post">
			      	<h2><font color="#000A8B"><?php echo $page_title ?></h2>
					<form enctype="multipart/form-data" action="uploader_airtel.php" method="POST">
					<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
					Choose a file to upload: <input name="uploadedfile" type="file" /><br />
					<!--<input type="submit" value="Upload File" />-->
					<button name="btnNewCard" id="button">Upload File</button>
				</form>
	      			</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	}
	include_once('includes/footer.php');
?>
