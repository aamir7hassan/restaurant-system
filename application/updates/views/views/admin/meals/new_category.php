<div id="page-inner">
    <?php echo form_open('', array('class' => 'form-new', "id" => "category", 'data-parsley-validate' => '')); ?>
        <div class="row">
            <div class="col-md-6">
                <h2><?php echo $head_title; ?> Category</h2>   
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
                        <?php echo $head_title; ?> Category
                    </div>
                    <div class="panel-body">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="product_nav">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="<?php echo site_url('admin/meals'); ?>"><span class="glyphicon glyphicon-list-alt"></span>Categories</a></li>
                                        <li><a href="<?php echo site_url('admin/meals/available/'); ?>"><span class="glyphicon glyphicon-link"></span>Location</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div> 
                        <div class="col-md-8">
                                <div class="form-group">
                                    <label>Category Name:</label>
                                    <?php echo form_input($name) ?>
                                    <?php echo form_error('category_name') ?>
                                </div>
                                <div class="form-group">
                                    <label>Sort order:</label>
                                    <?php echo form_input($sort) ?>
                                    <?php echo form_error('sort') ?>
                                </div>
                                <div class="form-group">
                                    <?php echo form_hidden($id) ?>
                                    <?php echo form_hidden($old) ?>
                                </div>
                        </div>    
                    </div>
                </div>
                <!--End Advanced Tables -->
            </div>
        </div>

        <script type="text/javascript">

            $('#category').parsley();

            window.Parsley.addAsyncValidator('validateCategory', function (xhr) {
                console.log(xhr.responseText); // jQuery Object[ input[name="q"] ]

                if (xhr.responseText == 0){
                    return false;
                }
                else{
                    return true;
                }

            }, '<?php echo site_url('admin/meals/exists/{value}');?>', { "type": "POST", "dataType": "json", "data": $('#category').serialize() } );

        </script>
    <?php echo form_close(); ?>
</div>
