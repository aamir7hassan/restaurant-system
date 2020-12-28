<div id="page-inner">
    <div class="row">
        <div class="col-md-11">
            <h2>Products</h2>   
        </div>
        <div class="clearfix"></div>
    </div>       
    <hr /> 
    <div class="row">
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    Products
                </div>
                <div class="panel-body">
                  
                    
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <a href="<?php echo site_url('admin/meals/new_meal/'); ?>" class="btn btn-success btn-sm pull-right">
                                    <i class="fa fa-sign-out"></i>
                                    Create New
                                </a><div class="clearfix"></div>

                            </div>
                        </div>    
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Out of stock</th>
                                    <th>Active</th>
                                    <th>Sort order</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> 
                                
                                <?php foreach ($meals as $key => $meal): ?>
                                    <tr class="<?php ($key%2 == 0) ? 'odd' : 'even'; ?> gradeX">
                                        <td><?php echo ( $key+1 ); ?></td>    
                                        <td><?php echo $meal->name; ?></td>
                                        <td><?php echo $meal->cname; ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <?php if($meal->out_of_stock == 1): ?>
                                                    <a title="Out of stock" href="<?php echo site_url('admin/meals/stock/'.$meal->id); ?>" class="btn btn-micro active success">Yes</a>															
                                                <?php else: ?>
                                                    <a title="In stock" href="<?php echo site_url('admin/meals/stock/'.$meal->id); ?>" class="btn btn-micro active success">No</a>															
                                                <?php endif; ?>														
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <?php if($meal->active == 1): ?>
                                                    <a title="Active" href="<?php echo site_url('admin/meals/status/'.$meal->id); ?>" class="btn btn-micro active success"><span class="glyphicon glyphicon-ok"></span></a>															
                                                <?php else: ?>
                                                    <a title="Inactive" href="<?php echo site_url('admin/meals/status/'.$meal->id); ?>" class="btn btn-micro active success"><span class="glyphicon glyphicon-remove"></span></a>															
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td><?php echo $meal->sort; ?></td>
                                        <td><?php echo pos_date( $meal->date ); ?></td>
                                        <td>
                                            <a class="btn btn-info btn-sm" href="<?php echo site_url('admin/meals/new_meal/'.$meal->id); ?>">
                                                <i class="fa fa-edit "></i> 
                                                Edit
                                            </a>
                                            <a class="btn btn-danger btn-sm delete" data-toggle="modal" data-target="#myModal" data-id="<?php echo $meal->id; ?>">
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
        </div>
    </div>
</div>

 <script>
        $(document).ready(function () {
            $('#dataTables-example').dataTable(); 
        });
        $(document).on("click", ".delete", function () {
            var aID = $(this).data('id');
            $(".modal-footer #continue").attr( 'href', '<?php echo base_url(rtrim($this->config->item('SITE_ID'),"_").'/admin/meals/delete_meal/'); ?>/'+aID+'.html' );
            // As pointed out in comments, 
            // it is superfluous to have to manually call the modal.
            // $('#addBookDialog').modal('show');
        });
    </script>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete</h4>
                </div>
                <div class="modal-body">
                    <p>Deleting a product will cause removal of all its details.<br/><br/>Really want to delete product ?</p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-danger btn-sm" id="continue">Continue</a>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</div>
