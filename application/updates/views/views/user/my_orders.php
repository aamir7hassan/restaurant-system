<!---->
<div class="cart_main">
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>">Home</a></li>
            <li class="active">My Orders</li>
        </ol>	
        <?php if(is_array($orders) && count($orders) > 0): ?>
            
            <h2>Purchase history</h2>
            <?php foreach ($orders as $sku => $items): ?>
                <div class="cart-items">
                    
                    <div class="cart-header">
                        <div class="cart-sec">

                            <div class="cart-item-info">
                                <h3><?php echo $items->product_name; ?></h3>
                                <h4><span></span><?php echo CURRENCY_CODE.price_calc($items->price); ?></h4>
                                <p class="qty">Qty : <?php echo $items->quantity; ?></p>
                            </div>
                            <div class="clearfix"></div>
                                						
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        
        <?php else: ?>
            <div class="alert alert-info">
                <h3>You haven't bought any products yet!</h3>
                <p>You can search our wide range of products from <a href="<?php echo site_url('products/all'); ?>">here!</a> and buy them at low cost!</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<!---->
