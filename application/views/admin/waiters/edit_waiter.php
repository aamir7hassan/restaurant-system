<div id="page-inner">
    <?php echo form_open('', array('class' => 'form-new', "id" => "waiter", 'data-parsley-validate' => '')); ?>
        <div class="row">
            <div class="col-md-6">
                <h2>Edit Clerk</h2>   
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
                        Edit Clerk
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
                                    <input type="password" name="password" id="password" value="<?=$pass?>" data-parsley-length="[5, 100]" class="form-control" />
                                    <?php echo form_error('password') ?>
                                    <small class="form-text text-muted"><?php echo $pass; ?></small>
                                </div>

                                <div class="form-group">
                                    <label>Repeat Password</label>
                                    <input type="password" name="password_confirm" id="password_confirm" value="<?=$pass?>" data-parsley-equalto="#password" data-parsley-error-message="This must be same as password field!" class="form-control" />
                                    <?php echo form_error('password_confirm') ?>
                                    <small class="form-text text-muted"><?php echo $pass; ?></small>
                                </div>
								<div class="form-group">
									<label><input type="radio" value="waiter" name="role" <?php echo $role['value']=='waiter'?'checked':''?> > Waiter</label>
									&nbsp;&nbsp;<label><input type="radio" value="driver" name="role" <?php echo $role['value']=='driver'?'checked':''?> > Driver</label>
								</div>

                                <div class="form-group">
                                    <label>Float</label>
                                    <?php echo form_input($waiter_float) ?>
                                    <?php echo form_error('waiter_float') ?>
                                </div>
								<div class="form-group">
									<label class="control-label">Take Away</label>
									<input type="hidden" name="take_away" value="0" />
									&nbsp;<input type="checkbox" name="take_away" value="1" <?php echo $user->take_away=='1'?'checked':''?>/>
								</div>
                                <div class="form-group">
                                    <label>Assign to location</label>
                                </div>
								<?php foreach($tempTables as $val) { ?>
									<div class="form-group hidden">
                                        <label>Location <?php echo $val['name']; ?></label>
                                        <input type="hidden" name="tables[]" value="<?php echo $val['id']; ?>" <?php echo in_array($val['id'], $associated_tables) ? "checked" : ""; ?>/>
                                    </div>
								<?php } ?>
                                <?php foreach ($tables as $table): ?>
                                    <div class="form-group">
                                        <label>Location <?php echo $table->name; ?></label>
                                        <input type="checkbox" name="tables[]" value="<?php echo $table->id; ?>" <?php echo in_array($table->id, $associated_tables) ? "checked" : ""; ?>/>
                                    </div>
								<?php  endforeach;?>
                                <div class="form-group">
                                    <?php echo form_hidden($id) ?>
                                </div>
                            
                        </div>    
                    </div>
                </div>
                <!--End Advanced Tables -->
            </div>
        </div>
    <?php echo form_close(); ?>
</div>
