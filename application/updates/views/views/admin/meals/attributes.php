<div id="page-inner">
    <div class="row">
        <div class="col-md-11">
            <h2>Add or update attributes</h2>   
        </div>
        <div class="col-md-1">
            <a href="<?php echo site_url('admin/meals/'); ?>" class="btn btn-success btn-sm new">
                <i class="fa fa-reply"></i>
                Back
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
                    Attributes
                </div>
                <div class="panel-body">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="product_nav">
                                <ul class="nav nav-tabs">
                                    <li><a href="<?php echo site_url('admin/meals/new_meal/'.$meal_id); ?>"><span class="glyphicon glyphicon-list-alt"></span>Basic Details</a></li>
                                    <li class="active"><a href="<?php echo site_url('admin/meals/attributes/'.$meal_id); ?>"><span class="glyphicon glyphicon-link"></span>Attributes</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>       
                    
                    <?php if( !isset($at_id) || empty($at_id)): ?>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h2>Existing Attributes!</h2>
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Attribute</th>
                                        <th>Value</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody> 

                                    <?php foreach ($existing as $key => $exist): ?>
                                        <tr class="<?php ($key%2 == 0) ? 'odd' : 'even'; ?> gradeX">
                                            <td><?php echo ( $key+1 ); ?></td>    
                                            <td><?php echo $exist->name; ?></td>
                                            <td><?php echo $exist->value; ?></td>
                                            <td>
                                                <a class="btn btn-info btn-sm" href="<?php echo site_url('admin/meals/attributes/'.$meal_id.'/'. $exist->id); ?>">
                                                    <i class="fa fa-edit "></i> 
                                                    Edit
                                                </a>
                                                <a class="btn btn-danger btn-sm delete" data-toggle="modal" data-target="#myModal" data-id="<?php echo $exist->id; ?>">
                                                    <i class="fa fa-times"></i>
                                                    Delete
                                                </a>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>


                                </tbody>
                            </table>
                            <script>
                                $(document).ready(function () {
                                    $('#dataTables-example').dataTable(); 
                                });
                                $(document).on("click", ".delete", function () {
                                    var aID = $(this).data('id');
                                    $(".modal-footer #continue").attr( 'href', '<?php echo base_url(rtrim($this->config->item('SITE_ID'),"_").'/admin/meals/delete_attribute/'.$meal_id); ?>/'+aID+'.html' );
                                    // As pointed out in comments, 
                                    // it is superfluous to have to manually call the modal.
                                    // $('#addBookDialog').modal('show');
                                });
                            </script>
                        </div>    
                    </div>
                    <br/><br/>
                    <?php endif; ?>
                    
                    <div class="row">
                        
                        <div class="col-md-8">
                            <h2>Create New</h2>
                             <?php echo form_open('', array('class' => 'form-new', "id" => "attrs", 'data-parsley-validate' => '')); ?>

                                <div class="form-group">
                                    <div class="col-md-6 no-margin">
                                        <label>Attributes:</label>
                                        <?php  echo form_dropdown('attributes', $attributes, isset($at_id) ? $at_id : $this->input->post('attributes'), 'class="form-control" required="" autocomplete="off"'); ?>
                                        <?php echo form_error('attributes') ?>
                                    </div>
                                    <div class="col-md-6 no-margin">

                                        <label>Value:</label>
                                        <?php  echo form_input($value); ?>
                                        <?php echo form_error('value') ?>

                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <?php echo form_hidden($id) ?>
                                    <button type="submit" class="btn btn-info">
                                        <i class="fa fa-plus"></i>
                                        Create New
                                    </button>
                                </div>
                            <?php echo form_close(); ?>
                        </div>  
                    </div>    
                </div>
            </div>
            <!--End Advanced Tables -->
        </div>
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete</h4>
                </div>
                <div class="modal-body">
                    <p>Really want to remove attribute from this product ?</p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-danger btn-sm" id="continue">Continue</a>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">No</button>
                </div>
            </div>

        </div>
    </div>
</div>
