<div id="page-inner">
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
                    QR Code
                </div>
                <div class="panel-body">
                    <div class="col-md-8">
                        <img src="<?php echo base_url(rtrim($this->config->item('SITE_ID'),"_").'/qr/get/'.$unique); ?>" />
                        <br/><br/>
                        <a class="btn brn-sm btn-success" href="<?php echo site_url('admin/settings/update_qr/'); ?>">Update Qr code</a>
                    </div>    
                </div>
            </div>
            <!--End Advanced Tables -->
        </div>
    </div>
</div>


