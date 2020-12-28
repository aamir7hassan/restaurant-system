<div id="page-inner">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
        <li><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Group</a></li>
    </ul>
    
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
             <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default" style="margin-top: 30px">
                        <div class="panel-heading">
                            Product Sales
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
                                            <th>#</th>
                                            <th>Meal</th>
                                            <th>Category</th>
                                            <th>Total Price</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        <?php $i = 1; foreach ($get_product_sales as $product_sale): ?>
                                            <tr class="">
                                                <td><?php echo $i; ?></td>    
                                                <td><?php echo $product_sale->meal_name ?></td>
                                                <td><?php echo $product_sale->category ?></td>
                                                <td><?php echo CURRENCY_CODE." ".$product_sale->price ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($product_sale->order_time)); ?></td>
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

        <div role="tabpanel" class="tab-pane" id="profile">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default" style="margin-top: 30px">
                        <div class="panel-heading">
                            Product Sales
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="group-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Meal</th>
                                            <th>Category</th>
                                            <th>Total Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        <?php $i = 1; foreach ($get_product_sale_group as $product_sale): ?>
                                            <tr class="">
                                                <td><?php echo $i; ?></td>    
                                                <td><?php echo $product_sale->meal_name ?></td>
                                                <td><?php echo $product_sale->category ?></td>
                                                <td><?php echo CURRENCY_CODE." ".$product_sale->price ?></td>
                                                <td>
                                                    <a class="btn btn-success" href="<?php echo site_url("admin/reports/product_sale/".$product_sale->meal_id); ?>">
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

