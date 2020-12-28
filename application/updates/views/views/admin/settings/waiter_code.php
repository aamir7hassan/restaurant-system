<div id="page-inner">
    <?php echo form_open('', array('class' => '')); ?>
    <div class="row">
        <div class="col-md-6">
            <h2>Waiter Code</h2>
        </div>
        <div class="col-md-6"><br/><br/>
            <div class="pull-right nav-area">
                <button type="submit" name="submitForm" class="btn btn-info btn-sm" value="formSave">
                    <span class="glyphicon glyphicon-share"></span>
                    Save
                </button>

                <button type="submit" name="submitForm" class="btn btn-info btn-sm " value="formSaveCloseNew">
                    <span class="glyphicon glyphicon-share"></span>
                    Save and add another
                </button>

                <button type="submit" name="submitForm" class="btn btn-info btn-sm " value="formSaveClose">
                    <span class="glyphicon glyphicon-share"></span>
                    Save and Close
                </button>

                <a href="<?php echo site_url('admin/settings/waiter_codes/'); ?>" class="btn btn-success btn-sm">
                    <i class="fa fa-reply"></i>
                    Back
                </a>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
    <hr />
    <div class="row">
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    Authorisation Code
                </div>
                <div class="panel-body">
                    <div class="col-md-8">

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Waiter Name:</label>
                                    <?php echo form_input($name); ?>
                                    <?php echo form_error('name') ?>
                                </div>

                                <div class="form-group">
                                    <label>Authorisation Node:</label>
                                    <?php echo form_input($unique); ?>
                                    <?php echo form_error('unique') ?>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                        </div>

                    </div>
                </div>
            </div>
            <!--End Advanced Tables -->
        </div>
    </div>

    <?php echo form_close(); ?>
</div>
