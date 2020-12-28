<div class="row">
    <div class="col-sm-8 col-sm-offset-2 text">
        <h1><strong>Welcome&nbsp;&nbsp;</strong><?php echo $account->name.' '.$account->surname; ?></h1>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-sm-offset-3 form-box">
        <div class="form-top">
            <div class="form-top-left">
                <h3>Check out <strong><a href="<?php echo site_url('../'.$account->sku.'/admin'); ?>" target="__blank"><?php echo $account->restaurant_name; ?></a></strong> you have already created!</h3>
                <p>Scan the following QR code and explore the wonder!!</p>
            </div>
            <div class="form-top-right">
                <i class="fa fa-lock"></i>
            </div>
        </div>
        <div class="form-bottom">
            
            <div class="form-group">

            </div>
            
                <img src="<?php echo site_url('qr/get/'.$account->sku); ?>" />
        </div>
    </div>
    
</div>

    
    

