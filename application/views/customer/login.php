<div class="container text-center col-lg-12">
    <div class="row">
        <div class="col-md-12">
            <br/><br/>
            <?php if($this->config->item("store_logo")): ?>
                <img src="<?php echo base_url( 'assets/images/'.$this->config->item("store_logo") ); ?>" class="img-responsive"/>
            <?php else: ?>
                <img src="<?php echo base_url('assets/images/takkilogo.png'); ?>">
            <?php endif; ?>
        </div>
    </div>
    
    <div class="row">
        
        <?php if ($error = $this->session->flashdata('app_error')): ?>
            <div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $error ?></div>
        <?php endif ?>
            
        <?php echo form_open(''); ?>
            
            <?php if ($error = $this->session->flashdata('app_error')): ?>
                <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $error ?></div>
            <?php endif ?>
            <?php if ($success = $this->session->flashdata('app_success')): ?>
                <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $success ?></div>
            <?php endif ?> 
            
            <div class="text-area">
                <?php echo form_input($email) ?>
                <?php echo form_error('email') ?>
            </div>
            
            <div class="text-area">
                <?php echo form_input($password) ?>
                <?php echo form_error('password') ?>
            </div>
            
            <div class="text-area">
                <input type="number" name="table" placeholder="Enter table number" class="form-control" required="" value="<?php echo !empty($qr_id) ? $qr_id : $this->input->post('table'); ?>">
                <?php echo form_error('table'); ?>
            </div>
            
            <h5><?php echo form_checkbox('remember', '1', FALSE, 'id="remember"') ?> Keep me logged in</h5>
            
            <div class="btn-area">
                <input type="image" src="<?php echo base_url('assets/images/login.png'); ?>" border="0" alt="Submit" />
            </div>
            
        <?php echo form_close(); ?>
            
        <br/>
        
        <a href="<?php echo $fb_url; ?>" target="_blank">
            <img src="<?php echo base_url('assets/images/fbbutton.jpg'); ?>" />
        </a>    
    </div>
    <div class="footer">
        <img src="<?php echo base_url('assets/images/poweredby.jpg') ?>" />
    </div>
</div>