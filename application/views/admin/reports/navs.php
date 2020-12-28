<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <!--
            <li class="<?php echo $current == 'shifts' ? 'active' : ''; ?>">
                <a href="<?php echo site_url('admin/reports'); ?>">Waiter Shifts</a>
            </li>-->
            
            <li class="<?php echo $current == 'waiter_sales' ? 'active' : ''; ?>">
                <a href="<?php echo site_url('admin/reports/waiter_sales'); ?>">Waiter Sales</a>
            </li>

            <!--
            <li class="<?php echo $current == 'tables' ? 'active' : ''; ?>">
                <a href="<?php echo site_url('admin/reports/tables'); ?>">Tables</a>
            </li>

            <li class="<?php echo $current == 'turnover' ? 'active' : ''; ?>">
                <a href="<?php echo site_url('admin/reports/turnover'); ?>">Table Turnover</a>
            </li>-->

            <li class="<?php echo $current == 'product_sales' ? 'active' : ''; ?>">
                <a href="<?php echo site_url('admin/reports/product_sales'); ?>">Product Sales</a>
            </li>

            <li class="<?php echo $current == 'table_sales' ? 'active' : ''; ?>">
                <a href="<?php echo site_url('admin/reports/table_sales'); ?>">Table Sales</a>
            </li>
        </ul>
    </div>
    <div class="clearfix"></div>
    <br/><br/>
</div>