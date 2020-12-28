<div id="page-inner">
    <?php echo form_open('', array('class' => 'form-new', "id" => "attribute", 'data-parsley-validate' => '')); ?>
        <div class="row">
            <div class="col-md-6">
                <h2><?php echo $title; ?> food type</h2>   
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

                    <a href="<?php echo site_url('admin/foods/'); ?>" class="btn btn-success btn-sm">
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
                        <?php echo $title; ?> food type
                    </div>
                    <div class="panel-body">
                        <div class="col-md-8">

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Food Name:</label>
                                            <input type="text" name="food_name" value="<?php echo isset($food->name) ? $food->name : '' ?>" placeholder="Food name" class="form-control" required="" />
                                            <?php echo form_error('food_name') ?>
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
