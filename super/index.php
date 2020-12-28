<?php	
	session_start(); 
	define('__EXEC__', 1); 
	require_once 'inc/actions.php'; 
	
	//$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http").$_SERVER['HTTP_HOST'].'/restaurant/signup.php';
	$link = '../signup';
	if(!isset($_SESSION['superuser'])) {
		header('location:login.php');
	}
	// echo "<pre>";
	// var_dump($accounts);
	// echo "</pre>";die;
	
?>
<html lang="en">
    <head>

        <meta charset="utf-8">
        <meta content="IE=edge" http-equiv="X-UA-Compatible">
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <title>Takki</title>

        <!-- CSS -->
        <link href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500" rel="stylesheet">
        <link href="../assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="../assets/css/form-elements.css" rel="stylesheet">
        <link href="../assets/css/style.css" rel="stylesheet">

        <meta content="" name="keywords">
        <meta content="" name="description">
        <link media="screen" href="../assets/css/bootstrap.css" type="text/css" rel="stylesheet">
        
        <script src="../assets/js/jquery-1.11.1.min.js" type="text/javascript"></script><link id="lite-css-list" rel="stylesheet" type="text/css" href="resource://jid1-dwtfbkqjb3siqp-at-jetpack/data/content_script/inject_b.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
        <style>
            body, h2{font-family: 'Roboto', sans-serif; color: #7F7F7F;}
            .error{padding: 12px 5px;}
            .odd td, .even td{padding-top: 25px !important; padding-bottom: 25px !important;}
            h5{font-size: 20px; font-weight: 500; color: #000000;}
            p.city{font-size: 14px;}th{color: #000;}
            .navbar{background: #3F729B; font-size: 16px; color: #FFFFFF;}
            h2.title{border-bottom: 1px solid #e0e0e0;border-top: 1px solid #e0e0e0;margin: 5rem 0 2rem;padding: 2rem 0;text-transform: uppercase; font-weight: 400;margin-top:5px;}
			.signup {
				margin-top: 8rem;
				cursor: pointer;
				background-color: #8e44ad;
				width: 72px;
				/* border-radius: 10px; */
				padding: 5px 15px;
				color: #fff;
			}
        </style>
        <link href="../assets/css/dataTables.bootstrap.css" rel="stylesheet">
		<link href="../assets/css/switchery.min.css" rel="stylesheet">
        <script type="text/javascript" src="../assets/js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="../assets/js/dataTables.bootstrap.js"></script>
		<script type="text/javascript" src="../assets/js/switchery.min.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>

    </head>

    <body>

        <nav class="navbar navbar-fixed-top scrolling-navbar double-nav top-nav-collapse">
            <div class="">
                <h1>My Takki</h1>
            </div>
        </nav>
        
        <div class="container-fluid">
            <div class="header">
				
                <p onClick="window.location='<?=$link?>'" class="signup text-left" style="margin-top:8rem">Signup</p>
				  <p onClick="window.location='report.php'" class="signup text-left" style="margin-top:0rem">Report</p>
				<?php 
					/*if(isset($_SESSION['superuser'])) {
						echo '<p onClick="window.location=\'logout.php\'" class="signup text-left" style="">Logout</p>';
					}*/
				?>
                <h2 class="title">Registered Stores </h2> 
				
            </div>
            <div class="body_section">
                <?php if(isset($_SESSION['message']) && $message = $_SESSION['message']): ?>
                    <?php echo $message; ?>
                <?php endif; ?>
                
                <div class="table-responsive">
                    <table class="table table-hover" id="accounts">
                        <thead>
                            <tr>
                                <th>Store</th>
                                <!--<th>Customer</th> -->
                                <th>Email</th>
                                <th>Phone</th>
								<th>Password</th>
								<th>Reservation</th>
								<th>Delivery</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($accounts): ?>
                                <?php foreach ($accounts as $account): ?>
                                    <tr>
                                        <td>
                                            <h5><?php echo $account['restaurant_name']; ?></h5>
                                            <p class="city"><!--in <?php //echo $account['city']; ?> --></p>
                                        </td>
                                        <!--<td><?php //echo $account['name'].' '.$account['surname']; ?></td>-->
                                        <td><?php echo $account['email']; ?></td>
                                        <td><?php echo $account['phone']; ?></td>
										<td><?php echo $account['pass']; ?></td>
                                       <td><input type="checkbox" data-reservation = "<?=$account['reservation']?>" <?php echo $account['reservation']=="1"?'checked':'';?> data-ids="<?=$account['id']?>" data-sku="<?php echo $account['sku'];?>" class="js-switch reservation" /></td>
									   <?php 
										$deli = $account['delivery']=="1"?"checked":"";
									   ?>
									   <td><input type="checkbox" data-delivery = "<?=$account['delivery']?>" data-ids="<?=$account['id']?>" class="js-switch delivery" <?=$deli?> data-sku="<?php echo $account['sku'];?>" /></td>
                                        <td><?php echo Date("d-m-Y H:i", strtotime($account['created'])); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="?action=<?php echo $account['status'] == 1 ? "disable" : 'enable'; ?>&id=<?php echo  $account['id']; ?>" class="btn btn-<?php echo $account['status'] == 1 ? "warning" : 'success'; ?>"><?php echo $account['status'] == 1 ? "Disable" : 'Enable'; ?></a>
                                                <a href="?action=delete&id=<?php echo $account['id']; ?>" class="btn btn-danger">Delete</a>
                                                <a href="?action=duplicate&id=<?php echo $account['id']; ?>" class="btn btn-success">Duplicate</a>
                                                <div class="clearfix"></div>
                                            </div>
                                            <br/>
                                            <div class="btn-group" style="padding-top: 10px;">
                                                <a href="../<?php echo $account['sku']; ?>/customer/" target="_blank" class="btn btn-blue">Front End</a>
                                                <a href="../<?php echo $account['sku']; ?>/admin/" target="_blank" class="btn btn-orange">Admin</a>
                                                <a href="../<?php echo $account['sku']; ?>/waiters/" target="_blank" class="btn btn-purple">Clerk</a>
                                            </div>
                                            <br/>
                                            <div class="btn-group" style="padding-top: 10px;">
                                                <a href="?action=<?php echo "option1"; ?>&id=<?php echo $account['id']; ?>" class="btn btn-<?php echo $account['packages'] == 'Option 1' ? "danger" : 'success'; ?>" <?php echo $account['packages'] == 'Option 1' ? "disabled" : ''; ?>>
                                                    Option 1
                                                </a>
                                                <a href="?action=<?php echo "option2"; ?>&id=<?php echo $account['id']; ?>" class="btn btn-<?php echo $account['packages'] == 'Option 2' ? "danger" : 'success'; ?>" <?php echo $account['packages'] == 'Option 2' ? "disabled" : ''; ?>>
                                                Option 2
                                                </a>
                                                <a href="?action=<?php echo "option3"; ?>&id=<?php echo $account['id']; ?>" class="btn btn-<?php echo $account['packages'] == 'Option 3' ? "danger" : 'success'; ?>" <?php echo $account['packages'] == 'Option 3' ? "disabled" : ''; ?>>
                                                Option 3
                                                </a>

                                                <a href="?action=<?php echo "option4"; ?>&id=<?php echo $account['id']; ?>" class="btn btn-<?php echo $account['packages'] == 'Option 4' ? "danger" : 'success'; ?>" <?php echo $account['packages'] == 'Option 4' ? "disabled" : ''; ?>>
                                                Option 4
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery('#accounts').DataTable({
					"pageLength": 25
				});
            });
			var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
			elems.forEach(function(html) {
			   var switchery = new Switchery(html, { color:'#1b7e5a' });
			});
			
			$(document).on('change','.delivery',function(e){
				var delivery = $(this).data('delivery');
				var id = $(this).data('ids');
				var sku = $(this).data('sku');
				window.location.href="?action=delivery&id="+id+"&delivery="+delivery+"&sku="+sku;
			});
			$(document).on('change','.reservation',function(e){
				var reservation = $(this).data('reservation');
				var id = $(this).data('ids');
				var sku = $(this).data('sku');
				window.location.href="?action=reservation&id="+id+"&reservation="+reservation+"&sku="+sku;
			});
        </script>
        <?php unset($_SESSION['message']); ?>
    </body>
</html>