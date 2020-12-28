    <div class="row">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.js"></script>
        <script>
            $(function() {
                $( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
            });
        </script>

        <div class="col-lg-12">
            <div class="page-title">
                <h1>Dashboard
                    <small>Takki Admin</small>
                </h1>
                <ol class="breadcrumb">
                    <li><i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                    </li>
                    <li class="active"></li>
                </ol>
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
	
    <div class="row">
    
    <form class="form-inline" method="post">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-md-6"><h3 style="float:right; margin-top: 0;">Search By:</h3></div>
                <div class="col-md-4" style="padding-top: 3px;">
                    <label class="radio-inline">
                        <input type="radio" name="cal_search" id="inlineRadio1" value="Day"> Day
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="cal_search" id="inlineRadio2" value="Week"> Week
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="cal_search" id="inlineRadio3" value="Month"> Month
                    </label>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
                <div class="form-group">
                    <label for="date_from">From:</label>
                    <input type="text" name="date_from" class="form-control datepicker" id="date_from" value="<?php echo isset($_POST['date_from']) ? $_POST['date_from'] : ''; ?>" autocomplete="off">
                </div>
        </div>

        <div class="col-sm-3">
            <form class="form-inline" method="post">
                <div class="form-group">
                    <label for="date_to">To:</label>
                    <input type="text" name="date_to" class="form-control datepicker" id="date_to" value="<?php echo isset($_POST['date_to']) ? $_POST['date_to'] : ''; ?>" autocomplete="off">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
        </div>
        <div class="clearfix"></div>
        
        </form>
    </div>
    
    <!-- /.row -->

    <div class="row"><br/><br/>
        <div class="col-lg-3 col-sm-6">
            <div class="circle-tile">
                <a href="#">
                    <div class="circle-tile-heading dark-blue">
                        <i class="fa fa-users fa-fw fa-3x"></i>
                    </div>
                </a>
                <div class="circle-tile-content dark-blue">
                    <div class="circle-tile-description text-faded">
                        Customers Today
                    </div>
                    <div class="circle-tile-number text-faded">
                        <?php echo $users_today; ?>
                    </div>
                    <a href="#" class="circle-tile-footer"></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="circle-tile">
                <a href="#">
                    <div class="circle-tile-heading green">
                        <i class="fa fa-money fa-fw fa-3x"></i>
                    </div>
                </a>
                <div class="circle-tile-content green">
                    <div class="circle-tile-description text-faded">
                        Total Customers
                    </div>
                    <div class="circle-tile-number text-faded">
                        <?php echo $users_total; ?>
                    </div>
                    <a href="#" class="circle-tile-footer"></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="circle-tile">
                <a href="#">
                    <div class="circle-tile-heading orange">
                        <i class="fa fa-bell fa-fw fa-3x"></i>
                    </div>
                </a>
                <div class="circle-tile-content orange">
                    <div class="circle-tile-description text-faded">
                        Total Locations
                    </div>
                    <div class="circle-tile-number text-faded">
                        <?php echo $tables_total; ?>
                    </div>
                    <a href="#" class="circle-tile-footer"></a>
                </div>
            </div>
        </div>


        <div class="col-lg-3 col-sm-6">
            <div class="circle-tile">
                <a href="#">
                    <div class="circle-tile-heading purple">
                        <i class="fa fa-comments fa-fw fa-3x"></i>
                    </div>
                </a>
                <div class="circle-tile-content purple">
                    <div class="circle-tile-description text-faded">
                        Clerks
                    </div>
                    <div class="circle-tile-number text-faded">
                        <?php echo $total_waiter; ?>
                    </div>
                    <a href="#" class="circle-tile-footer"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="portlet portlet-blue">
                <div class="portlet-heading">
                    <div class="portlet-title">
                        <h4>Customer - Amount chart <?php echo Date('Y'); ?></h4>
                    </div>
                    <div class="portlet-widgets">
                        <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                        <span class="divider"></span>
                        <a data-toggle="collapse" data-parent="#accordion" href="#barChart"><i class="fa fa-chevron-down"></i></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div id="barChart" class="panel-collapse collapse in">
                    <div class="portlet-body">
                        <div id="morris-chart-area"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="row">
        <!-- /.col-lg-12 -->

        <!-- Bar Chart Example -->
        <div class="col-lg-6">
            <div class="portlet portlet-blue">
                <div class="portlet-heading">
                    <div class="portlet-title">
                        <h4>Customer - Amount chart <?php echo Date('Y'); ?></h4>
                    </div>
                    <div class="portlet-widgets">
                        <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                        <span class="divider"></span>
                        <a data-toggle="collapse" data-parent="#accordion" href="#barChart"><i class="fa fa-chevron-down"></i></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div id="barChart" class="panel-collapse collapse in">
                    <div class="portlet-body">
                        <div id="morris-chart-bar"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.col-lg-6 -->

        <!-- Donut Chart Example -->
        <div class="col-lg-6">
            <div class="portlet portlet-orange">
                <div class="portlet-heading">
                    <div class="portlet-title">
                        <h4>Customer Arrival <?php echo Date('Y'); ?></h4>
                    </div>
                    <div class="portlet-widgets">
                        <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                        <span class="divider"></span>
                        <a data-toggle="collapse" data-parent="#accordion" href="#donutChart"><i class="fa fa-chevron-down"></i></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div id="donutChart" class="panel-collapse collapse in">
                    <div class="portlet-body">
                        <div class="portlet-body">
                            <div id="morris-chart-donut"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.col-lg-6 -->
        <script type="text/javascript" src="<?php echo base_url('assets/js/morris.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/js/morris-demo-data.js'); ?>"></script>
    </div>
