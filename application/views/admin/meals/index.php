<div id="page-inner">
    <div class="row">
        <div class="col-md-11">
            <h2>Categories</h2>   
        </div>
        <div class="clearfix"></div>
    </div>       
    <hr /> 
    <div class="row">
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    Product
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            
                            <div class="pull-right nav-area">
                                
                                <a href="<?php echo site_url('admin/categories/export/'); ?>" class="btn btn-success btn-sm">
                                    <i class="fa fa-sign-out"></i>
                                    Export
                                </a>
                                
                                <a href="<?php echo site_url('admin/categories/import/'); ?>" class="btn btn-success btn-sm">
                                    <i class="fa fa-sign-out"></i>
                                    Import
                                </a>

                                <a href="<?php echo site_url('admin/categories/new_category/'); ?>" class="btn btn-success btn-sm">
                                    <i class="fa fa-sign-out"></i>
                                    Create New
                                </a>
                            </div>
                            <div class="clearfix"></div>
                            <br/>
                        </div>    
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Sort order</th>
									<th>Volume Warning</th>
									<th>Volume Quantity</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <?php if(is_array($categories) && count($categories) > 0): ?>
                                    <?php foreach ($categories as $key => $category): ?>
                                        <tr class="<?php ($key%2 == 0) ? 'odd' : 'even'; ?> gradeX">
                                            <td><?php echo ( $key+1 ); ?></td>    
                                            <td><?php echo $category->cname; ?></td>
                                            <td><?php echo $category->sort; ?></td>
											<td>
												<div class="btn-group">
                                                <?php if($category->active == "1"): ?>
                                                    <a title="Active" href="<?php echo site_url('admin/meals/cat_status/'.$category->cid); ?>" class="btn btn-micro active success"><span class="glyphicon glyphicon-ok"></span></a>															
                                                <?php else: ?>
                                                    <a title="Inactive" href="<?php echo site_url('admin/meals/cat_status/'.$category->cid); ?>" class="btn btn-micro active success"><span class="glyphicon glyphicon-remove"></span></a>															
                                                <?php endif; ?>
                                            </div>
											</td>
											<td><?=$category->quantity?></td>
                                            <td><?php echo pos_date( $category->cdate ); ?></td>
                                            <td>
                                                <a class="btn btn-info btn-sm" href="<?php echo site_url('admin/meals/new_category/'.$category->cid); ?>">
                                                    <i class="fa fa-edit "></i> 
                                                    Edit
                                                </a>
                                                <a class="btn btn-danger btn-sm delete" data-toggle="modal" data-target="#myModal" data-id="<?php echo $category->cid; ?>">
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
        </div>
    </div>
</div>

 <script>
        $(document).ready(function () {
            $('#dataTables-example').dataTable({
				orderCellsTop:true,
				
			}); 
        });
        $(document).on("click", ".delete", function () {
            var aID = $(this).data('id');
            $(".modal-footer #continue").attr( 'href', '<?php echo base_url(rtrim($this->config->item('SITE_ID'),"_").'/admin/meals/delete/'); ?>/'+aID+'.html' );
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
                    <p>Deleting an category will cause removal of this category and its sub categories from all the existing meals.<br/><br/>Really want to delete category ?</p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-danger btn-sm" id="continue">Continue</a>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">No</button>
                </div>
            </div>

        </div>
    </div>   

</div>
