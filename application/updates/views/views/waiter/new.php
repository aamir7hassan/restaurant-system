<?php

$data = $atributes = array();
foreach ($meals as $key => $meal){
  $data[$meal->cid]['cname']                                 = isset($meal->cname)       ? $meal->cname : "";
  if( !empty($meal->mname) ){
    $data[$meal->cid]['meal'][$meal->mid]['name']          = isset($meal->mname)       ? $meal->mname : "";
    $data[$meal->cid]['meal'][$meal->mid]['stock']         = isset($meal->out_of_stock) ? $meal->out_of_stock : 0;
    $data[$meal->cid]['meal'][$meal->mid]['description']   = isset($meal->description) ? $meal->description : "";
    $data[$meal->cid]['meal'][$meal->mid]['price']         = isset($meal->price)       ? $meal->price : "";
    $data[$meal->cid]['meal'][$meal->mid]['quantity']      = isset($meal->quantity)    ? $meal->quantity : 0;
    $data[$meal->cid]['meal'][$meal->mid]['special']       = isset($meal->special)     ? $meal->special : "";
    $data[$meal->cid]['meal'][$meal->mid]['special_days']       = isset($meal->special_days)? json_decode($meal->special_days, true ): "";
    $data[$meal->cid]['meal'][$meal->mid]['special_from']       = isset($meal->special_from)     ? json_decode($meal->special_from, true) : "";
    $data[$meal->cid]['meal'][$meal->mid]['special_to']         = isset($meal->special_to)     ? json_decode($meal->special_to, true) : "";

    $atributes[$meal->cid]['meal']['attr'][$meal->aid][$meal->aname]  = isset($meal->value) ? $meal->value : "";
    if(!empty($meal->aname)){

      $values = json_decode($meal->values);
      if(isset($values) && is_array($values)){
        foreach ($values as $val){
          $attribute[$meal->mid][$meal->aname][]       = $val;
          $attribute[$meal->mid][$meal->aname]['id']   = $meal->aid;
          $attribute[$meal->mid][$meal->aname]['type'] = $meal->type;
          $attribute[$meal->mid][$meal->aname]['required'] = $meal->required;
        }
      }
    }
  }
}
?>
<div class="container">
  <style type="text/css">
  .alert-error{
    background: #f4ffbe;
}
.call_waiter_wrapper{
    background: #FFF;
    padding: 28px;
}
.timer{color: #F8A519; font-size: 12px;}
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
.layer {
    background-color: rgba(21, 22, 22, 0.7);
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
.take_away_section button{padding: 14px 22px;}
.take_away_section button.btn-default{background: #ffffff;}
.header-img{margin: -10px auto;width: 180px; }
.loader{
    background: yellow;
    padding: 12px;
    position: absolute;
    top: 52px;
    left: 42%;
    display: none;
}
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
    background: #ae0000;
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
.table_name{
    font-weight: bold;
    left: 68px;
    margin-top: 10px !important;
    position: absolute;
    top: 0px;
}
.open {
    background: #02d8fd;
}
.grey{ background: #eeeeee; padding: 0; height: auto;}
.greyed{background: #ccc;}
ul.menu_list {
    list-style: outside none none;
    padding: 0;
}
ul.menu_list > li {
    background: #333 none repeat scroll 0 0;
    border-top: 2px solid #fff;
    width: 100%;
    padding: 20px;
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=60)";       /* IE 8 */
    filter: alpha(opacity=60);  /* IE 5-7 */
    -moz-opacity: 0.6;          /* Netscape */
    -khtml-opacity: 0.6;        /* Safari 1.x */
    opacity: 0.6;               /* Good browsers */
    cursor: pointer;
}
.footer {
    background: #fff none repeat scroll 0 0;
    bottom: 0;
    padding: 5px;
    position: absolute;
    width: 100%;
}
a{ text-decoration: none; }
ul.menu_list li > a{
    color: #fff;
    font-size: 18px;
    font-weight: bold;

}
.menu_sub, .order_sub, .split_bill {
    list-style: outside none none;
    margin: 0;
    padding: 0;
    display: none;
}

.menu_sub > li, .order_sub > li, .split_bill li {
    background: #fff none repeat scroll 0 0 !important;
    border-top: 1px solid #333 !important;
    border-bottom: 1px solid #333 !important;
    opacity: 1 !important;
    width: 100%;
    padding: 20px;
    cursor: pointer;
}
.margins, .initial {
    border: 1px solid #ccc;
    margin: 4px 10px !important;
}
.menu_sub > li > a, .order_sub > li > a{
    color: #000000 !important;
    font-size: 18px;
    font-weight: normal !important;
}
.display{display: block !important; -webkit-transition-delay: 2s; /* Safari */ transition-delay: 2s;}
.bill_summary, .details, .pay_bill_wrapper, .add_budget_wrapper{ background: #FFF; }
.details_container, .bill_summary, .pay_bill_wrapper, .add_budget_wrapper{ display: none; }


.price{ font-size: 16px; font-weight: bold; padding-right: 5px;}
.error, .red{color: red;}
.green{ color: greenyellow; }

.table {
    margin-left: 10%;
    width: 80%;
}
.table td{ text-align: left;}
.radio_class {
    margin: 1px 26px 5px 8px !important;
}
.attr_area h1{font-size: 14px; font-weight: bold; margin-top: 10px;}
.attr_area {
    padding-bottom: 5px;
}
.button {
    background: #fab219 none repeat scroll 0 0;
    border: medium none;
    color: #fff;
    font-size: 18px;
    font-weight: bold;
    padding: 8px;
    width: 100%;
}
.nopadding {
   padding: 0 !important;
   margin: 0 !important;
}
button .glyphicon.glyphicon-plus {
    font-size: 16px;
    font-weight: normal !important;
    margin-right: 10px;
}
div.header_section{
    background:  #fab219;
}
div.header_section {
    background: #fab219 none repeat scroll 0 0;
    margin: 0 0 12px;
    padding: 7px 0;
}
.header_section .price, .header_section .glyphicon{ color: #FFF; }
.open, .open1 {
    color: #ffffff;
    cursor: pointer;
    float: right;
    font-size: 38px;
    height: 58px;
    margin-bottom: 5px;
    padding-top: 2px;
    text-align: center;
    width: 55px;
}
.open1{background: #ddd; cursor: default;}
.grey{background: #eee;}
.initial_view{cursor: pointer;}
.checkbox-area{    padding-top: 16px;text-align: center;
    color: red;
    font-size: 20px;}
.checkbox-area input{
        width: 77%;
    height: 30px;
    display: inline;
}

  </style>

  <div class="row">
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
              <h2 style="display: inline-block;"><a  class='all-orders' href="<?php echo site_url('waiters') ?>">All</a></h2>
              <h2 style="display: inline-block;padding-left:5px;padding-right:5px;"><img src="<?php echo base_url('assets/images/divider.png'); ?>" style="max-height: 34px;"></h2>
              <h2 style="display: inline-block;"><a class='new-orders' href="<?php echo site_url('waiters/index/new') ?>">New</a></h2>
              <h2 style="display: inline-block;padding-left:5px;padding-right:5px;"><img src="<?php echo base_url('assets/images/divider.png'); ?>" style="max-height: 34px;"></h2>
              <h2 style="display: inline-block;"><a class='delivered-orders' href="<?php echo site_url('waiters/index/delivered') ?>">Delivered</a></h2>
              <h2 style="display: inline-block;padding-left:5px;padding-right:5px;"><img src="<?php echo base_url('assets/images/divider.png'); ?>" style="max-height: 34px;"></h2>
              <h2 style="display: inline-block;"><a class='waiting-orders' href="<?php echo site_url('waiters/index/waiting') ?>">Waiting</a></h2>
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
      <div class="col-md-6 col-sm-6 col-md-offset-3 col-sm-offset-3 " >
        <h1 class="table_header" style="margin-bottom:0 !important; height:52px;background: #3498db ;color:white;border-radius: 25px 25px 0px 0px; cursor:n-resize;">
          <div class="force_close" data-id=""><img src="<?php echo base_url('assets/images/close2icn.png'); ?>" style="max-height: 48px;"></div>
          <?php echo 'L '.$order_details->tname.' | '.$order_details->customer_name; ?>
          <div class="pull-right">
            <?php echo Date('G:i A', strtotime( $det['rtime'] ) ); ?>
          </div>
          <div class="pull-right" style="padding-right: 40px;">
            <a href="<?php echo site_url('waiters') ?>" style="margin-top:0px;" class="btn btn-danger btn-sm pull-right" >Home</a>
            <div class="clearfix"></div>
          </h1>
          <div class="details" style="background:#272323CC; color: #FFFFF;">
            <div class="">
                <ul class="menu_list">
                    <li>
                        <a href="javascript:void(0);" class="show_submenu">View Menu</a>
                    </li>
                    <ul class="menu_sub menu_sub_1">
                        <?php foreach ($data as $key => $det): ?>
                        <?php if( count($det['meal']) > 0 ): ?>
                        <li><a href="javascript:void(0);"><?php echo $det['cname']; ?></a></li>
                            <div class="details_container">


                                <?php $k = 0; foreach ($det['meal'] as $k => $ml): ?>
                                    <?php $ml['stock'] = $display ? $ml['stock'] : 1;
                                    if( $ml['quantity'] == 0 &&  $hide_empty_stock):   ?>
                                                <div class="details text-left container">
                                                </div>
                                            <?php else :?>
                                    <div class="details text-left container">
                                        <div>
                                            <div class="margins margin-<?php echo $k; ?>" style="display:none;">

                                                <div class="header_section">
                                                <div class="col-xs-4">
                                                        <div class="price"><?php echo CURRENCY_CODE.' '.price_calc($ml['price']); ?></div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <?php if($show_avail_stock) { ?>
                                                        <div class="quantity"><?php echo $ml['quantity'] . " Available "; ?></div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <span class="glyphicon <?php echo in_array($k, $order) ? 'glyphicon-ok green' : ''; ?> glypph_<?php echo $k; ?>" style=''></span>
                                                    </div>
                                                    <div class="col-xs-2 initial_view" data-id="<?php echo $k; ?>">
                                                        <span class="glyphicon glyphicon-arrow-left"></span>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>

                                                <?php echo form_open('', array('class' => 'form-'.$k)); ?>
                                                    <div class="">
                                                        <div class="col-xs-12">
                                                            <p><strong><?php echo $ml['name']; ?></strong></p>
                                                            <?php echo $ml['description']; ?>
                                                        </div>

                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="">
                                                        <div class="col-xs-12">
                                                            <?php if( isset( $attribute[$k] )) : $i = 1;  ?>
                                                                <?php foreach ( $attribute[$k] as $attr_text => $value):?>
                                                                    <div class="attr_area">
                                                                        <h1><?php echo $attr_text; ?></h1>

                                                                        <?php foreach ($value as $v): ?>
                                                                        <?php if(empty($v->name)) continue; ?>
                                                                        <?php $price_details = (!empty($v->price) && $v->price > 0) ? " ( +".CURRENCY_CODE." ".price_calc($v->price)." ) " : "" ?>

                                                                        <p>
                                                                            <?php if( $value['type'] == 'multi'): ?>
                                                                                <input class="radio_class" <?php echo $value['required'] == 1 ? 'required' : '' ?> type="checkbox" name="attrs[<?php echo $value['id'] ?>][]" value="<?php echo $v->name ?>"><?php echo $v->name.$price_details ; ?>
                                                                            <?php else: ?>
                                                                                <input class="radio_class" <?php echo $value['required'] == 1 ? 'required' : '' ?> type="radio" name="attr[<?php echo $value['id'] ?>]" value="<?php echo $v->name ?>"><?php echo $v->name.$price_details ; ?>
                                                                            <?php endif; ?>
                                                                        </p>

                                                                        <?php endforeach; ?>
                                                                    </div>
                                                                <?php ++$i; endforeach; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <?php if( !in_array($k, $order) ): ?>
                                                    <div class=" cmnt_<?php echo $k; ?>">
                                                        <div class="col-xs-12">
                                                            <a href="javascript:void(0);" id="comment">Add a comment</a>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="col-xs-12">
                                                            <div class="comment_area">
                                                                <textarea name="comment" class="form-control" placeholder="No more than 60 characters" maxlength="60"></textarea><br/>
                                                                <div class="clearfix"></div>
                                                            </div><br/>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                    <div class="">
                                                        <div class="col-xs-2">Quantity</div>
                                                        <div class="col-xs-8">
                                                            <select name="qty" class="form-control">
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                                <option value="6">6</option>
                                                                <option value="7">7</option>
                                                                <option value="8">8</option>
                                                                <option value="9">9</option>
                                                                <option value="10">10</option>
                                                            </select>
                                                            <br/>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="">
                                                        <div class="col-xs-6 nopadding">
                                                            <button class="button" style="width:99%;" id="cancel_order" data-id="<?php echo $k; ?>">Cancel order <span class="counter"></span></button>
                                                        </div>
                                                        <div class="col-xs-6 nopadding">
                                                                <input type="hidden" name="meal_id" value="<?php echo $k; ?>">
                                                                <input type="hidden" name="details_id" id="details_id" value="">
                                                                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                                                                <input type="hidden" name="price" value="<?php echo $price = price_calc($ml['price']); ?>">

                                                                <button data-category="<?php echo $det['cname']; ?>" class="addit button" data-id="<?php echo $k; ?>" data-exist="<?php echo in_array($k, $order) ? 'yes' : 'no'; ?>"><span class="glyphicon glyphicon-plus"></span>Order Now</button>

                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>

                                                <?php echo form_close(); ?>
                                            </div>
                                            <div class="initial initial-<?php echo $k; ?>">
                                                <?php echo form_open('', array('class' => 'form-'.$k)); ?>
                                                    <div class=" <?php echo $ml['stock'] == 1 ? "grey" : ""; ?>">
                                                        <div class="col-lg-9">
                                                            <p><strong><?php echo $ml['name']; ?></strong></p>
                                                            <?php echo $ml['description']; ?>
                                                        </div>
                                                        <div class="col-lg-3 text-right">

                                                            <div class="<?php echo $ml['stock'] == 1 ? "open1" : "open"; ?>" data-id="<?php echo $k; ?>" data-exist="<?php echo in_array($k, $order) ? 'yes' : 'no'; ?>">+</div>
                                                            <div class="clearfix"></div>
                                                            <div class="price"><span class="glyphicon <?php echo in_array($k, $order) ? 'glyphicon-ok green' : ''; ?> glypph_<?php echo $k; ?>" style=''></span><?php echo CURRENCY_CODE.' '.$price; ?></div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>



                                                <?php echo form_close(); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif;?>
                                <?php endforeach; ?>
                            </div>

                        <?php endif; endforeach; ?>
                    </ul>

                    <li>
                        <a href="javascript:void(0);" class="specials">Specials</a>
                    </li>
                    <ul class="menu_sub_2 menu_sub">
                        <?php foreach ($data as $key => $det): ?>
                        <?php if( count($det['meal']) > 0): ?>
                        <li><a href="javascript:void(0);"><?php echo $det['cname']; ?></a></li>
                            <div class="details_container">


                                <?php foreach ($det['meal'] as $k => $ml): ?>
                                    <?php if($ml['special'] == 1 && check_dates_special($ml) ):
                                            if( $ml['quantity'] == 0 &&  $hide_empty_stock):   ?>
                                                <div class="details text-left container">
                                                </div>
                                            <?php else :?>

                                    <div class="details text-left container">
                                        <div>
                                            <div class="margins margin-<?php echo $k; ?>" style="display:none;">

                                                <div class="header_section">
                                                    <div class="col-xs-4">
                                                        <div class="price"><?php echo CURRENCY_CODE.' '.price_calc($ml['price']); ?></div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <?php if($show_avail_stock) { ?>
                                                            <div class="quantity"><?php echo $ml['quantity'] . " Available "; ?></div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <span class="glyphicon <?php echo in_array($k, $order) ? 'glyphicon-ok green' : ''; ?> glypph_<?php echo $k; ?>" style=''></span>
                                                    </div>
                                                    <div class="col-xs-2 initial_view" data-id="<?php echo $k; ?>">
                                                        <span class="glyphicon glyphicon-arrow-left"></span>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>

                                                <?php echo form_open('', array('class' => 'form-'.$k)); ?>
                                                    <div class="">
                                                        <div class="col-xs-12">
                                                            <p><strong><?php echo $ml['name']; ?></strong></p>
                                                            <?php echo $ml['description']; ?>
                                                        </div>

                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <?php if( isset( $attribute[$k] )) : $i = 1;  ?>
                                                                <?php foreach ( $attribute[$k] as $attr_text => $value):?>
                                                                    <div class="attr_area">
                                                                        <h1><?php echo $attr_text; ?></h1>

                                                                        <?php foreach ($value as $v): ?>
                                                                        <?php if(empty($v->name)) continue; ?>
                                                                        <?php $price_details = (!empty($v->price) && $v->price > 0) ? " ( +".CURRENCY_CODE." ".price_calc($v->price)." ) " : "" ?>

                                                                        <p>
                                                                            <?php if( $value['type'] == 'multi'): ?>
                                                                                <input class="radio_class" <?php echo $value['required'] == 1 ? 'required' : '' ?> type="checkbox" name="attrs[<?php echo $value['id'] ?>][]" value="<?php echo $v->name ?>"><?php echo $v->name.$price_details ; ?>
                                                                            <?php else: ?>
                                                                                <input class="radio_class" <?php echo $value['required'] == 1 ? 'required' : '' ?> type="radio" name="attr[<?php echo $value['id'] ?>]" value="<?php echo $v->name ?>"><?php echo $v->name.$price_details ; ?>
                                                                            <?php endif; ?>
                                                                        </p>

                                                                        <?php endforeach; ?>
                                                                    </div>
                                                                <?php ++$i; endforeach; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <?php if( !in_array($k, $order) ): ?>
                                                    <div class="row cmnt_<?php echo $k; ?>">
                                                        <div class="col-xs-12">
                                                            <a href="javascript:void(0);" id="comment">Add a comment</a>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="col-xs-12">
                                                            <div class="comment_area">
                                                                <textarea name="comment" class="form-control" placeholder="No more than 60 characters" maxlength="60"></textarea><br/>
                                                                <div class="clearfix"></div>
                                                            </div><br/>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                    <div class="row">
                                                        <div class="col-xs-2">Quantity</div>
                                                        <div class="col-xs-8">
                                                            <select name="qty" class="form-control">
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                                <option value="6">6</option>
                                                                <option value="7">7</option>
                                                                <option value="8">8</option>
                                                                <option value="9">9</option>
                                                                <option value="10">10</option>
                                                            </select>
                                                            <br/>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-6 nopadding">
                                                            <button class="button" style="width:99%;" id="cancel_order" data-id="<?php echo $k; ?>">Cancel order <span class="counter"></span></button>
                                                        </div>
                                                        <div class="col-xs-6 nopadding">
                                                                <input type="hidden" name="meal_id" value="<?php echo $k; ?>">
                                                                <input type="hidden" name="details_id" id="details_id" value="">
                                                                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                                                                <input type="hidden" name="price" value="<?php echo $price = price_calc($ml['price']); ?>">

                                                                <button data-category="<?php echo $det['cname']; ?>" class="addit button" data-id="<?php echo $k; ?>" data-exist="<?php echo in_array($k, $order) ? 'yes' : 'no'; ?>"><span class="glyphicon glyphicon-plus"></span>Order Now</button>

                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>

                                                <?php echo form_close(); ?>
                                            </div>
                                            <div class="initial initial-<?php echo $k; ?>">
                                                <?php echo form_open('', array('class' => 'form-'.$k)); ?>
                                                    <div class="">
                                                        <div class="col-lg-9">
                                                            <p><strong><?php echo $ml['name']; ?></strong></p>
                                                            <?php echo $ml['description']; ?>
                                                        </div>
                                                        <div class="col-lg-3 text-right">

                                                            <div class="open" data-id="<?php echo $k; ?>" data-exist="<?php echo in_array($k, $order) ? 'yes' : 'no'; ?>">+</div>
                                                            <div class="clearfix"></div>
                                                            <div class="price"><span class="glyphicon <?php echo in_array($k, $order) ? 'glyphicon-ok green' : ''; ?> glypph_<?php echo $k; ?>" style=''></span><?php echo CURRENCY_CODE.' '.$price; ?></div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>



                                                <?php echo form_close(); ?>
                                            </div>
                                        </div>
                                    </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>

                        <?php endif; endforeach; ?>
                    </ul>

                </ul>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
      function isValidEmailAddress(emailAddress) {
          if(emailAddress == '')
              return false;

          var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
          return pattern.test(emailAddress);
      };
      function toHHMMSS(time) {

          if(time == '' || typeof time == 'undefined')
              return;

          var start  = new Date().getTime();
          var end    = new Date(time).getTime();
          millisec  = (start-end);

          var seconds = (millisec / 1000).toFixed(0);
          var minutes = Math.floor(seconds / 60);
          var hours = "";
          if (minutes > 59) {
              hours = Math.floor(minutes / 60);
              hours = (hours >= 10) ? hours : "0" + hours;
              minutes = minutes - (hours * 60);
              minutes = (minutes >= 10) ? minutes : "0" + minutes;
          }

          seconds = Math.floor(seconds % 60);
          seconds = (seconds >= 10) ? seconds : "0" + seconds;
          if (hours != "") {
              timer = hours + ":" + minutes + ":" + seconds;
          }
          else
              timer =  minutes + ":" + seconds; console.log(timer);

          if(!isNaN(minutes) && !isNaN(seconds))
              $('.request_bill_btn').text('Request Bill '+timer);
          else
              $('.request_bill_btn').text('Request Bill');
      }


      function start(time){
          toHHMMSS(time);
          clearInterval(backgroundInterval);
          var backgroundInterval = setInterval(function() { toHHMMSS(time); },1000);
      }

      function isOneChecked(form) {
          // All <input> tags...
          var chx = $(form+' input[type=radio], '+form+' input[type=checkbox]');
          if(chx.length <= 0){
              return true;
          }
          for (var i=0; i<chx.length; i++) {
            // If you have more than one radio group, also check the name attribute
            // for the one you want as in && chx[i].name == 'choose'
            // Return true from the function on first match of a checked item
            if ((chx[i].type == 'radio' || chx[i].type == 'checkbox' ) && chx[i].checked) {
              return true;
            }
          }
          // End of the loop, return false
          return false;
      }

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
              if(d.status == 1)
                  swal({   title: "Success",   text: d.msg,   type: "success"});
              else
                  swal({   title: "Error",   text: d.msg,   type: "error"});
          });
      }

      function check_session(){
          var request = $.ajax({
              url: "<?php echo site_url('customer/check_session/'); ?>/",
              method: "GET",
              dataType: "html"
          });


          request.done(function( msg ) {

              var d = jQuery.parseJSON(msg);
              if (d.status == 0){
                  alert('You have been logged out.');
                  location.href = "<?php echo site_url('customer/') ?>";
                  clearInterval(backgroundInterval);
              }
          });

      }
    //  var backgroundInterval = setInterval(check_session,10000);

      $(document).ready(function(){

          $(document).on('click', '.request_done_btn', function(){
              swal({   title: "Please wait",   text: 'Bill already requested.',   type: "error"});
          });

          $(document).on('click', '.authorize', function(){

              var order_id = $(this).attr('data-id');
              $('.update_order').val(order_id);

              var data = $('.pay_form').serialize();
              var request = $.ajax({
                  url: "<?php echo site_url('customer/authorize/'); ?>/",
                  method: "POST",
                  data: data,
                  dataType: "html"
              });


              request.done(function( msg ) {

                  var d = jQuery.parseJSON(msg);
                  if (d.status == 1){
                      $("#paynow").click();
                      swal({   title: "Success",   text: d.msg,   type: "success"});
                  }
                  else{
                      swal({   title: "Error",   text: d.msg,   type: "error"});
                  }
              });

              request.fail(function( jqXHR, textStatus ) {
                  alert( "Request failed: " + textStatus );
              });

          });


          $(document).on('click', '.call_waiter', function(){
              var data = $('.call_waiter_form').serialize();
              var request = $.ajax({
                  url: "<?php echo site_url('customer/call_waiter/'); ?>/",
                  method: "POST",
                  data: data,
                  dataType: "html"
              });


              request.done(function( msg ) {

                  var d = jQuery.parseJSON(msg);
                  if (d.status == 1){
                      $('.call_waiter_wrapper').addClass('hidden');
                      swal({   title: "Success",   text: d.msg,   type: "success"});
                  }
                  else{
                      swal({   title: "Error",   text: d.msg,   type: "error"});
                  }
              });

              request.fail(function( jqXHR, textStatus ) {
                  alert( "Request failed: " + textStatus );
              });


          });


          $(document).on('click', '.open', function(){
              var id = $(this).attr('data-id');
              $('.margin-'+id).show();
              $('.initial-'+id).hide();
          });

          $(document).on('click', '.initial_view', function(){
              var id = $(this).attr('data-id');
              $('.margin-'+id).hide();
              $('.initial-'+id).show();
          });


          $(document).on('click', '.addBud', function(e){
              e.preventDefault();
              var data = $('.budget').serialize();

              var request = $.ajax({
                  url: "<?php echo site_url('customer/add_budget/'); ?>/",
                  method: "POST",
                  data: data,
                  dataType: "html"
              });


              request.done(function( msg ) {

                  var d = jQuery.parseJSON(msg);
                  if (d.status == 1){
                      swal({   title: "Success",   text: d.msg,   type: "success"});
                  }
                  else{
                      swal({   title: "Error",   text: d.msg,   type: "error", html : true});
                  }
              });

              request.fail(function( jqXHR, textStatus ) {
                  swal({   title: "Error",   text: 'Request failed!',   type: "error", html : true});
              });
          });

          $(document).on('click', '.request_bill_btn', function(){
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



         $(".menu_list > li:first").click(function(){
            $('.pay_bill_wrapper').removeClass('display');
            $('.add_budget_wrapper').removeClass('dislay');
            $(".menu_sub_1").toggleClass('display');
         });

         $(".specials").click(function(){
            $('.pay_bill_wrapper').removeClass('display');
            $('.add_budget_wrapper').removeClass('dislay');
            $(".menu_sub_1").removeClass('display');
            $(".menu_sub_2").toggleClass('display');
         });

         $(".menu_list > li:last").click(function(){
            $(".split_bill").toggleClass('display');
         });

         $("#orders").click(function(){

             $('.order_sub').toggleClass('display');
             $(".menu_sub").removeClass('display');

             var data = $('.view_orders').serialize();

             var request = $.ajax({
                      url: "<?php echo site_url('customer/view_orders'); ?>",
                      method: "POST",
                      data: data,
                      dataType: "html"
              });

              request.done(function( msg ) {

                  var d = jQuery.parseJSON(msg);
                  if (d.status == 1){
                      orders = d.orders;
                      $('.load').hide();

                      var content = '';

                      $( orders ).each(function( index, element ) {
                          content += '<li>'+element.name+'</li>'
                      });
                      $('.order_sub').html(content);

                  }
                  else{
                      $('.order_sub').html('<li>Sorry. No orders found</li>');
                  }
              });

              request.fail(function( jqXHR, textStatus ) {
                  $('.order_sub').html('<li>Sorry. No orders found</li>');
              });


         });

         $(".menu_sub > li").click(function(){

              if( !$(this).next().hasClass('display') ){
                  $(this).next().show();
                  $(this).next().addClass('display');
              }
              else{
                  $(this).next().hide();
                  $(this).next().removeClass('display');
              }
         });

          $(document).on('click', '#cancel_order', function(e){
              e.preventDefault();
              var meal  = $(this).attr('data-id');
              var data  = $('.form-'+meal).serialize();
              var request = $.ajax({
                      url: "<?php echo site_url('customer/cancel_order'); ?>",
                      method: "POST",
                      data: data,
                      dataType: "html"
              });

              request.done(function( msg ) {

                  var d = jQuery.parseJSON(msg);
                  if (d.status == 1){
                      swal({   title: "Success",   text: d.msg,   type: "success"});
                      $('.glypph_'+meal).remove();
                  }
                  else{
                      swal({   title: "Error",   text: d.msg,   type: "error"});
                  }
              });

              request.fail(function( jqXHR, textStatus ) {
                  swal({   title: "Sorry",   text: d.msg,   type: "error"});
              });

          });

          $(document).on('click', '.add_comment', function(e){
              e.preventDefault();
              var meal  = $(this).attr('data-id');

              if($('.form-'+meal+' textarea').val() == ''){
                  alert('Please enter your comment');
                  return false;
              }

              var data  = $('.form-'+meal).serialize();
              var request = $.ajax({
                      url: "<?php echo site_url('customer/comment'); ?>",
                      method: "POST",
                      data: data,
                      dataType: "html"
              });

              request.done(function( msg ) {

                  var d = jQuery.parseJSON(msg);
                  if (d.status == 1){
                      alert('Comment added!');
                  }
                  else{
                      alert('Comment failed');
                  }
              });

              request.fail(function( jqXHR, textStatus ) {
                  alert( "Comment failed");
              });


          });


          $(document).on('click', '.addit', function(e){
             e.preventDefault();
             var meal  = $(this).attr('data-id');
             var exist = $(this).attr('data-exist');
             var cat   = $(this).attr('data-category');

             var cont = true;

             //if (exist == 'yes'){
               //  cont = confirm('Are you sure you want to order another?');
             //}

             if(!cont)
                 return false;

             $('.glypph_'+meal).addClass('glyphicon-play');

             var data = $('.form-'+meal).serializeArray();
             console.log(data);
             data.push({ name: "category", value: cat });
             /*
             var isValid = $(e.target).parents('form').isValid();
             alert(isValid);
             return;
             if(!isOneChecked('.form-'+meal))
             {
                 swal({   title: "Error",   text: "Please select an attribute",   type: "error"});
                 return false;
             }**/
             var request = $.ajax({
                  url: "<?php echo site_url('customer/order_meal/0'); ?>",
                  method: "POST",
                  data: data,
                  dataType: "html"
              });

              request.done(function( msg ) {

                  var d = jQuery.parseJSON(msg);
                  if (d.status == 1){
                      $('.glypph_'+d.meal).removeClass('glyphicon-play');
                      $('.glypph_'+d.meal).addClass('glyphicon-ok green');
                      $('.cmnt_'+d.meal).hide();
                      $('.margin-'+d.meal).find( ".quantity" ).text(d.quantity+' Available');

                      $('.form-'+meal+' #details_id').val(d.oid);
                      $('.form-'+meal+' .addit').attr('data-exist', 'yes');
                      $('.form-'+meal+' .addit').html('<span class="glyphicon glyphicon-plus"></span>Processing..').prop('disabled', true);
                      setTimeout(function(){
                          $('.form-'+meal+' .addit').html('<span class="glyphicon glyphicon-plus"></span>Order Another').prop('disabled', false);
                      }, 10*1000);

                      var count = 10, timer = setInterval(function() {
                          $('.form-'+meal+' .counter').html(count--);
                          if(count == 0) { clearInterval(timer); $('.form-'+meal+' .counter').html(''); };
                      }, 1000);

                      $('.form-'+meal+' input[type=radio]').removeAttr('checked');
                      // Refresh the jQuery UI buttonset.
                  }
                  else if(d.status == 0){
                      $('.glypph_'+d.meal).removeClass('glyphicon-play');
                      $('.glypph_'+d.meal).addClass('glyphicon-remove red');
                      swal({   title: "Error",   text: d.msg,   type: "error", html : true});
                  }
                  else if(d.status == 2){

                      swal({
                          title: d.msg,
                          text: "You budget limit is crossed!",
                          type: "warning",
                          showCancelButton: true,
                          confirmButtonColor: "#DD6B55",
                          confirmButtonText: "Continue ordering",
                          cancelButtonText: "Cancel order",
                          closeOnConfirm: true,
                          closeOnCancel: true
                        },
                        function(isConfirm){
                          if (isConfirm) {
                             addMeal(meal, data);
                          } else {
                              swal("Cancelled", "Your order has canceled!", "error");
                          }
                        });
                  }
                  else if(d.status == 3){

                      swal({
                          title: "Warning!",
                          text: "We are experiencing a high volume of orders please expect delays. Proceed?",
                          type: "warning",
                          showCancelButton: true,
                          confirmButtonColor: "#DD6B55",
                          confirmButtonText: "Continue ordering",
                          cancelButtonText: "Cancel order",
                          closeOnConfirm: true,
                          closeOnCancel: true
                        },
                        function(isConfirm){
                          if (isConfirm) {
                             addMeal(meal, data);
                          } else {
                              swal("Cancelled", "Your order has canceled!", "error");
                          }
                        });
                  }
              });

              request.fail(function( jqXHR, textStatus ) {
                  swal({   title: "Error",   text: 'Request failed!',   type: "error", html : true});
              });

         });

         $(".call_waiter_open").click(function(){
              $('.menu_sub').removeClass('display');
              $('.pay_bill_wrapper').removeClass('display');
              $('.call_waiter_wrapper').toggleClass('hidden');

              var data = $('.pay_form').serialize();
              var request = $.ajax({
                  url: "<?php echo site_url('customer/view_waiter_call'); ?>",
                  method: "POST",
                  data: data,
                  dataType: "html"
              });

              request.done(function( order ) {
                  var d = jQuery.parseJSON(order);
                  $(".waiter_msg").val("");

                  $(".timer").text(d.time);
              });


              $('.add_budget_wrapper').removeClass('display');
         });

          $('#add_budget').click(function(){
              $('.menu_sub').removeClass('display');
              $('.pay_bill_wrapper').removeClass('display');
              $('.call_waiter_wrapper').addClass('hidden');

              $('.add_budget_wrapper').toggleClass('display');

          });

          $(document).on('click', '.receipt_send', function(e){
              e.preventDefault();
              $('.receipt_email_section').toggleClass('hidden');
          });

         $(document).on("click", ".send_email", function(){
              var data = $('.pay_form').serializeArray();

              var tip     = $(".tip").val();
              var payment = $("input[name=payop]:checked").val();
              var email   = $("#receipt_email").val();

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
                  if(d.status == 1)
                      swal({   title: "Success",   text: d.msg,   type: "success"});
                  else
                      swal({   title: "Error",   text: d.msg,   type: "error"});
              });
         });

         $("#view_bill").click(function(){
              $('.pay_bill_wrapper').toggleClass('display');
         });

         $(document).on('click', '.save_address', function(e){
             e.preventDefault();
             var name    = $('#address_name').val();
             var address = $('#address_details').val();

             if(name == ''){
                 swal({   title: "Error",   text: 'Please enter your name',   type: "error", html : false});
                 return false;
             }
             if(address == ''){
                 swal({   title: "Error",   text: 'Please enter your address',   type: "error", html : false});
                 return false;
             }

             var form    = $('.pay_form').serializeArray();
             form.push({name: 'name', value: name});
             form.push({name: 'address', value: address});
             var request = $.ajax({
                  url: "<?php echo site_url('customer/address_save'); ?>",
                  method: "POST",
                  data: form
              });
              request.done(function( order ) {
                  if(order.status == 1){
                     $(".paynow").click();
                  }
                  else{
                      location.reload();
                  }
              });

              request.fail(function( jqXHR, textStatus ) {
                  alert( "Request failed: " + textStatus );
              });
         });

         $(".delivery_option").click(function(){
             $('.pay_bill_wrapper').html('<div class="row"><div class="col-sm-12"><div class="form-group"><br/><input class="form-control" type="text" name="address_name" id="address_name" placeholder="Contact name" /><br/><textarea class="form-control" id="address_details" placeholder="Address"></textarea> </div><div class="clearfix"></div><button class="save_address btn btn-success btn-sm">Add address</button><div class="clearfix"></div></div><div class="clearfix"></div></div>');
         });

         $(".paynow").click(function(){
              <?php if($take_away == 0): ?>
                  $('.pay_bill_wrapper').addClass('display');
              <?php endif; ?>
              $('.menu_sub').removeClass('display');
              $('.add_budget_wrapper').removeClass('dislay');
              $('.pay_bill_wrapper').html('<span class="load">Loading..</span>');
              //$('.pay_bill_wrapper').toggleClass('display');
              $('.call_waiter_wrapper').addClass('hidden');

              var data = $('.pay_form').serialize();
              var request = $.ajax({
                  url: "<?php echo site_url('customer/pay_bill'); ?>",
                  method: "POST",
                  data: data,
                  dataType: "html"
              });

              request.done(function( order ) {
                  var d = jQuery.parseJSON(order);
                  var master  = d.master;
                  var data    = d.data;
                  var delivery_charge = parseFloat(d.delivery_charge);
                  var total   = 0;
                  var payed_by= 0;

                  if (typeof d.payed_by != "undefined") {
                      d.payed_by = 1;
                  }

                  var content = '<br/>'+d.authorize+'<br/>';
                      content += '<table class="table"><tr><td><strong>Product</strong></td><td><strong>Qty</strong></td><td><strong>Price</strong></td></tr>';

                  var request_time = '';
                  $( data ).each(function( index, element ) {
                      content += '<tr><td>'+element.name+'</td><td>'+element.qty+'</td><td><?php echo CURRENCY_CODE; ?> '+element.price+'</td></tr>';
                      total   += parseFloat(element.price);

                      payed_by = element.payed_by;
                      request_time = (element.billrequest_time == '0000-00-00 00:00:00') ? '' : element.billrequest_time;
                  });

                  var timing = '';
                  timing     = start(request_time);
                  timing     = typeof timing == 'undefined' ? '' : timing;
                  var class_str  = timing == '' ? 'request_bill_btn' : 'request_done_btn';

                  content += '<tr><td><strong>TOTAL</strong></td><td>&nbsp;</td><td><strong><?php echo CURRENCY_CODE; ?> '+total.toFixed(2)+'</strong><input type="hidden" id="pr" value="'+total.toFixed(2)+'" /></td></tr>';
                  content += '<tr><td><strong>Delivery Charge</strong></td><td>&nbsp;</td><td><strong><?php echo CURRENCY_CODE; ?> '+delivery_charge.toFixed(2)+'</strong><input type="hidden" id="dl" value="'+delivery_charge.toFixed(2)+'" /></td></tr>';

                  if(total != 0){
                      content += '<tr><td>Tip</td><td>&nbsp;</td><td><input type="hidden" class="form-control tip" name="tip" style="width:60px;" value="'+(d.tip == '0.00' ? '' : d.tip)+'"/><input type="text" class="form-control tip_perc" name="tip_perc" style="width:60px;" value="'+(d.tip == '0.00' ? '' : d.tip)+'"/><span id="percentage"></span></td></tr>';
                      content += '<tr><td>New Total</td><td>&nbsp;</td><td><span id="new_total"><?php echo CURRENCY_CODE; ?>'+(parseFloat(total)+parseFloat(d.tip)+parseFloat(delivery_charge)).toFixed(2)+'</span></td></tr>';
                  }
                  content += '</table>';

                  if (master == 1 || payed_by == 0){
                      content += '<strong>Pay Using</strong><br/>';

                      content += '<table class="table">';

                      <?php if($this->config->item('payment_mode') == 1 || $this->config->item('payment_mode') == 3): ?>
                          content += '<tr><td><div class="radio"><label><input type="radio" name="payop" value="1" checked>Cash</label></div></td></tr>' ;
                      <?php endif; ?>

                      <?php if($this->config->item('payment_mode') == 2 || $this->config->item('payment_mode') == 3): ?>
                          content += '<tr><td><div class="radio"><label><input type="radio" name="payop" value="2">Card</label></div></td></tr>' ;
                      <?php endif; ?>

                      content += '</table>';

                      content += '<button class="btn btn-success '+class_str+'">Request Bill '+timing+'</button><br/><br/>';
                      content += '<br/><br/><br/>';
                      content += '';
                  }

                  else{
                      content += '<span><strong>'+d.customer_authorized+' has added you to their bill.</strong></span><br/><br/><a href="<?php echo site_url('customer/close'); ?>" class="btn btn-danger">Close</a><br/><br/>';
                  }
                  $('.pay_bill_wrapper').html(content);
              });

              request.fail(function( jqXHR, textStatus ) {
                  alert( "Request failed: " + textStatus );
              });

         });
          $(document).on('keyup', '.tip_perc', function(){

              var tip   = ($(this).val());
              var price = $("#pr").val();
              var del   = $('#dl').val();


              if(isNaN(tip) || isNaN(del) || isNaN(price)){
                  $("#percentage").text("");
                  $("#new_total").text('');
              }
              else{
                  //var perc  = (parseFloat(tip)*parseFloat(price)/100);
                  //perc      = perc.toFixed(2);
                  $(".tip").val(parseFloat(tip).toFixed(2));
                  var final = (parseFloat(del) + parseFloat(price)+parseFloat((tip))).toFixed(2);
                  $("#new_total").text(isNaN(final) ? (parseFloat(price)+0).toFixed(2) : final);
              }


          });

          $(document).on("blur", "#budget", function(){

              var budget = ($(this).val());

                 budget     = (parseInt(budget)).toFixed(2) ;
                 if(!isNaN(budget))
                  $(this).val(budget);

          });
          $(document).on('submit','.search-form',function(e){

              e.preventDefault();
              if($('input[name=q]').val() != '' ){
                  $('.loader').show();
                  var data = $(".search-form").serialize();
                   var request = $.ajax({
                       url: "<?php echo site_url('customer/search_meal'); ?>",
                       method: "POST",
                       data: data,
                       dataType: "html"
                   });
                   request.done(function( msg ) {
                       $('.search-results').html(msg);
                   });
                   request.always(function(){
                       $('.loader').hide();
                   });
              }else{
                  $('.search-results').html('');
              }
          });

      });

      function decimalPlaces(num) {
          var match = (''+num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
          if (!match) { return 0; }
          return Math.max(
               0,
               // Number of digits right of decimal point.
               (match[1] ? match[1].length : 0)
               // Adjust for scientific notation.
               - (match[2] ? +match[2] : 0));
        }
      function addMeal(meal, data)
      {
          var request = $.ajax({
                  url: "<?php echo site_url('customer/order_meal/1'); ?>",
                  method: "POST",
                  data: data,
                  dataType: "html"
          });

          request.done(function( msg ) {

              var d = jQuery.parseJSON(msg);
              if (d.status == 1){
                  $('.glypph_'+d.meal).removeClass('glyphicon-play');
                  $('.glypph_'+d.meal).addClass('glyphicon-ok green');
                  $('.form-'+meal+' #details_id').val(d.oid);
                  $('.form-'+meal+' .addit').attr('data-exist', 'yes');
                  $('.form-'+meal+' .addit').html('<span class="glyphicon glyphicon-plus"></span>Processing..').prop('disabled', true);
                  setTimeout(function(){
                      $('.form-'+meal+' .addit').html('<span class="glyphicon glyphicon-plus"></span>Order Another').prop('disabled', false);
                  }, 15*1000);

                  var count = 10, timer = setInterval(function() {
                      $('.form-'+meal+' .counter').html(count--);
                      if(count == 1) { clearInterval(timer); $('.form-'+meal+' .counter').html(''); };
                  }, 1000);

                  $('.form-'+meal+' input[type=radio]').removeAttr('checked');
                  // Refresh the jQuery UI buttonset.
              }
              else if(d.status == 0){
                  $('.glypph_'+d.meal).removeClass('glyphicon-play');
                  $('.glypph_'+d.meal).addClass('glyphicon-remove red');
                  swal({   title: "Error",   text: d.msg,   type: "error", html : true});
              }
          });

          request.fail(function( jqXHR, textStatus ) {
              swal({   title: "Error",   text: 'Request failed!',   type: "error", html : true});
          });
      }


  </script>
