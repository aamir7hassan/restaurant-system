<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
    <title><?php echo $template['title'] ?></title>
    <link href="<?php echo base_url( 'assets/css/bootstrap.css' ); ?>" rel="stylesheet" />
    <link href="<?php echo base_url( 'assets/css/font-awesome.min.css' ); ?>" rel="stylesheet" />
    <link href="<?php echo base_url( 'assets/css/custom.css' ); ?>" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    
    <script src="<?php echo base_url( 'assets/js/jquery-1.11.1.min.js' ); ?>"></script>
    <script src="<?php echo base_url( 'assets/js/bootstrap.min.js' ); ?>"></script>
    <script src="<?php echo base_url( 'assets/js/jquery.metisMenu.js' ); ?>"></script>
    <script src="<?php echo base_url( 'assets/js/custom.js' ); ?>"></script>
    
    <?php echo $template['metadata'] ?>
    
    
</head>
<body> 
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Takki Admin </a> 
            </div>
            <div style="color: white;
                padding: 15px 50px 5px 50px;
                float: right;
                font-size: 16px;"> Last access : 30 May 2014 &nbsp; <a href="#" class="btn btn-danger square-btn-adjust">Logout</a> 
            </div>
        </nav>   
           <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <img src="<?php echo base_url( 'assets/images/find_user.png' ); ?>" class="user-image img-responsive"/>
                    </li>
                    <li>
                        <a  href="<?php echo site_url('admin/meals/'); ?>" class="<?php echo ( strtolower( $this->uri->segment(2) ) == 'attributes') ? 'active-menu' : ''; ?>"><i class="fa fa-list-ul fa-3x"></i>Meals</a>
                    </li>
                    <li>
                        <a  href="<?php echo site_url('admin/tables/'); ?>" class="<?php echo ( strtolower( $this->uri->segment(2) ) == 'manufacturer') ? 'active-menu' : ''; ?>" ><i class="fa fa-wrench fa-3x"></i>Tables</a>
                    </li>
                    <li>
                        <a  href="<?php echo site_url('admin/waiters/'); ?>" class="<?php echo ( strtolower( $this->uri->segment(2) ) == 'categories') ? 'active-menu' : ''; ?>" ><i class="fa fa-qrcode fa-3x"></i>Waiters</a>
                    </li>
                    <li  >
                        <a  href="<?php echo site_url('admin/reports/'); ?>" class="<?php echo ( strtolower( $this->uri->segment(2) ) == 'users') ? 'active-menu' : ''; ?>"><i class="fa fa-user fa-3x"></i>Reports</a>
                    </li>	
                    <li  >
                        <a  href="<?php echo site_url('admin/get_started/'); ?>" class="<?php echo ( strtolower( $this->uri->segment(2) ) == 'products') ? 'active-menu' : ''; ?>" ><i class="fa fa-table fa-3x"></i>Get Started</a>
                    </li>
                    <li  >
                        <a  href="<?php echo site_url('admin/chat_report/'); ?>" class="<?php echo ( strtolower( $this->uri->segment(2) ) == 'csv') ? 'active-menu' : ''; ?>" ><i class="glyphicon glyphicon-share fa-3x"></i>Chat report</a>
                    </li>	
                    	
                </ul>
               
            </div>
            
        </nav>  
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <?php echo $template['partials']['flash_messages'] ?>
            <?php echo $template['body'] ?>
        </div>
        </div>

    
   
</body>
</html>
