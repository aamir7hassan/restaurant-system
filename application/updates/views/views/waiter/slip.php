<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Print Slip</title>
    <style>
        @media print {
            @page {
                margin: 0 auto; /* important to logo margin */
                sheet-size: 400px 250mm; /* important to set paper size */
            }

            html {
                direction: rtl;
            }

            html,body{
                margin:0;padding:0
            }

            #printContainer {
                width: 350px;
                margin: 20px auto;
                /*padding: 10px;*/
                /*border: 2px dotted #000;*/
                text-align: justify;
            }

           .text-center{
               text-align: center;
            }

            #tax{
                font-weight: 12px;
                font-size: 20px;
                padding-top: 20px;
            }

            .company{
                font-weight: 12px;
                font-size: 20px;
                padding-top: -20px;
            }

            .address{
                font-weight: 12px;
                font-size: 12px;
                padding-top: -15px;
            }

            .pl-30{
                padding-left: 30px;
            }
        }
    </style>
</head>
<body onload="window.print();">
    <div id='printContainer'>
        <p id="tax" class="text-center">Tax Invoice </p>
        <p class="text-center company"><?php echo ($this->config->item('company_name')); ?></p>
        <p class="text-center address"><?php echo ($this->config->item('slip_address')); ?></p>
        <p class="text-center address">
            TEL. <?php echo ($this->config->item('slip_tel')); ?> 
            <span class="pl-30">FAX. <?php echo ($this->config->item('slip_fax')); ?></span>
        </p>
        <p class="text-center address">
            VAT NO. <?php echo ($this->config->item('slip_vat')); ?>
        </p>
        <p class="text-center address">
            INVOICE NO. 000<?php echo $this->uri->segment('3'); ?>
        </p>
        <?php echo $get_email->email?>
        <table style="width: 100%">
            <tr><td colspan="2"></td></tr>
            <?php foreach($order as $ord):?>
            <tr>
                <td><span style="font-size: 13px;"><?php echo $ord->mname; ?></span></td>
                <td></td>
                <td><span style="font-size: 13px;"><?php echo $ord->single_price; ?></span></td>
            </tr>
            <?php endforeach; ?>
            
            <tr><td colspan="2"></td></tr>

            <tr>
                <td><span style="font-size: 13px; padding-top: 20px"></span></td>
                <td><span style="font-size: 13px; padding-top: 20px">TIP</span></td>
                <td><span style="font-size: 13px; padding-top: 20px"><?php echo $order_details->tip; ?></span></td>
            </tr>

            <tr><td colspan="2"></td></tr>

            <tr>
                <td><span style="font-size: 13px; padding-top: 20px">ITEMS <?php echo count($order); ?></span></td>
                <td><span style="font-size: 13px; padding-top: 20px">TOTAL</span></td>
                <?php foreach($order_tot_price as $order_tot_pric):?>
                    <?php $tot_vat = $order_tot_pric->price * ($this->config->item('vat') / 100); ?>
                    <td><span style="font-size: 13px; padding-top: 20px"><?php echo $order_tot_pric->price + $tot_vat + $order_tot_pric->tip; ?></span></td>
                <?php endforeach; ?>
            </tr>
            
            <tr><td colspan="2"></td></tr>

            <tr>
                <?php if($order_details->payment_method == 1){ ?>
                    <td><span style="font-size: 13px;">CASH</span></td>
                <?php }else if($order_details->payment_method == 2){?>
                    <td><span style="font-size: 13px;">CARD</span></td>
                <?php } ?>
                <td></td>
                <td><span style="font-size: 13px;"><?php echo $order_details->tendered; ?></span></td>
            </tr>
            
            <tr><td colspan="2"></td></tr>

            <tr>
                <td><span style="font-size: 13px;">CHANGE</span></td>
                <td></td>
                <td><span style="font-size: 13px;"><?php echo $order_details->tendered_change; ?></span></td>
            </tr>

            <tr><td colspan="2"></td></tr>

            <tr>
                <td><span style="font-size: 13px;">VAT-CODE</span></td>
                <td><span style="font-size: 13px;">NET-VAL</span></td>
                <td><span style="font-size: 13px;">VAT-VAL</span></td>
            </tr>
            
            <tr><td colspan="2"></td></tr>

            <tr>
                <td><span style="font-size: 13px;">VAT <?php echo ($this->config->item('vat')); ?>%</span></td>
                <td><span style="font-size: 13px;"><?php echo $order_tot_pric->price; ?></span></td>
                <td><span style="font-size: 13px;"><?php echo $order_tot_pric->price * ($this->config->item('vat')) / 100; ?></span></td>
            </tr>

            <tr><td colspan="4"></td></tr>

            <tr>
                <td></td>
                <td><span style="font-size: 13px;"><?php echo date ('H:i:s',strtotime($order_details->reserved_time)); ?></span></td>
                <td><span style="font-size: 13px;"><?php echo date ('d/m/Y',strtotime($order_details->reserved_time));?></span></td>
            </tr>
        </table>
        
        <div class="text-center" style="font-size: 16px;padding-top: 20px;">
            <?php echo ($this->config->item('slip_thank_you_message')); ?>
        </div>
        <hr>
    </div>
</body>
</html>