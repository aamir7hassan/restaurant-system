<style>
    tfoot {
        background-color: #c4c6c780;
        color: #000;
    }
</style>
<div id="page-inner">
    <div class="row">
        <div class="col-md-11">
            <h2>Reports</h2>   
        </div>
        <div class="clearfix"></div>
    </div>       
    <hr /> 

    <div class="row">
        <div class="col-sm-12">
            <h2>Download Reports</h2>
            <a class="btn btn-info btn-sm" href="<?php echo site_url('admin/reports/exports_data'); ?>">
                <i class="fa fa-download "></i> 
                Download
            </a>
        </div>
    </div>
    <hr/>
    <?php require APPPATH.'views/admin/reports/navs.php'; ?>

    <ul class="nav nav-tabs" role="tablist" id="myTabs">
        <li class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Individual</a></li>
        <li><a href="#group" aria-controls="group" role="tab" data-toggle="tab">Group</a></li>
        <li><a href="#cash_up" aria-controls="cash_up" role="tab" data-toggle="tab">Cash Up</a></li>
    </ul>
    
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default" style="margin-top: 30px">
                        <div class="panel-heading">
                            Individual Shifts
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <div class="row" style="margin-bottom: 30px">
                                    <div class="col-md-2">From: <input name="min" id="min" type="text" class="form-control" autocomplete="off"></div>
                                    <div class="col-md-2">To: <input name="max" id="max" type="text" class="form-control" autocomplete="off"></div>
                                </div>
                        
                                <table class="table table-striped table-bordered table-hover" id="example">
                                    <thead>
                                        <tr>
                                            <th>Waiter</th>
                                            <th>Shift Start</th>
                                            <th>Shift End</th>
                                            <th style="display: none"></th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        <?php foreach ($waiter_sales as $waiter): ?>
                                            <tr class=""> 
                                                <td><?php echo $waiter->email ?></td>
                                                <td><?php echo Date('h:i:s A', strtotime($waiter->login)); ?></td>
                                                <td><?php echo Date('h:i:s A', strtotime($waiter->logout)); ?></td>
                                                <td style="display: none"></td>
                                                <td><?php echo date('Y/m/d', strtotime($waiter->date)); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div role="tabpanel" class="tab-pane" id="group">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default" style="margin-top: 30px">
                        <div class="panel-heading">
                            Group Shifts
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="group-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Waiter</th>
                                            <th>Total Price</th>
                                            <th>Avg Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        <?php $i = 1; foreach ($waiter_sales_groups as $waiter_sale):  
                                        ?>
                                            <tr class="">
                                                <td><?php echo $i; ?></td>    
                                                <td><?php echo $waiter_sale->email ?></td>
                                                <td><?php echo CURRENCY_CODE." ".$waiter_sale->price ?></td>
                                                <td><?php echo date('H:i:s', $waiter_sale->avg_time); ?></td>
                                                <td>
                                                    <a class="btn btn-success" href="<?php echo site_url("admin/reports/waiter_sale/".$waiter_sale->user_id); ?>">
                                                        View Report
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php ++$i; endforeach; ?>
                                    </tbody>
                                </table>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div role="tabpanel" class="tab-pane" id="cash_up">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default" style="margin-top: 30px">
                        <div class="panel-heading">
                            Cash Up
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <div class="row" style="margin-bottom: 30px">
                                    <div class="col-md-2">From: <input name="cash_min" id="cash_min" type="text" class="form-control" autocomplete="off"></div>
                                    <div class="col-md-2">To: <input name="cash_max" id="cash_max" type="text" class="form-control" autocomplete="off"></div>
                                </div>

                                <table class="table table-striped table-bordered table-hover" id="cashs_up">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Waiter</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        <?php $i = 1; foreach ($get_cash_up as $cash_up):  
                                        ?>
                                            <tr class="">
                                                <td><?php echo $i; ?></td>    
                                                <td><?php echo $cash_up->first_name .' '. $cash_up->last_name ?></td>
                                                <td><?php echo date('j F Y', strtotime($cash_up->date)); ?></td>
                                                <td>
                                                    <a class="btn btn-success" href="<?php echo site_url("admin/reports/cash_up/".$cash_up->waiter_id); ?>">
                                                        View Report
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php ++$i; endforeach; ?>
                                    </tbody>
                                </table>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) {
            var min = $('#min').datepicker("getDate");
            var max = $('#max').datepicker("getDate");
            var startDate = new Date(data[4]);
            if (min == null && max == null) { return true; }
            if (min == null && startDate <= max) { return true;}
            if(max == null && startDate >= min) {return true;}
            if (startDate <= max && startDate >= min) { return true; }
            return false;
        }
        );

    
        $("#min").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });
        $("#max").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });
        var table = $('#example').DataTable({
            dom: 'Bfrtip',
            buttons: [
               'csv', 'excel', 'pdf', 'print'
            ],
        });

        // Event listener to the two range filtering inputs to redraw on input
        $('#min, #max').change(function () {
            table.draw();
        });
    });

    $(document).ready(function(){
        $('#group-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
               'csv', 'excel', 'pdf', 'print'
            ],
        });
    });
</script> 
