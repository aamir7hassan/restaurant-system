<style>
    tfoot {
        background-color: #c4c6c780;
        color: #000;
    }
</style>
<div id="page-inner">
    <div class="row">
        <div class="col-md-11">
            <h2>Cash Up Report</h2>   
        </div>
        <div class="clearfix"></div>
    </div>       
    <hr /> 
    
    
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default" style="margin-top: 30px">
                <div class="panel-heading">
                    Cash Up
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="cashs_up">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Float</th>
                                    <th><?php echo CURRENCY_CODE." ".$get_float->waiter_float; ?></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <th>Location</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Time</th>
                                    <th>Price</th>
                                    <th>Tip</th>
                                    <th>Payment</th>
                                    <th>Method</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <?php if($cash_ups): ?>
                                    <?php foreach ($cash_ups as $cash_up): ?>
                                        <tr class="">  
                                            <td><?php echo date('j F Y', strtotime($cash_up->date)); ?></td>
                                            <td><?php echo $cash_up->table_name ?></td>
                                            <td><?php echo $cash_up->customer_name ?></td>
                                            <td><?php echo $cash_up->product ?></td>
                                            <td><?php echo $cash_up->category ?></td>
                                            <td>
                                                <?php
                                                    $start = strtotime($cash_up->billrequest_time);
                                                    $end = strtotime($cash_up->released_time);
                                                    $mins = round(abs($end - $start) / 60);
                                                    echo $mins."min"; 
                                                ?>
                                            </td>
                                            <td><?php echo CURRENCY_CODE." ".$cash_up->price ?></td>
                                            <td><?php echo CURRENCY_CODE." ".$cash_up->tip ?></td>
                                            <td><?php echo CURRENCY_CODE; ?> <?php echo $cash_up->price + $cash_up->tip; ?></td>
                                            <td>
                                                <?php 
                                                    if($cash_up->payment_method == 1){
                                                        echo "Cash";
                                                    }
                                                    elseif($cash_up->payment_method == 2){
                                                        echo "Card";
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif;?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td style="font-size: 16px;">Sub Total</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="font-size: 16px;"><?php echo CURRENCY_CODE." ".$get_total_price->total_price; ?></td>
                                    <td style="font-size: 16px;"><?php echo CURRENCY_CODE." ".$get_total_tip->total_tip; ?></td>
                                    <td style="font-size: 16px;"><?php echo CURRENCY_CODE; ?><?php echo $get_total_price->total_price + $get_total_tip->total_tip; ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td style="font-size: 16px;">Balance</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="font-size: 16px;"><?php echo CURRENCY_CODE; ?> <?php echo $get_float->waiter_float + $get_total_tip->total_tip; ?></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td style="font-size: 16px;">Cash</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="font-size: 16px;"><?php echo CURRENCY_CODE; ?> <?php echo $get_cash_payment->total_cash_price; ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td style="font-size: 16px;">Till Balance</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="font-size: 16px;"><?php echo CURRENCY_CODE; ?> <?php echo $get_total_price->total_price + $get_total_tip->total_tip + $get_float->waiter_float + $get_total_tip->total_tip; ?></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#cashs_up').DataTable({
            dom: 'Bfrtip',
            buttons: [
               'csv', 'excel', 'pdf', 'print'
            ],
        });
    });
</script> 
