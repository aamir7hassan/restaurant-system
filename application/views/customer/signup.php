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

            <div class="text-area">
                <?php echo form_input($first_name) ?>
                <?php echo form_error('first_name') ?>
            </div>
        
            <div class="text-area">
                <?php echo form_input($email) ?>
                <?php echo form_error('email') ?>
            </div>
            
            <div class="text-area">
                <?php echo form_input($password) ?>
                <?php echo form_error('password') ?>
            </div>

            <div class="btn-area">
                <input type="image" src="<?php echo base_url('assets/images/signup.png'); ?>" border="0" alt="Submit" />
            </div>
        <?php echo form_close(); ?>
    </div>
    <div class="footer">
        <img src="<?php echo base_url('assets/images/poweredby.jpg') ?>" />
    </div>
</div>