<div id="page-inner">
    <?php echo form_open('', array('class' => 'form-new', "id" => "attribute", 'data-parsley-validate' => '')); ?>
    <div class="row">
        <div class="col-md-6">
            <h2>Styles</h2>   
        </div>
        <div class="col-md-6">
            <div class="pull-right nav-area">
                <button type="submit" name="submitForm" class="btn btn-info btn-sm" value="formSave">
                    <span class="glyphicon glyphicon-share"></span>
                    Save
                </button>

                <button type="submit" name="submitForm" class="btn btn-info btn-sm " value="formSaveClose"> 
                    <span class="glyphicon glyphicon-share"></span>
                    Save and Close
                </button>

                <a href="<?php echo site_url('admin/'); ?>" class="btn btn-success btn-sm">
                    <i class="fa fa-reply"></i>
                    Back
                </a>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>       
    <hr />
    <?php require APPPATH.'views/admin/settings/navs.php'; ?>
    <div class="row">
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    Styles
                </div>
                <div class="panel-body">
                    
                    <div class="form-group">
                        <div class="col-md-4"> <label>New Order</label> </div>
                        <div class="col-md-6">
                            
                            <div class="form-inline">
                                <input type="color" data-id="new_order_colour" name="" class="" value="<?php echo ($this->config->item('new_order_colour')); ?>"/>
                                <input type="text" name="new_order_colour" class="form-control" style="margin-top: -5px;" value="<?php echo ($this->config->item('new_order_colour')); ?>"/>
                            </div>
                            <?php echo form_error('new_order_colour') ?>
                        
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-4"> <label>Clerk processed Order</label> </div>
                        <div class="col-md-6">
                            
                            <div class="form-inline">
                                <input type="color" data-id="processed_order_colour" name="" class="" value="<?php echo ($this->config->item('processed_order_colour')); ?>"/>
                                <input type="text" name="processed_order_colour" class="form-control" style="margin-top: -5px;" value="<?php echo ($this->config->item('processed_order_colour')); ?>"/>
                            </div>
                            <?php echo form_error('processed_order_colour') ?>
                        
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-4"> <label>Clerk NOT Processed Order in 5 minutes</label> </div>
                        <div class="col-md-6">
                            
                            <div class="form-inline">
                                <input type="color" data-id="fivemin_order_colour" name="" class="" value="<?php echo ($this->config->item('fivemin_order_colour')); ?>"/>
                                <input type="text" name="fivemin_order_colour" class="form-control" style="margin-top: -5px;" value="<?php echo ($this->config->item('fivemin_order_colour')); ?>"/>
                            </div>
                            <?php echo form_error('fivemin_order_colour') ?>
                        
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-4"> <label>Order left Work Station</label> </div>
                        <div class="col-md-6">
                            
                            <div class="form-inline">
                                <input type="color" data-id="kitchen_left_color" name="" class="" value="<?php echo ($this->config->item('kitchen_left_color')); ?>"/>
                                <input type="text" name="kitchen_left_color" class="form-control" style="margin-top: -5px;" value="<?php echo ($this->config->item('kitchen_left_color')); ?>"/>
                            </div>
                            <?php echo form_error('billto_order_colour') ?>
                        
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-4"> <label>Work Station NOT Processed Order in 25 minutes</label> </div>
                        <div class="col-md-6">
                            
                            <div class="form-inline">
                                <input type="color" data-id="tfivemin_order_colour" name="" class="" value="<?php echo ($this->config->item('tfivemin_order_colour')); ?>"/>
                                <input type="text" name="tfivemin_order_colour" class="form-control" style="margin-top: -5px;" value="<?php echo ($this->config->item('tfivemin_order_colour')); ?>"/>
                            </div>
                            <?php echo form_error('tfivemin_order_colour') ?>
                        
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    
                    <div class="form-group">
                        <div class="col-md-4"> <label>Bill to Location</label> </div>
                        <div class="col-md-6">
                            
                            <div class="form-inline">
                                <input type="color" data-id="billto_order_colour" name="" class="" value="<?php echo ($this->config->item('billto_order_colour')); ?>"/>
                                <input type="text" name="billto_order_colour" class="form-control" style="margin-top: -5px;" value="<?php echo ($this->config->item('billto_order_colour')); ?>"/>
                            </div>
                            <?php echo form_error('billto_order_colour') ?>
                        
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    
                    
                </div>
            </div>
            <!--End Advanced Tables -->
        </div>
    </div>
    
    <script type="text/javascript">
        $(document).ready(function(){
           $("input[type=color]").change(function(){
               var name = $(this).attr('data-id');
               $("input[name="+name+"]").val($(this).val());
           }) ;
        });
    </script>
    
    <?php echo form_close(); ?>
</div>
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

