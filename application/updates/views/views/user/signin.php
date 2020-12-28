<div class="container">
    <div class="row">
        <style>
            .header {
                background: #fff none repeat scroll 0 0;
                padding-bottom: 9px;
                padding-top: 15px;
            }
            .text-area input {
                background: #eeeeee none repeat scroll 0 0;
                border: 1px solid #333333;
                color: #000000;
                display: inline;
                height: 50px;
                width: 80%;
            }
            .text-area, .btn-area{
                padding-top: 38px;
            }
        </style>
    </div>
    
    <div class="content_area">
        
        <div class="row">
            <div class="col-md-2">&nbsp;</div>
            <div class="col-md-8">
                <?php if ($error = $this->session->flashdata('app_error')): ?>
                <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $error ?></div>
                <?php endif ?>

                <?php if ($success = $this->session->flashdata('app_success')): ?>
                <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $success ?></div>
                <?php endif ?>
            </div>
            <div class="clearfix"></div>
        </div>
        
        <br/>
        <div class="row text-center">
            <?php echo form_open(''); ?>
                <div class="col-md-4"></div>
                <div class="col-md-4">
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
                </div>
                <div class="clearfix"></div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>