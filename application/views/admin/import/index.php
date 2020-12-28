<div id="page-inner">
    <?php echo form_open_multipart('', array('class' => 'form-new', "id" => "category", 'data-parsley-validate' => '')); ?>
        <div class="row">
            <div class="col-md-6">
                <h2>Import categories</h2>   
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

                    <a href="<?php echo site_url('admin/meals/'); ?>" class="btn btn-success btn-sm">
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
                        Import categories
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="file" name="csv" />
                                    <?php echo form_error('csv') ?>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
                <!--End Advanced Tables -->
            </div>
        </div>
    <?php echo form_close(); ?>
</div>
