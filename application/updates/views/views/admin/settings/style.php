<div id="page-inner">
    <?php echo form_open_multipart('', array('class' => 'form-new', "id" => "attribute", 'data-parsley-validate' => '')); ?>
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
                    Restaurant Info
                </div>
                <div class="panel-body">
                    <div class="col-md-8">
                        
                        <div class="form-group">
                            <label>Store logo:</label><br/>
                            <span class="btn btn-info btn-file">
                                <span class="glyphicon glyphicon-upload">
                                    Browse---- <input type="file" name="images[store_logo]">
                                </span>
                            </span>
                            <?php echo form_error('store_logo'); ?>
                        </div>
                        
                        <?php if($this->config->item('store_logo')): ?>
                        <div style="max-width:400px">
                            <img class="img-responsive" src="<?php echo base_url("assets/images/".$this->config->item('store_logo')); ?>">
                        </div>
                        <?php endif; ?>
                        <br/>
                        <div class="form-group">
                            <label>Store background:</label><br/>
                            <small>Ideal image size 700 pixels x 400 pixels</small><br/>
                            <span class="btn btn-info btn-file">
                                <span class="glyphicon glyphicon-upload">
                                    Browse---- <input type="file" name="images[store_background]">
                                </span>
                            </span>
                            <?php echo form_error('store_background'); ?>
                        </div>
                        
                        <?php if($this->config->item('store_background')): ?>
                        <div style="width:700px">
                            <img  class="img-responsive" src="<?php echo base_url("assets/images/backgrounds/".$this->config->item('store_background')); ?>">
                        </div>
                        <?php endif; ?> 
                        <br/>
                        <div class="form-group">
                            <label>System colour</label><br/>
                            <input type="color" name="system_colour" placeholder="System colur" value="<?php echo ($this->config->item('system_colour')); ?>">
                            <?php echo form_error('system_colour'); ?>
                        </div>
                        
                        <div class="form-group">
                            <button class="btn btn-info" type="submit">Update</button>
                        </div>
                    </div>    
                </div>
            </div>
            <!--End Advanced Tables -->
        </div>
        <script type="text/javascript">
            $(document).ready(function(){  
                $("input[type=checkbox]").click(function(){ 
                   var source      = $(this).attr("data-source"); 
                   var destination = $(this).attr("data-destination"); 
                   
                   $("input[name="+destination+"]").val($("input[name="+source+"]").val());
                   
                });
            }); 
        </script>
    </div>
    <?php echo form_close(); ?>
</div>


