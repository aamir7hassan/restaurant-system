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
					<div class="row">
						<div class="col-md-4">
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
						</div>
						<div class="col-md-6">
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
							<div>
								<img  class="img-responsive" src="<?php echo base_url("assets/images/".$this->config->item('store_background')); ?>">
							</div>
							<?php endif; ?>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>System colour</label><br/>
								<input type="color" name="system_colour" placeholder="System colur" value="<?php echo ($this->config->item('system_colour')); ?>">
								<?php echo form_error('system_colour'); ?>
							</div>
						</div>
					</div>
					<hr/>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div class="col-md-5"> <label>New Order</label> </div>
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
								<div class="col-md-5"> <label>Clerk processed Order</label> </div>
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
								<div class="col-md-5"> <label>Clerk NOT Processed Order in minutes</label> <input type="numer" style="width:60px" name="firstProc" value="<?=$this->config->item('firstProc')?>"/></div>
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
								<div class="col-md-5"> <label>Order left Work Station</label> </div>
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
								<div class="col-md-5"> <label>Work Station NOT Processed Order in minutes</label> <input type="numer" style="width:60px" name="secProc" value="<?=$this->config->item('secProc')?>"/> </div>
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
								<div class="col-md-5"> <label>Order Transit</label> <input type="numer" style="width:60px" name="transitProc" value="<?=$this->config->item('transitProc')?>"/> </div>
								<div class="col-md-6">
									<div class="form-inline">
										<input type="color" data-id="transit_color" name="" class="" value="<?php echo ($this->config->item('transit_color')); ?>"/>
										<input type="text" name="transit_color" class="form-control" style="margin-top: -5px;" value="<?php echo ($this->config->item('transit_color')); ?>"/>
									</div>
									<?php echo form_error('transit_color') ?>
								</div>
								<div class="clearfix"></div>
							</div>
							
							<div class="form-group">
								<div class="col-md-5"> <label>Late Delivery</label></div>
								<div class="col-md-6">
									<div class="form-inline">
										<input type="color" data-id="late_color" name="" class="" value="<?php echo ($this->config->item('late_color')); ?>"/>
										<input type="text" name="late_color" class="form-control" style="margin-top: -5px;" value="<?php echo ($this->config->item('late_color')); ?>"/>
									</div>
									<?php echo form_error('late_color') ?>
								</div>
								<div class="clearfix"></div>
							</div>
							
							<div class="form-group">
								<div class="col-md-5"> <label>Bill to Location</label> </div>
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
                    <div class="col-md-8">
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
			
			$(document).ready(function(){
				$("input[type=color]").change(function(){
					var name = $(this).attr('data-id');
					$("input[name="+name+"]").val($(this).val());
				});
			});
        </script>
    </div>
    <?php echo form_close(); ?>
</div>


