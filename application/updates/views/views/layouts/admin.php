<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
    <title><?php echo $template['title'] ?></title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="<?php echo base_url( 'assets/css/custom.css' ); ?>" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/v/bs-3.3.7/jq-2.2.4/dt-1.10.15/datatables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
    <?php echo $template['metadata'] ?>
    <style type="text/css">
        .btn-success, .square-btn-adjust, .navbar-cls-top .navbar-brand, .active-menu, .square-btn-adjust:hover, .btn-success:hover, .btn-info, .btn-info:hover, .btn-default, .btn-default:hover, .btn-warning, .btn-warning:hover, .btn-info, .btn-info:hover{ background: <?php echo $this->config->item("system_colour") ? $this->config->item("system_colour") : "#EE9F2F"; ?> !important; border: <?php echo $this->config->item("system_colour") ? $this->config->item("system_colour") : "#EE9F2F"; ?>}
    </style>
    
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
                font-size: 16px;"><a href="<?php echo site_url('admin_takki/logout'); ?>" class="btn btn-danger square-btn-adjust">Logout</a> 
            </div>
        </nav>   
           <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center logo-box">
                        <?php if($this->config->item("store_logo")): ?>
                            <img src="<?php echo base_url( 'assets/images/'.$this->config->item("store_logo") ); ?>" class="user-image img-responsive"/>
                        <?php else: ?>
                            <img src="<?php echo base_url( 'assets/images/find_user.png' ); ?>" class="user-image img-responsive"/>
                        <?php endif; ?>
                    </li>
                    <li>
                        <a  href="<?php echo site_url('admin/'); ?>" class="<?php echo ( strtolower( $this->uri->segment(2) ) == '') ? 'active-menu' : ''; ?>"><i class="fa fa-dashboard fa-3x"></i>Dashboard</a>
                    </li>
                    <li>
                        <a  href="<?php echo site_url('admin/settings/'); ?>" class="<?php echo ( strtolower( $this->uri->segment(2) ) == 'settings') ? 'active-menu' : ''; ?>" ><i class="fa fa-cog fa-3x" aria-hidden="true"></i>Settings</a>
                    </li>
                    <li>
                        <a  href="<?php echo site_url('admin/attributes/'); ?>" class="<?php echo ( strtolower( $this->uri->segment(2) ) == 'attributes') ? 'active-menu' : ''; ?>"><i class="fa fa-list-ul fa-3x"></i>Product attributes</a>
                    </li>
                    <li>
                        <a  href="<?php echo site_url('admin/categories/'); ?>" class="<?php echo ( strtolower( $this->uri->segment(2) ) == 'categories') ? 'active-menu' : ''; ?>"><i class="fa fa-book fa-3x"></i>Categories</a>
                    </li>
                    <li>
                        <a  href="<?php echo site_url('admin/meals/available'); ?>" class="<?php echo ( strtolower( $this->uri->segment(2) ) == 'meals') ? 'active-menu' : ''; ?>"><i class="fa fa-spoon fa-3x"></i>Products</a>
                    </li>
                    <li>
                        <a  href="<?php echo site_url('admin/table/'); ?>" class="<?php echo ( strtolower( $this->uri->segment(2) ) == 'table') ? 'active-menu' : ''; ?>" ><i class="fa fa-th fa-3x" aria-hidden="true"></i>Location</a>
                    </li>
                    <li>
                        <a  href="<?php echo site_url('admin/waiters/'); ?>" class="<?php echo ( strtolower( $this->uri->segment(2) ) == 'waiters') ? 'active-menu' : ''; ?>" ><i class="fa fa-user fa-3x"></i>Clerks</a>
                    </li>
                    <li>
                        <a  href="<?php echo site_url('admin/reports/waiter_sales'); ?>" class="<?php echo ( strtolower( $this->uri->segment(2) ) == 'waiter_sales') ? 'active-menu' : ''; ?>"><i class="fa fa-bar-chart fa-3x"></i>Reports</a>
                    </li>
                    <li>
                        <a  href="<?php echo site_url('admin/foods/'); ?>" class="<?php echo ( strtolower( $this->uri->segment(2) ) == 'foods') ? 'active-menu' : ''; ?>"><i class="fa fa-coffee fa-3x" aria-hidden="true"></i>Food Types</a>
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

    
    
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>

    <script src="//cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="//cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>


    <script src="<?php echo base_url( 'assets/js/jquery.metisMenu.js' ); ?>"></script>
    <script src="<?php echo base_url( 'assets/js/custom.js' ); ?>"></script>
</body>
</html>
