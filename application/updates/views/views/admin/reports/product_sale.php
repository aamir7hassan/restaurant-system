<div id="page-inner">
    <div class="row">
        <div class="col-md-11">
            <h2>Product Sales Report</h2>   
        </div>
        <div class="clearfix"></div>
    </div>       
    <hr /> 

    <hr/>
    <div class="row">
        <style>
            p.headliner {
                background: #CCC;
                color: #FFF;
                text-align: center;
                padding: 9px 0;
                margin: 23px 0px;
                font-weight: bold;
            }
            p{padding-top:3px;}
            p.spacer{padding-left: 30px;}
        </style>
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Product Sales
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <?php if(count($shift_data) > 0): ?>
                            <?php 
                                $subtotal = 0; 
                                $cash_price; 
                            ?>

                            <?php foreach ($shift_data as $shift):
                                $subtotal  += $shift->price;
                            endforeach;?>

                            <div class="row">
                                <div class="col-sm-12">Orders: <?php echo count($shift_data); ?></div><br/><br/>
                            </div>
                        
                            <div class="row">
                                <div class="col-sm-8">
                                    <p>Subtotal</p>
                                </div>
                                <div class="col-sm-4">
                                    <p><?php echo CURRENCY_CODE." ".price_calc($subtotal); ?></p>
                                </div>
                            </div>
                            
                        
                            <div class="row">
                                <div class="col-sm-8">
                                    <p><strong>Total</strong></p>
                                </div>
                                <div class="col-sm-4">
                                    <p><?php echo CURRENCY_CODE." ".price_calc($subtotal); ?></p>
                                </div>
                            </div>
                        
                            <div class="row">
                                <p class="headliner">Payment Totals</p>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-8">
                                    <p>Card Tip</p>
                                </div>
                                <div class="col-sm-4">
                                    <p><?php echo CURRENCY_CODE." ".price_calc($card_tip); ?></p>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-sm-8">
                                    <p><strong>Total Tip</strong></p>
                                </div>
                                <div class="col-sm-4">
                                    <p><strong><?php echo CURRENCY_CODE." ".price_calc($card_tip); ?></strong></p>
                                </div>
                            </div>
                        
                        <?php else: ?>
                            <p class="">No data found!</p>
                        <?php endif; ?>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
