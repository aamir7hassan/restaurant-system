<?php

$details        = array();  $i = 0;
$existing_auths = array();
$ordered_now    = FALSE;

$new_list = array();


$uniquer = array();

foreach ($orders as $key => $order):

  if(empty($order->order_time) && $order->virtual == 1)
  continue;

  if(!is_null($order->oid)){
    if(in_array($order->oid, $uniquer))
    continue;

    $uniquer[] = $order->oid;
  }

  if($order->payed_by == 0){
    $new_list[$order->order_id][] = $order;
  }
  else{
    $new_list[$order->payed_by][] = $order;
  }

endforeach;
//echo "<pre>";var_dump($new_list); echo "</pre>"; die;

foreach ($new_list as $key => $orders)
{
  foreach ($orders as $ids => $order)
  {


    $attr = array();

    $attribute_data = json_decode(trim($order->attribute), true);

    if(is_array($attribute_data)){
      foreach ($attribute_data as $attrs){
        $attr[] = $attrs;
      }
    }

    $attr_txt = implode(', ', $attr);

    $attr_actual_text = !empty($attr_txt) ? $attr_txt : '';

    //$order->id = $key;
    $order->id = $key;

    $details['master'.$order->id]['tname']          = $order->tname;
    $details['master'.$order->id]['tid']          = $order->id;
    $details['master'.$order->id]['address']        = $order->address;
    $details['master'.$order->id]['virtual']        = $order->virtual;

    if(!empty($order->mode))
    $details['master'.$order->id]['mode']           = $order->mode;

    $details['master'.$order->id]['meal'][]         = '(Category) '.$order->category.', (Product) '.$order->mname.', (Attributes) '.$attr_actual_text;
    $details['master'.$order->id]['time'][]         = $order->order_time;
    $details['master'.$order->id]['rtime']          = $order->reserved_time;
    $details['master'.$order->id]['qty'][]          = $order->qty;
    $details['master'.$order->id]['ptime'][]        = $order->process_time;
    $details['master'.$order->id]['wprocess'][]     = $order->waiter_process_time;
    $details['master'.$order->id]['kitchen'][]      = $order->kitchen_left;
    $details['master'.$order->id]['processed'][]    = $order->processed;
    $details['master'.$order->id]['oid'][]          = $order->oid;
    $details['master'.$order->id]['user_id']      = $order->user_id;
    $details['master'.$order->id]['comment'][]      = $order->comment;
    $details['master'.$order->id]['customer_name'][]= $order->customer_name;
    $details['master'.$order->id]['under_18'][]     = $order->under_18;

    $details['master'.$order->id]['status']         = (isset($details['master'.$order->id]['status']) && $details['master'.$order->id]['status'] == 'paybill') ? 'paybill' : $order->status ;

    $details['master'.$order->id]['order_id']       = $order->order_id;

    if ($order->payed_by == 0)
    {
      $details['master'.$order->id]['master_id']  = $order->order_id;
      $details['master'.$order->id]['price']      = $order->price;
      $details['master'.$order->id]['delivery']   = $order->delivery_charge;
      $details['master'.$order->id]['tip']        = $order->tip;

    }

    ++$i;        unset($attr);
  }
}
?>

<div class="container">
  <style type="text/css">
  a:hover{
    text-decoration: none;
  }
  .stop-scrolling {height: 100% !important;overflow: hidden!important;}
  .text-area.take_away_section{padding:0;}
  .take_away_section .btn-true{color: #FFFFFF !important;}
  .take_away_section div.buttons{
    height: 100%;
    text-align: center;
    padding: 9px 0;
    font-size: 17px;
    cursor: pointer;
  }
  .button-area{
    height: 44px;
    margin: 0 40px;
  }
  .take_away_section button{padding: 14px 22px;}
  .take_away_section button.btn-default{background: #ffffff;}
  .header-img{margin: -10px auto;width: 180px; }

  .stylish-input-group .input-group-addon{
    background: white !important;
  }
  .stylish-input-group .form-control{
    border-right:0;
    box-shadow:0 0 0;
    border-color:#ccc;
  }
  .stylish-input-group button{
    border:0;
    background:transparent;
  }
  .text-area, .btn-area{
    padding-top: 16px;
  }
  button.submit{
    background: #d9edf7;
    color: #FFF;
    font-size: 16px;
    font-weight: 600;
  }
  .text-area input[type="text"],.text-area button, .text-area input[type="number"]{
    width: 80%;
    display: inline;
    color: #000000;
    height: 44px;
  }
  .calculator_view .modal-dialog{max-width: 800px; width: 100%;}
  .pre-cost{text-decoration: line-through; color: #a5a5a5;}
  .space-ten{padding: 10px 0;}

  #new_customer_sit {
  	background-color: gray;
  	text-align: center;
  	padding: 7px;

  }

  #display {
  	width: 98%;
  	height: 30px;
  	text-align: right;
  	font-size: 1.5rem;
  }
  .digit {
  	font-size: 2rem;
  	background-color: white;
  	height: 60px;
  	width: 22%;
  	border-radius: 5px;
  	display: inline-block;
  	padding: 5px;
    margin: 3px;
    font-weight:bold;
  }
  .oper {
    font-weight:bold;
    margin: 3px;
  	font-size: 2rem;
  	background-color: #3498db;
  	height: 60px;
  	width: 22%;
  	border-radius: 5px;
  	display: inline-block;
  	padding: 4px;
    background-image: -webkit-gradient(radial, 50% 0%, 0, 50% 0%, 100, color-stop(0%, #2c8cbf), color-stop(100%, #194f6c));
    background-image: -webkit-radial-gradient(top center, #2c8cbf, #194f6c);
    background-image: -moz-radial-gradient(top center, #2c8cbf, #194f6c);
    background-image: -o-radial-gradient(top center, #2c8cbf, #194f6c);
    background-image: radial-gradient(top center, #2c8cbf, #194f6c);
    background-image: -ms-radial-gradient(top center, #2c8cbf, #194f6c);
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    -ms-border-radius: 5px;
    -o-border-radius: 5px;
    border-radius: 5px;
    -webkit-box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
    -moz-box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
    color: white;
    border: 0px;
    font-weight: bold;
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.6);
  }
  .digit:hover {
    position: relative;
    top: 1px;
    left: 1px;
    border-color: #e5e5e5;
    cursor: pointer;
    -webkit-box-shadow: 0px 0px 10px 3px rgba(25,79,108,0.6);
box-shadow: 0px 0px 10px 3px rgba(25,79,108,0.6);
  }
  .oper:hover {
    position: relative;
    top: 1px;
    left: 1px;
    border-color: #e5e5e5;
    cursor: pointer;
    -webkit-box-shadow: 0px 0px 10px 3px rgba(25,79,108,0.6);
box-shadow: 0px 0px 10px 3px rgba(25,79,108,0.6);
  }
  #clearMem {
  	background-color: green;
  }
  #equal {
  	background-color: yellow;
  }
  hr {
    border: 0;
    clear:both;
    display:block;
    width: 100%;
    background-color:black;
    height: 1px;
    margin-top: 10px;
    margin-bottom: 10px;
  }
  .green{ background: <?php echo $this->config->item('new_order_colour') ? $this->config->item('new_order_colour') : '#006633'; ?>; color: #FFFFFF !important;}
  .blue{ background: <?php echo $this->config->item('fivemin_order_colour') ? $this->config->item('fivemin_order_colour') : '#00b3b3'; ?>; color: #FFFFFF !important;}
  .dark{ background: <?php echo $this->config->item('processed_order_colour') ? $this->config->item('processed_order_colour') : '#888e8e'; ?>; color: #FFFFFF !important;}
  .red{ background: <?php echo $this->config->item('tfivemin_order_colour') ? $this->config->item('tfivemin_order_colour') : '#16a085'; ?>; color: #FFFFFF !important;}
  .pay_bill{background:<?php echo $this->config->item('billto_order_colour') ? $this->config->item('billto_order_colour') : '#000'; ?>; color:#FFFFFF;}
  .min30{background:<?php echo $this->config->item('thirty_order_colour') ? $this->config->item('thirty_order_colour') : '#16a085'; ?>; color:#FFFFFF;}
  .left_kitchen{background:<?php echo $this->config->item('kitchen_left_color') ? $this->config->item('kitchen_left_color') : '#000'; ?>; color:#FFFFFF;}
  </style>

  <div class="row">
    <a href="javascript:void(0);" class="slider-login slider-hide" style="background: #3498db;border: none;display: block;height : 130px;margin-right: 0px;right: 0px;position: fixed;top: 76px;width: 25px;z-index: 999;writing-mode: vertical-lr;text-align: center;border-radius: 25px 0px 0px 25px;">New Table</a>
    <div class='panel' style="width:300px;float:right;height:550px;background: #3498db;position:fixed;right: -300px;top: 60px;z-index:999;">
    <div class="container text-center col-lg-12">
      <div class="">
        <div class="row custom-header-area">
          <div class="col-md-12">
            <br/><br/>

            <?php if($this->config->item("store_logo")): ?>
              <img  src="<?php echo base_url( 'assets/images/'.$this->config->item("store_logo") ); ?>" class="header-img img-responsive"/>
            <?php else: ?>
              <img src="<?php echo base_url('assets/images/takkilogo.png'); ?>" class="header-img img-responsive">
            <?php endif; ?>

          </div>
        </div>

        <div class="row">
          <div class="text-area button-area take_away_section">
            <div class="col-xs-6 buttons btn-true" data-id="#sit">Eat-In</div>
            <div class="col-xs-6 buttons" data-id="#take">Take Away</div>
          </div>

          <div class="clearfix"></div>
        </div>

        <div class="row" id="sit">
          <?php if ($error = $this->session->flashdata('app_error')): ?>
            <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $error ?></div>
          <?php endif ?>
          <?php if ($success = $this->session->flashdata('app_success')): ?>
            <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $success ?></div>
          <?php endif ?>

          <?php $attributes = array( 'id' => 'new_customer_sit');
          echo form_open('waiters/test', $attributes); ?>
          <div class="text-area">
            <input type="text" name="customer_name" placeholder="Enter your name" class="form-control" required="" value="<?php echo $this->input->post('customer_name') ? $this->input->post('customer_name') : ''; ?>">
            <?php echo form_error('customer_name'); ?>
          </div>

          <div class="text-area">
            <input type="number" name="table" placeholder="Enter table number" class="form-control" required="" value="<?php echo !empty($qr_id) ? $qr_id : $this->input->post('table'); ?>">
            <?php echo form_error('table'); ?>
          </div>

          <div class="text-area manager">
            <input type="text" name="manager_code" class="manager_code" placeholder="Enter your code" class="form-control" required >
            <?php echo form_error('manager_code'); ?>
          </div>
          <!--
            <div class="text-area">
              <p>I am of the legal age to consume alcohol. <input type="checkbox" name="over_18" /></p>
            </div>
          -->
          <div class="text-area">
            <input class="submit btn" type="submit" value="GET ORDERING">
          </div>

          <input type="hidden" name="option" value="normal" />
          <input type="hidden" name="over_18" class="over_18" value="0" />
          <?php echo form_error('option'); ?>
          <?php echo form_close(); ?>
          <div class="clearfix"></div>
        </div>

        <div class="row" id="take" style="display: none">
          <?php if ($error = $this->session->flashdata('app_error')): ?>
            <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $error ?></div>
          <?php endif ?>
          <?php if ($success = $this->session->flashdata('app_success')): ?>
            <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $success ?></div>
          <?php endif ?>

          <?php $attributes = array( 'id' => 'new_customer_take');
          echo form_open('waiters/test', $attributes); ?>
          <div class="text-area">
            <input type="text" name="cell" placeholder="Cell no" class="form-control" value="<?php echo $this->input->post('cell') ? $this->input->post('cell') : ''; ?>" required >
            <?php echo form_error('cell'); ?>
          </div>
          <div class="text-area manager">
            <input type="text" name="manager_code" class="manager_code"  placeholder="Enter your code" class="form-control" required >
            <?php echo form_error('manager_code'); ?>
          </div>
          <!--
            <div class="text-area">
              <p>I am of the legal age to consume alcohol. <input type="checkbox" name="over_18" /></p>
            </div>
          -->
          <div class="text-area">
            <input class="submit btn" type="submit" value="GET ORDERING">
          </div>

          <input type="hidden" name="option" value="takeaway" />
          <input type="hidden" name="over_18" class="over_18" value="0" />
          <?php echo form_error('option'); ?>
          <?php echo form_close(); ?>
          <div class="clearfix"></div>
        </div>

      </div>
      <div class="confirmation hide" style="max-width: 350px">
        <h2 style="font-size: 23px;">I am of the legal age to consume alcohol</h2>
        <div class="btn-group">
          <button type="button" class="btn btn-yes">Yes</button>
          <button type="button" class="btn btn-no">No</button>
        </div>
      </div>
    </div>

  </div>
  <div class="header " style="max-height: 70px;background: #3498db;color:white;">
    <div class="col col-md-3 col-xs-3" style="max-height: 70px">
      <?php if($this->config->item("store_logo")): ?>
        <img src="<?php echo base_url( 'assets/images/'.$this->config->item("store_logo") ); ?>" style="max-height: 64px;" class="img-responsive"/>
      <?php else: ?>
        <img src="<?php echo base_url('assets/images/ResturantLogo.jpg'); ?>" style="max-height: 64px;">
      <?php endif; ?>
    </div>

    <?php $user = $this->ion_auth->user()->row(); ?>
    <div class="col col-md-3 hidden-sm hidden-xs">
      <h2>Welcome <?php echo ucwords($user->username); ?></h2>
    </div>
    <div class="col col-md-1  col-xs-1">
      <h2>
        <?php echo date("g:i A"); ?>
      </h2>
    </div>
    <div class="col col-md-5  col-xs-5">
      <div class="row" style="padding-left:15px;">
        <div class="col col-md-7  col-xs-7">
          <div class="row"  style="padding-left:15px;">
            <h2 style="display: inline-block;"><a  class='all-orders ' href="<?php echo site_url('waiters') ?>">All</a></h2>
            <h2 style="display: inline-block;padding-left:5px;padding-right:5px;"><img src="<?php echo base_url('assets/images/divider.png'); ?>" style="max-height: 34px;"></h2>
            <h2 style="display: inline-block;"><a class="new-orders <?php echo $notifications['new'] ?>" href="<?php echo site_url('waiters/index/new') ?>">New</a></h2>
            <h2 style="display: inline-block;padding-left:5px;padding-right:5px;"><img src="<?php echo base_url('assets/images/divider.png'); ?>" style="max-height: 34px;"></h2>
            <h2 style="display: inline-block;"><a class="delivered-orders <?php echo $notifications['delivered'] ?>" href="<?php echo site_url('waiters/index/delivered') ?>">Delivered</a></h2>
            <h2 style="display: inline-block;padding-left:5px;padding-right:5px;"><img src="<?php echo base_url('assets/images/divider.png'); ?>" style="max-height: 34px;"></h2>
            <h2 style="display: inline-block;"><a class="waiting-orders <?php echo $notifications['waiting'] ?>" href="<?php echo site_url('waiters/index/waiting') ?>">Waiting</a></h2>
            <h2 style="display: inline-block;padding-left:5px;"><img src="<?php echo base_url('assets/images/divider.png'); ?>" style="max-height: 34px;"></h2>
          </div>
        </div>
        <div class="col col-md-5  col-xs-5">
          <div class="row" style="margin-left:5px;padding-left:5px;">
            <h2  id="collapse_all" style="display: inline-block;">
              <a href="#">
                <img src="<?php echo base_url('assets/images/collapseicn.png'); ?>" style="max-height: 34px;">
              </a>
            </h2>
            <h2 id="expand_all" style="display: inline-block;">
              <a href="#">
                <img src="<?php echo base_url('assets/images/expandicn.png'); ?>" style="max-height: 34px;">
              </a>
            </h2>
            <h2 style="display: inline-block;">
              <a href="<?php echo site_url('waiters/logout') ?>">
                <img src="<?php echo base_url('assets/images/logouticn.png'); ?>" style="max-height: 34px;">
              </a>
            </h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col col-md-2 hidden-sm hidden-xs hide">
      <h2>
        <?php echo date("g:i A"); ?>
      </h2>
    </div>
    <div class="clearfix"></div>
  </div>
</div>
<div class="content_area">
  <div class="row">
    <?php $i = 1; ?>
    <?php foreach ($details as $key => $det): ?>

      <?php //if(empty($det['tname'])){continue;} ?>
      <div class="col-md-6 col-sm-6" >
        <h1 class="table_header" style="margin-bottom:0 !important; height:52px;background: #3498db ;color:white;border-radius: 25px 25px 0px 0px; cursor:n-resize;">
          <div class="force_close" data-id="<?php echo $det['master_id']; ?>" data-userid="<?php echo $det['user_id']; ?>"><img src="<?php echo base_url('assets/images/close2icn.png'); ?>" style="max-height: 48px;"></div>
          <span class="table_customer"><?php echo 'L '.$det['tname'].' | '.$det['customer_name'][0]; ?></span>
          <div class="pull-right">
            <?php echo Date('G:i A', strtotime( $det['rtime'] ) ); ?>
          </div>
          <div class="pull-right" style="padding-right: 20px;">
            <?php echo $det['under_18'][0] == 1 ? "<span class='under'>18</span>" : ""; ?>
          </div>
          <div class="pull-right" style="padding-right: 40px;">
            <a href="<?php echo site_url('waiters/print_order/'.$det['master_id']) ?>"><img src="<?php echo base_url('assets/images/printicn.png'); ?>" style="max-height: 30px;"></a>
            <div class="clearfix"></div>
          </h1>
          <div class="close_box text-justify" style="display:none;background:white;height:250px;text-align: center;">
            <div class="row">
              <span style="color:red;font-size: 30px;">Bill to customer</span>
            </div>
            <div class="row" style="padding:10px;">
            <button type="button" name="close_table"  data-id="<?php echo $det['master_id']; ?>" data-userid="<?php echo $det['user_id']; ?>"class="btn btn-danger btn-large close_table">CLOSE TABLE</button>
            </div>
            <div class="row" style="padding:10px;">
            <button type="button" name="point_of_sale" data-id="<?php echo $det['master_id']; ?>" data-userid="<?php echo $det['user_id']; ?>"class="btn btn-danger btn-large point_sale"  data-toggle="modal" data-target="#calculator_view">POINT OF SALE</button>
            </div>

          </div>
          <div class="details" style="background:#272323CC; color: #FFFFF;">
            <?php if (isset($det['meal']) && count($det['meal']) > 0): ?>
              <?php if( $det['status'] == 'nvr' ): ?>
                <div class="pay_bill">
                  <?php echo form_open('', array('class' => 'close_form')); ?>
                  <input type="hidden" name="order_id" value="<?php echo $det['master_id']; ?>">
                  <h2 class="error">Bill to customer</h2>
                  <h3><?php echo CURRENCY_CODE.' '.number_format($det['price']+$det['tip']+$det['delivery'], 2, '.', ''); ?></h3>
                  <br/>
                  <?php if(isset($det['mode'])):?>
                    <p>Payment :
                      <?php

                      if($det['mode'] == 1)
                      echo 'Cash';
                      else if( $det['mode'] == 2)
                      echo 'Card';
                      ?></p>
                    <?php endif; ?>
                    <!-- <button class="btn btn-danger btn-large" id="close">Close location</button>-->
                    <?php echo form_close(); ?>
                  </div>
                <?php else: ?>
                  <ul class="meal_list">
                    <?php if( $det['status'] == 'paybill' ): ?>
                      <li class="" style="color:#FFFFFF; background: #FF0000;">
                        <div class="col-xs-9">
                          <?php echo form_open('', array('class' => 'close_form')); ?>
                          <input type="hidden" name="order_id" value="<?php echo $det['master_id']; ?>">
                          Bill to customer - <?php echo CURRENCY_CODE.' '.number_format($det['price']+$det['tip']+$det['delivery'], 2, '.', ''); ?> -
                          <?php
                          if($det['mode'] == 1)
                          echo 'Cash';
                          else if( $det['mode'] == 2)
                          echo 'Card';
                          ?>
                          <br/>
                          <?php echo $det['virtual'] == 1 ? empty($det['address']) ? "Option: Collection" : "Option: Delivery, Address: ".$det['address'] : "Option: Sit down"; ?>
                          <!-- <button style="margin-right:10px;" class="btn btn-danger btn-sm pull-right" id="close">X</button> -->
                          <?php echo form_close(); ?>
                        </div>
                        <div class="col-xs-3">
                          <button style="font-size:12px;" class="btn btn-confirm-user-payment" data-id="<?php echo $det['order_id']; ?>">Confirm Payment</button>
                        </div>
                        <div class="clearfix"></div>
                      </li>
                    <?php endif; ?>

                    <?php if( $det['status'] == 'paid' ): ?>
                      <li class="" style="color:#FFFFFF; background: #FF0000;">
                        <div class="col-xs-12 text-center" style="font-size:18px;">
                          PAID
                        </div>
                        <div class="clearfix"></div>
                      </li>
                    <?php endif; ?>

                    <?php foreach ($det['meal'] as $k => $data): ?>
                      <?php if(!empty($data)){ ?>
                        <?php
                        $li_class_color = '';
                        if($det['processed'][$k] == 1)
                        $li_class_color = 'left_kitchen';
                        else if($det['processed'][$k] == 2)
                        $li_class_color = get_colour_kitchen($det['wprocess'][$k]);//'left_kitchen';
                        else if($det['processed'][$k] == 0)
                        $li_class_color = get_colour($det['time'][$k]);
                        ?>
                        <?php ( (strtotime('now')-strtotime($det['time'][$k])) <= 10 ) ?  $ordered_now = TRUE : ''; ?>
                        <li class="order <?php echo $li_class_color; ?>" style="color:#FFFFFF" data-oid="<?php echo $det['oid'][$k]; ?>">
                          <p class="col-xs-10">
                            <?php echo $data.' ('.time_took($det['time'][$k], $det['ptime'][$k]).')'; ?>
                            <br/>
                            <?php echo !empty($det['comment'][$k]) ? '<br/>Comment: '.$det['comment'][$k].'' : ''; ?>
                            <?php echo !empty($det['customer_name'][$k]) ? '<br/>Customer: '.$det['customer_name'][$k].'' : ''; ?>
                          </p>
                          <div class="col-xs-2">
                            <div style="padding-left:30px" data-oid="<?php echo $det['oid'][$k]; ?>"><img src="<?php echo base_url('assets/images/close1icn.png'); ?>" style="max-height: 28px;"></div>
                          </div>
                          <div class="clearfix"></div>
                        </li>
                      <?php } ?>
                    <?php endforeach; ?>
                    <li class="text-center" style="background: #3498db;">
                        <div class="text-center" >
                            <a  class="order_button" style="font-size: 16px;color:#FFFFFF;" href="<?php echo site_url('waiters/waiter/order_'.$det['order_id'].'/table_'.$det['tid']) ?>" >ORDER</a>
                        </div>
                    </li>
                  </ul>
                <?php endif; ?>
              <?php endif; ?>
            </div>

          </div>

          <?php if ($i == 2): ?>
            <div class="clearfix"></div>
          </div>
          <div class="row">
            <?php $i = 0; endif; ?>
            <?php ++$i; ?>
          <?php endforeach; ?>
          <div class="clearfix"></div>
        </div>

        <?php echo form_open('', array('id' => 'order_form')); ?>
        <input type="hidden" name="order_id" id="order_id">
        <input type="hidden" name="notice_id" id="notice_id" value="">
        <input type="hidden" name="main_id" id="main_id" value="">
        <?php echo form_close(); ?>
        <div class="notice_area">
          <?php if(isset($notices) && is_array($notices)) :?>
            <?php foreach ($notices as $notice): ?>
              <div class="notice close_notice" style="cursor:pointer;" data-id="<?php echo $notice->id; ?>">
                <?php echo $notice->message.' '.$notice->table_id; ?> (<?php echo time_took($notice->date, Date('Y-m-d H:i:s')); ?>) <div data-id="<?php echo $notice->id; ?>" class="pull-right glyphicon glyphicon-eye-close close_notice" style="cursor:pointer;"></div><div class="clearfix"></div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="flash_red flash_blue hidden"></div>
      <?php if($ordered_now): ?>
        <audio id="id1" src="<?php echo base_url('/audio/glass_ping-Go445-1207030150.mp3'); ?>"></audio>
        <script type="text/javascript">
          jQuery(document).ready(function(){
            $("#id1").get(0).play();
          });

        </script>
      <?php endif; ?>


          </div>

          <?php if (!$this->input->is_ajax_request()) : ?>
            <div class="bottom_row hidden-xs hidden-sm">
              <ul class="list-inline" style="color:#FFFFFF;  height: 28px; ">
                <li class="footer_li" style="background:<?php echo $this->config->item('new_order_colour') ? $this->config->item('new_order_colour') : '#006633'; ?>"><b>NEW ORDER</b>
                </li>
                <li class="footer_li" style="background:<?php echo $this->config->item('processed_order_colour') ? $this->config->item('processed_order_colour') : '#888e8e'; ?>"><b> ORDER PROCESSED</b>
                </li>
                <li class="footer_li" style="background:<?php echo $this->config->item('fivemin_order_colour') ? $this->config->item('fivemin_order_colour') : '#00b3b3'; ?>"><b> CLERK NOT PROCESSED ORDER IN 5 MINUTES</b>
                </li>
                <li class="footer_li" style="background:<?php echo $this->config->item('kitchen_left_color') ? $this->config->item('kitchen_left_color') : '#000'; ?>"> <b>ORDER LEFT KITCHEN</b>
                </li>
                <li class="footer_li" style="background:<?php echo $this->config->item('tfivemin_order_colour') ? $this->config->item('tfivemin_order_colour') : '#16a085'; ?>"> <b>ORDER NOT LEFT KITCHEN IN 25 MINUTES</b>
                </li>
                <li class="footer_li" style="background:<?php echo $this->config->item('billto_order_colour') ? $this->config->item('billto_order_colour') : '#16a085'; ?>"> <b>BILL TO LOCATION</b>
                </li>

              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="modal fade calculator_view" id="calculator_view">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 product_img">
                                  <div class="row">
                                    <div class="col-md-8 col-md-offset-2 " style="font-size:22px;">
                                      <span id="customer_name"></span> | L <span id="tab_name"></span>
                                    </div>
                                  </div>
                                  <hr>
                                  <div class="row">
                                    <div class="col-md-5 col-md-offset-3" style="font-size:22px;">
                                      <span class="currency"></span> <span id="header_total">00.00</span>
                                    </div>
                                  </div>
                                  <hr>
                                  <div class="row">
                                    <div class="col-md-6">
                                    <b>Product</b>
                                    </div>
                                    <div class="col-md-2">
                                      <b>Qty</b>
                                    </div>
                                    <div class="col-md-4">
                                    <b>Price</b>
                                    </div>
                                  </div>
                                  <hr>
                                  <div class="row" id="bill_items">
                                  </div>
                                  <hr>
                                  <div class="row">
                                    <div class="col-md-6">
                                    <b>Total</b>
                                    </div>
                                    <div class="col-md-4 col-md-offset-2">
                                    <span class="currency"></span> <span id="total" ></span>
                                    </div>
                                  </div>
                                  <hr>
                                  <div class="row">
                                    <div class="col-md-6">
                                      Tip
                                    </div>
                                    <div class="col-md-3 col-md-offset-2">
                                      <input type="number" name="tip" id="tip" class="form-control">
                                    </div>
                                  </div>
                                  <hr>
                                  <div class="row">
                                    <div class="col-md-6">
                                      New total
                                    </div>
                                    <div class="col-md-4 col-md-offset-2">
                                    <span class="currency"></span> <span id="new_total" >00.00</span>
                                    </div>
                                  </div>
                                  <div class="clearfix" style="margin-bottom:20px;"></div>
                                  <div class="row text-center">
                                    <span>Pay using</span>
                                    <hr>
                                      <div class="col-md-3 col-md-offset-3">
                                        <label><input type="radio" name="pay_mode" value="cash" checked>Cash</label>
                                      </div>
                                      <div class="col-md-3">
                                        <label><input type="radio" name="pay_mode" value="card"  >Card</label>
                                      </div>
                                    </div>
                                </div>
                                <div class="col-md-6 calculator_content"  style="background-color:#e0dddd">
                                  <form name="case" style="background-color:#e0dddd">
                                        <input type="hidden" id="modal_main_id" value="">
                                    <div class="row">
                                      <div class="col-md-4">
                                        <label for="due_balance" style="font-size:11px;">Balance Due</label>
                                      </div>
                                      <div class="col-md-4">
                                        <label for="tendered" style="font-size:11px;">Amount Tendered</label>
                                      </div>
                                      <div class="col-md-4" >
                                        <label for="change" style="font-size:11px;">Change</label>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-4" style="width: 36%; padding-right: 0px;">
                                       <div name="due_balance" style="color:blue;font-size:16px;" id="due_balance" class="form-control"></div>
                                      </div>
                                      <div class="col-md-4" style="width: 36%; padding-right: 0px;padding-left: 0px;">
                                        <input type="text" name="tendered" style="font-size:16px;text-align: center;" class="form-control" id="tendered" value="">
                                      </div>
                                      <div class="col-md-3">
                                        <div  name="change" id="change" style="color:red;font-size:16px;" class="form-control"></div>
                                      </div>
                                    </div>
                                    <div class="clearfix" style="margin-top:20px;"></div>
                                      <input type="button" class="digit" value="1" id="run1">
                                      <input type="button" class="digit" value="2" id="run2">
                                      <input type="button" class="digit" value="3" id="run3">
                                      <input type="button" class="oper" 	value="10"  id="run10">

                                      <input type="button" class="digit" value="4" id="run4">
                                      <input type="button" class="digit" value="5" id="run5">
                                      <input type="button" class="digit" value="6" id="run6">
                                      <input type="button"	class="oper" 	value="20" id="run20" >

                                      <input type="button" class="digit" value="7" id="run7">
                                      <input type="button" class="digit" value="8" id="run8">
                                      <input type="button" class="digit" value="9" id="run9">
                                      <input type="button"	class="oper" 	value="50" id="run50" >

                                      <input type="button" class="digit" value="C" id="runC" >
                                      <input type="button" class="digit" value="0" id="run0">
                                      <input type="button" 	class="digit"	value="<" id="runback">
                                      <input type="button"	class="digit" 	value="." id="rundecimal" >

                                    </form> <!--  END opercase -->
                                    <div class="clearfix" style="margin-top:20px;"></div>

                                    <div class="row">
                                      <div class="col-md-8 col-md-offset-2">
                                        <button type="button" id="done_btn" class="btn btn-default oper" style="width:100%;height:40px;padding:1px;" data-dismiss="modal">Done</button>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

          <?php endif; ?>
</div>
<?php if (!$this->input->is_ajax_request()) : ?>
  <script type="text/javascript">
    function initialise(){
      if (typeof(Storage) !== "undefined") {
        var displayAll = localStorage.getItem('displayAll');
        var collapseAll = localStorage.getItem('collapseAll');
        var block_hide = false;
        var block_show = false;
        $('.table_header').each(function(){
          var block_id = $(this).find('.force_close').data('id');
          var item_status = localStorage.getItem('display'+block_id);
          if(item_status == 'false' ){
            $(this).next('.details').hide();
            block_hide = true;
          }else if(item_status == 'true' ){
            $(this).next('.details').show();
            block_show = true;
          }
        });
        if(!block_show && !block_hide){
          if(displayAll== true){
            $('.details').toggle(true);
          }else if(collapseAll==true){
            $('.details').toggle(false);
          }
        }
      }
      if( $('.new-orders').hasClass('notification')){
        $('.new-orders').addClass('flash_red');
      }
      if( $('.waiting-orders').hasClass('notification')){
        $('.waiting-orders').addClass('flash_red');
      }
      if( $('.delivered-orders').hasClass('notification')){
        $('.delivered-orders').addClass('flash_red');
      }
    }
    $(document).ready(function(){
      initialise();

      $(document).on('click', '.table_header', function(e){
        if(this === e.target){
          $(this).next('.details').toggle();
          var id = $(this).find('.force_close').data('id');
          if (typeof(Storage) !== "undefined") {
            localStorage.setItem('display'+id, $(this).next('.details').is(':visible'));
            localStorage.setItem('displayAll', false);
            localStorage.setItem('collapseAll', false);
          }
        }
      });

      $(document).on('click', '#expand_all', function(e){
        $('.details').toggle(true);
        if (typeof(Storage) !== "undefined") {
          localStorage.clear();
          localStorage.setItem('displayAll', true);
          localStorage.setItem('collapseAll', false);
        }

      });
      $(document).on('click', '#collapse_all', function(e){
        $('.details').toggle(false);
        if (typeof(Storage) !== "undefined") {
          localStorage.clear();
          localStorage.setItem('collapseAll', true);
          localStorage.setItem('displayAll', false);
        }


      });

      $(document).on("click", ".btn-confirm-user-payment", function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        if(!isNaN(id)){
          var form = $("#order_form").serializeArray();
          form.push({ name: "order_id", value: id });

          var request = $.ajax({
            url: "<?php echo site_url('waiters/status_paid'); ?>",
            method: "POST",
            data: form,
            dataType: "html"
          });

          request.done(function( msg ) {

            var d = jQuery.parseJSON(msg);
            if (d.status == 1){
              update();
            }
            else{
              alert('Cannot update order status!');
            }
          });

          request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
          });

        }
        else{
          alert('Something went wrong. Please refresh the page and try.');
        }
      });

      $(document).on("click", ".close_order_waiter", function(){
        var id = $(this).attr('data-oid');
        if(!isNaN(id)){
          var con  = confirm('Really want to delete item ?');
          var form = $("#order_form").serializeArray();

          if(con == true){
            form.push({ name: "oid", value: id });
            var request = $.ajax({
              url: "<?php echo site_url('waiters/remove_order'); ?>",
              method: "POST",
              data: form,
              dataType: "html"
            });

            request.done(function( msg ) {

              var d = jQuery.parseJSON(msg);
              if (d.status == 1){
                update();
              }
              else{
                alert('Cannot delete order!');
              }
            });

            request.fail(function( jqXHR, textStatus ) {
              alert( "Request failed: " + textStatus );
            });
          }
        }
      });

      // if ($(".flash_red").length){
        var backgroundInterval = setInterval(function(){
          $(".flash_red").toggleClass("red");
        },500);
        //}
        //if ($(".flash_blue").length){
          var backgroundInterval = setInterval(function(){
            $(".flash_blue").toggleClass("blue");
          },500);
          //}

          var backgroundInterval = setInterval(update,10000);


          function update(){
            var full_url      = window.location.href;     // Returns full URL
            var pathname = window.location.pathname; // Returns path only
            if($('.slider-login').hasClass('slider-hide')){
              $.ajax({
                method: "GET",
                url: full_url,
              })
              .done(function( html ) {
                $( ".container" ).replaceWith( html );
                initialise();
              });
            }
          }
          $(document).on('click','.force_close',function(){
            $(this).parent().siblings('.close_box').css("display", "block");
            $(this).parent().siblings('.details').css("display", "none");

          });

          $(document).on('click', '.close_table', function(){
            var main_id = $(this).attr('data-id');
            var user_id = $(this).attr('data-userid');
            if(main_id != ''){
              $('#main_id').val(main_id);
              var form = $("#order_form").serialize();

              var conf = confirm('Do you really want to close this location?');
              if(conf === false){
                return false;
              }
              console.log('rfr '+user_id);

              if(user_id != '' && user_id > 0){
                console.log(user_id);
                return false;
              }
              return false;
              var request = $.ajax({
                url: "<?php echo site_url('waiters/release_table'); ?>",
                method: "POST",
                data: form,
                dataType: "html"
              });

              request.done(function( msg ) {

                var d = jQuery.parseJSON(msg);
                if (d.status == 1){
                  update();
                }
                else{
                  alert(d.order_id_error);
                }
              });

              request.fail(function( jqXHR, textStatus ) {
                alert( "Request failed: " + textStatus );
              });
            }
          });
          $(document).on('click', '.point_sale', function(){
            var main_id = $(this).attr('data-id');
            var user_id = $(this).attr('data-userid');
            if(main_id != ''){
              var request = $.ajax({
                url: "<?php echo site_url('waiters/get_order'); ?>",
                method: "POST",
                data: {'main_id':main_id},
                dataType: "json"
              });

              request.done(function( result ) {

                if (result.status == 1){
                  //console.log(msg);

                  $("#bill_items").html('');
                  var orders = result.order;
                  var html_items = '';
                  var total_val = 0;
                  var tname = '';
                  var cus_name = '';
                  orders.forEach(function(element) {
                    // console.loglog(element);
                    html_items += '<div class="col-md-6">'+element.mname+
                      '</div><div class="col-md-2">'+
                        element.qty+
                      '</div><div class="col-md-4">'+result.currency.value+' '+element.price+
                    '</div>';
                    total_val += parseFloat(element.price);
                    cus_name = element.customer_name;
                    table_name = element.tname;
                  });
                  $("#bill_items").html(html_items);
                  $("#total").html(total_val);
                  $("#new_total").html(total_val);
                  $("#due_balance").html(total_val);
                  $("#header_total").html(total_val);
                  $("#customer_name").html(cus_name);
                  $("#tab_name").html(table_name);
                  $(".currency").html(result.currency.value);
                  $("#modal_main_id").val(main_id);



                }
                else{
                  alert(d.order_id_error);
                }
              });

              request.fail(function( jqXHR, textStatus ) {
                alert( "Request failed: " + textStatus );
              });
            }
          });

          $(document).on('click', '.order', function(e){
            if(!$(e.target).hasClass('close_order_waiter') ){
              var oid = $(this).attr('data-oid');
              if (oid != ''){
                $('#order_id').val(oid);
                var form = $("#order_form").serialize();
                var request = $.ajax({
                  url: "<?php echo site_url('waiters/update_order');?>",
                  method: "POST",
                  data: form,
                  dataType: "html"
                });

                request.done(function( msg ) {

                  var d = jQuery.parseJSON(msg);
                  if (d.status == 1){
                    update();
                  }
                  else{
                    alert(d.order_id_error);
                  }
                });

                request.fail(function( jqXHR, textStatus ) {
                  alert( "Request failed: " + textStatus );
                });
              }
            }
          });

          $(document).on('click', '.close_notice', function(){
            var notice = $(this).attr('data-id');
            $('#notice_id').val(notice);

            var form = $("#order_form").serialize();
            var request = $.ajax({
              url: "<?php echo site_url('waiters/close_notice'); ?>",
              method: "POST",
              data: form,
              dataType: "html"
            });

            request.done(function( msg ) {

              update();

            });

            request.fail(function( jqXHR, textStatus ) {
              alert( "Request failed: " + textStatus );
            });

          });


          $(document).on('click', '.order_button', function(){
            var notice = $(this).attr('data-id');
            $('#notice_id').val(notice);

            var form = $("#order_form").serialize();
            var request = $.ajax({
              url: "<?php echo site_url('waiters/close_notice'); ?>",
              method: "POST",
              data: form,
              dataType: "html"
            });

            request.done(function( msg ) {

              update();

            });

            request.fail(function( jqXHR, textStatus ) {
              alert( "Request failed: " + textStatus );
            });

          });
          $(document).on('click', '#close', function(e){
            e.preventDefault();
            var form = $(".close_form").serialize();
            var request = $.ajax({
              url: "<?php echo site_url('waiters/close_table'); ?>",
              method: "POST",
              data: form,
              dataType: "html"
            });

            request.done(function( msg ) {

              var d = jQuery.parseJSON(msg);
              if (d.status == 1){
                alert('Location closed!');
                update();
              }
              else{
                alert('Please try again');
              }
            });

            request.fail(function( jqXHR, textStatus ) {
              alert( "Request failed: " + textStatus );
            });


          });

          $(document).on('click', '#done_btn', function(){
            var main_id = parseInt($("#modal_main_id").val());
            var tip =  parseFloat($("#tip").val());
            var pay_mode = $("[name='pay_mode']").val();
            if(main_id != ''){
              var request = $.ajax({
                url: "<?php echo site_url('waiters/save_payment'); ?>",
                method: "POST",
                data: {'main_id':main_id,'tip':tip,'pay_mode':pay_mode},
                dataType: "json"
              });

              request.done(function( result ) {
                  if (result.status == 1){
                    update();
                  }
                  else{
                    alert(result.order_id_error);
                  }
              });

              request.fail(function( jqXHR, textStatus ) {
                alert( "Request failed: " + textStatus );
              });
            }
          });


          $(document).on('click','.slider-login',function(e){
            e.preventDefault();
            if($(this).hasClass('show')){
              $( ".slider-login, .panel" ).animate({
                left: "+=300"
              }, 700, function() {
                // Animation complete.
                $('body').removeClass('stop-scrolling');
              });
              $(this).removeClass('show');
              $(this).addClass('slider-hide');

            }
            else {
              $( ".slider-login, .panel" ).animate({
                left: "-=300"
              }, 700, function() {
                // Animation complete.
                $('body').addClass('stop-scrolling');

              });
              $(this).addClass('show');
              $(this).removeClass('slider-hide');

            }
          });
          $(document).on('click',".submit", function(e){
            var element =   e.target;
            var manager_code = $(element).parent().siblings('.manager').find('.manager_code').val();
            if(manager_code===''){
              swal({
                    title: "",
                    text: "Please fill manager code!",
                    icon: "failure",
                  });
            return false;
            }
            var confirm = $('.confirmation').html();
            e.preventDefault();
            swal({
              title:"",
              text: confirm,
              html : true,
              showConfirmButton: false });
            });

            $(document).on('click', ".btn-yes, .btn-no", function(){
              var over_18 = $(this).text() === 'Yes' ? 1 : 0;
              $('.over_18').val(over_18);
              var form_container = $("#sit").is(':visible') ? "#sit" : "#take";
              $(form_container+' form').submit();
            });

            $(document).on('click','.take_away_section div',function(){
              $('.take_away_section .buttons').removeClass('btn-true');
              $(this).addClass('btn-true');
              $('#sit, #take').hide();
              $($(this).attr('data-id')).show();
            });
            $(document).on('click','#run1',function(){
              var field_val = $("#tendered").val();
              $("#tendered").val(field_val+"1");
            });
            $(document).on('click','#run2',function(){
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val+ "2");
            });
            $(document).on('click','#run3',function(){
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val+ "3");
            });
            $(document).on('click','#run4',function(){
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val+ "4");
            });
            $(document).on('click','#run5',function(){
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val+ "5");
            });
            $(document).on('click','#run6',function(){
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val+ "6");
            });
            $(document).on('click','#run7',function(){
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val+ "7");
            });
            $(document).on('click','#run8',function(){
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val+ "8");
            });
            $(document).on('click','#run9',function(){
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val+ "9");
            });
            $(document).on('click','#run0',function(){
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val+ "0");
            });
            $(document).on('click','#runC',function(){
                $('#tendered').val("");
            });
            $(document).on('click','#run10',function(){
                var field_val = $("#tendered").val();
                field_val+= "10";
                field_val = parseFloat(field_val);
                $("#tendered").val(field_val);
            });
            $(document).on('click','#run20',function(){
              var field_val = $("#tendered").val();
                field_val+= "20";
                field_val = parseFloat(field_val);
                $("#tendered").val(field_val);
            });
            $(document).on('click','#run50',function(){
                var field_val = $("#tendered").val();
                field_val+= "50";
                field_val = parseFloat(field_val);
                $("#tendered").val(field_val);
            });
            $(document).on('click','#rundecimal',function(){
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val+ ".");
            });
            $(document).on('click','#runback',function(){
                var field_val = $("#tendered").val().slice(0,-1);
                $("#tendered").val(field_val);
            });
            $(document).on('keyup','#tip',function(){
                if($("#tip").val()){
                  var total_val = parseFloat($("#tip").val()) + parseFloat($("#total").html());
                  total_val = total_val.toFixed(2);

                  $("#new_total").html(total_val);
                  $("#header_total").html(total_val);
                  $("#due_balance").html(total_val);
                  $("#change").html("");
                  $("#tendered").val("");
                }

            });
            $(document).on('keyup','#tendered',function(){
                  $("#change").html("");
                  var total_val = parseFloat($("#new_total").html());
                 var tendered = parseFloat($("#tendered").val());
                 if(tendered > total_val){
                  var change =  tendered - total_val;
                  change = change.toFixed(2);
                  $("#change").html(change);
                 }

             });
             $(document).on('click','oper',function(){
              $("#change").html("");
                 var total_val = parseFloat($("#new_total").html());
                 var tendered = parseFloat($("#tendered").val());
                 if(tendered > total_val){
                  var change =  tendered - total_val;

                  change = change.toFixed(2);
                  $("#change").html(change);
                 }

             });
             $(document).on('click','.digit',function(){
                  $("#change").html("");
                 var total_val = parseFloat($("#new_total").html());
                 var tendered = parseFloat($("#tendered").val());
                 if(tendered > total_val){
                  var change =  tendered - total_val;

                  change = change.toFixed(2);
                  $("#change").html(change);
                 }

             });
          });

        </script>
      <?php endif; ?>
