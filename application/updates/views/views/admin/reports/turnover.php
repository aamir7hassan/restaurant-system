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
            <a class="btn btn-info btn-sm" href="<?php echo site_url('admin/reports/download'); ?>">
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
                    Table Turnover
                </div>
                <div class="panel-body">
            <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Table</th>
                                    <th>Turnover</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <?php $i = 1; foreach ($turnover_data as $turnover): ?>
                                        <tr class="">
                                            <td><?php echo $i; ?></td>    
                                            <td><?php echo $turnover->name; ?></td>
                                            <td><?php echo $turnover->total; ?></td>
                                            <td><?php echo $turnover->date; ?></td> 
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

 <script>
     $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
                      
            if($('#min').val() == '' || $('#max').val() == '')
                return true;
            
            var min = new Date( $('#min').val() );
            var max = new Date( $('#max').val());
            var date = new Date( data[3] ) || 0; // use data for the age column
            
            if(date == 0) return true;
            if(min <= date && max >= date){ return true; }


            return false;
        }
    );
    $(document).ready(function () {
        var table = $('#dataTables-example').DataTable(); 
        $('#min, #max').datepicker({ 
            dateFormat: 'yy-mm-dd',
            onSelect: function(dateText, inst) {
                table.draw();
            }
        });
    });
</script>
