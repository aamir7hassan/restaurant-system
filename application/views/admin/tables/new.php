<div id="page-inner">
    <?php echo form_open('', array('class' => 'form-new', "id" => "table", 'data-parsley-validate' => '')); ?>
        <div class="row">
            <div class="col-md-6">
                <h2><?php echo $head_title; ?> Location</h2>   
                <h5>QR code will be generated automatically.!</h5>
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

                    <a href="<?php echo site_url('admin/table/'); ?>" class="btn btn-success btn-sm">
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
                        <?php echo $head_title; ?> Location
                    </div>
                    <div class="panel-body">
                        <div class="col-md-8">

                                <div class="form-group">
                                    <label>Location Name:</label>
                                    <?php echo form_input($name) ?>
                                    <?php echo form_error('name') ?>
                                </div>
                                <div class="form-group">
                                    <label>Number of seats:</label>
                                    <?php echo form_input($seats) ?>
                                    <?php echo form_error('seats') ?>
                                </div>
                                <div class="form-group">
                                    <?php echo form_hidden($id) ?>
                                    <?php echo form_hidden($old) ?>
                                    
                                </div>
                                
                                <?php if(!empty($unique)): ?>
                                <div class="form-group">
                                    <label>Qr code</label>
                                    <img src="<?php echo site_url('qr/get_table/'.$unique); ?>" />
                                </div>
                                <div class="form-group">
                                    <a class="btn brn-sm btn-success" href="<?php echo site_url('admin/table/update_qr/'.$table_id); ?>">Update Qr code</a>
                                </div>
                                <?php endif; ?>
                        </div>    
                    </div>
                </div>
                <!--End Advanced Tables -->
            </div>
        </div>

        <script type="text/javascript">

            $('#attribute').parsley();

            window.Parsley.addAsyncValidator('validateTable', function (xhr) {
                console.log(xhr.responseText); // jQuery Object[ input[name="q"] ]

                if (xhr.responseText == 0){
                    return false;
                }
                else{
                    return true;
                }

            }, '<?php echo site_url('admin/table/exists/{value}');?>', { "type": "POST", "dataType": "json", "data": $('#table').serialize() } );


        </script>
    <?php echo form_close(); ?>
</div>
