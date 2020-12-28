<div id="page-inner">
    <?php echo form_open('', array('class' => 'form-new', "id" => "attribute", 'data-parsley-validate' => '')); ?>
        <div class="row">
            <div class="col-md-6">
                <h2><?php echo $head_title; ?> Attribute</h2>   
                <h5>Attributes that can be assigned to products!</h5>
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

                    <a href="<?php echo site_url('admin/attributes/'); ?>" class="btn btn-success btn-sm">
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
                        <?php echo $head_title; ?> Attribute
                    </div>
                    <div class="panel-body">
                        <div class="col-md-8">

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Attribute Name:</label>
                                            <?php echo form_input($name) ?>
                                            <?php echo form_error('attribute_name') ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Sort order:</label>
                                            <?php echo form_input($sort) ?>
                                            <?php echo form_error('sort') ?>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Select option: &nbsp;</label> 
                                            Single <input type="radio" name="select_option" value="single" <?php echo $select_option == 'single' ? 'checked' : ''; ?> required="" />&nbsp;
                                            Multi <input type="radio" name="select_option" value="multi" <?php echo $select_option == 'multi' ? 'checked' : ''; ?> required=""/>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Mandatory: &nbsp;</label> 
                                            <input type="checkbox" name="mandatory" value="1" <?php echo $mandatory == 1 ? 'checked' : ''; ?>  />&nbsp;
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                    
                                <div class="form-group after">
                                    <label>Attribute Vaues (Leave empty to remove):</label><br/>
                                </div> 

                                <?php if(isset($attributes) && count(attributes) > 0): ?>
                                    <?php foreach ($attributes as $attr): ?>
                                        <div class="form-group after">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <input type="text" name="attribute_values[]" class="form-control" placeholder="Attribute Value" value="<?php echo $attr->name; ?>" />
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="attribute_prices[]" class="form-control" placeholder="Attribute Price" value="<?php echo $attr->price; ?>" />
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>    
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <div class="form-group after">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" name="attribute_values[]" class="form-control" placeholder="Attribute Value" value="" />
                                            <?php echo form_error('attribute_values') ?>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="attribute_prices[]" class="form-control" placeholder="Attribute Price" value="" />
                                            <?php echo form_error('attribute_prices') ?>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div> 
                            <div class="form-group">
                                <a href="javascript:void(0);" class="addNew">Add New</a>
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

            $('#attribute').parsley();

            window.Parsley.addAsyncValidator('validateAttribute', function (xhr) {
                console.log(xhr.responseText); // jQuery Object[ input[name="q"] ]

                if (xhr.responseText == 0){
                    return false;
                }
                else{
                    return true;
                }

            }, '<?php echo site_url('admin/attributes/exists/{value}');?>', { "type": "POST", "dataType": "json", "data": $('#attribute').serialize() } );


            $('.addNew').click(function(){
                $('.after:last').after('<div class="form-group after"><div class="row"><div class="col-md-8"><input type="text" name="attribute_values[]" class="form-control" placeholder="Attribute Value" value="" /></div><div class="col-md-4"><input type="text" name="attribute_prices[]" class="form-control" placeholder="Attribute Price" value="" /></div></div></div>');
            });

        </script>
    <?php echo form_close(); ?>
</div>
