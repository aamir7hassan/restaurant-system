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
            <a class="btn btn-info btn-sm" href="<?php echo site_url('admin/reports/export'); ?>">
                <i class="fa fa-download "></i> 
                Download
            </a>
        </div>
    </div>
    <hr/>
        <?php require APPPATH.'views/admin/reports/navs.php'; ?>
        
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Waiter Shifts
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
                                    <!--<th>Shift Report</th>-->
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <?php foreach ($waiter_data as $waiter): ?>
                                    <tr class=""> 
                                        <td><?php echo $waiter->email ?></td>
                                        <td><?php echo Date('h:i:s A', strtotime($waiter->login)); ?></td>
                                        <td><?php echo Date('h:i:s A', strtotime($waiter->logout)); ?></td>
                                        <!--<td>
                                            <a class="btn btn-success" href="<?php echo site_url("admin/reports/index/".$waiter->waiter_id."/".$waiter->date); ?>"> Generate </a>
                                        </td>-->
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
</script>  
