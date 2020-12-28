<?php
	
	$isSecure = false;
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
		$isSecure = true;
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
		$isSecure = true;
	}
	$protocol = $isSecure ? 'https' : 'http';
	$domain = $protocol."://".$_SERVER['SERVER_NAME']."/legal/";
?>
<?php require_once 'actions.php'; ?>
<html lang="en">
    <head>

        <meta charset="utf-8">
        <meta content="IE=edge" http-equiv="X-UA-Compatible">
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <title>W8R</title>

        <!-- CSS -->
        <link href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500" rel="stylesheet">
        <link href="../assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="../assets/css/form-elements.css" rel="stylesheet">
        <link href="../assets/css/style.css" rel="stylesheet">

        <meta content="" name="keywords">
        <meta content="" name="description">
        <link href="../store_1/account/create.html" rel="canonical">
        <link media="screen" href="../assets/css/bootstrap.css" type="text/css" rel="stylesheet">
        <link media="screen" href="../assets/css/waiter.css" type="text/css" rel="stylesheet">
        <link media="screen" href="../assets/css/cross_browser/webkit.css" type="text/css" rel="stylesheet">
        <!--[if IE 6]><link rel="stylesheet" type="text/css" href="../assets/css/cross_browser/ie6.css" media="screen" /><![endif]-->
        <!--[if IE 7]><link rel="stylesheet" type="text/css" href="../assets/css/cross_browser/ie7.css" media="screen" /><![endif]-->
        <!--[if IE 8]><link rel="stylesheet" type="text/css" href="../assets/css/cross_browser/ie8.css" media="screen" /><![endif]-->
        <!--[if IE 9]><link rel="stylesheet" type="text/css" href="../assets/css/cross_browser/ie9.css" media="screen" /><![endif]-->
        <script src="../assets/js/jquery-1.11.1.min.js" type="text/javascript"></script><link id="lite-css-list" rel="stylesheet" type="text/css" href="resource://jid1-dwtfbkqjb3siqp-at-jetpack/data/content_script/inject_b.css">
        <script src="../assets/js/bootstrap.min.js" type="text/javascript"></script>
        <!--[if lt IE 9]><script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <script type="text/javascript"></script>
        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <script src="../assets/js/scripts.js"></script>
        
        <!--[if lt IE 10]>
            <script src="../assets/js/placeholder.js"></script>
        <![endif]-->
        
        <!-- Favicon and touch icons -->
        <style>
            .error{padding: 12px 5px;}
            button.btn{background: #00a0c8;}
            body{background: #FFFFFF;}
            .alert-success {
                background: #00a0c8 none repeat scroll 0 0;
                border-color: #00a0c8;
                color: #fff;
            }
        </style>
    </head>

    <body>

        <!-- Top content -->
        <div class="top-content">
        	
            <div class="">
                <div class="container">
                    <?php if(isset($message) && !empty($message)): ?>
                    <div class="row">
                        <div class="alert alert-success">
                            <?php echo $message; ?>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="row">
                        <div class="">
                            <div class="form-top">
                                <div class="form-top-left">
                                    <h3>Create your account</h3>
                                    <p>Please enter your details below</p>
                                </div>
                                <div class="form-top-right">
                                    <i class="fa fa-lock"></i>
                                </div>
                            </div>
                            <div class="form-bottom">
                                
                                <div class="error">
                                    <?php echo implode("<br/>", $errors);?>
                                </div>
                                
                                
                                <form id="loginform" class="form-signin login-form" accept-charset="utf-8" method="post" action="">            
                                    <div class="form-group">
                                        <label for="form-username" class="sr-only">Name</label>                        
                                        <input type="text" id="form-username" class="form-username form-control" placeholder="Name" name="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="form-username" class="sr-only">Surname</label>                        
                                        <input type="text" id="form-username" class="form-username form-control" placeholder="Surname" name="surname" value="<?php echo isset($_POST['surname']) ? $_POST['surname'] : ''?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="form-username" class="sr-only">Restaurant name</label>                        
                                        <input type="text" id="form-username" class="form-username form-control" placeholder="Restaurant name" name="restaurant_name" value="<?php echo isset($_POST['restaurant_name']) ? $_POST['restaurant_name'] : ''?>"> 
                                    </div>

                                    <div class="form-group">
                                        <label for="form-username" class="sr-only">Email</label>                        
                                        <input type="text" id="form-username" class="form-username form-control" placeholder="Email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="form-username" class="sr-only">Phone</label>                        
                                        <input type="text" id="form-username" class="form-username form-control" placeholder="Phone" name="phone" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="form-username" class="sr-only">Address</label>                        
                                        <input type="text" id="form-username" class="form-username form-control" placeholder="address,city,country" name="city" value="<?php echo isset($_POST['city']) ? $_POST['city'] : ''?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="form-username" class="sr-only">Packages</label>
                                        <select name="packages" class="form-control">
                                            <option value="">Select Package</option>
                                            <option value="Option 1">Option 1</option>
                                            <option value="Option 2">Option 2</option>
                                            <option value="Option 3">Option 3</option>
                                            <option value="Option 4">Option 4</option>
                                        </select>
                                    </div>

                                    <!--<div class="form-group">
                                        <label for="form-username" class="sr-only">Password</label>                        
                                        <input type="password" id="" class="form-control" placeholder="Password" name="password">
                                    </div>-->

                                    <p class="text-center">By clicking "Create your account" below you agree to our <a target="_blank" style="color:#00A0C8" href="<?=$domain?>Terms-Get-Ordering.docx">W8R Agreement.</a></p>
                                    <button class="btn" type="submit">Create your account</button>
                                </form>        
                            </div>
                        </div>
    
                        </script>
    
                    </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </body>
</html>