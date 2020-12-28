<?php	
	session_start(); 
	if(isset($_SESSION['superuser'])) {
		header('location:index.php');
	}
	define('__EXEC__', 1);
	// $host1 = 'localhost';
	// $user1 = 'sevekzpz_restaurant';
	// $pass1 = 'c@PgAo{BF!Kl';
	// $dbase1 = 'sevekzpz_restaurant';
	$host1 = 'takki-db.cgqeqdugauga.us-east-2.rds.amazonaws.com';
	$user1 = 'admin'; 
	$pass1 = 'Temp1234'; 
	$dbase1 = 'takki';
	$con = mysqli_connect($host1,$user1,$pass1,$dbase1);
	if(!$con) {
		echo 'database not connected';
	}
	if(isset($_POST['submitB'])) {
		$email = $_POST['email'];
		$pass  = $_POST['password'];
		
		$q = "SELECT * FROM superuser where email  = '".$email."' && password = '".sha1($pass)."'";
		$res = mysqli_query($con,$q);
		$row = mysqli_fetch_assoc($res);
		//var_dump($row);die;
		if($row!=null) {
			$_SESSION['superuser'] = '1';
			echo "<script>window.location.href='index.php'</script>";
		}
	}
?>
<html lang="en"> 
    <head>

        <meta charset="utf-8">
        <meta content="IE=edge" http-equiv="X-UA-Compatible">
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <title>Takki Login</title>

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
			.blok {
				margin-top:10rem;
			}
        </style>
        <link href="../assets/css/dataTables.bootstrap.css" rel="stylesheet">
        <script type="text/javascript" src="../assets/js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="../assets/js/dataTables.bootstrap.js"></script>
        

    </head>

    <body>

        <nav class="navbar navbar-fixed-top scrolling-navbar double-nav top-nav-collapse">
            <div class="">
                <h1>My Takki</h1>
            </div>
        </nav>
        
        <div class="container blok" >
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					
					<div id="loginbox" style="margin-top:50px;" class="mainbox">                    
						<div class="panel panel-info" >
							<div class="panel-heading">
								<div class="panel-title">Login</div>
								<!--<div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Forgot password?</a></div>-->
							</div>
							<div style="padding-top:30px" class="panel-body" >
								<div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
								<form action="" method="post" id="loginform" class="form-horizontal" role="form">
									<div style="margin-bottom: 25px" class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
												<input id="login-username" type="text" class="form-control" name="email" value="" placeholder="Email" required>                                        
											</div>
										
									<div style="margin-bottom: 25px" class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
												<input id="login-password" type="password" class="form-control" name="password" placeholder="Password" required>
											</div>
									<!--<div class="input-group">
									  <div class="checkbox">
										<label>
										  <input id="login-remember" type="checkbox" name="remember" value="1"> Remember me
										</label>
									  </div>
									</div>-->
										<div style="margin-top:10px" class="form-group">
											<!-- Button -->

											<div class="col-sm-12 controls">
											<input type="submit" name="submitB" class="btn btn-success" value="Login" />
											  
											  <!--<a id="btn-fblogin" href="#" class="btn btn-primary">Login with Facebook</a>-->

											</div>
										</div>
										<!--<div class="form-group">
											<div class="col-md-12 control">
												<div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
													Don't have an account! 
												<a href="#" onClick="$('#loginbox').hide(); $('#signupbox').show()">
													Sign Up Here
												</a>
												</div>
											</div>
										</div> -->   
									</form>   
								</div>                     
							</div>  
					</div>
				
				</div>
			</div>
        </div>
        
        <script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery('#accounts').DataTable();
            });
        </script>
        <?php unset($_SESSION['message']); ?>
    </body>
</html>