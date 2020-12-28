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
                    Manager Code Settings
                </div>
                <div class="panel-body">
                    <div class="col-md-8">
                    <div class="form-group">
                            <label>Manager code</label>
                            <input type="text" name="manager_code" class="form-control" placeholder="Manager code" required="" value="<?php echo $this->config->item('manager_code'); ?>">
                            <?php echo form_error('manager_code'); ?>
                        </div>


                        <div class="form-group">
                            <button class="btn btn-info" type="submit">Update</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php echo form_close(); ?>
</div>
