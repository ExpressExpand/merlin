<?php
	$userid = "";
	$adminstatus = 3;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$username = $_SESSION["username"];
	}

	//if($adminstatus != 1 || $adminstatus != 2 || $adminstatus != 4){
	if($adminstatus == 3){
		include_once('includes/header.php');
		?>
		<script type="text/javascript">
			document.location = "insufficient_permission.php";
		</script>
		<?php
	}
	else{
	?>
		<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
		<style>
			body {
				color: #000;
				line-height: 2em;
				background: #FFF;
				font-size: 12pt;
			}

			body,input {
				font-family: 'Oxygen', sans-serif;
			}

			br.clearfix {
				clear: both;
			}

			strong {
				color: #000;
			}

			h1,h2,h3,h4 {
				font-weight: bold;
			}

			h2,h3,h4 {
				color: #000;
				font-family: 'Oxygen', sans-serif;
				margin-bottom: 0.01em;
			}

			h2 {
				font-size: 1.6em;
			}

			h3 {
				font-size: 1.0em;
			}

			h4 {
				font-size: 1.0em;
			}
			p {
				margin-bottom: 0.75em;
				text-align: justify;
			}

			ul {
				margin-bottom: 0.75em;
			}
		</style>
	<?php
		include_once('includes/db_conn.php');
		if (!empty($_GET)){	
			$user_id = $_GET['user_id'];
			$action = $_GET['action'];
			$loan_id = $_GET['loan_id'];
		}
		
		$sql = mysql_query("select first_name, last_name, mobile_no, national_id, stations from users where id = '$user_id'");
		while ($row = mysql_fetch_array($sql))
		{
			$first_name = $row['first_name'];
			$last_name = $row['last_name'];
			$first_name = ucwords(strtolower($first_name));	
			$last_name = ucwords(strtolower($last_name));
			$name = $first_name.' '.$last_name;		
			$mobile_no = $row['mobile_no'];
			$national_id = $row['national_id'];
			$stations = $row['stations'];
			$sql = mysql_query("select stations from stations where id = '$stations'");
			while ($row = mysql_fetch_array($sql))
			{
				$station_name = $row['stations'];
				$station_name = ucwords(strtolower($station_name));	
			}
		}
		
		$sql = mysql_query("select loan_date, loan_total_interest, loan_late_interest, loan_code, loan_due_date, late_status from loan_application where customer_id = '$user_id' and loan_id = '$loan_id'");
		while ($row = mysql_fetch_array($sql))
		{
			$latest_loan = $row['loan_total_interest'];
			$loan_date = $row['loan_date'];
			//$loan_date = date('d/m/y', strtotime(str_replace('-', '/', $loan_date)));
			$loan_date = date("d M, Y", strtotime($loan_date));
			$latest_loan_code = $row['loan_code'];
			$loan_due_date = $row['loan_due_date'];
			$loan_late_interest = $row['loan_late_interest'];
			$late_status = $row['late_status'];
		}
		$sql = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$latest_loan_code' group by loan_rep_code");
		while ($row = mysql_fetch_array($sql))
		{
			$repayments = $row['repayments'];
		}
		
		$balance = $latest_loan - $repayments;
		
		$transactiontime = date("Y-m-d G:i:s");
		$filter_month = date("m");
		$filter_year = date("Y");
		$filter_day = date("d");
		$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
		$current_date = date("d M, Y", strtotime($current_date));
		$loan_date = date("d M, Y", strtotime($loan_date));
		$page_title = "RE: CLEARANCE LETTER";
		//include_once('includes/header.php');

		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h1><a href="#"><img src="images/4gcapital.png" width="180px"></a></h1>
					<br />
					<p align='right'><?php echo $current_date ?></p>
					<p>To: <strong><?php echo $name ?></strong></p>
					<p>From: <strong>Fourth Generation Capital Limited</strong></p>
					<h3><u><?php echo $page_title ?></u></h3>

					<p>In reference to the loan agreement entered between us <strong>4G Capital Ltd</strong> of <strong>P.O. Box 4916 00100 Nairobi, Kenya</strong>, on one part and <strong><?php echo $name ?></strong> of ID No. <strong><?php echo $national_id ?></strong> and mobile phone number: <strong><?php echo $mobile_no ?></strong> on <strong><?php echo $loan_date ?></strong>.</p>
					<p>The above named has cleared his/her loan of <strong>KES <?php echo number_format($latest_loan, 0) ?></strong> and has fully settled the account.</p>
					<p>Yours faithfully,<br /></p>
					<p>&nbsp;</p>
					<p><strong>Branch Manager</strong><br />
					<?php echo $station_name ?> Branch</p>
				</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	}
	//include_once('includes/footer.php');
?>
