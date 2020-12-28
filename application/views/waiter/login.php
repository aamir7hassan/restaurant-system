<div class="container">
    <div class="row">
        <div class="header " style="max-height: 100px;background: #b2b2b2;">
            <div class="col col-md-9 col-xs-6" style="max-height: 100px">
                <?php if($this->config->item("store_logo")): ?>
                <img src="<?php echo base_url( 'assets/images/'.$this->config->item("store_logo") ); ?>" style="max-height: 97px;" class="img-responsive"/>
                <?php else: ?>
                    <img src="<?php echo base_url('assets/images/ResturantLogo.jpg'); ?>" style="max-height: 97px;">
                <?php endif; ?>
            </div>
            <div class="col col-md-3 grey hidden-sm hidden-xs hide">
                <h2>Welcome</h2>
            </div>
            <div class="col col-md-3 dark_grey col-xs-6">
                <h2>
                    <a href="#">Login</a>
                </h2>    
            </div>    
            <div class="col col-md-2 grey hidden-sm hidden-xs hide">
                <h2>
                    <?php echo date("g:i A"); ?>
                </h2>    
            </div>
            <div class="clearfix"></div>
        </div>
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
        
        <br/><br/><br/><br/><br/><br/>
        <div class="row text-center">
            <?php echo form_open(''); ?>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="email" class="form-control input-lg" placeholder="Name" required="">
                        <?php echo form_error('email'); ?>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control input-lg" placeholder="Password" required="" autocomplete="off">
                        <?php echo form_error('password'); ?>
                    </div>
                    <input type="hidden" name="remember" value="1"/>
                    <div class="form-group">
                        <input type="submit" name="Proceed.." value="Login" class="btn btn-lg btn-success">            
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>