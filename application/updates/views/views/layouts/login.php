<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Takki</title>

        <!-- CSS -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="<?php echo base_url('assets/font-awesome/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/form-elements.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css') ?>">

        <?php echo $template['metadata'] ?>
        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <script src="<?php echo base_url('assets/js/jquery.backstretch.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/js/scripts.js') ?>"></script>
        
        <!--[if lt IE 10]>
            <script src="<?php echo base_url('assets/js/placeholder.js') ?>"></script>
        <![endif]-->
        
        <!-- Favicon and touch icons -->
    </head>

    <body>

        <!-- Top content -->
        <div class="top-content">
        	
            <div class="inner-bg">
                <div class="container">
                    
                    <?php echo $template['partials']['flash_messages'] ?>
                    <?php echo $template['body'] ?>
                    
                </div>
            </div>
            
        </div>

    </body>

</html>