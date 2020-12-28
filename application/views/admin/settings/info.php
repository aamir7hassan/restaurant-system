<script src="https://cdn.tiny.cloud/1/wrwm27uofr40i4fueeiturxrp42t53i9t2192w1go0ku6x9d/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({selector:'textarea'});
</script>
<div id="page-inner">
    <?php echo form_open('', array('class' => 'form-new', "id" => "attribute", 'data-parsley-validate' => '',  'autocomplete' => 'no')); ?>
    <div class="row">
        <div class="col-md-6">
            <h2>Settings</h2>
        </div>
        <div class="col-md-6"></div>
        <div class="clearfix"></div>
    </div>
    <hr />
    <?php require APPPATH . 'views/admin/settings/navs.php'; ?>
    <div class="row">
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    Restaurant
                </div>
                <div class="panel-body">
                    <div class="col-md-8">
                        <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('user_id'); ?>">
                        <div class="form-group">
                            <label>Company Name: </label>
							<?php 
								$companyname = $this->config->item('company_name')==true?$this->config->item('company_name'):$account['restaurant_name'];
							?>
                            <input type="text" name="company_name" class="form-control" placeholder="Company Name" required="" value="<?=$companyname?>">
                            <?php echo form_error('company_name'); ?>
                        </div>

                        <div class="form-group">
                            <label>Trading As: </label>
                            <input type="text" name="trading_as" class="form-control" placeholder="Trading as" required="" value="<?php echo ($this->config->item('trading_as')); ?>">
                            <p style="padding-top: 5px;"><input type="checkbox" name="same_trend" id="same_trend" data-destination="trading_as" data-source="company_name">&nbsp;<small>Similar to Company name</small></p>
                            <?php echo form_error('trading_as'); ?>
                        </div>
						<?php 
							$address = $this->config->item('address')?$this->config->item('address'):$account['city'];
						?>
                        <div class="form-group">
                            <label>Address: </label>
                            <input type="text" name="address" class="form-control" placeholder="Address" required="" value="<?php echo $address; ?>">
                            <?php echo form_error('address'); ?>

                            <?php
                                $current_hour = 17;
                                $start_hour = 8;
                                $end_hour = 17;
                                
                                if($current_hour >= $start_hour && $current_hour < $end_hour){
                                    echo 'Open';
                                }
                                else{
                                    echo 'Closed';
                                }
                            ?>
                        </div>
						
						<div class="form-group">
                            <label>Website</label>
                            <input type="text" name="website" class="form-control" placeholder="Website" value="<?php echo ($this->config->item('website')); ?>">
                            <?php echo form_error('website'); ?>
                        </div>
						<div class="form-group">
                            <label>Telephone number</label>
							<?php 
								$phone = $this->config->item('telephone_no')==true?$this->config->item('telephone_no'):$account['phone'];
							?>
                            <input type="text" name="telephone_no" class="form-control" placeholder="Telephone no" required="" value="<?=$phone?>">
                            <?php echo form_error('telephone_no'); ?>
                        </div>
						<div class="form-group">
                            <label>VAT #</label>
                            <input type="text" name="vat_number" class="form-control" placeholder="Vat number" value="<?php echo ($this->config->item('vat_number')); ?>">
                            <?php echo form_error('vat_number'); ?>
                        </div>
						<?php 
							$contact = $this->config->item('contact_person')?$this->config->item('contact_person'):$account['name']." ".$account['surname'];
						?>
						<div class="form-group">
                            <label>Contact Person</label>
                            <input type="text" name="contact_person" class="form-control" placeholder="Contact person" required="" value="<?php echo $contact; ?>">
                            <?php echo form_error('contact_person'); ?>
                        </div>
						<div class="form-group">
							<?php 
								$email = $this->config->item('primary_email')==true?$this->config->item('primary_email'):$account['email'];	
							?>
                            <label>Primary email address</label>
                            <input type="email" name="primary_email" class="form-control" placeholder="Primary email address" value="<?=$email?>" autocomplete="no">
                            <?php echo form_error('primary_email'); ?>
                        </div>
						 <div class="form-group">
                            <label>Secondary email address</label>
                            <input type="email" name="secondary_email" class="form-control" placeholder="Secondary email address" required="" value="<?php echo ($this->config->item('secondary_email')); ?>">

                            <p style="padding-top: 5px;"><input type="checkbox" name="same_prime" id="same_trend" data-destination="secondary_email" data-source="primary_email" autocomplete="off">&nbsp;<small>Similar to primary email</small></p>
                            <?php echo form_error('secondary_email'); ?>
                        </div>
						<div class="form-group">
                            <label>Company Registration #</label>
                            <input type="text" name="company_registration" class="form-control" placeholder="Company Registration" value="<?php echo ($this->config->item('company_registration')); ?>">
                            <?php echo form_error('company_registration'); ?>
                        </div>
						<div class="form-group">
                            <label>VAT %</label>
                            <input type="text" name="vat" class="form-control" placeholder="VAT" value="<?php echo ($this->config->item('vat')); ?>">
                            <?php echo form_error('vat'); ?>
                        </div>
                        <div class="form-group">
                            <label>Marketing tag line</label>
                            <input type="text" name="tag_line" class="form-control" placeholder="Marketing tag line" value="<?php echo ($this->config->item('tag_line')); ?>">
                            <?php echo form_error('tag_line'); ?>
                        </div>

                        <div class="form-group">
							<label>Thank you message</label>
							<textarea name="thankyou_message" class="form-control" placeholder="Thank you message"  rows="4" cols="50"><?php echo ($this->config->item('thankyou_message')); ?></textarea>
                        <?php echo form_error('thankyou_message'); ?>
						</div>

                         <div class="form-group">
                            <label>Opened since</label>
                            <select name="opened" class="form-control">
                                <option value="">---SELECT---</option>
                                <?php for ($i = Date("Y"); $i >= 1990; $i--) : ?>
                                    <option value="<?php echo $i; ?>" <?php echo $this->config->item('opened') == $i ? "selected" : "" ?>><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                            <?php echo form_error('opened'); ?>
                        </div>

                        <?php $user = $this->ion_auth->user()->row(); ?>
                        <div class="form-group">
                            <label>New password</label>
                            <input type="password" name="password" class="form-control" placeholder="New Password" autocomplete="off" >
                            <?php echo form_error('password'); ?>
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
            $(document).ready(function() {
                $("input[type=checkbox]").click(function() {
                    var source = $(this).attr("data-source");
                    var destination = $(this).attr("data-destination");

                    $("input[name=" + destination + "]").val($("input[name=" + source + "]").val());

                });
            });
			
        </script>
    </div>
    <?php echo form_close(); ?>
</div>