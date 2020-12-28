<div id="page-inner">
    <div class="row">
        <div class="col-md-11">
            <h2>Clerks</h2>   
            <h5>Available clerks!</h5>
        </div>
        <div class="col-md-1">
            <a href="<?php echo site_url('admin/waiters/new_waiter/'); ?>" class="btn btn-success btn-sm new">
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
                    Clerks
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
									<th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <?php if(is_array($waiters) && count($waiters) > 0): ?>
                                    <?php foreach ($waiters as $key => $waiter): ?>
                                        <tr class="<?php ($key%2 == 0) ? 'odd' : 'even'; ?> gradeX">
                                            <td><?php echo ( $key+1 ); ?></td>    
                                            <td><?php echo $waiter->first_name.' '.$waiter->last_name; ?></td>
                                            <td><?php echo $waiter->email; ?></td>
											<td><?=ucwords($waiter->role)?></td>
                                            <td>
                                                <a class="btn btn-info btn-sm" href="<?php echo site_url('admin/waiters/edit_waiter/'.$waiter->id); ?>">
                                                    <i class="fa fa-edit "></i> 
                                                    Edit
                                                </a>
                                                <a class="btn btn-danger btn-sm delete" data-toggle="modal" data-target="#myModal" data-id="<?php echo $waiter->id; ?>">
                                                    <i class="fa fa-times"></i>
                                                    Delete
                                                </a>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
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
            $(".modal-footer #continue").attr( 'href', '<?php echo base_url(rtrim($this->config->item('SITE_ID'),"_").'/admin/waiters/delete/'); ?>/'+aID+'.html' );
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
                    <p>Do you really want to delete the waiter ?</p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-danger btn-sm" id="continue">Continue</a>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">No</button>
                </div>
            </div>

        </div>
    </div>
</div>
