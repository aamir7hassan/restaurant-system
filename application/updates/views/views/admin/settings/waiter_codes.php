<div id="page-inner">
<?php echo form_open('', array('class' => 'form-new', "id" => "attribute", 'data-parsley-validate' => '')); ?>

    <div class="row">
        <div class="col-md-6">
            <h2>Settings</h2>
        </div>
        <div class="col-md-6"></div>
        <div class="clearfix"></div>
    </div>
    <hr />
    <?php require APPPATH.'views/admin/settings/navs.php'; ?>

    <div class="row">
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    Authorisation Code Settings
                </div>
                <div class="panel-body">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Waiter Name</label>
                        <input type="text" name="waiter_name" class="form-control" placeholder="Waiter Name" required="" >
                        <?php echo form_error('waiter_name'); ?>
                    </div>
                    <div class="form-group">
                        <label>Authorisation code</label>
                        <input type="text" name="waiter_code" class="form-control" placeholder="Authorisation code" required="" >
                        <?php echo form_error('waiter_code'); ?>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-info" type="submit">Save</button>
                    </div>

                </div>
                <div class="col-md-8">  
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($waiter_codes as $waiter):?>
                        <tr>
                            <td><?php echo $waiter->name;?></td>
                            <td><?php echo $waiter->unique;?></td>
                            <td>
                                <a class="btn btn-info btn-sm" href="<?php echo site_url('admin/settings/waiter_code/'.$waiter->id); ?>">
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

                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script>
    $(document).ready(function () {
        $('#dataTables-example').dataTable();
    });
    $(document).on("click", ".delete", function () {
        var aID = $(this).data('id');
        $(".modal-footer #continue").attr( 'href', '<?php echo base_url(rtrim($this->config->item('SITE_ID'),"_").'/admin/settings/delete_code/'); ?>/'+aID+'.html' );
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
                <p>Do you really want to delete the authorisation code?</p>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-danger btn-sm" id="continue">Continue</a>
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>