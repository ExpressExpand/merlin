<?php
	$userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$username = $_SESSION["username"];
		$station = $_SESSION["station"] ;
	}

	//if($adminstatus != 1 || $adminstatus != 2 || $adminstatus != 4){
	if($adminstatus == 4){
		include_once('includes/header.php');
		?>
		<script type="text/javascript">
			document.location = "insufficient_permission.php";
		</script>
		<?php
	}
	else{
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "Loans Disbursed Today Report";
		include_once('includes/header.php');
		include_once('includes/db_conn.php');
		
		$filter_month = date("m");
		$filter_year = date("Y");
		$filter_day = date("d");
		$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
		$current_date_full = date("d M, Y", strtotime($current_date));
		
		$report_term = 7;
		$start_report_date = date('Y-m-d',strtotime($current_date) - (24 * 3600 * $report_term));
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<h3>Report Date: <?php echo $current_date_full ?></h3>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
					<thead bgcolor="#E6EEEE">
						<tr>
						    <th>Type</th>
							<th>Customer</th>
							<th>Branch</th>
							<th>Due</th>
							<th>Code</th>
							<!--<th>Phone</th>-->
							<th>M. Money</th>
							<th>Status</th>
							<th>Init.</th>
							<th>Amount</th>
							<th>Ext.</th>
							<th>Late</th>
							<th>Interest</th>
							<th>Alloc</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
					<?php
						if($station == '3'){
							 $sql = mysql_query("select loan_id, customer_id, customer_station, loan_date, loan_due_date, loan_mobile, initiation_fee, loan_amount, loan_extension, loan_interest, loan_late_interest, loan_total_interest, loan_code, loan_mpesa_code, loan_failure_status, loan_status, loan_status, UID, admin_fee, appointment_fee, early_settlement, early_settlement_surplus, fix, joining_fee from loan_application where loan_date = '$current_date' and loan_failure_status = '0' order by loan_date desc");
						}
						else{
							  $sql = mysql_query("select loan_id, customer_id, customer_station, loan_date, loan_due_date, loan_mobile, initiation_fee, loan_amount, loan_extension, loan_interest, loan_late_interest, loan_total_interest, loan_code, loan_mpesa_code, loan_failure_status, loan_status, loan_status, UID< admin_fee, appointment_fee, early_settlement, early_settlement_surplus, fix, joining_fee from loan_application where loan_date = '$current_date' and customer_station = '$station' and loan_failure_status = '0' order by loan_date desc");
						}
						 
						 $interest = 0;
						 $total_loan_amount = 0;
						 $total_interest = 0;
						 $total_loan_total_interest = 0;
						 $intcount = 0;
						 $total_allocation_fees = 0;
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$loan_id = $row['loan_id'];					
							$loan_date = $row['loan_date'];
							$loan_due_date = $row['loan_due_date'];
							$loan_mobile = $row['loan_mobile'];
							$customer_id = $row['customer_id'];
							$customer_station = $row['customer_station'];
							$initiation_fee = $row['initiation_fee'];
							$loan_amount = $row['loan_amount'];
							$loan_extension = $row['loan_extension'];
							$loan_interest = $row['loan_interest'];
							$loan_total_interest = $row['loan_total_interest'];
							$loan_status = $row['loan_status'];
							$loan_code = $row['loan_code'];
							$loan_status = $row['loan_status'];
							$loan_late_interest = $row['loan_late_interest'];
							$loan_mpesa_code = $row['loan_mpesa_code'];
							$loan_failure_status = $row['loan_failure_status'];
							$UID = $row['UID'];
							
							$admin_fee = $row['admin_fee'];
							$appointment_fee = $row['appointment_fee'];
							$early_settlement = $row['early_settlement'];
							$early_settlement_surplus = $row['early_settlement_surplus'];
							$fix = $row['fix'];
							$joining_fee = $row['joining_fee'];
							
							if($UID == '94'){
							    $loan_type = 'Rtr';
							}
							else{  
							    $loan_type = 'Mnl';
							}
							
							$allocation_fees = $admin_fee + $appointment_fee + $early_settlement + $early_settlement_surplus + $fix + $joining_fee;
							
							$sql2 = mysql_query("select status from customer_status where id = '$loan_status'");
							while ($row = mysql_fetch_array($sql2))
							{
								$loan_status_name = $row['status'];
							}
							
							$sql2 = mysql_query("select id, stations from stations where id = '$customer_station'");
							while($row = mysql_fetch_array($sql2)) {
								$stations = $row['stations'];
								$stations = ucwords(strtolower($stations));
							}
							$sql2 = mysql_query("select first_name, last_name from users where id = '$customer_id'");
							while($row = mysql_fetch_array($sql2)) {
								$first_name = $row['first_name'];
								$first_name = ucwords(strtolower($first_name));
								$last_name = $row['last_name'];
								$last_name = ucwords(strtolower($last_name));
								$customer_name = $first_name.' '.$last_name;
							}
							
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$loan_type</td>";	
							echo "<td valign='top'>$customer_name</td>";	
							echo "<td valign='top'>$stations</td>";	
							echo "<td valign='top'>$loan_due_date</td>";
							//echo "<td valign='top'>$loan_code</td>";
							echo "<td valign='top'>$loan_mobile</td>";					
							echo "<td valign='top'>$loan_mpesa_code</td>";
							echo "<td valign='top'>$loan_status_name</td>";
							echo "<td valign='top' align='right'>".number_format($initiation_fee, 2)."</td>";
							echo "<td valign='top' align='right'>".number_format($loan_amount, 2)."</td>";
							echo "<td valign='top' align='right'>".number_format($loan_extension, 2)."</td>";
							echo "<td valign='top' align='right'>".number_format($loan_late_interest, 2)."</td>";
							echo "<td valign='top' align='right'>".number_format($loan_interest, 2)."</td>";
							echo "<td valign='top' align='right'>".number_format($allocation_fees, 2)."</td>";
							echo "<td valign='top' align='right'>".number_format($loan_total_interest, 2)."</td>";
							
							echo "</tr>";
							$total_initiation_fee = $total_initiation_fee + $initiation_fee;
							$total_loan_amount = $total_loan_amount + $loan_amount;
							$total_interest = $total_interest + $loan_interest;
							$total_extension = $total_extension + $loan_extension;
							$total_loan_late_interest = $total_loan_late_interest + $loan_late_interest;
							$total_loan_total_interest = $total_loan_total_interest + $loan_total_interest;
							$total_allocation_fees = $total_allocation_fees + $allocation_fees;
							
							$initiation_fee = 0;
							$loan_amount = 0;
							$loan_extension = 0;
							$loan_late_interest = 0;
							$loan_interest = 0;
							$loan_total_interest = 0;
							$allocation_fees = 0;
							$loan_mpesa_code = "";
						}
						?>
					</tbody>
					<tr bgcolor = '#E6EEEE'>
							<td colspan='7'><strong>&nbsp;</strong></td>
							<td align='right' valign='top'><strong><?php echo number_format($total_initiation_fee, 2) ?></strong></td>
							<td align='right' valign='top'><strong><?php echo number_format($total_loan_amount, 2) ?></strong></td>
							<td align='right' valign='top'><strong><?php echo number_format($total_extension, 2) ?></strong></td>
							<td align='right' valign='top'><strong><?php echo number_format($total_loan_late_interest, 2) ?></strong></td>
							<td align='right' valign='top'><strong><?php echo number_format($total_interest, 2) ?></strong></td>
							<td align='right' valign='top'><strong><?php echo number_format($total_allocation_fees, 2) ?></strong></td>
							<td align='right' valign='top'><strong><?php echo number_format($total_loan_total_interest, 2) ?></strong></td>
						</tr>
						</tr>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Customer</th>
							<th>Branch</th>
							<th>Due</th>
							<th>Code</th>
							<!--<th>Phone</th>-->
							<th>M. Money</th>
							<th>Status</th>
							<th>Init.</th>
							<th>Amount</th>
							<th>Ext.</th>
							<th>Late</th>
							<th>Interest</th>
							<th>Alloc</th>
							<th>Total</th>
						</tr>
					</tfoot>
				</table>
				<br />
				Click here to export to Excel >> <button id="btnExport">Excel</button>
				<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
				<script src="js/jquery.btechco.excelexport.js"></script>
				<script src="js/jquery.base64.js"></script>
				<script src="http://wsnippets.com/secure_download.js"></script>
				<script>
					$(document).ready(function () {
					$("#btnExport").click(function () {
						$("#example2").btechco_excelexport({
						containerid: "example2"
						   , datatype: $datatype.Table
						});
					});
					});
				</script>
				</div>
				</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
		</div>
<?php
	}
	include_once('includes/footer.php');
?>
