
<div id="page-inner">
    <?php echo form_open('', array('class' => 'form-new', "id" => "waiter", 'data-parsley-validate' => '')); ?>
        <div class="row">
            <div class="col-md-6">
                <h2>Create Clerk</h2>   
                <h5>Clerks!</h5>
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

                    <a href="<?php echo site_url('admin/waiters/'); ?>" class="btn btn-success btn-sm">
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
                        Create Clerk
                    </div>
                    <div class="panel-body">
                        <div class="col-md-8">
                                <div class="form-group">
                                    <label>First Name:</label>
                                    <?php echo form_input($first_name) ?>
                                    <?php echo form_error('first_name') ?>
                                </div>
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <?php echo form_input($last_name) ?>
                                    <?php echo form_error('last_name') ?>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <?php echo form_input($email) ?>
                                    <?php echo form_error('email') ?>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
									
                                   <input type="text" name="password" value="" id="password" class="form-control" data-parsley-length="[3, 250]" required="" placeholder="Password">
                                    <?php echo form_error('password') ?>
                                </div>
                                
                                <div class="form-group">
                                    <label>Repeat Password</label>
                                    <input type="text" name="password_confirm" value="" id="password_confirm" class="form-control" placeholder="Repeat password" data-parsley-equalto="#password" data-parsley-error-message="This must be same as password field!">
                                    <?php echo form_error('password_confirm') ?>
                                </div>
								<div class="form-group">
									<label><input type="radio" value="waiter" name="role"  > Waiter</label>
									<label><input type="radio" value="driver" name="role"  > Driver</label>
									<?php echo form_error('role') ?>
								</div>
                                <div class="form-group">
                                    <label>Float</label>
                                    <?php echo form_input($waiter_float) ?>
                                    <?php echo form_error('waiter_float') ?>
                                </div>
								<div class="form-group">
									<label class="control-label">Take Away</label>
									<input type="hidden" name="take_away" value="0" />
									&nbsp;<input type="checkbox" name="take_away" value="1"/>
								</div>
                                <div class="form-group">
                                    <label>Assign to location</label>
                                </div>

                                <?php foreach ($tables as $table): ?>
                                    <div class="form-group">
                                        <label>Location <?php echo $table->name; ?></label>
                                        <input type="checkbox" name="tables[]" value="<?php echo $table->id; ?>" />
                                    </div>
                                <?php endforeach; ?>
                        </div>    
                    </div>
                </div>
                <!--End Advanced Tables -->
            </div>
        </div>
    <?php echo form_close(); ?>
</div>
