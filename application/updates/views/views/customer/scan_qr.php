<div class="container text-center col-lg-12">
    <div class="row">
        <div class="col-xs-6 grey">
            <?php if($this->config->item("store_logo")): ?>
                <img src="<?php echo base_url( 'assets/images/'.$this->config->item("store_logo") ); ?>" class="img-responsive"/>
            <?php else: ?>
                <img src="<?php echo base_url('assets/images/ResturantLogo.jpg'); ?>" style="width: 100%;">
            <?php endif; ?>
        </div>
        <div class="col-xs-6 grey">
            
        </div>
    </div>
    
    <div class="row">
        
        <div class="btn-area">
            <input type="image" src="<?php echo base_url('assets/images/scanqrcodebutton.png'); ?>">
        </div>
        
    </div>
    
</div>