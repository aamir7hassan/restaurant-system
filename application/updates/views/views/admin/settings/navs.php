<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?php echo $current == 'basic' ? 'active' : ''; ?>">
                <a href="<?php echo site_url('admin/settings'); ?>">Basic Settings</a>
            </li>
            <li class="<?php echo $current == 'info' ? 'active' : ''; ?>">
                <a href="<?php echo site_url('admin/settings/info'); ?>">Restaurant</a>
            </li>
            <li class="<?php echo $current == 'style' ? 'active' : ''; ?>">
                <a href="<?php echo site_url('admin/settings/style'); ?>">Styling</a>
            </li>
            <li class="<?php echo $current == 'qr_code' ? 'active' : ''; ?>">
                <a href="<?php echo site_url('admin/settings/qr_code'); ?>">QR code</a>
            </li>
            <li class="<?php echo $current == 'colour_codes' ? 'active' : ''; ?>">
                <a href="<?php echo site_url('admin/settings/colour_codes'); ?>">Clerk colour codes</a>
            </li>
            <li class="<?php echo $current == 'order_volume' ? 'active' : ''; ?>">
                <a href="<?php echo site_url('admin/settings/order_volume'); ?>">Order Volume</a>
            </li>
            <li class="<?php echo $current == 'import' ? 'active' : ''; ?>">
                <a href="<?php echo site_url('admin/settings/import'); ?>">Import/Export</a>
            </li>
            <li class="<?php echo $current == 'manager_code' ? 'active' : ''; ?>">
                <a href="<?php echo site_url('admin/settings/manager_code'); ?>">Manager Code</a>
            </li>
            <li class="<?php echo $current == 'waiter_codes' ? 'active' : ''; ?>">
                <a href="<?php echo site_url('admin/settings/waiter_codes'); ?>">Authorisation Codes</a>
            </li>
            <li class="<?php echo $current == 'slips' ? 'active' : ''; ?>">
                <a href="<?php echo site_url('admin/settings/slips'); ?>">Slip</a>
            </li>
        </ul>
    </div>
    <div class="clearfix"></div>
    <br/><br/>
</div>