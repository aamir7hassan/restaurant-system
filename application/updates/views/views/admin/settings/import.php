<div id="page-inner">
    <?php echo form_open_multipart('', array('class' => 'form-new', "id" => "category", 'data-parsley-validate' => '')); ?>
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
                    Export
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <a href="<?php echo site_url('admin/settings/export/'); ?>" class="btn btn-success">
                                    <i class="fa fa-sign-out"></i>
                                    Export
                                </a>
                            </div>
                            <br/>
                        </div>    
                    </div>
                </div>
            </div>
            <!--End Advanced Tables -->
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    Import
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            
                            <div class="pull-right nav-area">
                                <button type="submit" name="submitForm" class="btn btn-info btn-sm" value="formSave">
                                    <span class="glyphicon glyphicon-share"></span>
                                    Import
                                </button>
                            </div>
                            <div class="clearfix"></div> 
                            <br/>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="file" name="csv" />
                                <?php echo form_error('csv') ?>
                            </div>
                            <br/>
                        </div>    
                    </div>
                </div>
            </div>
            <!--End Advanced Tables -->
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
