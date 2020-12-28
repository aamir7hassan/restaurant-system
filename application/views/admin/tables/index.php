<div id="page-inner">
    <div class="row">
        <div class="col-md-11">
            <h2>Locations</h2>   
            <h5>Available locations!</h5>
        </div>
        <div class="col-md-1">
            <a href="<?php echo site_url('admin/table/new_table/'); ?>" class="btn btn-success btn-sm new">
                <i class="fa fa-sign-out"></i>
                Create New
            </a>
        </div>
        <div class="clearfix"></div>
    </div>       
    <hr />

    <div class="row">
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    Locations
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Location</th>
                                    <th>Seats</th>
                                    <th>QR code</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody> 
                                
                                <?php foreach ($tables as $key => $table): ?>
                                    <tr class="<?php ($key%2 == 0) ? 'odd' : 'even'; ?> gradeX">
                                        <td><?php echo $table->id; ?></td>    
                                        <td><?php echo $table->name; ?></td>
                                        <td><?php echo $table->seats ?></td>
                                        <td><img src="<?php echo site_url('qr/get_table/'.$table->unique); ?>" /></td>
                                        <td>
                                            <a class="btn btn-info btn-sm" href="<?php echo site_url('admin/table/new_table/'.$table->id); ?>">
                                                <i class="fa fa-edit "></i> 
                                                Edit
                                            </a>
                                            <a class="btn btn-danger btn-sm delete" data-toggle="modal" data-target="#myModal" data-id="<?php echo $table->id; ?>">
                                                <i class="fa fa-times"></i>
                                                Delete
                                            </a>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                
                                
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <!--End Advanced Tables -->
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#dataTables-example').dataTable(); 
        });
        $(document).on("click", ".delete", function () {
            var aID = $(this).data('id');
            $(".modal-footer #continue").attr( 'href', '<?php echo base_url(rtrim($this->config->item('SITE_ID'),"_").'/admin/table/delete/'); ?>/'+aID+'.html' );
            // As pointed out in comments, 
            // it is superfluous to have to manually call the modal.
            // $('#addBookDialog').modal('show');
        });
    </script>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete</h4>
                </div>
                <div class="modal-body">
                    <p>Deleting a location will remove it from the entire system.<br/><br/>Really want to delete location ?</p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-danger btn-sm" id="continue">Continue</a>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">No</button>
                </div>
            </div>

        </div>
    </div>
</div>
