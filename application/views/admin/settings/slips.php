<script src="https://cdn.tiny.cloud/1/wrwm27uofr40i4fueeiturxrp42t53i9t2192w1go0ku6x9d/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({selector:'textarea'});
</script>

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
                    Slip Settings
                </div>
                <div class="panel-body">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="slip_address" class="form-control" placeholder="Address" value="<?php echo ($this->config->item('slip_address')); ?>" />
                        <?php echo form_error('slip_address'); ?>
                    </div>

                    <div class="form-group">
                        <label>Tel</label>
                        <input type="text" name="slip_tel" class="form-control" placeholder="Tel" value="<?php echo ($this->config->item('slip_tel')); ?>" />
                        <?php echo form_error('slip_tel'); ?>
                    </div>
                    <div class="form-group">
                        <label>Fax</label>
                        <input type="text" name="slip_fax" class="form-control" placeholder="Fax" value="<?php echo ($this->config->item('slip_fax')); ?>" />
                        <?php echo form_error('slip_fax'); ?>
                    </div>
                    <div class="form-group">
                        <label>VAT</label>
                        <input type="text" name="slip_vat" class="form-control" placeholder="VAT" value="<?php echo ($this->config->item('slip_vat')); ?>" />
                        <?php echo form_error('slip_vat'); ?>
                    </div>
                    <div class="form-group">
                        <label>Thank you message</label>
                        <textarea name="slip_thank_you_message" class="form-control" placeholder="Thank you message"  rows="4" cols="50"><?php echo ($this->config->item('slip_thank_you_message')); ?></textarea>
                        <?php echo form_error('slip_thank_you_message'); ?>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-info" type="submit">Save</button>
                    </div>

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
                <p>Do you really want to delete the Slip?</p>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-danger btn-sm" id="continue">Continue</a>
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>