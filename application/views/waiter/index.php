<?php
$firstProc = (int)$this->config->item('firstProc');
$secProc = (int)$this->config->item('secProc');
$details        = array();
$i = 0;
$existing_auths = array();
$ordered_now    = FALSE;

$new_list = array();

$uniquer = array();
$ordersD = $orders;
	$budgetCol = array_map(function($e) {
		return is_object($e) ? $e->order_id : $e['order_id'];
	}, $ordersD);
	$paidBY = array_map(function($e) {
		return is_object($e) ? $e->payed_by : $e['payed_by'];
	}, $ordersD);
	$priced = array_map(function($e) {
		return is_object($e) ? $e->price : $e['price'];
	}, $ordersD);
	
	//echo "<pre>";var_dump($orders);die;
	$statuses=[];
foreach ($orders as $key => $order) :
		$statuses[$order->order_id] = $order->status;
		
	
    if (empty($order->order_time) && $order->virtual == 1)
        continue;

    if (!is_null($order->oid)) {
        if (in_array($order->oid, $uniquer))
            continue;

        $uniquer[] = $order->oid;
    }
	//$new_list[$order->order_id][] = $order;
    if ($order->payed_by > 0 && $order->payed_by_confirm=="1") {
        $new_list[$order->payed_by][] = $order;
    } else {
        $new_list[$order->order_id][] = $order;
    }

endforeach;
//echo "<pre>";var_dump($order); echo "</pre>"; die;


foreach ($new_list as $key => $orderss) {
    foreach ($orderss as $ids => $order) {

		
        $details['master' . $order->id]['address']        = $order->address;
        $details['master' . $order->id]['virtual']        = $order->virtual;
		
        $attr = array();
        $attribute_data = json_decode(trim($order->attribute), true);
		$cats = array_values(json_decode(trim($order->attr_cats),true));
        if (is_array($attribute_data)) {
            foreach ($attribute_data as $at=>$attrs) {
                $attr[] = $cats[$at].":".$attrs;
            }
        }

        $attr_txt = implode(', ', $attr);
        $attr_actual_text = !empty($attr_txt) ? $attr_txt : '';
        $order->id = $key;

        if (!empty($order->mode))
            $details['master' . $order->id]['mode']       = $order->mode;
		$details['master' . $order->id]['temp_user'][]    = $order->temp_user;
        $details['master' . $order->id]['meal'][]         = '(Category) ' . $order->category . ', (Product) ' . $order->mname . ', (Attributes) ' . $attr_actual_text;
		$details['master' . $order->id]['category'][]     = $order->category;
		$details['master' . $order->id]['product'][]        = $order->mname;
        $details['master' . $order->id]['time'][]         = $order->order_time;
        $details['master' . $order->id]['rtime']          = $order->reserved_time;
        $details['master' . $order->id]['qty'][]          = $order->qty;
        $details['master' . $order->id]['ptime'][]        = $order->process_time;
        $details['master' . $order->id]['wprocess'][]     = $order->waiter_process_time;
        $details['master' . $order->id]['kitchen'][]      = $order->kitchen_left;
        $details['master' . $order->id]['processed'][]    = $order->processed;
        $details['master' . $order->id]['oid'][]          = $order->oid;
        $details['master' . $order->id]['user_id']        = $order->user_id;
        $details['master' . $order->id]['comment'][]      = $order->comment;
        $details['master' . $order->id]['customer_name'][]= $order->customer_name;
		$details['master' . $order->id]['contact_name'][] = $order->contact_name;
        $details['master' . $order->id]['under_18'][]     = $order->under_18;
		$details['master' . $order->id]['orderids'][]     = $order->order_id;
		$details['master' . $order->id]['attribute'][]    = trim($order->attribute);
		$details['master' . $order->id]['attr_cats'][]    = trim($order->attr_cats);
		

        //$details['master' . $order->id]['status']         = (isset($details['master' . $order->id]['status']) && $details['master' . $order->id]['status'] == 'paid') ? 'paid' : $order->status;
		$details['master' . $order->id]['prices'] =0;
        $details['master' . $order->id]['order_id']       = $order->order_id;
		 $details['master' . $order->id]['odid']       = $order->odid;
        $details['master' . $order->id]['status']       = $order->status;
		$details['master' . $order->id]['type']     = $order->type;
		$details['master' . $order->id]['coords']     = $order->coords;
		$details['master' . $order->id]['cell']     = $order->cell;
		$details['master' . $order->id]['oaddress']     = $order->oaddress;
		$details['master' . $order->id]['self']     = $order->self;
		$details['master' . $order->id]['payed_by']     = $order->payed_by;
		$details['master' . $order->id]['change_for']     = $order->change_for;
		$details['master' . $order->id]['tid']            = $order->id;
		$details['master' . $order->id]['tname']          = $order->tname;
		$details['master' . $order->id]['self_payment']          = $order->self_payment;
		
		$details['master' . $order->id]['prices']     += $order->price;
        if ($order->payed_by == 0) {
            $details['master' . $order->id]['master_id']  = $order->order_id;
            $details['master' . $order->id]['price']      = $order->price;
            $details['master' . $order->id]['delivery']   = $order->delivery_charge;
            $details['master' . $order->id]['tip']        = $order->tip;
			$details['master' . $order->id]['status']     = $order->status;
			$details['master' . $order->id]['payed_by']     = $order->payed_by;
        } else {
            $details['master' . $order->id]['master_id']  = $order->order_id;
            $details['master' . $order->id]['price']      = $order->price;
            $details['master' . $order->id]['delivery']   = $order->delivery_charge;
            $details['master' . $order->id]['tip']        = $order->tip;
			$details['master' . $order->id]['status']     = $order->status;
			$details['master' . $order->id]['payed_by']     = $order->payed_by;
        }
		
        ++$i;
        unset($attr);
		
    }
}

	$sku = trim($this->config->item('SITE_ID'),'_');
	$accounts = accounts($sku);
	$adminDel = $this->config->item('delivery_show');
	$superDel = $accounts['delivery'];
	//var_dump($priced);
?>

<div class="container">
    <style type="text/css">
        a,
        a:hover,
        a:active,
        a:visited,
        a:focus {
            text-decoration: none;
        }

        .active {
            color: #3f3e3e;
        }

        .stop-scrolling {
            height: 100% !important;
            overflow: hidden !important;
        }

        .text-area.take_away_section {
            padding: 0;
        }

        .take_away_section .btn-true {
            color: #FFFFFF !important;
        }

        .take_away_section div.buttons {
            height: 100%;
            text-align: center;
            padding: 9px 0;
            font-size: 17px;
            cursor: pointer;
        }

        .button-area {
            height: 44px;
            margin: 0 40px;
        }

        .take_away_section button {
            padding: 14px 22px;
        }

        .take_away_section button.btn-default {
            background: #ffffff;
        }

        .header-img {
            margin: -10px auto;
            width: 180px;
        }

        .stylish-input-group .input-group-addon {
            background: white !important;
        }

        .stylish-input-group .form-control {
            border-right: 0;
            box-shadow: 0 0 0;
            border-color: #ccc;
        }

        .stylish-input-group button {
            border: 0;
            background: transparent;
        }

        .text-area,
        .btn-area {
            padding-top: 16px;
        }

        button.submit {
            background: #d9edf7;
            color: #FFF;
            font-size: 16px;
            font-weight: 600;
        }

        .text-area input[type="text"],
        .text-area button,
        .text-area input[type="number"] {
            width: 80%;
            display: inline;
            color: #000000;
            height: 44px;
        }

        .calculator_view .modal-dialog {
            max-width: 800px;
            width: 100%;
        }

        .pre-cost {
            text-decoration: line-through;
            color: #a5a5a5;
        }

        .space-ten {
            padding: 10px 0;
        }

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
            font-weight: bold;
        }

        .oper {
            font-weight: bold;
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
            -webkit-box-shadow: 0px 0px 10px 3px rgba(25, 79, 108, 0.6);
            box-shadow: 0px 0px 10px 3px rgba(25, 79, 108, 0.6);
        }

        .oper:hover {
            position: relative;
            top: 1px;
            left: 1px;
            border-color: #e5e5e5;
            cursor: pointer;
            -webkit-box-shadow: 0px 0px 10px 3px rgba(25, 79, 108, 0.6);
            box-shadow: 0px 0px 10px 3px rgba(25, 79, 108, 0.6);
        }

        #clearMem {
            background-color: green;
        }

        #equal {
            background-color: yellow;
        }

        hr {
            border: 0;
            clear: both;
            display: block;
            width: 100%;
            background-color: black;
            height: 1px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .green {
            background: <?php echo $this->config->item('new_order_colour') ? $this->config->item('new_order_colour') : '#006633'; ?>;
            color: #FFFFFF !important;
        }

        .blue {
            background: <?php echo $this->config->item('fivemin_order_colour') ? $this->config->item('fivemin_order_colour') : '#00b3b3'; ?>;
            color: #FFFFFF !important;
        }

        .dark {
            background: <?php echo $this->config->item('processed_order_colour') ? $this->config->item('processed_order_colour') : '#888e8e'; ?>;
            color: #FFFFFF !important;
        }

        .red {
            background: <?php echo $this->config->item('tfivemin_order_colour') ? $this->config->item('tfivemin_order_colour') : '#16a085'; ?>;
            color: #FFFFFF !important;
        }

        .pay_bill {
            background: <?php echo $this->config->item('billto_order_colour') ? $this->config->item('billto_order_colour') : '#000'; ?>;
            color: #FFFFFF;
        }

        .min30 {
            background: <?php echo $this->config->item('thirty_order_colour') ? $this->config->item('thirty_order_colour') : '#16a085'; ?>;
            color: #FFFFFF;
        }

        .left_kitchen {
            background: <?php echo $this->config->item('kitchen_left_color') ? $this->config->item('kitchen_left_color') : '#3f8870'; ?>;
            color: #FFFFFF;
        }

        #new_customer_seat{
            background-color: #3498db;
            text-align: center;
            padding: 0;
        }

        .get_ordering_btn{
            background: transparent;
            border: none;
            font-size: 16px;
        }
		body{
			position:relative;
		}
		.ajax-loader {
		  visibility: hidden;
		  background-color: rgba(255,255,255,0);
		  position: absolute;
		  z-index: +100 !important;
		  width: 100%;
		  height:100%;
		}

		.ajax-loader img {
		  position: relative;
		  top:50%;
		  left:50%;
		}
		.table {
			width:100%;
			margin-bottom:0px !important;
		}
		.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
			padding: 2px !important;
			line-height: 1.42857143;
			vertical-align: top;
			border-top: 0px solid #ddd;
		}
		.bold {
			font-weight:900;
		}
		.nav-pills > li.active > a, .nav-pills > li.active > a:hover, .nav-pills > li.active > a:focus {
			color: #fff;
			background-color: #008000;
		}
		.newd {
		   padding: 0px 37px;
		}
		.clc {width:100% !important}
		/*.custom-header-area {
			padding: 120px 0 0px 0;
		}*/
		.input-group-addon:last-child {
			border-left: 0;
			border: 1px solid green;
		}
		.dell {
			margin-top:-10px; 
		}
    </style>
		<div class="ajax-loader">
			<img src="<?=base_url('assets/loader.gif');?>" class="img-responsive" />
		</div>
	<div class="ajax_loader hidden"></div>
    <div class="row">
    <?php if ($package->packages != 'Option 3'){ ?>
        <a href="javascript:void(0);" class="slider-login slider-hide" style="background: #3498db;border: none;display: block;height : 130px;margin-right: 0px;right: 0px;position: fixed;top: 76px;width: 25px;z-index: 999;writing-mode: vertical-lr;text-align: center;border-radius: 25px 0px 0px 25px;">New Table</a>
    <?php } ?>
        <div class='panel' style="width:300px;float:right;min-height:550px;background: #3498db;position:fixed;right: -300px;top: 60px;z-index:999;padding-bottom:10px">
            <div class="container text-center col-lg-12">
                <div class="">
                    <div class="row custom-header-area">
                        <div class="col-md-12">
                            <br /><br />

                            <?php if ($this->config->item("store_logo")) : ?>
                                <img src="<?php echo base_url('assets/images/' . $this->config->item("store_logo")); ?>" class="header-img img-responsive" />
                            <?php else : ?>
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
                        <?php if ($error = $this->session->flashdata('app_error')) : ?>
                            <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $error ?></div>
                        <?php endif ?>
                        <?php if ($success = $this->session->flashdata('app_success')) : ?>
                            <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $success ?></div>
                        <?php endif ?>

                        <?php $attributes = array('id' => 'new_customer_sit');
                        echo form_open('waiters/index/', $attributes); ?>
                        <?php $user = $this->ion_auth->user()->row(); ?>
                        <div class="text-area">
                            <input type="hidden" name="customer_name" class="form-control" required="" value="<?php echo ucwords($user->first_name); ?>">
                        </div>

                        <div class="text-area">
                            <input type="number" id="table_id" name="table" placeholder="Enter table number" class="form-control" required="" autocomplete="off" value="<?php echo !empty($qr_id) ? $qr_id : $this->input->post('table'); ?>">
                            <?php echo form_error('table'); ?>
                            <script>
                                $("#table_id").keyup(function(){
                                    let table_id = this.value;
                                    console.log(table_id);
                                });
                            </script>
                        </div>

                        <div class="text-area manager">
                            <input type="password" autocomplete="off" name="waiter_code" placeholder="Enter authorization code" class="form-control waiter_code" required autocomplete="off">
                            <?php echo form_error('waiter_code'); ?>
                        </div>
                        <!--
                              <div class="text-area">
                                <p class="white-text">I am of the legal age to consume alcohol. <input type="checkbox" name="over_18" /></p>
                              </div>-->
                        <div class="text-area">
                            <input class="submit btn btn-order" type="submit" value="GET ORDERING">
                        </div>

                        <input type="hidden" name="option" value="normal" />
                        <input type="hidden" name="over_18" class="over_18" value="0" />
                        <?php echo form_error('option'); ?>
                        <?php echo form_close(); ?>
                        <div class="clearfix"></div>
                    </div>

                    <div class="row" id="take" style="display: none">
                        <?php if ($error = $this->session->flashdata('app_error')) : ?>
							<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $error ?></div>
						<?php endif ?>
						<?php if ($success = $this->session->flashdata('app_success')) : ?>
							<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $success ?></div>
						<?php endif ?>
						<br/>
						<?php echo form_open(''); ?>
						<div class="row">
							<div class="col-md-12 newd">
							<ul class="nav nav-pills nav-justified">
								<?php 
									$styleS="";
									//if($adminDel=="1") {
										$styleS="active";
								?>
								<li class="active"><a data-toggle="pill" class="choice" data-tb="dell" href="#dell">Delivery</a></li>
								<?php //} ?>
								<?php 
									if($this->config->item('collection_show')=="1") {
								?>
								<li><a data-toggle="pill" class="choice" data-tb="calc" href="#calc">Collection</a></li>
								<?php } ?>
							</ul>
							<div class="tab-content">
								<div id="dell" class="tab-pane fade in <?=$styleS?>"><br/>
									<div class="text-area">
										<input type="text" class="form-control clc" name="contact_name1" autocomplete="off" placeholder="Contact name" id="contact_name" value="" />
										<?php echo form_error('contact_name'); ?>
									</div>
									<div class="text-area">
										<input type="text" name="cell1" autocomplete="off" placeholder="Cell no" class="form-control clc" value="<?php echo $this->input->post('cell1') ? $this->input->post('cell1') : ''; ?>">
										<?php echo form_error('cell1'); ?>
									</div>
									
									<div class="text-area">
										<input type="hidden"  name="coords" id="coords" value="" />
										
											<input type="text" name="address" id="address" autocomplete="off" placeholder="Address" class="form-control clc" value="<?php echo $this->input->post('address') ? $this->input->post('address') : ''; ?>" >
										
										<?php echo form_error('address'); ?>
									</div>
									
								</div>
								<div id="calc" class="tab-pane fade">
									<div class="text-area">
										<input type="text" class="form-control clc" name="contact_name" autocomplete="off" placeholder="Contact name" id=""  />
										<?php echo form_error('contact_name'); ?>
									</div>
									<div class="text-area">
										<input type="text" name="cell2" placeholder="Cell no" class="form-control clc" value="<?php echo $this->input->post('cell2') ? $this->input->post('cell2') : ''; ?>">
										<?php echo form_error('cell2'); ?>
									</div>
								</div>
							</div>
							<div class="text-area manager">
										<input type="text" name="waiter_code" placeholder="Enter authorization code" class="form-control waiter_code clc" required>
										<?php echo form_error('waiter_code'); ?>
									</div>
							</div>
						</div>
						
						
						<!--
							<div class="text-area">
								<p>I am of the legal age to consume alcohol. <input type="checkbox" name="over_18" /></p>
							</div>
							-->
						<div class="text-area">
							<button class="submit btn" type="submit" name="">GET ORDERING</button>
						</div>
						<input type="hidden" name="choice" id="choice" value="1" /> <!-- 1 means nothing-->
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
                <?php if ($this->config->item("store_logo")) : ?>
                    <img src="<?php echo base_url('assets/images/' . $this->config->item("store_logo")); ?>" style="max-height: 64px;" class="img-responsive" />
                <?php else : ?>
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
                        <div class="row" style="padding-left:15px;">
                            <h2 style="display: inline-block;"><a class='all-orders nav-link-item' href="<?php echo site_url('waiters') ?>">All</a></h2>
                            <h2 style="display: inline-block;padding-left:5px;padding-right:5px;"><img src="<?php echo base_url('assets/images/divider.png'); ?>" style="max-height: 34px;"></h2>
                            <h2 style="display: inline-block;"><a class="new-orders nav-link-item" href="<?php echo site_url('waiters/index/new') ?>">New</a></h2>
                            <h2 style="display: inline-block;padding-left:5px;padding-right:5px;"><img src="<?php echo base_url('assets/images/divider.png'); ?>" style="max-height: 34px;"></h2>
                            <h2 style="display: inline-block;"><a class="delivered-orders nav-link-item" href="<?php echo site_url('waiters/index/waiting') ?>">Waiting</a></h2>
                            <h2 style="display: inline-block;padding-left:5px;padding-right:5px;"><img src="<?php echo base_url('assets/images/divider.png'); ?>" style="max-height: 34px;"></h2>
							<?php if($this->config->item('driver')=="1" && $this->config->item('delivery_show')=="1" && $accounts['delivery']=="1") { ?>
							<h2 style="display: inline-block;"><a class="delivered-orders nav-link-item" href="<?php echo site_url('waiters/index/transit') ?>">Transit</a></h2>
                            <h2 style="display: inline-block;padding-left:5px;padding-right:5px;"><img src="<?php echo base_url('assets/images/divider.png'); ?>" style="max-height: 34px;"></h2>
							<?php } ?>
                            <h2 style="display: inline-block;"><a class="waiting-orders nav-link-item" href="<?php echo site_url('waiters/index/delivered') ?>">Delivered</a></h2>
                            <h2 style="display: inline-block;padding-left:5px;"><img src="<?php echo base_url('assets/images/divider.png'); ?>" style="max-height: 34px;"></h2>
                        </div>
                    </div>
                    <div class="col col-md-5  col-xs-5">
                        <div class="row" style="margin-left:5px;padding-left:5px;">
                            <h2 id="collapse_all" style="display: inline-block;">
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
            <?php 
				$allUsers = array_column($users, 'username', 'id');
				$takeAway =$user->take_away;
				$totalP = 0;$pstatus="";
				foreach ($details as $key => $det) : 
					
					
					$kk = array_search($det['order_id'], $budgetCol);
					$k = $orders[$kk]->budget;
					$ks = array_search($det['order_id'],$paidBY);
					
					if(!$ks) {
						$pd[] = $orders[$ks]->id;
						$PayedbyName = $orders[$ks]->customer_name;
					}
					
					if(count($statuses)>0) {
						if(array_key_exists($det['payed_by'],$statuses)) {
							$pstatus = $statuses[$det['payed_by']];
							
						}
					}
					
					$totalP = $totalP+$det['price']+$det['tip'];
					$pk=0;
					if($k > 0) {
						$pk = $k - $totalP;
						if($pk<0) {
							$pk = "Budget cancelled";
						} else {
							$pk = abs($pk);
						}
						$bgtt ='<p class="text-center" >Available budget R: '.$pk.'</p>';
					} else {
						$bgtt = '';
					}
					$totalP=0; // re-initialize to 0
					foreach ($det['meal'] as $ks => $dats) {
						$arrids[] = $det['oid'][$ks];
					}
					
					$oType = $det['type'];
					$firstId=0;
					if(count($det['orderids'])>0) {
						$firstId = reset(array_unique($det['orderids']));
						$printItem = implode('_',array_unique($det['orderids']));
					}
					
					$fidArr = explode('_',$printItem);
					
					foreach($fidArr as $cv) {
						//var_dump($cv.'--'.$det['oid'].'--'.$det['order_id'].'--'.$det['odid']);
						
						if($ordersD[$cv]->status=='paybill' && in_array($cv,$det['oid'])) {
							$totalPriced = $ordersD[$cv]->price;
							$changFor = $ordersD[$cv]->change_for;
							break;
						}
					}
					
					$arrids=[];
					$cords = $det['coords'];
					$distance="";
					$timeD="";
					
					if($this->config->item('delivery_show')=="1" && $accounts['delivery']=="1") {
						$exp = explode(',',$cords);
						$cords = trim($exp[0]).','.trim($exp[1]);
						$url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=32.582968,74.064206&destinations=".$cords."&mode=driving&sensor=false&key=".$this->config->item('google_key');

						$res = json_decode(file_get_contents($url),true);
						if($res['status']=="OK") {
							$distance = $res['rows'][0]['elements'][0]['distance']['text'];
							$timeD = $res['rows'][0]['elements'][0]['duration']['text'];
						}
					} 
					
					
					
			?>
							 
                <?php //if(empty($det['tname'])){continue;} 
					if($det['customer_name'][0]=='Take Away' && $takeAway=="0") {
						continue; 
					} else {  
						
						if($det['order_id']!=NULL ) {
					?>
					  
					<div class="col-md-6 col-sm-6"> 
						<h1 class="table_header" style="margin-bottom:0 !important; height:52px;background: #3498db ;color:white;border-radius: 25px 25px 0px 0px;">
                        <div class="force_close" data-id="<?php echo $det['master_id']; ?>" data-userid="<?php echo $det['user_id']; ?>">
                            <a href="#" name="close_table" data-status="<?=$det['status']?>" data-name="<?php echo $det['tname']; ?>" data-id="<?php echo $det['master_id']; ?>" data-ids="<?=$printItem?>" data-userid="<?php echo $det['user_id']; ?>" class="close_table"><img src="<?php echo base_url('assets/images/close2icn.png'); ?>" style="max-height: 48px;"></a>
                        </div>
						<div class="pull-left minmax mini cls<?=$det['order_id']?>" style="display:inline" data-min="<?=$det['order_id']?>" id="minmax">
							&nbsp;<a href="#" title="Minimize"> <span class="fa fa-window-minimize"></span></a> 
						</div>
								
                        <span class="table_customer">
							<?php 
							if($det['type']=="delivery" || $det['type']=="collection") {
								$nameS = ucfirst($det['type']);
							} else {
								$nameS = $det['customer_name'][0];
							}
							//var_dump($det['self']);
							?>
						
                            <?php echo 'L ' . $det['tname'] . ' | '. $nameS; ?>
                        </span>

                        <div class="pull-right" style="padding-top: 5px">
							<?php 
								$start = strtotime($det['rtime']);
								$end = time(); 
								$diff = abs($end - $start);
								
								$tmins = $diff/60;
								$hours = floor($tmins/60);
								$mins = $tmins%60;
								
							?>
                            <?php echo intval($hours).'h'.$mins.'m'; ?>
                        </div>

                        <div class="pull-right" style="padding-right: 10px;padding-top: 5px">
                            <?php echo $det['under_18'][0] == 1 ? "<span class='under'>18</span>" : ""; ?>
                        </div>
                        
                        <div class="pull-right" style="padding-right: 30px;padding-top: 4px">
                            <!--<a href="<?php echo site_url('waiters/email_slip/' . $printItem) ?>">
                                <i class='fa fa-envelope' style="font-size: 22px;"  data-toggle="tooltip" data-placement="right" title="Email slip"></i>
                            </a>-->
							<?php //if ($package->packages != 'Option 2' && $package->packages != 'Option 3'){ ?>
								
								<?php 
									if($det['status'] != 'paid' || (!is_null($det['payed_by']) && $pstatus != 'paid')) {
								?>
                                <a href="#" name="point_of_sale" data-name="<?php echo $det['tname']; ?>" data-id="<?php echo $printItem; ?>" data-userid="<?php echo $det['user_id']; ?>"  class="point_sale cart"><i class='fa fa-shopping-cart' style="font-size: 22px;"  data-toggle="tooltip" data-placement="right" title="Point of Sale"></i></a>
									<?php } ?>
                        <?php //} ?>
                        </div>
						<?php 
							 
						?> 
                        <div class="pull-right" style="padding-right: 30px;padding-top:5px">
                            <a target="_blank" href="<?php echo site_url('waiters/print_item/' . trim($printItem,'_')) ?>">
                               <i class='fa fa-print' style="font-size: 22px;"  data-toggle="tooltip" data-placement="right" title="Print"></i>
                            </a>
                            <div class="clearfix"></div>
                        </div>
						<?=$bgtt?>
                    </h1>
					
                    <div class="details mini<?=$det['order_id'];?>" style="background:#272323CC; color: #FFFFF;">
                        <?php if (isset($det['meal']) && count($det['meal']) > 0) : ?>
                            <?php if ($det['status'] == 'nvr') : ?>
                                <div class="pay_bill">
                                    <?php echo form_open('', array('class' => 'close_form')); ?>
                                    <input type="hidden" name="order_id" value="<?php echo $det['master_id']; ?>">
                                    <h2 class="error">Bill to customer</h2>
                                    <h3><?php 
										
										if($det['price'] > $totalPriced) {
											$tp = $det['price'];
										} else {
											$tp = $totalPriced;
										}
									echo CURRENCY_CODE . ' ' . number_format( $tp+ $det['tip'] + $det['delivery'], 2, '.', ''); ?></h3>
                                    <br />
                                    <?php if (isset($det['mode'])) : ?>
                                        <p>Payment :
                                            <?php
                                                if ($det['mode'] == 1)
                                                    echo 'Cash';
                                                else if ($det['mode'] == 2)
                                                    echo 'Card';
                                            ?>
                                        </p>
                                    <?php endif; ?>
                                    <!-- <button class="btn btn-danger btn-large" id="close">Close location</button>-->
                                    <?php echo form_close(); ?>
                                </div>
                            <?php else : ?>
                                <ul class="meal_list">
									<?php if(!empty($distance) && !empty($timeD)) { 	?>
									<li style="background-color:#333">
										<div class="text-center" >
											<i>Distance: <?=$distance?></i> |
											<i class="">Time: <?=$timeD?></i>
										</div>
									</li> 
									<?php } ?>
                                    <?php 
										
										$arr1[]=$det['status'];
									if(!is_null($det['payed_by']) && $pstatus == 'paybill') {
										
									?>
											<li class="" style="color:#FFFFFF; background: #FF0000;">
                                            <div class="col-xs-9">
                                                <?php 
													
													if($det['price'] > $totalPriced) {
														$tp = $det['price'];
													} else {
														$tp = $totalPriced;
													}
													if(!empty($changFor)) {
														$cng = $changFor;
													} else {
														$cng = $det['change_for'];
													}
													if(empty($cng))
														$cng = 0;
												echo form_open('', array('class' => 'close_form')); ?>
                                                <input type="hidden" name="order_id" value="<?php echo $det['master_id']; ?>">
                                                Bill to customer - (<?php
                                                if ($det['mode'] == 1)
                                                    echo 'Cash for:'.$cng;
                                                else if ($det['mode'] == 2)
                                                    echo 'Card'; 
                                                ?>:
                                                <?php 
													
												echo CURRENCY_CODE . ' ' . number_format($tp + $det['delivery'], 2, '.', ''); ?>, Tip: <?php echo CURRENCY_CODE . ' ' . number_format($det['tip']); ?>)

                                                <br />
                                                <?php echo $det['virtual'] == 1 ? empty($det['address']) ? "Option: $nameS" : "Option: $nameS, Address: " . $det['address'] : "Option: Sit down"; ?>
                                              
                                                <?php echo form_close(); ?>
                                            </div>
                                            <div class="col-xs-3">
                                                <button style="font-size:12px;" name="point_of_sale" data-name="<?php echo $det['tname']; ?>" data-id="<?php echo $printItem; ?>" data-userid="<?php echo $det['user_id']; ?>" class="btn btn-confirm-user-payment point_sale poss" >Cash Up</button>
                                            </div>
                                            <div class="clearfix"></div>
                                        </li>
									<?php	} else if($det['status'] == 'paybill') { ?>
                                        <li class="" style="color:#FFFFFF; background: #FF0000;">
                                            <div class="col-xs-9">
                                                <?php 
													if($det['price'] > $totalPriced) {
														$tp = $det['price'];
													} else {
														$tp = $totalPriced;
													}
												echo form_open('', array('class' => 'close_form')); ?>
                                                <input type="hidden" name="order_id" value="<?php echo $det['master_id']; ?>">
                                                Bill to customer - (<?php
                                                if ($det['mode'] == 1)
                                                    echo 'Cash for:'.$det['change_for'];
                                                else if ($det['mode'] == 2)
                                                    echo 'Card';
                                                ?>:
                                                <?php echo CURRENCY_CODE . ' ' . number_format($tp + $det['delivery'], 2, '.', ''); ?>, Tip: <?php echo CURRENCY_CODE . ' ' . number_format($det['tip']); ?>)

                                                <br />
                                                <?php echo $det['virtual'] == 1 ? empty($det['address']) ? "Option: $nameS" : "Option: $nameS, Address: " . $det['address'] : "Option: Sit down"; ?>
                                                <!-- <button style="margin-right:10px;" class="btn btn-danger btn-sm pull-right" id="close">X</button> -->
                                                <?php echo form_close(); ?>
                                            </div>
                                            <div class="col-xs-3">
                                                <!--<button style="font-size:12px;" class="btn btn-confirm-user-payment" data-ids="<?php echo $det['master_id']; ?>" data-name="<?php echo $det['tname']; ?>" data-id="<?php echo $det['order_id']; ?>">Confirm Payment</button>-->

                                                <button style="font-size:12px;" name="point_of_sale" data-name="<?php echo $det['tname']; ?>" data-id="<?php echo $printItem; ?>" data-userid="<?php echo $det['user_id']; ?>" class="btn btn-confirm-user-payment point_sale poss" >Cash Up</button>
                                            </div>
                                            <div class="clearfix"></div>
                                        </li>
                                    <?php } ?>
                                    <?php if (!is_null($det['payed_by']) && $pstatus == 'paid'){
									?>
                                        <li class="" style="color:#FFFFFF; background: #FF0000;">
                                            <div class="col-xs-12 text-center" style="font-size:18px;">
                            <button type="button" name="close_table" data-status="<?=$det['status']?>" data-ids="<?=$printItem?>" data-name="<?php echo $det['tname']; ?>" data-id="<?php echo $det['master_id']; ?>" data-userid="<?php echo $det['user_id']; ?>" class="btn btn-large close_table" style="background-color: transparent;">CLOSE TABLE</button>
                                            </div>
                                            <div class="clearfix"></div>
                                        </li>
									<?php } else if($det['status'] == 'paid'){ ?> 
										<li class="" style="color:#FFFFFF; background: #FF0000;">
                                            <div class="col-xs-12 text-center" style="font-size:18px;">
                            <button type="button" name="close_table" data-status="<?=$det['status']?>" data-ids="<?=$printItem?>" data-name="<?php echo $det['tname']; ?>" data-id="<?php echo $det['master_id']; ?>" data-userid="<?php echo $det['user_id']; ?>" class="btn btn-large close_table" style="background-color: transparent;">CLOSE TABLE</button>
                                            </div>
                                            <div class="clearfix"></div>
                                        </li>
									<?php } ?>
											
                                    <?php foreach ($det['meal'] as $k => $data) : ?>
                                        <?php if (!empty($data)) {
												
												$usid = $det['temp_user'][$k];
												if($usid > 0) {
													if(array_key_exists($usid,$allUsers)){
														$main_user = $allUsers[$usid];
													}
												} else { 
													if($det['contact_name'][$k]!="") {
														$main_user = ucfirst($det['contact_name'][$k]);
													} else {
														$main_user = ucfirst($det['customer_name'][$k]);
													}
												}
										
                                                $processed_time = strtotime(date_format(new DateTime($det['wprocess'][$k]),"H:i:s"));
                                                $order_time = strtotime(date_format(new DateTime($det['time'][$k]),"H:i:s"));

                                                $time_now = time();
                                                $ordered_time = abs($time_now - $order_time) / 60;
                                                $proce_time = ($time_now - $processed_time) / 60;
                                                $li_class_color = '';
												
                                                if ($det['processed'][$k] == 1)
                                                    $li_class_color = 'left_kitchen';
                                                else if ($det['processed'][$k] == 2 && $ordered_time < $secProc)
                                                    $li_class_color = 'dark'; // processed
                                                else if ($det['processed'][$k] == 2 && $ordered_time >= $secProc)
                                                    $li_class_color = 'flash_red';
                                                else if ($det['processed'][$k] == 0 && $ordered_time >= $firstProc)
                                                    $li_class_color = 'flash_blue';
                                                else if ($det['processed'][$k] == 0 && $ordered_time < $firstProc)
                                                    $li_class_color = 'green'; // new order
												elseif($det['processed'][$k] == 3) {
													$li_class_color = 'dark';
												} 
                                            ?>
                                            <?php ((strtotime('now') - strtotime($det['time'][$k])) <= 10) ?  $ordered_now = TRUE : ''; ?>
											<?php
												if($det['oid'][$k]){
											?>
                                            <form id="<?php echo $det['oid'][$k]; ?>">
                                                <li class="order order_process <?php echo $li_class_color; ?>" style="color:#FFFFFF" id="<?php echo $det['oid'][$k]; ?>" data-oid="<?php echo $det['oid'][$k]; ?>">
														<div class="ajax-loader loader<?=$det['oid'][$k]?>">
															<img src="<?=base_url('assets/loader.gif');?>" class="img-responsive" />
														</div>
                                                    <input type="hidden" name="order_id" id="<?php echo $det['oid'][$k]; ?>" class="order_id" value="<?php echo $det['oid'][$k]; ?>">
                                                    <div class="col-xs-12">
														<div class="table-responsive">
                                                        <table class="table">
		<tbody>
			<?php
				
				$timeT = time_took($det['time'][$k], $det['ptime'][$k]);
				$attribute_datas = json_decode(trim($det['attribute'][$k]), true);
				$origAttr = $attribute_datas;
				$catss = array_values(json_decode(trim($det['attr_cats'][$k]),true));
				$fArr=$atr=[];
				$keys = array_keys($attribute_datas);
				if( count($catss) > 0 ) {
					foreach($catss as $valt) {
						if(array_key_exists($valt,$origAttr)) {
							$fArr[]=$valt;
							$atr[] = implode(',',$origAttr[$valt]);
						}
					}
				}
				
				$process = "";
				
				if (is_array($attribute_datas) && count($attribute_datas) > 0) {
					$Count = count($attribute_datas); 
					$catN=$catV=$ndH=$ndV=$close=$back="";
					$ar = array_values($attribute_datas);
					//var_dump($attribute_datas);
					foreach ($ar as $at=>$attrs) { 
						$back = '<p style="margin-top:2px"><i data-processed="'.$det["processed"][$k].'" data-oid="'.$det["oid"][$k].'" data-id="'.$det["order_id"].'" class="fa fa-long-arrow-left back-step" style="border:1px solid white;border-radius:50px;margin-top:10px !important;padding:4px;display:inline" title="Back Stage"></i></p>';
					if($at==0) { 
						$catN = "Category";
						$catV = $det['category'][$k];
						$close ='<div style="padding-left:20px;display:inline-block" class="close-order pull-right" data-processed = "'.$det["processed"][$k].'" data-oid="'.$det["oid"][$k].'"><img src="'.base_url("assets/images/close1icn.png").'" style="max-height: 28px;display:inline-block"></div>';
						$process = '<p style="margin-top:2px"><i data-processed = "'.$det["processed"][$k].'" id="'.$det["oid"][$k].'" data-oid="'.$det["oid"][$k].'" class="fa fa-futbol-o order-contents" style="border:1px solid white;border-radius:50px;margin-top:10px !important;padding:4px;display:inline" title="Process State"></i></p>';
					} else if($at==1) { 
						$catN = "Product";
						$catV = $det['product'][$k];
						$close = $main_user;
						$process="";
					} else {
						$close="";
						$process="";
					}
					if($Count==1) {
						$ndH = isset($fArr[$at])?$fArr[$at]:"";
						$ndV = $atr[$at];
					}
					if(!is_null($det['payed_by']) && $pstatus == 'paid') {
						$close="";
					} else if($det['status']=="paid") {
						$close="";
					}
			?>
				<tr>
					<th class="bold"><?php echo $catN;?></th>
					<th><?php echo $catV;?></th>
					<th class="bold"><?php echo isset($fArr[$at])?$fArr[$at]:"";?></th>
					<td><?php echo $atr[$at]?></td>
					<td class="text-right"><?php echo $close." ".$process;?></td>
				</tr>
				
				<?php 
					if($at===0 && $Count==1) { ?>
					<tr>
						<th class="bold">Product</th>
						<th><?php echo $det['product'][$k];?></th>
						<th class="bold"></th>
						<td></td>
						<td class="text-right"><?php echo $timeT;?></td>
					</tr>
					<?php } else if($Count == ($at+1)) { ?>
					<tr>
						<th><p style="margin-top:2px"><i data-processed="<?=$det['processed'][$k]?>" data-oid="<?=$det['oid'][$k]?>" data-id="<?=$det['order_id']?>" class="fa fa-long-arrow-left back-step" style="border:1px solid white;border-radius:50px;margin-top:10px !important;padding:4px;display:inline" title="Back Stage"></i></p></th>
						<td></td>
						<th></th>
						<td></td>
						<td class="text-right"><?php echo $timeT;?></td>
					</tr>
				<?php	}
				?>
				
			<?php $catN=$catV="";} ?>
				
			<?php } else {if($det['oid'][$k]){?>
				<tr>
					<th class="bold">Category</th>
					<th><?php echo $det['category'][$k];?></th>
					<th></th>
					<td></td>
					<td class="text-right">
					<?php if($pstatus!="paid") { ?>
						<div style="padding-left:20px;display:inline-block;max-height:28px;" class="close-order pull-right" data-processed = "<?php echo $det['processed'][$k]; ?>" data-oid="<?php echo $det['oid'][$k]; ?>"><img src="<?php echo base_url('assets/images/close1icn.png'); ?>"></div>
					<?php } ?>
						<p style="margin-top:2px"><i data-processed = "<?=$det["processed"][$k]?>" id="<?=$det["oid"][$k]?>" data-oid="<?=$det["oid"][$k]?>" class="fa fa-futbol-o order-contents" style="border:1px solid white;border-radius:50px;margin-top:10px !important;padding:4px;display:inline" title="Process State"></i></p>
					</td>
				</tr>
				<tr>
					<th class="bold">Product</th>
					<th><?php echo $det['product'][$k];?></th>
					<th></th> 
					<td></td> 
					<td class="text-right"><?php echo $main_user?></td> 
				</tr>
				<tr>
					<th><p style="margin-top:2px"><i data-processed="<?=$det['processed'][$k]?>" data-oid="<?=$det['oid'][$k]?>" data-id="<?=$det['order_id']?>" class="fa fa-long-arrow-left back-step" style="border:1px solid white;border-radius:50px;margin-top:10px !important;padding:4px;display:inline" title="Back Stage"></i></p></th>
					<td></td>
					<th></th>
					<td></td>
					<td class="text-right"><?php echo $timeT;?></td>
				</tr>
				
			<?php } } ?>
			<?php if($det['oid'][$k]){ ?>
				<tr>
					<th>Comment</th><td colspan="4"><?php echo $det['comment'][$k];?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table> 
	</div>
                                                    </div>
													
													
                                                    <div class="clearfix"></div>
                                                </li>
                                            </form>
                                        <?php } } ?>
                                    <?php endforeach; ?> 
                                    <?php if ($package->packages != 'Option 3'){
											if($oType=="") {
											 if(!is_null($det['payed_by']) && $pstatus == 'paid') {
									?>
										
										
                                        <li class="text-center" style="background: #3498db;">
                                            <div class="text-center" >
                                                <a  class="order_button" data-url="<?php echo site_url('waiters/menu/order_'.$det['order_id'].'/table_'.$det['tname']) ?>" style="font-size: 16px;color:#FFFFFF;" href="#" >ORDER</a>
                                            </div>
                                        </li>
										<?php } else if($det['status']!='paybill' && $det['status']!='paid' && $pstatus!='paybill') { ?>
											<li class="text-center" style="background: #3498db;">
                                            <div class="text-center" >
                                                <a  class="order_button" data-url="<?php echo site_url('waiters/menu/order_'.$det['order_id'].'/table_'.$det['tname']) ?>" style="font-size: 16px;color:#FFFFFF;" href="#" >ORDER</a>
                                            </div>
											</li>
										<?php } } else { ?>
												<li class="text-center" style="background: #3498db;">
                                            <div class="text-center" >
                                                <?php 
													echo $det['oaddress']." |";
													echo " Phone:".$det['cell'];
												?> 
                                            </div>
												</li>
									<?php } } ?>
                                </ul>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div> 

                </div>
					
					<?php } } ?> 

                <?php if ($i == 2) : ?>
                    
        
       
        <?php $i = 0;
            endif; ?>
        <?php ++$i; ?>
    <?php endforeach; ?>
    </div>

        <?php echo form_open('', array('id' => 'order_form')); ?>
        <input type="hidden" name="order_id" id="order_id"> 
        <input type="hidden" name="notice_id" id="notice_id" value="">
        <input type="hidden" name="main_id" id="main_id" value="">
        <?php echo form_close(); ?>
        <div class="notice_area">
            <?php if (isset($notices) && is_array($notices)) : ?>
                <?php 
					$arrayColumnData = array_map(function($e) {
						return is_object($e) ? $e->order_id : $e['order_id'];
					}, $ordersD);
					
					foreach ($notices as $notice) : 
						
						$k = array_search($notice->order_id,$arrayColumnData);
						$Cname = $ordersD[$k]->customer_name;
						if(empty($Cname)) {
							$Cname="";
						}
				?>
                    <div class="notice close_notice" style="cursor:pointer;" data-id="<?php echo $notice->id; ?>">
						<p style="margin-bottom:0px"><?=$Cname ." | ". $notice->table_id ." | ". ( time_took($notice->date, Date('Y-m-d H:i:s'))) ?></p>
                        <?php echo $notice->message ; ?>  <div data-id="<?php echo $notice->id; ?>" class="pull-right glyphicon glyphicon-eye-close close_notice" style="cursor:pointer;"></div>
                        <div class="clearfix"></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="flash_red flash_blue hidden"></div>
    <?php if ($ordered_now) : ?>
        <audio id="id1" src="<?php echo base_url('/audio/glass_ping-Go445-1207030150.mp3'); ?>"></audio>
        <script type="text/javascript">
            jQuery(document).ready(function() {
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
            <li class="footer_li" style="background:<?php echo $this->config->item('fivemin_order_colour') ? $this->config->item('fivemin_order_colour') : '#00b3b3'; ?>"><b> CLERK NOT PROCESSED ORDER IN <?=$firstProc;?> MINUTES</b>
            </li>
            <li class="footer_li" style="background:<?php echo $this->config->item('kitchen_left_color') ? $this->config->item('kitchen_left_color') : '#000'; ?>"> <b>ORDER LEFT KITCHEN</b>
            </li>
            <li class="footer_li" style="background:<?php echo $this->config->item('tfivemin_order_colour') ? $this->config->item('tfivemin_order_colour') : '#16a085'; ?>"> <b>ORDER NOT LEFT KITCHEN IN <?=$secProc;?> MINUTES</b>
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
                                <div class="col-md-8" style="font-size:22px;">
                                    <span id="customer_name"></span> | L <span id="tab_name"></span>
                                </div>
                                <div class="col-md-4">
                                    <!--<a target="_blank" href="<?php echo site_url('waiters/print_item/' . $det['master_id']) ?>" style="color: #000">
                                        Print Slip
                                    </a>-->
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
                                    <strong>VAT</strong>
                                </div>
                                <div class="col-md-4 col-md-offset-2">
                                    <span class="currency"></span> <span id="total_vat">00.00</span>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <b>Total</b> 
                                </div>
                                <div class="col-md-4 col-md-offset-2">
                                    <span class="currency"></span> <span id="total"></span>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    Tip
                                </div>
                                <div class="col-md-3 col-md-offset-2">
                                    <input type="text" name="tip" id="tip" class="form-control">
									 <input type="hidden" name="htip" id="htip" class="form-control">
                                </div>
                            </div>
                            <hr>
							<div class="row" id="deliveryS">
                                
                            </div>
							
                            <div class="row">
                                <div class="col-md-6">
                                    New total
                                </div>
                                <div class="col-md-4 col-md-offset-2">
                                    <span class="currency"></span> <span id="new_total">00.00</span>
                                </div>
                            </div>
                            <div class="clearfix" style="margin-bottom:20px;"></div>
                            <div class="row text-center">
                                <span>Pay using</span>
                                <hr>
                                <div class="col-md-3 col-md-offset-3">
                                    <label><input type="radio" class="pay_mode pay1" name="pay_mode" value="cash" checked >Cash</label>
                                </div>
                                <div class="col-md-3">
                                    <label><input type="radio" class="pay_mode pay2" name="pay_mode" value="card">Card</label>
                                </div>
                            </div>
                            <div class="clearfix" style="margin-top:10px;"></div>
                            <div class="row text-center">
                                <button type="button" class="btn btn-default" data-dismiss="modal">close</button>
                            </div>
                        </div>
                        <div class="col-md-6 calculator_content" style="background-color:#e0dddd">
                            <form name="case" style="background-color:#e0dddd">
                                <input type="hidden" id="modal_main_id" value="">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="due_balance" style="font-size:11px;">Balance Due</label>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="tendered" style="font-size:11px;">Amount Tendered</label>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="change" id="changelbl" style="font-size:11px;">Change</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4" style="width: 36%; padding-right: 0px;">
                                        <div name="due_balance" style="color:blue;font-size:16px;" id="due_balance" class="form-control"></div>
                                    </div>
                                    <div class="col-md-4" style="width: 36%; padding-right: 0px;padding-left: 0px;">
                                        <input type="text" name="tendered" autofocus style="font-size:16px;text-align: center;" class="form-control" id="tendered" value="">
                                    </div>
                                    <div class="col-md-3">
                                        <div name="tendered_change" id="change" style="color:red;font-size:16px;" class="form-control"></div>
                                    </div>
                                </div>
                                <div class="clearfix" style="margin-top:20px;"></div>
                                <input type="button" class="digit" value="1" id="run1">
                                <input type="button" class="digit" value="2" id="run2">
                                <input type="button" class="digit" value="3" id="run3">
                                <input type="button" class="oper" value="10" id="run10">

                                <input type="button" class="digit" value="4" id="run4">
                                <input type="button" class="digit" value="5" id="run5">
                                <input type="button" class="digit" value="6" id="run6">
                                <input type="button" class="oper" value="20" id="run20">

                                <input type="button" class="digit" value="7" id="run7">
                                <input type="button" class="digit" value="8" id="run8">
                                <input type="button" class="digit" value="9" id="run9">
                                <input type="button" class="oper" value="50" id="run50">

                                <input type="button" class="digit" value="C" id="runC">
                                <input type="button" class="digit" value="0" id="run0">
                                <input type="button" class="digit" value="<" id="runback">
                                <input type="button" class="digit" value="." id="rundecimal">

                            
                            <div class="clearfix" style="margin-top:20px;"></div>

                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    <button type="button" id="done_btn" data-name="<?php echo $det['tname']; ?>" class="btn btn-default oper" style="width:100%;height:40px;padding:1px;">Done</button>
                                </div>
                            </div>
							<div class="row"></br>
								<input type="hidden" name="hpr" class="hpr" value="" />
								<div class="col-md-6"><input type="radio" name="pr" class="pr o1" value="1"> Email</div>
								<div class="col-md-6"><input type="radio" name="pr" class="pr o2" value="2"> Print</div>
								<div class="col-md-6"><input type="radio" name="pr" class="pr o3" value="3"> Email &amp; Print</div>
								<div class="col-md-6"><input type="radio" name="pr" class="pr o4" value="4"> None</div>
								<div class="col-md-12 newe">
									<input type="hidden" name="oldemail" id="oldemail" class="oldemail" value="" />
									<input type="email" name="newemail" id="newemail" class="newemail form-control" value=""><br/>
								</div>
							</div>
							
							</form> <!--  END opercase -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade view_bill" id="view_bill">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 product_img">
                            <div class="row">
                                <div class="col-md-6" style="font-size:22px;">
                                    <span id="bill_customer_name"></span> | L <span id="bill_tab_name"></span>
                                </div>
                                <div class="col-md-6" style="font-size:22px;">
                                    <span id="bill_customer_name"></span> Waiter : <?php echo ucwords($user->username); ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-3" style="font-size:22px;">
                                    <span class="currency"></span> <span id="bill_header_total">00.00</span>
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
                            <div class="row" id="view_bill_items">
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>VAT</strong>
                                </div>
                                <div class="col-md-4 col-md-offset-2">
                                    <span class="currency"></span> <span id="bill_total_vat">00.00</span>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <b>Total</b>
                                </div>
                                <div class="col-md-4 col-md-offset-2">
                                    <span class="currency"></span> <span id="bill_total"></span>
                                </div>
                            </div>
                            <div class="clearfix" style="margin-bottom:20px;"></div>
                            <div class="row text-center">
                                <button type="button" class="btn btn-default" data-dismiss="modal">close</button>
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
			
        //Save product
        $(document).on('click', '.order-contents', function(e) {
			
            var id = $(this).attr('data-oid');
			var processed = $(this).data('processed');
			if(processed == "1") {
				// 3 : just a flag that order is completed no more clicks allowed.
				swal("Order delivered", "error");
                return false;
			}
			
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('waiters/update_order') ?>",
                dataType: "JSON",
                data: { 
                    order_id: id
                }, 
				beforeSend:function() {
					$('.loader'+id).css("visibility", "visible");
				},
                success: function(data) {
                    $('[name="order_id"]').val("");
                },
				complete:function(){ 
					setTimeout(function(){
						$('.loader'+id).css("visibility", "hidden");
					},3000);
				} 
            }); 
			
            var backgroundInterval = setInterval(update, 1000);

            return false; 
        });
		
		
        function initialise() {
            if (typeof(Storage) !== "undefined") {
				//localStorage.clear();
                var displayAll = localStorage.getItem('displayAll');
                var collapseAll = localStorage.getItem('collapseAll');
				for (var a in localStorage) {
					if(localStorage.getItem(a)=="true") {
						$('.'+a).addClass('hidden');
						var newStr = a.substring(4);
						$('.cls'+newStr).addClass('maxi').removeClass('mini');
						$('.cls'+newStr).html('&nbsp;<a href="#" title="maximize"> <span class="fa fa-window-maximize"></span></a>');
					}
				}
				if(displayAll=="true") {
					$('.details').removeClass('hidden');
					localStorage.setItem('collapseAll', false);
					localStorage.setItem('displayAll', true);
				} else if(collapseAll=="true") {
					$('.details').addClass('hidden');
					localStorage.setItem('displayAll', false);
					localStorage.setItem('collapseAll', true);
				}
            }
            if ($('.new-orders').hasClass('notification')) {
                $('.new-orders').addClass('flash_red');
            }
            if ($('.waiting-orders').hasClass('notification')) {
                $('.waiting-orders').addClass('flash_red');
            }
            if ($('.delivered-orders').hasClass('notification')) {
                $('.delivered-orders').addClass('flash_red');
            }
            var full_url = window.location.href; // Returns full URL

            $('.nav-link-item').each(function() {
                var current = $(this).attr("href");
                if (full_url == current) { 
                    $(this).addClass('active');
                }
            });

        }

        $(document).ready(function() {
            initialise();
			
            // $(document).on('click', '.table_header', function(e) {
                // if (this === e.target) {
                    // $(this).next('.details').toggle();
                    // var id = $(this).find('.force_close').data('id');
                    // if (typeof(Storage) !== "undefined") {
                        // localStorage.setItem('display' + id, $(this).next('.details').is(':visible'));
                        // localStorage.setItem('displayAll', false);
                        // localStorage.setItem('collapseAll', false);
                    // }
                // }
            // });

            $(document).on('click', '#expand_all', function(e) {
				$('.details').toggle(true);
                if (typeof(Storage) !== "undefined") {
                    localStorage.clear();
                    localStorage.setItem('displayAll', true);
                    localStorage.setItem('collapseAll', false);
                }
            });

            $(document).on('click', '#collapse_all', function(e) {
					$('.details').toggle(false);
                if (typeof(Storage) !== "undefined") {
                    localStorage.clear();
                    localStorage.setItem('collapseAll', true);
                    localStorage.setItem('displayAll', false);
                }
            });
			
			$(document).on('click','.minmax',function(e){
					var oid = $(this).data('min');
				if($(this).hasClass('mini')) {
					$(this).html('&nbsp;<a href="#" title="maximize"> <span class="fa fa-window-maximize"></span></a>');
					$('.mini'+oid).addClass('hidden');
					localStorage.setItem('mini'+oid, true);
					$(this).addClass('maxi').removeClass('mini');
					console.log(localStorage.getItem('mini'+oid));
					console.log(typeof(localStorage.getItem('mini'+oid)));
				} else if($(this).hasClass('maxi')) {
					
					$(this).html('&nbsp;<a href="#" title="Minimize"> <span class="fa fa-window-minimize"></span></a>');
					$('.mini'+oid).removeClass('hidden');
					localStorage.setItem('mini'+oid, false);
					$(this).removeClass('maxi').addClass('mini');
				}
			});
			
			$(document).on("click", ".btn-confirm-user-payment", function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                var table_name = $(this).attr('data-name');
                var main_id = $(this).attr('data-ids');

                console.log(table_name);
                console.log(main_id);

                if (!isNaN(id)) {
                    var form = $("#order_form").serializeArray();
                    form.push({
                        name: "order_id",
                        value: id,
                        table_name: "table_name",
                    });

                    var request = $.ajax({
                        url: "<?php echo site_url('waiters/release_table'); ?>",
                        method: "POST",
                        data: {
                            'main_id': main_id,
                            'table_name': table_name,
							'type':'close_table'
                        },
                        dataType: "html"
                    });

                    var request = $.ajax({
                        url: "<?php echo site_url('waiters/status_paid'); ?>",
                        method: "POST",
                        data: {
                            name: 'order_id',
                            value: id,
                            table_name: 'table_name',
                        },
                        dataType: "html"
                    });

                    request.done(function(msg) {

                        var d = jQuery.parseJSON(msg);
                        if (d.status == 1) {
                            update();
                        } else {
                            alert('Cannot update order status!');
                        }
                    });

                    request.fail(function(jqXHR, textStatus) {
                        alert("Request failed: " + textStatus);
                    });
                } else {
                    //alert('Something went wrong. Please refresh the page and try.');
                }
            });

            $(document).on("click", ".close_order_waiter", function() {
                var id = $(this).attr('data-oid');
                if (!isNaN(id)) {
                    var con = confirm('Really want to delete item ?');
                    var form = $("#order_form").serializeArray();

                    if (con == true) {
                        form.push({ 
                            name: "oid",
                            value: id 
                        });
                        var request = $.ajax({
                            url: "<?php echo site_url('waiters/remove_order'); ?>",
                            method: "POST",
                            data: form,
                            dataType: "html"
                        });

                        request.done(function(msg) {

                            var d = jQuery.parseJSON(msg);
                            if (d.status == 1) {
                                update();
                            } else {
                                alert('Cannot delete order!');
                            }
                        });

                        request.fail(function(jqXHR, textStatus) {
                            alert("Request failed: " + textStatus);
                        });
                    }
                }
            });

            // if ($(".flash_red").length){
            var backgroundInterval = setInterval(function() {
                $(".flash_red").toggleClass("red"); 
            }, 5000); 
            //}

            //if ($(".flash_blue").length){
            var backgroundInterval = setInterval(function() {
                $(".flash_blue").toggleClass("blue");
            }, 5000);
            //}

            var backgroundInterval = setInterval(update, 5000);


            function update() {
                var full_url = window.location.href; // Returns full URL
                var pathname = window.location.pathname; // Returns path only
                var codeSwalOpen = false;
                var pos_open = false;
                var bill_open = false;

                if ($(".sweet-alert.visible").length > 0) {
                    codeSwalOpen = true;
                }

                pos_open = $('#calculator_view').is(':visible');
                bill_open = $('#view_bill').is(':visible');

                if ($('.slider-login').hasClass('slider-hide') && codeSwalOpen == false && pos_open == false) {
                    $.ajax({
                            method: "GET",
                            url: full_url,
                        })
                        .done(function(html) {
                            $(".container").replaceWith(html);
                            initialise();
                        });
                }

                if ($('.slider-login').hasClass('slider-hide') && codeSwalOpen == false && bill_open == false) {
                    $.ajax({
                            method: "GET",
                            url: full_url,
                        })

                        .done(function(html) {
                            $(".container").replaceWith(html);
                            initialise();
                        });
                }
            }

            $(document).on('click', '.force_close', function() {
                $(this).parent().siblings('.close_box').css("display", "block");
                $(this).parent().siblings('.details').css("display", "none");

            });

            $(document).on('click', '.close_table', function() {
                var main_id = $(this).attr('data-id');
                var user_id = $(this).attr('data-userid');
                var table_name = $(this).attr('data-name');
				var status = $(this).attr('data-status');
				var ids = $(this).attr('data-ids');
                var codeStatus = '';
                var total_val = 0;
				//return false;
                if (main_id != '') {
                    $('#main_id').val(main_id);
                    var form = $("#order_form").serialize();

                    var request = $.ajax({
                        url: "<?php echo site_url('waiters/get_order'); ?>",
                        method: "POST",
                        data: {
                            'main_id': main_id
                        },
                        dataType: "json"
                    });
					
					if(status=='paid') {
							request.done(function(result) {
                                    var request = $.ajax({
                                        url: "<?php echo site_url('waiters/release_table'); ?>",
                                        method: "POST",
                                        data: {
                                            'main_id': main_id,
											'ids': ids,
                                            'table_name': table_name,
											'type':'close_table',
                                        },
                                        dataType: "html"
                                    });

                                    request.done(function(msg) {

                                        var d = jQuery.parseJSON(msg);
										
										if(d.status==2) {
											
											var title = "Manager Code!";
											var text = "Enter Manager Code:";
											var url = "<?php echo site_url('waiters/manager_code_validate'); ?>";
											var input = "password";
											release_table(title,text,url,input,1,main_id,ids,table_name);
											//swal("All orders must be deliveredrtrttrtrtrt", "error");
											return false;
										}
                                        if (d.status == 1) {
                                            update();
                                        } else {
                                            alert(d.order_id_error);
                                        }
                                    });

                                    request.fail(function(jqXHR, textStatus) {
                                        alert("Request failed: " + textStatus);
                                    });
                                
                            });
                            request.fail(function(jqXHR, textStatus) {
                                alert("Request failed: " + textStatus);
                            });
							return false;
						}
					
                    request.done(function(result) {
                        if (result.status == 1) {
                            var orders = result.order;
                            orders.forEach(function(element) {
                                total_val += parseFloat(element.price);
                            });
                        }
						
                        if (total_val == 0) {
                            var title = "Authorisation Code!";
                            var text = "Enter Authorisation Code:";
                            var url = "<?php echo site_url('waiters/waiter_code_validate'); ?>";
							var input = "number";
                        } else {
                            var title = "Manager Code!";
                            var text = "Enter Manager Code:";
                            var url = "<?php echo site_url('waiters/manager_code_validate'); ?>";
							var input = "password";
                        }
                        swal({
                            title: title,
                            text: text,
                            type: "input",
							inputType: input,
							inputValue: '',
                            showCancelButton: true,
                            closeOnConfirm: true,
                            inputPlaceholder: "Enter Code..",
							inputClass:'passcodes',
                        }, function(codeValue) {
                            if (codeValue === false) return false;
                            if (codeValue === "") {
                                swal.showInputError("You need to write something!");
                                return false
                            }
                            if (total_val == 0) {
                                var request = $.ajax({
                                    url: url,
                                    method: "POST",
                                    data: {
                                        code: codeValue 
                                    },
                                    dataType: "html"
                                });
                            } else {
                                var request = $.ajax({
                                    url: url,
                                    method: "POST",
                                    data: {
                                        manager_code: codeValue
                                    },
                                    dataType: "html"
                                });
                            }
						
                            request.done(function(msg) {
                                codeStatus = jQuery.parseJSON(msg);
                                if (codeStatus.status == false) {
                                    swal("Invalid code", "error");
                                    return false;

                                } else {
                                    var request = $.ajax({
                                        url: "<?php echo site_url('waiters/release_table'); ?>",
                                        method: "POST",
                                        data: {
                                            'main_id': main_id,
											'ids': ids,
                                            'table_name': table_name,
											'type':'close_table',
                                        },
                                        dataType: "html"
                                    });

                                    request.done(function(msg) {

                                        var d = jQuery.parseJSON(msg);
                                        if (d.status == 1) {
                                            update();
                                        } else if(d.status==2) {
											var title = "Manager Code!";
											var text = "Enter Manager Code:";
											var url = "<?php echo site_url('waiters/manager_code_validate'); ?>";
											var input = "password";
											release_table(title,text,url,input,1,main_id,ids,table_name);
											//swal("All orders must be deliveredrtrttrtrtrt", "error");
											return false;
										} else {
                                            //alert(d.order_id_error);
                                        }
                                    });

                                    request.fail(function(jqXHR, textStatus) {
                                        alert("Request failed: " + textStatus);
                                    });
                                }
                            });
                            request.fail(function(jqXHR, textStatus) {
                                alert("Request failed: " + textStatus);
                            });
                            // swal("Nice!", "You wrote: " + inputValue, "success");
                        });
                    });

                    // if(user_id != '' && user_id > 0){
                    //   console.log(user_id);
                    //   return false;
                    // }
                    // return false;

                }
                $(this).removeClass('swal-open');
                return false;

            });
            //closing each order
			
			function release_table(title,text,url,input,total_val,main_id,ids,table_name) {
				swal({
					title: title,
					text: text,
					type: "input",
					inputType: input,
					inputValue: '',
					showCancelButton: true,
					closeOnConfirm: true,
					inputPlaceholder: "Enter Code..",
					inputClass:'passcodes',
				}, function(codeValue) {
					if (codeValue === false) return false;
					if (codeValue === "") {
						swal.showInputError("You need to write something!");
						return false
					}
					
					if (total_val == 0) {
						var request = $.ajax({
							url: url,
							method: "POST",
							data: {
								code: codeValue 
							},
							dataType: "html"
						});
					} else {
						var request = $.ajax({
							url: url,
							method: "POST",
							data: {
								manager_code: codeValue
							},
							dataType: "html"
						});
					}
				
					request.done(function(msg) {
						codeStatus = jQuery.parseJSON(msg);
						if (codeStatus.status == false) {
							swal("Invalid code", "error");
							return false;

						} else {
							var request = $.ajax({
								url: "<?php echo site_url('waiters/release_table'); ?>",
								method: "POST",
								data: {
									'main_id': main_id,
									'ids': ids,
									'table_name': table_name,
									'type':'',
								},
								dataType: "html"
							});

							request.done(function(msg) {

								var d = jQuery.parseJSON(msg);
								if (d.status == 1 || d.status==2) {
									update();
								} else {
									//alert(d.order_id_error);
								}
							});

							request.fail(function(jqXHR, textStatus) {
								alert("Request failed: " + textStatus);
							});
						}
					});
					request.fail(function(jqXHR, textStatus) {
						alert("Request failed: " + textStatus);
					});
					// swal("Nice!", "You wrote: " + inputValue, "success");
				});
			} // end release_table function
			
            $(document).on('click', '.close-order', function() {
                var order_details_id = $(this).attr('data-oid');
				var process = $(this).attr('data-processed');
                var codeStatus = '';
                var total_val = 0;

                if (order_details_id != '') {
                    if (process == '0' || process == "2") {
						var title = "Authorisation Code!";
						var text = "Enter Authorisation Code:";
						var url = "<?php echo site_url('waiters/waiter_code_validate'); ?>";
						var input = "number";
					} else if(process == "1") {
						var title = "Manager Code!"; 
						var text = "Enter Manager Code:";
						var url = "<?php echo site_url('waiters/manager_code_validate'); ?>";
						var input = "password";
					}
                    swal({
                        title: title,
                        text: text,
                        type: "input",
						inputValue: '',
						inputType: input,
                        showCancelButton: true,
						inputClass:'passcodes',
                        closeOnConfirm: true,
                        inputPlaceholder: "Enter Code.."
                    }, function(codeValue) {
                        if (codeValue === false) return false;
                        if (codeValue === "") {
                            swal.showInputError("You need to write something!");
                            return false
                        }
                        if (process == '0' || process == "2") {
							var request = $.ajax({
								url: url,
								method: "POST",
								data: {
									code: codeValue
								},
								dataType: "html"
							});
						} else if(process == "1") {
							var request = $.ajax({
								url: url,
								method: "POST",
								data: {
									manager_code: codeValue
								},
								dataType: "html"
							});
						}


                        request.done(function(msg) {
                            codeStatus = jQuery.parseJSON(msg);
                            if (codeStatus.status == false) {
                                swal("Invalid code", "error");
                                return false;

                            } else {
                                var request = $.ajax({
                                    url: "<?php echo site_url('waiters/remove_order'); ?>",
                                    method: "POST",
                                    data: {
                                        oid: order_details_id
                                    },
                                    dataType: "html"
                                });

                                request.done(function(msg) {

                                    var d = jQuery.parseJSON(msg);
                                    if (d.status == 1) {
                                        update();
                                    } else {
                                        alert(d.order_id_error);
                                    }
                                });

                                request.fail(function(jqXHR, textStatus) {
                                    alert("Request failed: " + textStatus);
                                });
                            }
                        });
                        request.fail(function(jqXHR, textStatus) {
                            alert("Request failed: " + textStatus);
                        });
                        // swal("Nice!", "You wrote: " + inputValue, "success");
                    });
                }
            });

            // button click point of sale
            $(document).on('click', '.point_sale', function() {
                var main_id = $(this).attr('data-id');
                var user_id = $(this).attr('data-userid');
				var title = "Authorisation Code!";
				var text = "Enter Authorisation Code:";
				var url = "<?php echo site_url('waiters/waiter_code_validate'); ?>";
				swal({
					title: title,
					text: text,
					type: "input",
					inputValue: '',
					inputType: "number",
					showCancelButton: true,
					inputClass:'passcodes',
					closeOnConfirm: true,
					inputPlaceholder: "Enter Code.."
				}, function(codeValue) {
					if (codeValue === false) return false;
					if (codeValue === "") {
						swal.showInputError("You need to write something!");
						return false
					}
					var requestt = $.ajax({
						url: url,
						method: "POST",
						data: {
							code: codeValue
						},
						dataType: "html"
					});
					requestt.done(function(msg) {
						codeStatus = jQuery.parseJSON(msg);
						if (codeStatus.status == false) {
							swal("Invalid code", "error");
							return false;
						} else {
							$('#tendered').val(''); 
							$('#calculator_view').modal('show');
						}
					});
				});

                if (main_id != '') {
                    var request = $.ajax({
                        url: "<?php echo site_url('waiters/get_order'); ?>",
                        method: "POST",
                        data: {
                            'main_id': main_id
                        },
                        dataType: "json"
                    });

                    request.done(function(result) {
                        if (result.status == 1) {
                            //console.log(msg);

                            $("#bill_items").html('');
                            var orders = result.order;
                            var html_items = '';
							var vat = "1.<?php echo $this->config->item('vat'); ?>";
							
                            var total_val = 0;
                            var total_vat = 0;
							var tpi = 0;
                            var tname = '';
                            var cus_name = '';
							var email = '';
							var options = '';
							var mode='';
							var im=0;
                            orders.forEach(function(element) {
                                // console.loglog(element);
                                html_items += '<div class="col-md-6">' + element.mname +
                                    '</div><div class="col-md-2">' +
                                    element.qty +
                                    '</div><div class="col-md-4">' + result.currency.value + ' ' + element.meal_price +
                                    '</div>';
                                total_vat += parseFloat(element.meal_price) - (parseFloat(element.meal_price)/parseFloat(vat)) ;
                                total_val += parseFloat(element.meal_price);
                                cus_name = element.customer_name;
                                table_name = element.tname;
								if(im==0) {
									email = element.email;
									options = element.options;
									mode=element.mode;
								}
								im++;
                            });
							
                            if(options=="1") {
								$('.o1').attr('checked','checked');
								$('.hpr').val('1');
							} else if(options=="2") {
							
								$('.o2').prop('checked','checked');
								$('.hpr').val('2');
							}
							else if(options=="3") {
								$('.o3').prop('checked','checked');
								$('.hpr').val('3');
							}
							else if(options=="4") {
								$('.o4').prop('checked','checked');
								$('.hpr').val('4');
							}
							tpi = parseFloat(result.tip);
							
							
							$('#deliveryS').html(result.deliveryS);
							if(tpi=="") {
								var all_total = parseFloat(total_val)
							} else {
								var all_total = parseFloat(total_val) + parseFloat(tpi);
							}
							if(parseFloat($('#del_cost').html())>0) {
								var all_total = parseFloat(all_total) + parseFloat($('#del_cost').html());
							}
						
                            $("#bill_items").html(html_items);
                            $("#total").html(parseFloat(total_val).toFixed(2));
                            $("#total_vat").html(parseFloat(total_vat).toFixed(2));
                            $("#new_total").html(parseFloat(all_total).toFixed(2));
							$('#tip').val(result.tip);
							$('#htip').val(result.tip);
                            $("#due_balance").html(parseFloat(all_total).toFixed(2));
                            $("#header_total").html(parseFloat(all_total).toFixed(2));
                            $("#customer_name").html(cus_name);
                            $("#tab_name").html(table_name);
                            $(".currency").html(result.currency.value);
                            $("#modal_main_id").val(main_id);
							$('.newemail').val(email);
							$('.oldemail').val(email);
							if(mode=="2") {
								$('.pay2').prop('checked','checked');
								$('#tendered').val(parseFloat(all_total).toFixed(2));
							} else if(mode=="1") {
								$('.pay1').prop('checked','checked');
							}
                        } else {
                            alert(d.order_id_error);
                        }
                    });
 
                    request.fail(function(jqXHR, textStatus) {
                        alert("Request failed: " + textStatus);
                    });
                }

                if (main_id != '') {
                    var request = $.ajax({
                        url: "<?php echo site_url('waiters/get_order'); ?>",
                        method: "POST",
                        data: {
                            'main_id': main_id
                        },
                        dataType: "json"
                    });

                    request.done(function(result) {

                        if (result.status == 1) {
                            //console.log(msg);

                            $("#view_bill_items").html('');
                            var orders = result.order;
                            var html_items = '';
                            var vat = "<?php echo ($this->config->item('vat') / 100); ?>";
                            var total_val = 0;
                            var total_vat = 0;
							var tpi = 0;
                            var tname = '';
                            var cus_name = '';
                            orders.forEach(function(element) {
                                // console.loglog(element);
                                html_items += '<div class="col-md-6">' + element.mname +
                                    '</div><div class="col-md-2">' +
                                    element.qty +
                                    '</div><div class="col-md-4">' + result.currency.value + ' ' + element.meal_price +
                                    '</div>';
                                total_vat += vat;
                                total_val += parseFloat(element.meal_price);
                                cus_name = element.customer_name;
                                table_name = element.tname;
                            });
							tpi = parseFloat(result.tip);
                            var all_total = parseFloat(total_vat) + parseFloat(total_val);
							if(tpi=="") {
								var all_total = parseFloat(total_val)
							} else {
								var all_total = parseFloat(total_val) + parseFloat(tpi);
							}
                            $("#view_bill_items").html(html_items);
							console.log(all_total);
                            $("#bill_total").html(parseFloat(all_total).toFixed(2));
                            $("#bill_total_vat").html(parseFloat(total_vat).toFixed(2));
                            $("#new_total").html(parseFloat(all_total).toFixed(2));
                            $("#due_balance").html(parseFloat(all_total).toFixed(2));
                            $("#bill_header_total").html(parseFloat(all_total).toFixed(2));
                            $("#bill_customer_name").html(cus_name);
                            $("#bill_tab_name").html(table_name);
                            $(".currency").html(result.currency.value);
                            $("#modal_main_id").val(main_id);
                        } else {
                            alert(d.order_id_error);
                        }
                    });

                    request.fail(function(jqXHR, textStatus) {
                        alert("Request failed: " + textStatus);
                    });
                }

            });

            $(document).on('click', '.close_notice', function() {
                var notice = $(this).attr('data-id');
                $('#notice_id').val(notice);

                var form = $("#order_form").serialize();
                var request = $.ajax({
                    url: "<?php echo site_url('waiters/close_notice'); ?>",
                    method: "POST",
                    data: form,
                    dataType: "html"
                });

                request.done(function(msg) {
                    update();
                });

                request.fail(function(jqXHR, textStatus) {
                    alert("Request failed: " + textStatus);
                });

            });


            $(document).on('click', '.order_button', function() {
                var notice = $(this).attr('data-id');
                $('#notice_id').val(notice);
				var redirectUrl = $(this).attr('data-url');
				
				var title = "Authorisation Code!";
				var text = "Enter Authorisation Code:";
				var url = "<?php echo site_url('waiters/waiter_code_validate'); ?>";
				swal({
					title: title,
					text: text,
					type: "input",
					inputValue: '',
					inputType: "number",
					showCancelButton: true,
					inputClass:'passcodes',
					closeOnConfirm: true,
					inputPlaceholder: "Enter Code.."
				}, function(codeValue) {
					if (codeValue === false) return false;
					if (codeValue === "") {
						swal.showInputError("You need to write something!");
						return false
					}
					var requestt = $.ajax({
						url: url,
						method: "POST",
						data: {
							code: codeValue
						},
						dataType: "html"
					});
					requestt.done(function(msg) {
						codeStatus = jQuery.parseJSON(msg);
						if (codeStatus.status == false) {
							swal("Invalid code", "error");
							return false;
						} else {
							var form = $("#order_form").serialize();
							var request = $.ajax({
								url: "<?php echo site_url('waiters/close_notice'); ?>",
								method: "POST",
								data: form,
								dataType: "html"
							});
							request.done(function(msg) {
								update();
							});
							window.location.href=redirectUrl;
							request.fail(function(jqXHR, textStatus) {
								alert("Request failed: " + textStatus);
							});
						}
						$('.passcodes').val('');
					});
				});
            });

            $(document).on('click', '#close', function(e) {
                e.preventDefault();
                var form = $(".close_form").serialize();
                var request = $.ajax({
                    url: "<?php echo site_url('waiters/close_table'); ?>",
                    method: "POST",
                    data: form,
                    dataType: "html"
                });

                request.done(function(msg) {

                    var d = jQuery.parseJSON(msg);
                    if (d.status == 1) {
                        alert('Location closed!');
                        update();
                    } else {
                        alert('Please try again');
                    }
                });

                request.fail(function(jqXHR, textStatus) {
                    alert("Request failed: " + textStatus);
                });
            });

            
            $(document).on('click', '#done_btn', function() {
				
                var main_id = parseInt($("#modal_main_id").val());
				var oldpr = $('.pr').val();
				var newpr = $('.hpr').val();
				var newtip = $('#htip').val();
				var email = $('.newemail').val();
				var tendered = parseFloat($("#tendered").val());
				var oldemail = $('.oldemail').val();
				var totalC = parseFloat($('#due_balance').html());
				var cng = parseFloat($('#change').html());
				var tend = Number(tendered);
				if(isNaN(tend) || tend < totalC) {
					swal({title: "Error",   text: "Tendered amount should atleast "+totalC+" or higher",   type: "error"});
					return false;
				}
				// if(isNaN(cng) || tend < 0) {
					// swal({title: "Error",   text: "Change amount should atleast 0 or higher",   type: "error"});
					// return false;
				// }
				if ($('input[name=pr]').is(':checked')) {
					/***
					
						process printer here or email sending based on checked radio button
					
					***/
				} else {
					//toastr.error('Please choose at least one option');
					//return false;
				}
				
                if ($("#tip").html()) {
                    var tip = parseFloat($("#tip").html())
                } else { 
                    var tip = 0;
                }
                var new_total = parseFloat($("#new_total").html());
                var closed_by = 0;

                if (new_total == 0) {
                    var title = "Manager Code!";
                    var text = "Enter Manager Code:";
                    var url = "<?php echo site_url('waiters/manager_code_validate'); ?>";
					var input = "password";
                } else {
                    var title = "Authorisation Code!";
                    var text = "Enter Authorisation Code:";
                    var url = "<?php echo site_url('waiters/waiter_code_validate'); ?>";
					var input = "number";
                }
                swal({
                    title: title,
                    text: text,
                    type: "input",
					inputType: input,
					inputValue: '',
					inputClass:'passcodes',
                    showCancelButton: true,
                    closeOnConfirm: true,
                    inputPlaceholder: "Enter Code.."
                }, function(codeValue) {
                    if (codeValue === false) return false;
                    if (codeValue === "") {
                        swal.showInputError("You need to write something!");
                        return false
                    }
                    if (new_total == 0) {
                        var request = $.ajax({
                            url: url,
                            method: "POST",
                            data: {
                                manager_code: codeValue
                            },
                            dataType: "html"
                        });
                    } else {
                        var request = $.ajax({
                            url: url,
                            method: "POST",
                            data: {
                                code: codeValue
                            },
                            dataType: "html"
                        });
                    }

                    request.done(function(msg) {
                        var codeStatus = jQuery.parseJSON(msg);
                        if (codeStatus.status == false) {
                            swal("Invalid code", "error");
                            return false;

                        } else {
                            if (codeStatus.hasOwnProperty('waiter_id')) {
                                closed_by = codeStatus.waiter_id;
                            }

                            var pay_mode = $("[name='pay_mode']").val();
                            
                            var tendered_change = parseFloat($("#change").html());
                            var tip = parseInt($("#tip").val());
                            
                            if (main_id != '') {
								if(pay_mode=='card') {
									tendered_change=0;
								}
								
                                var request = $.ajax({
                                    url: "<?php echo site_url('waiters/save_payment'); ?>",
                                    method: "POST",
                                    data: {
                                        'main_id': main_id,
                                        'tip': tip,
                                        'pay_mode': pay_mode,
                                        'closed_by': closed_by,
                                        'tendered': tendered,
                                        'tendered_change': tendered_change,
										'htip':newtip,
										'tip':tip,
										'oldoption':oldpr,
										'newoption':newpr,
										'email':email,
										'oldemail':oldemail 
                                    },
                                    dataType: "json"
                                });

                                request.done(function(result) {
                                    if (result.status == 1) {
                                        update();
                                    } else {
                                        alert(result.order_id_error);
                                        $('#calculator_view').modal('hide');

                                    }
                                });

                                request.fail(function(jqXHR, textStatus) {
                                    //alert("Request failed: " + textStatus);
                                    $('#calculator_view').modal('hide');

                                });

                                request.done(function(result) {
                                    if (result.status == 1) {
                                        update();
                                    } else {
                                        alert(result.order_id_error);
                                        $('#view_bill').modal('hide');

                                    }
                                });

                                request.fail(function(jqXHR, textStatus) {
                                    //alert("Request failed: " + textStatus);
                                    $('#view_bill').modal('hide');

                                });
                            }
                        }
                    });
                    request.fail(function(jqXHR, textStatus) {
                        alert("Request failed: " + textStatus);
                    });
                });
                $('#calculator_view').modal('hide');
                $('#view_bill').modal('hide');
            });


            $(document).on('click', '.slider-login', function(e) {
                e.preventDefault();
                if ($(this).hasClass('show')) {
                    $(".slider-login, .panel").animate({
                        left: "+=300"
                    }, 700, function() {
                        // Animation complete. 
                        $('body').removeClass('stop-scrolling');
                    });
                    $(this).removeClass('show');
                    $(this).addClass('slider-hide');

                } else {
                    $(".slider-login, .panel").animate({
                        left: "-=300"
                    }, 700, function() {
                        // Animation complete.
                        $('body').addClass('stop-scrolling');

                    });
                    $(this).addClass('show');
                    $(this).removeClass('slider-hide');

                }
            });

            $(document).on('click', ".btn-order", function(e) {
                var element = e.target;
                var waiter_code = $(element).parent().siblings('.manager').find('.waiter_code').val();
                if (waiter_code === '') {
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
                    title: "",
                    text: confirm,
                    html: true,
                    showConfirmButton: false
                });
            });

            $(document).on('click', ".btn-yes, .btn-no", function() {
                var over_18 = $(this).text() === 'Yes' ? 1 : 0;
                $('.over_18').val(over_18);
                var form_container = $("#sit").is(':visible') ? "#sit" : "#take";
                $(form_container + ' form').submit();
            });

            $(document).on('click', '.take_away_section div', function() {
                $('.take_away_section .buttons').removeClass('btn-true');
                $(this).addClass('btn-true');
                $('#sit, #take').hide();
                $($(this).attr('data-id')).show();
            });
            $(document).on('click', '#run1', function() {
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val + "1");
            });
            $(document).on('click', '#run2', function() {
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val + "2");
            });
            $(document).on('click', '#run3', function() {
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val + "3");
            });
            $(document).on('click', '#run4', function() {
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val + "4");
            });
            $(document).on('click', '#run5', function() {
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val + "5");
            });
            $(document).on('click', '#run6', function() {
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val + "6");
            });
            $(document).on('click', '#run7', function() {
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val + "7");
            });
            $(document).on('click', '#run8', function() {
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val + "8");
            });
            $(document).on('click', '#run9', function() {
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val + "9");
            });
            $(document).on('click', '#run0', function() {
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val + "0");
            });
            $(document).on('click', '#runC', function() {
                $('#tendered').val("");
            });
            $(document).on('click', '#run10', function() {
                var field_val = $("#tendered").val();
                field_val += "10";
                field_val = parseFloat(field_val);
                $("#tendered").val(field_val);
            });
            $(document).on('click', '#run20', function() {
                var field_val = $("#tendered").val();
                field_val += "20";
                field_val = parseFloat(field_val);
                $("#tendered").val(field_val);
            });
            $(document).on('click', '#run50', function() {
                var field_val = $("#tendered").val();
                field_val += "50";
                field_val = parseFloat(field_val);
                $("#tendered").val(field_val);
            });
            $(document).on('click', '#rundecimal', function() {
                var field_val = $("#tendered").val();
                $("#tendered").val(field_val + ".");
            });
            $(document).on('click', '#runback', function() {
                var field_val = $("#tendered").val().slice(0, -1);
                $("#tendered").val(field_val);
            });
            $(document).on('keyup', '#tip', function() {
                if ($("#tip").val()) {
					var tip = $(this).val();
                    var total_val = parseFloat(tip) + parseFloat($("#total").html());
                    total_val = total_val.toFixed(2);
                    $("#new_total").html(total_val);
                    $("#header_total").html(total_val);
                    $("#due_balance").html(total_val);
                    $("#change").html("");
                    $("#tendered").val(total_val);
					//$('#htip').val(parseFloat($("#tip").val()));
                }

            });
            $(document).on('keyup', '#tendered', function() {
                $("#change").html("");
                var total_val = parseFloat($("#new_total").html());
                var tendered = parseFloat($("#tendered").val());
                if (tendered > total_val) {
                    var change = tendered - total_val;
                    change = change.toFixed(2);
                    $("#change").html(change);
                }

            });
            $(document).on('click', 'oper', function() {
                $("#change").html("");
                var total_val = parseFloat($("#new_total").html());
                var tendered = parseFloat($("#tendered").val());
                if (tendered > total_val) {
                    var change = tendered - total_val;

                    change = change.toFixed(2);
                    $("#change").html(change);
                }

            });
            $(document).on('click', '.digit', function() {
                $("#change").html("");
                var total_val = parseFloat($("#new_total").html());
                var tendered = parseFloat($("#tendered").val());
                if (tendered > total_val) {
                    var change = tendered - total_val;
                    change = change.toFixed(2);
                    $("#change").html(change);
                }

            });
        });

        /** Request Email Bill */
        $(document).on('click', '.request_email_btn', function(){
            swal({
                title: "Request Bill",
                text: "Send bill to my email:",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                inputPlaceholder: "Email:"
                }, function (inputValue) {
                if (inputValue === false){ 
                    request_bill();
                    return false;
                }
                if (inputValue === "") {
                    swal.showInputError("Please enter your email!");
                    return false;
                }
                    request_bill();
                    if(inputValue != '')
                    send_bill_email(inputValue);
                });
            
        });

        function request_bill(){
            var data = $('.pay_form').serializeArray(); 
            var tip     = $(".tip").val();
            var payment = $("input[name=payop]:checked").val(); 

            if(typeof payment === typeof undefined && payment > 3){
                swal({   title: "Error",   text: 'Please select a payment option!',   type: "error", html : true});
            }

            data.push({ name: "tip", value: tip });
            data.push({ name: "payment", value: payment });

            var request = $.ajax({
                url: "<?php echo site_url('customer/request_bill/'); ?>/",
                method: "POST",
                data: data,
                dataType: "html"
            });
            request.done(function( msg ) {

                var d = jQuery.parseJSON(msg);
                if (d.status == 1){
                    $("#paynow").click();
                    if(payment == 3){
                        location.href = 'http://zapper.com/';
                    }else{
                        swal({   title: "Success",   text: d.msg,   type: "success"});
                        $(".request_bill_btn").addClass('request_done_btn');
                        $('.request_done_btn').removeClass('request_bill_btn');
                    }    
                }
                else if(d.status == 2){

                    swal({   title: "Error",   text: d.msg, type : 'error',  html: true })
                }
                else{
                    swal({   title: "Error",   text: d.msg,   type: "error"});
                }    
            });

            request.fail(function( jqXHR, textStatus ) {
                alert( "Request failed: " + textStatus );
            });
        }
        
        function send_bill_email(email){
            var data = $('.pay_form').serializeArray();
                                
            var tip     = $(".tip").val();
            var payment = $("input[name=payop]:checked").val(); 

            if(!isValidEmailAddress(email)){
                swal({   title: "Error",   text: 'Please enter a valid email address!',   type: "error", html : true});
                return;
            }

            if(typeof payment === typeof undefined && payment > 3){
                swal({   title: "Error",   text: 'Please select a payment option!',   type: "error", html : true});
                return; 
            }

            data.push({ name: "tip", value: tip });
            data.push({ name: "payment", value: payment });
            data.push({ name: "email", value: email });

            var request = $.ajax({
                url: "<?php echo site_url('customer/send_bill'); ?>",
                method: "POST",
                data: data,
                dataType: "html"
            });

            request.done(function( order ) {
                var d = jQuery.parseJSON(order);
                if(d.status == 1){
                    swal({   title: "Success. You will now be logged out..!",   text: d.msg,   type: "success"});
                    location.href = "<?php echo site_url('customer/close') ?>";
                    clearInterval(backgroundInterval);
                }
                else {
                    swal({   title: "Error",   text: d.msg,   type: "error"});
                }
            });    
        }
		
		$(document).on('change','.pay_mode',function(e){
			var name = $(this).val();
			if(name=="card") {
				var r = $('#due_balance').text();
				$('#tendered').val(r);
				$('#change,#changelbl').addClass('hidden');
				$('#tendered').attr('disabled','disabled');
			} else if(name=="cash") {
				$('#change,#changelbl').removeClass('hidden').val('');
				var r = $('#due_balance').text();
				$('#tendered').removeAttr('disabled');
				$('#tendered').val('');
			}
		});
		
		$(document).on('change','.pr',function(e){
			var name = $(this).val();
			if(name=="1") {
				$('.hpr').val('1');
				$('.newe').removeClass('hidden');
				//($('.newemail').val());
			} else if(name=="2") {
				$('.hpr').val('2');
				$('.newe').addClass('hidden');
			} else if(name=="3") {
				$('.hpr').val('3');
				$('.newe').removeClass('hidden');
			} else if(name=="4") {
				$('.hpr').val('4');
				$('.newe').addClass('hidden');
			}
		});
		$(document).on('click','.poss',function() {
			$('.pay2').attr('checked','');
			$('.pay1').attr('checked','checked');
			var title = "Authorisation Code!";
            var text = "Enter Authorisation Code:";
			var url = "<?php echo site_url('waiters/waiter_code_validate'); ?>";
			swal({
				title: title,
				text: text,
				type: "input",
				inputValue: '',
				inputType: "number",
				showCancelButton: true,
				inputClass:'passcodes',
				closeOnConfirm: true,
				inputPlaceholder: "Enter Code.."
			}, function(codeValue) {
				if (codeValue === false) return false;
				if (codeValue === "") {
					swal.showInputError("You need to write something!");
					return false
				}
				var request = $.ajax({
					url: url,
					method: "POST",
					data: {
						code: codeValue
					},
					dataType: "html"
				});
				request.done(function(msg) {
					codeStatus = jQuery.parseJSON(msg);
					if (codeStatus.status == false) {
						swal("Invalid code", "error");
						return false;
					} else {
						$('#tendered').val('');
						$('#calculator_view').modal('show');
					}
					$('.passcodes').val('');
				});
			});
			
			$('#tendered').val('');
		});
		
		$(document).on('click','.back-step',function(){
			var oid = $(this).data('oid');
			var id = $(this).data('id');
			var processed = $(this).data('processed');
			if(processed == 0 ) {
				toastr.error('Invalid process state!');
				return false;
			} else if(processed=="1" || processed == "2") {
				
				$.ajax({
					url: "<?php echo site_url('waiters/stage_back'); ?>",
					method: "POST",
					data: {'processed':processed,'oid':oid,'id':id},
					dataType: "JSON",
					beforeSend:function(){
						$('.loader'+oid).css("visibility", "visible");
					},
					success:function(res) {
						$('.ajax-loader').css("visibility", "hidden");
						if(res!="1") {
							toastr.error(res);
						}
					},
					complete:function() {
						setTimeout(function(){
							$('.loader'+oid).css("visibility", "hidden");
						},3000);
					}
				});
			}
		}); 
		
		$(document).on('click','.choice',function(){
			var choice = $(this).attr('data-tb');
			if(choice=="calc") {
				$('#choice').val('collection');
			} else if(choice=='dell') {
				$('#choice').val('delivery');
			}
		});
    </script> 
<?php endif; ?>