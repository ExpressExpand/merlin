<?php
	if (!empty($_GET)){	
		$user_id = $_GET['user_id'];
	}
	$page_title = "National ID Photo Upload - Back";
	include_once('includes/header.php');
?>
<div id="page">
	<div id="content">
		<div class="post">
		<h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
		<?php if($user_id != ""){ ?>
			<form enctype="multipart/form-data" action="resume_uploader_back.php?user_id=<?php echo $user_id ?>" method="POST">
		<?php }
		else { ?>
			<form enctype="multipart/form-data" action="resume_uploader_back.php" method="POST">
		<?php } ?>
			<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
			Choose a file to upload: <input name="resumefileupload_back" type="file" /><br />
			<button name="btnNewCard" id="button">Upload Photo</button>
		</form>
</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	include_once('includes/footer.php');
?>
