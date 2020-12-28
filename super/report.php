<?php 
	session_start(); 
	define('__EXEC__', 1); 
	require_once 'inc/actions.php'; 
	if(!isset($_SESSION['superuser'])) {
		header('location:login.php');
	}
	
	function thousandsCurrencyFormat($num) {
		if($num>1000) {
			$x = round($num);
			$x_number_format = number_format($x);
			$x_array = explode(',', $x_number_format);
			$x_parts = array('k', 'm', 'b', 't');
			$x_count_parts = count($x_array) - 1;
			$x_display = $x;
			$x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
			$x_display .= $x_parts[$x_count_parts - 1];
			return $x_display;
		}
		return $num;
	}
	
	$accountsss = $accounts;
	if(isset($_POST['post'])) {
		$restaurant = isset($_POST['restuarants'])?$_POST['restuarants']:'';
		$start = isset($_POST['start'])?$_POST['start']:'';
		$end   = isset($_POST['end'])?$_POST['end']:'';
		if(!empty($restaurant)) {
			$ids = "'" . implode ( "', '", $restaurant ) . "'"; 
			$accountsss = getAccounts($ids);
		} else {
			$accountsss = getAccounts();
		}
	}
	
	
	
	if(isset($_POST['csv'])) {
		$filename = date('d-m-Y').".xlsx";
		$fp = fopen('php://output', 'w');
		$header = ['Sr.','Client Name','ID','Option','Delivery','Reservation','Online Payment','Total Sales','Delivery Fee','Delivery','Delivery Fee Due','Reservation','Reservation Fee Due','EFT 1.5%','Paygate EFT Due','Masterpass 3.5%','Paygate online Due'];
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		fputcsv($fp, $header);
		$onlinePayment="No";
		$cap=10000;$capVal=0;$deliveryM=0;$grandDelivery=0;$reservationS=0;$reservation_multiplier=5;$grandReservation=0;$EFT=0;$eft=0;$master=0;$masterpass=0;
		$option1 = 200;
		$option2 = 0.015; // 1.5%
		$delComm = 0.015; //1.5%
		$option3 = 0.0175; // 1.75%
		$option4 = 0.02; // 2%
		$grandTotal = 0;
		$restaurant = isset($_POST['rest1'])?$_POST['rest1']:'';
		$start = isset($_POST['strSt1'])?$_POST['strSt1']:'';
		$end   = isset($_POST['strEnd1'])?$_POST['strEnd1']:'';
		if(!empty($restaurant)) {
			$ids = "'" . implode ( "', '", $restaurant ) . "'"; 
			$accountsss = getAccounts($ids);
		} else {
			$accountsss = getAccounts();
		}
		foreach ($accountsss as $key=>$account){
			$option = $account['packages'];
			$prefix = $account['sku']."_";
			$settings = getSettings("`".$prefix.'settings'.'`');
			if(!empty($_POST['strSt1']) && !empty($_POST['strEnd1'])) {
				$start = $_POST['strSt1'];$end = $_POST['strEnd1'];
				$salesData = getReportOrders("`".$prefix.'orders'.'`',$start,$end);
			} else {
				$salesData = getReportOrders("`".$prefix.'orders'.'`');
			}
			//$reservationS = getReservationCount();
			//$EFT = getEFT();
			//$masterpass = getMasterpass();
			$total_sales = $salesData['total_sales'];
			$delivery_sale = $salesData['delivery'];
			if(count($settings)>1) {
				foreach($settings as $k=>$v) {
					if($v['index']=='payment_mode') {
						if(($v['value']=="3" || $v['value']=="2")) {
							$onlinePayment = "Yes";
						}
					}
				}
			}
			if($option=="Option 1") {
				$capVal = $option1 * $total_sales;
			} else if($option=="Option 2") {
				$capVal = $option2 * $total_sales;
			} else if($option=="Option 3") {
				$capVal = $option3 * $total_sales;
			} else if($option=="Option 4") {
				$capVal = $option4 * $total_sales;
			}
			$deliveryM = $delComm * $delivery_sale;
		$delivery = $account['delivery']=="1" ? "Yes":"NO";
		$reservation = $account['reservation']=="1" ? "Yes":"No";
		$reserv = $reservationS*$reservation_multiplier;
		$grandTotal += $total_sales;
		$grandDelivery += $deliveryM;
		$grandReservation += $reserv;
		$eft += $EFT;
		$master += $masterpass;
		$grandEFT = 0;
		$grandMasterpass = 0;
		$row = [$key+1,$account['restaurant_name'],$account['id'],$option,$delivery,$reservation,$onlinePayment,number($total_sales),'delivery fee',number($delivery_sale),$deliveryM,$reservationS,$reserv,'','','',''];
			fputcsv($fp, $row);
		}
		$total = ['','','','','','','Total Sale',number($grandTotal),'','',number($grandDelivery),'',number($grandReservation),'',number($grandEFT),'',number($grandMasterpass)];
		fputcsv($fp, $total);
		$row1 = ['','','','','','','W8R',number(($grandDelivery+$grandReservation+$grandEFT+$grandMasterpass)),'','','','','','','','',''];
		fputcsv($fp, $row1);
		$row2 = ['','','','','','','Merchant Total',number(($master+$eft)),'','','','','','','','',''];
		fputcsv($fp, $row2);
		$row3 = ['','','','','','','Paygate',number(($grandEFT+$grandMasterpass)),'','','','','','','','',''];
		fputcsv($fp, $row3);
		exit;
	}
	
	if(isset($_POST['excel'])) {
		$filename = date('d-m-Y').".xlsx";
		
		$header = ['Sr.','Client Name','ID','Option','Delivery','Reservation','Online Payment','Total Sales','Delivery Fee','Delivery','Delivery Fee Due','Reservation','Reservation Fee Due','EFT 1.5%','Paygate EFT Due','Masterpass 3.5%','Paygate online Due'];
		$sep = "\t";
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename='.$filename);
		header("Pragma: no-cache"); 
		header("Expires: 0");
		foreach($header as $head) {
			echo $head . "\t";
		}
		print("\n"); 
		$onlinePayment="No";
		$cap=10000;$capVal=0;$deliveryM=0;$grandDelivery=0;$reservationS=0;$reservation_multiplier=5;$grandReservation=0;$EFT=0;$eft=0;$master=0;$masterpass=0;
		$option1 = 200;
		$option2 = 0.015; // 1.5%
		$delComm = 0.015; //1.5%
		$option3 = 0.0175; // 1.75%
		$option4 = 0.02; // 2%
		$grandTotal = 0;
		$restaurant = isset($_POST['rest2'])?$_POST['rest2']:'';
		$start = isset($_POST['strSt2'])?$_POST['strSt2']:'';
		$end   = isset($_POST['strEnd2'])?$_POST['strEnd2']:'';
		if(!empty($restaurant)) {
			$ids = "'" . implode ( "', '", $restaurant ) . "'"; 
			$accountsss = getAccounts($ids);
		} else {
			$accountsss = getAccounts();
		}
		
		foreach ($accountsss as $key=>$account):
		
			$option = $account['packages'];
			$prefix = $account['sku']."_";
			$settings = getSettings("`".$prefix.'settings'.'`');
			if(!empty($_POST['strSt2']) && !empty($_POST['strEnd2'])) {
				$start = $_POST['strSt2'];$end = $_POST['strEnd2'];
				$salesData = getReportOrders("`".$prefix.'orders'.'`',$start,$end);
			} else {
				$salesData = getReportOrders("`".$prefix.'orders'.'`');
			}
			//$reservationS = getReservationCount();
			//$EFT = getEFT();
			//$masterpass = getMasterpass();
			$total_sales = $salesData['total_sales'];
			$delivery_sale = $salesData['delivery'];
			if(count($settings)>1) {
				foreach($settings as $k=>$v) {
					if($v['index']=='payment_mode') {
						if(($v['value']=="3" || $v['value']=="2")) {
							$onlinePayment = "Yes";
						}
					}
				}
			}
			if($option=="Option 1") {
				$capVal = $option1 * $total_sales;
			} else if($option=="Option 2") {
				$capVal = $option2 * $total_sales;
			} else if($option=="Option 3") {
				$capVal = $option3 * $total_sales;
			} else if($option=="Option 4") {
				$capVal = $option4 * $total_sales;
			}
			$deliveryM = $delComm * $delivery_sale;
		$delivery = $account['delivery']=="1" ? "Yes":"NO";
		$reservation = $account['reservation']=="1" ? "Yes":"No";
		$reserv = $reservationS*$reservation_multiplier;
		$grandTotal += $total_sales;
		$grandDelivery += $deliveryM;
		$grandReservation += $reserv;
		$eft += $EFT;
		$master += $masterpass;
		$grandEFT = 0;
		$grandMasterpass = 0;
		$row = [$key+1,$account['restaurant_name'],$account['id'],$option,$delivery,$reservation,$onlinePayment,number($total_sales),'delivery fee',number($delivery_sale),$deliveryM,$reservationS,$reserv,'','','',''];
			echo implode("\t", array_values($row)) . "\r\n";
		endforeach;
		$total = ['','','','','','','Total Sale',number($grandTotal),'','',number($grandDelivery),'',number($grandReservation),'',number($grandEFT),'',number($grandMasterpass)];
		echo implode("\t", array_values($total)) . "\r\n";
		$row1 = ['','','','','','','W8R',number(($grandDelivery+$grandReservation+$grandEFT+$grandMasterpass)),'','','','','','','','',''];
		echo implode("\t", array_values($row1)) . "\r\n";
		$row2 = ['','','','','','','Merchant Total',number(($master+$eft)),'','','','','','','','',''];
		echo implode("\t", array_values($row2)) . "\r\n";
		$row3 = ['','','','','','','Paygate',number(($grandEFT+$grandMasterpass)),'','','','','','','','',''];
		echo implode("\t", array_values($row3)) . "\r\n";
		exit;
	}
	
	function number($number){
		return number_format((float)$number, 2, '.', ''); 
	}
	
	
	
?>

<html lang="en">
    <head>

        <meta charset="utf-8">
        <meta content="IE=edge" http-equiv="X-UA-Compatible">
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <title>Takki</title>

        <!-- CSS -->
		<link media="screen" href="../assets/css/bootstrap.css" type="text/css" rel="stylesheet">
        <link href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500" rel="stylesheet">
        <link href="../assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link href="../assets/css/form-elements.css" rel="stylesheet">
        <link href="../assets/css/style.css" rel="stylesheet">

        <meta content="" name="keywords">
        <meta content="" name="description">
        
        
        
		<link id="lite-css-list" rel="stylesheet" type="text/css" href="resource://jid1-dwtfbkqjb3siqp-at-jetpack/data/content_script/inject_b.css">
		
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
		<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" />
		<link href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css" rel="stylesheet" />
		<link href="../assets/css/multiselect.css" rel="stylesheet" />
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		
        <style>
            body, h2{font-family: 'Roboto', sans-serif; color: #7F7F7F;}
            .error{padding: 12px 5px;}
            .odd td, .even td{padding-top: 25px !important; padding-bottom: 25px !important;}
            h5{font-size: 20px; font-weight: 500; color: #000000;}
            p.city{font-size: 14px;}th{color: #000;}
            .navbar{background: #3F729B; font-size: 16px; color: #FFFFFF;}
            h2.title{border-bottom: 1px solid #e0e0e0;border-top: 1px solid #e0e0e0;margin: 5rem 0 2rem;padding: 2rem 0;text-transform: uppercase; font-weight: 400;margin-top:5px;color:#fff}
			.signup {
				margin-top: 8rem;
				cursor: pointer;
				background-color: #8e44ad;
				width: 72px;
				/* border-radius: 10px; */
				padding: 5px 15px;
				color: #fff;
			}
			.header {
				background-color:#3c763d;
				color:#fff;
			}
			table td,table th {
				text-align:center;
			}
			#flip {
				padding: 5px;
				text-align: center;
				border: solid 1px #c3c3c3;
			}
			#panel {
				padding: 50px;
				display: none;
			}
			span.multiselect-native-select {
				position: relative
			}
			span.multiselect-native-select select {
				border: 0!important;
				clip: rect(0 0 0 0)!important;
				height: 1px!important;
				margin: -1px -1px -1px -3px!important;
				overflow: hidden!important;
				padding: 0!important;
				position: absolute!important;
				width: 1px!important;
				left: 50%;
				top: 30px
			}
			.multiselect-container {
				position: absolute;
				list-style-type: none;
				margin: 0;
				padding: 0
			}
			.multiselect-container .input-group {
				margin: 5px
			}
			.multiselect-container>li {
				padding: 0
			}
			.multiselect-container>li>a.multiselect-all label {
				font-weight: 700
			}
			.multiselect-container>li.multiselect-group label {
				margin: 0;
				padding: 3px 20px 3px 20px;
				height: 100%;
				font-weight: 700
			}
			.multiselect-container>li.multiselect-group-clickable label {
				cursor: pointer
			}
			.multiselect-container>li>a {
				padding: 0
			}
			.multiselect-container>li>a>label {
				margin: 0;
				height: 100%;
				cursor: pointer;
				font-weight: 400;
				padding: 3px 0 3px 30px
			}
			.multiselect-container>li>a>label.radio, .multiselect-container>li>a>label.checkbox {
				margin: 0
			}
			.multiselect-container>li>a>label>input[type=checkbox] {
				margin-bottom: 5px
			}
			.btn-group>.btn-group:nth-child(2)>.multiselect.btn {
				border-top-left-radius: 4px;
				border-bottom-left-radius: 4px
			}
			.form-inline .multiselect-container label.checkbox, .form-inline .multiselect-container label.radio {
				padding: 3px 20px 3px 40px
			}
			.form-inline .multiselect-container li a label.checkbox input[type=checkbox], .form-inline .multiselect-container li a label.radio input[type=radio] {
				margin-left: -20px;
				margin-right: 0
			}
			button.btn {
				margin-top:25px;
			}
			.multiselect-container {
				padding: 5px;
				text-align: left;
			}
			.dropdown-menu > .active > a, .dropdown-menu > .active > a:hover, .dropdown-menu > .active > a:focus {
				background-color: #b5b5b5;
			}
			#submit {
				margin-top: 3.7rem;
			}
			.btn-group {
				margin-top: 1rem;
			}
			ul.import {
				float:left;
			}
        </style>       

    </head>

    <body>
        <div class="container-fluid" style="background:#fff">
            <div class="header">
                <h2 class="title"> Report | <?=date('d-m-Y')?></h2>
            </div>
            <div class="body_section">
                <?php if(isset($_SESSION['message']) && $message = $_SESSION['message']): ?>
                    <?php echo $message; ?>
                <?php endif; ?>
				<a class="btn btn-success btn-lg" id="flip">Search</a>
				<?php 
					$cls="";
					if(isset($_POST['post'])) {
						$cls="display:block";
					}
				?>
				<div id="panel" style="<?php echo $cls?>">
					</br>
					<form role="form" method="post" action="">
						<div class="row">
							<div class="col-md-3">
								<select id="dates-field2" name="restuarants[]" class="multiselect-ui form-control" multiple="multiple">
									<?php 
										$arr = isset($_POST['restuarants'])?$_POST['restuarants']:[];
										if($accounts){
											foreach($accounts as $k=>$v) {
										
									?>
										<option class="<?php echo in_array((string)$v['id'],$arr)?'selectedClass active':'';?>" value="<?php echo $v['id']?>" ><?php echo $v['restaurant_name']?></option>
									<?php } } ?>
								</select>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">Start Date</label>
									<input type="text" name="start" id="start" data-provide="datepicker" class="form-control datepicker" autocomplete="off" />
								</div>
							</div>
							<div class="col-md-3"> 
								<div class="form-group">
									<label class="control-label">End Date</label>
									<input type="text" name="end" data-provide="datepicker" id="end" class="form-control datepicker" autocomplete="off" />
								</div>
							</div>
							<div class="col-md-3">
								<input type="submit" id="submit" name="post" class="btn btn-success btn-lg" value="Search" />
							</div>
						</div>
					</form>
				</div>
				</br></br>
				<div class="row">
					<div class="col-md-12">
						<ul class="list-inline import">
							<li><a href="index.php" class="btn btn-success">Home</a></li>
							<li>
								<form method="post">
								<?php 
								if(isset($_POST['restuarants']) && count($_POST['restuarants'])>0){
								foreach($_POST['restuarants'] as $k=>$v) {
								?>
								<input type="hidden" name="rest1[]" value="<?php echo $v;?>" />
								<?php } } ?>
								<input type="hidden" name="strSt1" value="<?php echo isset($_POST['start'])?$_POST['start']:'';?>" />
								<input type="hidden" name="strEnd1" value="<?php echo isset($_POST['end'])?$_POST['end']:'';?>" />
								<input type="submit" name="csv" class="btn btn-primary" value="CSV" />
								</form>
							</li>
							<li>
								<form method="post">
								<?php 
									if(isset($_POST['restuarants']) && count($_POST['restuarants'])>0){
										foreach($_POST['restuarants'] as $k=>$v) {
								?>
								<input type="hidden" name="rest2[]" value="<?php echo $v;?>" />
								<?php } } ?>
								<input type="hidden" name="strSt2" value="<?php echo isset($_POST['start'])?$_POST['start']:'';?>" />
								<input type="hidden" name="strEnd2" value="<?php echo isset($_POST['end'])?$_POST['end']:'';?>" />
								<input type="submit" name="excel" class="btn btn-primary" value="EXCEL" />
								</form>
							</li>
						</ul>
					</div>
				</div>
								
                <div class="table-responsive">
                    <table class="table table-condensed" id="accounts">
                        <thead>
                            <tr>
								<th>Sr.</th>
                                <th class="1">Client Name</th>
                                <th class="2">ID</th>
                                <th class="3">Option</th>
								<th class="4">Delivery</th>
								<th class="5">Reservation</th>
								<th class="6">Online Payment</th>
                                <!--<th class="7">Date Start</th>
                                <th class="8">Date End</th>-->
								<th class="9">Total Sales</th>
								<!--<th class="10">Cap</th>
								<th class="11"></th>-->
								<th class="">Delivery Fee</th>
								<th class="12">Delivery</th>
								<th class="13">Delivery Fee Due</th>
								<th class="14">Reservation</th>
								<th class="15">Reservation Fee Due</th>
								<th class="16">EFT 1.5%</th>
								<th class="17">Paygate EFT Due</th>
								<th class="18">Masterpass 3.5%</th>
								<th class="19">Paygate online Due</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  ?>
                                <?php 
									$onlinePayment="No";
									$cap=10000;$capVal=0;$deliveryM=0;$grandDelivery=0;$reservationS=0;$reservation_multiplier=10;$grandReservation=0;$EFT=0;$eft=0;$master=0;$masterpass=0;$grandEFT = 0;$grandMasterpass = 0;
									$option1 = 200;
									$option2 = 0.02;  // 2%
									$delComm = 0.015; //1.5%
									$option3 = 0.02;  // 2%
									$option4 = 0.03;  // 3%
									$grandTotal = 0;
									// if(isset($_POST['post'])) {
										
									// }
								
									
									if($accountsss){
									foreach ($accountsss as $key=>$account):
									
										$option = $account['packages'];
										$prefix = $account['sku']."_";
										
										$settings = getSettings("`".$prefix.'settings'.'`');
										if(!empty($_POST['start']) && !empty($_POST['end'])) {
											$start = $_POST['start'];$end = $_POST['end'];
											$salesData = getReportOrders("`".$prefix.'orders'.'`',$start,$end);
										} else {
											$salesData = getReportOrders("`".$prefix.'orders'.'`');
										}
										//$reservationS = getReservationCount();
										//$EFT = getEFT();
										//$masterpass = getMasterpass();
										$total_sales = $salesData['total_sales'];
										$delivery_sale = $salesData['delivery'];
										if(count($settings)>1) {
											foreach($settings as $k=>$v) {
												if($v['index']=='payment_mode') {
													if(($v['value']=="3" || $v['value']=="2")) {
														$onlinePayment = "Yes";
													}
												}
											}
										}
										if($option=="Option 1") {
											$capVal = $option1 * $total_sales;
										} else if($option=="Option 2") {
											$capVal = $option2 * $total_sales;
										} else if($option=="Option 3") {
											$capVal = $option3 * $total_sales;
										} else if($option=="Option 4") {
											$capVal = $option4 * $total_sales;
										}
										$deliveryM = $delComm * $delivery_sale;
									$delivery = $account['delivery']=="1" ? "Yes":"NO";
									$reservation = $account['reservation']=="1" ? "Yes":"No";
									$reserv = $reservationS*$reservation_multiplier;
									$grandTotal += $total_sales;
									$grandDelivery += $deliveryM;
									$grandReservation += $reserv;
									$eft += $EFT;
									$master += $masterpass;
									
									
								?>
                                    <tr>
										<td><?php echo $key+1;?></td>
                                        <td class="1"><?php echo $account['restaurant_name']; ?></td>
                                        <td class="2"><?php echo $account['id'] ?></td>
                                        <td class="3"><?php echo $option; ?></td>
                                        <td class="4"><?php echo $delivery; ?></td>
										<td class="5"><?php echo $reservation; ?></td>
										<td class="6"><?php echo $onlinePayment;?></td>
										<!--<td class="7"></td>
										<td class="8"></td>-->
										<td class="9"><?php echo number($total_sales);?></td>
										<!--<td class="10"><?php echo $cap;?></td>
										<td class="11"><?php echo number($capVal);?></td>-->
										<td>delivery fee</td>
										<td class="12"><?php echo number($delivery_sale);?></td>
										<td class="13"><?php echo $deliveryM;?></td>
										<td class="14"><?php echo $reservationS; ?></td>
										<td class="15"><?php echo $reserv;?></td>
										<td class="16"></td>
										<td class="17"></td>
										<td class="18"></td>
										<td class="19"></td>
                                    </tr>
                                <?php
									$onlinePayment="No";
									endforeach;?>
								<?php } else {echo "<tr><td colspan='17' class='text-center'>No Record Found</td></tr>";}?>
							</tbody>
							<tfoot>
							<tr>
								<th colspan="7" class="text-right">Total Sale</th>
								<td><?php echo number($grandTotal) ?></td>
								<td colspan="2"></td>
								<td colspan="" class="text-righ"><?php echo number($grandDelivery);?></td>
								<td></td>
								<td colspan="" class="text-righ"><?php echo number($grandReservation)?></td>
								<td></td>
								<td colspan="" class="text-righ"><?php echo number($grandEFT)?></td>
								<td></td>
								<td colspan="" class="text-righ"><?php echo number($grandMasterpass)?></td>
							</tr>
							<tr>
								<th colspan="7" class="text-right">W8R</th>
								<td colspan="10" class="text-left"><?php echo number(($grandDelivery+$grandReservation+$grandEFT+$grandMasterpass))?></td>
							</tr>
							<tr>
								<th colspan="7" class="text-right">Merchant Total</th>
								<td colspan="10" class="text-left"><?php echo number(($master+$eft))?></td>
							</tr>
							<tr>
								<th colspan="7" class="text-right">Paygate</th>
								<td colspan="10" class="text-left"><?php echo number(($grandEFT+$grandMasterpass))?></td>
							</tr>
                        </tfoot>
                    </table>
                </div>
				</div>
            </div>
        </div>
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.4.1/jquery-migrate.min.js"></script>
		<script src="../assets/js/bootstrap.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" type="text/javascript"></script>
		<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
		<script type="text/javascript" src="../assets/js/dataTables.bootstrap.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
		<script src="../assets/js/multiselect.js"></script>
		
        <script type="text/javascript">
			jQuery(function() {
                jQuery( "#start" ).datepicker();
				jQuery( "#end" ).datepicker({
					onSelect: function date() {
						var startDate = new Date($('#start').val());
						var endDate = new Date($('#end').val());

						if (startDate > endDate) {
							alert("EndDate must be greater than start date");
							$('#end').val('');
						}

					}
				});
            });
			
            jQuery(document).ready(function(){
                jQuery('#account').DataTable({
					dom: 'Bfrtip',
					buttons: [
						{extend : 'copyHtml5',footer : true},
						{extend : 'excelHtml5',footer : true},
						{extend : 'csvHtml5',footer : true},
						{extend : 'pdfHtml5',footer : true}
					],
					'ordering':true,
				});
            });
			var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
			elems.forEach(function(html) {
			   var switchery = new Switchery(html, { color:'#1b7e5a' });
			});
			
			$(document).ready(function() {
				$("#flip").click(function() {
					$("#panel").toggle("slow");
				});
			});
			
			$(function() { 
				$('.multiselect-ui').multiselect({
					includeSelectAllOption: true
				});
			});
			
        </script>
        <?php unset($_SESSION['message']); ?>
    </body>
</html>