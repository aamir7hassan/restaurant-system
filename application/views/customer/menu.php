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
			$data[$meal->cid]['meal'][$meal->mid]['show_available']     = isset($meal->show_available)     ? $meal->show_available : "";
            
            $atributes[$meal->cid]['meal']['attr'][$meal->aid][$meal->aname]  = isset($meal->value) ? $meal->value : "";
            if(!empty($meal->aname)){
                
                $values = json_decode($meal->values);
                if(isset($values) && is_array($values)){    
                    foreach ($values as $val){
                        $attribute[$meal->mid][$meal->aname][]       	= $val;
                        $attribute[$meal->mid][$meal->aname]['id']   	= $meal->aid;
                        $attribute[$meal->mid][$meal->aname]['type'] 	= $meal->type;
                        $attribute[$meal->mid][$meal->aname]['required']= $meal->required;
                    }    
                }
            }
        }    
    }


?>
<style>
	div.header_section, .button {
		background: #008000;
	}
</style>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?libraries=geometry&key=<?=$this->config->item('google_key')?>&sensor=false&v=3"></script>
<div class="container text-center col-lg-12">
    <script>
        history.pushState(null, null, document.URL);
        window.addEventListener('popstate', function () {
            history.pushState(null, null, document.URL);
        });
    </script>
    <span class="paynow"></span>
    <div class="row">
        <div class="col-xs-3 grey">
            <?php if($this->config->item("store_logo")): ?>
                <img src="<?php echo base_url( 'assets/images/'.$this->config->item("store_logo") ); ?>" class="img-responsive" style="max-height: 98px"/>
            <?php else: ?>
                <img src="<?php echo base_url('assets/images/ResturantLogo.jpg'); ?>" style="width: 100%;">
            <?php endif; ?>
             
        </div> 
		<?php  
			//var_Dump((float)$order_budget->budget-$prices);die; 
			if($prices < (float)$order_budget->budget) {
				$remianingB = number_format(abs((float)$order_budget->budget-$prices),2);
			} else {
				$remianingB=0;
			}
		?>
		 <?php $take_away = $this->session->userdata('take_away');?>
        <div class="col-xs-9 grey">
		<?php $c = "left:0px;right:10% !important;text-align:right;";
		if($orderinfo['customer_name'] !== 'Take Away' && $orderinfo['customer_name'] !== 'delivery' && $orderinfo['customer_name'] !== 'collection') {
			
		$c = ""; } ?>
            <span class="table_name" style="<?=$c?>"> 
				<?php 
					
					//if($orderinfo!=null && $orderinfo['type']=="delivery" && $orderinfo['status']=='paybill') {
						if($orderinfo!=null && $orderinfo['type']=="delivery") {
						$cords = $orderinfo['coords'];
						$exp = explode(',',$cords);
						$cords = trim($exp[0]).','.trim($exp[1]);
						$url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=32.582968,74.064206&destinations=".$cords."&mode=driving&sensor=false&key=".$this->config->item('google_key');

						$res = json_decode(file_get_contents($url),true);
						if($res['status']=="OK") {
							$dist = $res['rows'][0]['elements'][0]['distance']['text'];
							$time = $res['rows'][0]['elements'][0]['duration']['text'];
						}
						$allowedDistance = $this->config->item('delivery_distance');
						$distanceS = "0";
						if($dist > $allowedDistance) {
							$distanceS = "1";
						}
					
				?>
					 Distance <?php echo $dist; ?> | <span class="txt">Time: <?php echo $time; ?> </span>
				<?php	} else {
				?>
                Location <?php echo $this->session->userdata('table_no'); ?>  
                <!---<span>Budget: <?php echo CURRENCY_CODE ?>. <?php echo $order_budget->budget; ?></span>-->
				<?php 
					if($orderinfo['customer_name'] !== 'Take Away' && $orderinfo['customer_name'] !== 'delivery' && $orderinfo['customer_name'] !== 'collection') {
				?>
				| <span class="txt">Remaining: <?php echo CURRENCY_CODE ?> <span id="remaining"><?=$remianingB?></span></span>
				<?php } } ?>
					
                <br/><br/>
				
                <a href="<?php echo site_url('customer/close') ?>" class="btn btn-danger btn-sm pull-right">Logout</a>
                <br/>
            </span>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <span class="loader">Loading...</span>
        <?php echo form_open('', array('class' => 'search-form')); ?>
        <div class="">
            <div id="imaginary_container"> 
                <div class="input-group stylish-input-group">
                    <input type="text" class="form-control"  placeholder="Search" name="q">
                    <span class="input-group-addon">
                        <button type="submit">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>  
                    </span>
                </div>
            </div>
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
            <input type="hidden" name="table_id" value="<?php echo $table_id; ?>">
        </div>
        <?php echo form_close(); ?>
    </div>
    
    <div class="row search-results">
        
    </div>
   
    <div class="row">
        <ul class="menu_list">
            <li>
                <a href="javascript:void(0);" class="show_submenu">View Menu</a>
            </li>
            <ul class="menu_sub menu_sub_1">
                <?php foreach ($data as $key => $det): ?>
                <?php if( count($det['meal']) > 0 ):
				?>
                <li><a href="javascript:void(0);"><?php echo $det['cname']; ?></a></li>
                    <div class="details_container">
                        <?php 
							$k = 0; 
							foreach ($det['meal'] as $k => $ml): ?>
                            <?php 
								if($ml['special'] != 1): ?>
                                <?php $ml['stock'] = $display ? $ml['stock'] : 1; 
                                if( $ml['quantity'] == 0 &&  $hide_empty_stock):   ?>
                                        <div class="details text-left">
                                        </div>
                                <?php else :?>
                            <?php endif; ?> 
                                <div class="details text-left">
                                    <div class="row"> 
                                        <div class="margins margin-<?php echo $k; ?>" style="display:none;">
                                            <div class="header_section">
												<div class="col-xs-4">
                                                    <div class="price"><?php echo CURRENCY_CODE.' <span class="prc-'.$k.'" data-orig="'.price_calc($ml['price']).'">'.price_calc($ml['price']).'</span>'; ?></div>
                                                </div>
                                                <div class="col-xs-4">
                                                    <?php if($ml['show_available']=="1") {
														$cnt = (int)$ml['quantity'];
														if($this->config->item('hide_empty_stock') == '1' && $cnt > 0) {
															echo '<div class="quantity">'.$ml["quantity"].' Available</div>';
														}
													}
														?>
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
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <p><strong><?php echo $ml['name']; ?></strong></p>
                                                        <?php echo $ml['description']; ?>
                                                    </div>
                                                    
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <?php if( isset( $attribute[$k] )) : $i = 1;  
															$radArr=[];
															$radText=[];
															$typesText=[];
														?>
                                                            <?php foreach ( $attribute[$k] as $attr_text => $value ):
																$req = "";
																if($value['required'] == "1") {
																	$radArr[] = $value['id'];
																	$radText[] = $attr_text;
																	$req = " <small style='color:red;font-size:78%'>(Required)</small>";
																	$typesText[] = $value['type'];
																}
																 
															?>  
                                                                <div class="attr_area">
                                                                    <h1><?php echo $attr_text.$req; ?></h1>                                 
																	<input type="hidden" name="main_cat[<?php echo $value['id'] ?>]" value="<?php echo $attr_text; ?>" />
                                                                    <?php foreach ($value as $v): ?>
                                                                    <?php if(empty($v->name)) continue; ?>
                                                                    <?php $price_details = (!empty($v->price) && $v->price > 0) ? " ( +".CURRENCY_CODE." ".price_calc($v->price)." ) " : "" ?>
                                                                
                                                                    <p>
                                                                        <?php if( $value['type'] == 'multi'): ?>
																			
                                                                            <input class="radio_class" data-prc="<?=$k?>" data-radio="<?=$v->price?>" type="checkbox" name="attrs[<?php echo $value['id'] ?>][]" value="<?php echo $v->name ?>"><?php echo $v ->name.$price_details ; ?>
                                                                        <?php else: ?>
																			
                                                                            <input class="radio_class" data-prc="<?=$k?>" data-radio="<?=$v->price?>" <?php echo $value['required'] == 1 ? 'required' : '' ?> type="radio" name="attr[<?php echo $value['id'] ?>]" value="<?php echo $v->name ?>"><?php echo $v->name.$price_details ; ?>
                                                                        <?php endif; ?>
                                                                    </p>
                                                                    <?php endforeach; ?>
                                                                </div>    
                                                            <?php ++$i; endforeach; ?>
                                                        <?php endif; 
															
															$radId  = implode('_', $radArr);
															$radVal = implode('_',$radText);
															$types = implode('_',$typesText);
															
														?>
                                                    </div>
													
                                                    <div class="clearfix"></div>
                                                </div>
                                                
                                                <?php if ($package->packages != 'Option 1'): ?>
												<?php if($status==true) { ?>
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
														<button data-category="<?php echo $det['cname']; ?>" data-warning="<?php echo $distanceS;?>" class="addit button" data-radid="<?=$radId?>" data-radval="<?=$radVal?>" data-id="<?php echo $k; ?>" data-types="<?=$types;?>" data-exist="<?php echo in_array($k, $order) ? 'yes' : 'no'; ?>"><span class="glyphicon glyphicon-plus"></span>Order Now</button>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
												<?php } ?>
                                                <?php endif; ?>
                                                
                                            <?php echo form_close(); ?>    
                                        </div>  
                                        <div class="initial initial-<?php echo $k; ?>">
                                            <?php echo form_open('', array('class' => 'form-'.$k)); ?>
                                                <div class="row <?php echo $ml['stock'] == 1 ? "grey" : ""; ?>">
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
            <?php if($status==true) { ?>
            <li>
                <a href="javascript:void(0);" class="specials">Specials</a>
            </li>
            <ul class="menu_sub_2 menu_sub">
                <?php foreach ($data as $key => $det): ?>
                <?php if( count($det['meal']) > 0): ?>
                    <li><a href="javascript:void(0);"><?php echo $det['cname']; ?></a></li>
                    <div class="details_container"> 
                        <?php foreach ($det['meal'] as $k => $ml): ?>  
                            <?php if($ml['special'] == "1"): 
                                if( $ml['quantity'] == 0 &&  $hide_empty_stock):   ?>
                                    <div class="details text-left">
                                    </div>
                                <?php else :?> 
								<?php endif; ?> 
                            <div class="details text-left">
                                <div class="row">
                                    <div class="margins margin-<?php echo $k; ?>" style="display:none;">
                                        
                                        <div class="header_section">
                                            <div class="col-xs-4">
                                               <div class="price"><?php echo CURRENCY_CODE.' <span class="prc-'.$k.'" data-orig="'.price_calc($ml['price']).'">'.price_calc($ml['price']).'</span>'; ?></div>
                                            </div>
                                            <div class="col-xs-4">
                                               <?php if($ml['show_available']=="1") {
														$cnt = (int)$ml['quantity'];
														if($this->config->item('hide_empty_stock') == '1' && $cnt > 0) {
															echo '<div class="quantity">'.$ml["quantity"].' Available</div>';
														}
													}
														?>
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
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <p><strong><?php echo $ml['name']; ?></strong></p>
                                                    <?php echo $ml['description']; ?>
                                                </div>
                                                
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <?php 
													
													
													if( isset( $attribute[$k] )) : $i = 1; 
														$radArr1 = [];
														$radText1 = [];
														$typesText1 = [];
													?>
                                                        <?php foreach ( $attribute[$k] as $attr_text => $value):
																$req="";
																
																if($value['required'] == "1") {
																	$radArr1[] = $value['id'];
																	$radText1[] = $attr_text;
																	$req = " ( Required )";
																	$typesText1 = $value['type'];
																}
																
															?> 
                                                            <div class="attr_area">
																<input type="hidden" name="main_cat1[<?php echo $value['id'] ?>]" value="<?php echo $attr_text; ?>" />
                                                                <h1><?php echo $attr_text . $req; ?></h1>
                                                                <?php foreach ($value as $v): ?>
                                                                <?php if(empty($v->name)) continue; ?>
                                                                <?php $price_details = (!empty($v->price) && $v->price > 0) ? " ( +".CURRENCY_CODE." ".price_calc($v->price)." ) " : "" ?>
                                                               
                                                                <p>
                                                                    <?php if( $value['type'] == 'multi'): ?>
                                                                        <input class="radio_class" data-prc="<?=$k?>" data-radio="<?=$v->price?>" type="checkbox" name="attrs[<?php echo $value['id'] ?>][]" value="<?php echo $v->name ?>"><?php echo $v->name.$price_details ; ?>
                                                                    <?php else: ?>
                                                                        <input class="radio_class" data-prc="<?=$k?>" data-radio="<?=$v->price?>"  <?php echo $value['required'] == 1 ? 'required' : '' ?> type="radio" name="attr[<?php echo $value['id'] ?>]" value="<?php echo $v->name ?>"><?php echo $v->name.$price_details ; ?>
                                                                    <?php endif; ?> 
                                                                </p>
                                                                
                                                                <?php endforeach; ?>
                                                            </div>    
                                                        <?php ++$i; endforeach; ?>
                                                    <?php endif; 
															$radId1 = implode('_', $radArr1);
															$radVal1 = implode('_',$radText1);
															$types1 = implode('_',$typesText1);
													?>
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
                                                        
                                                        <button data-category="<?php echo $det['cname']; ?>" class="addit button" data-warning="<?php echo $distanceS;?>" data-radid="<?=$radId1?>" data-radval="<?=$radVal1?>" data-id="<?php echo $k; ?>" data-types="<?=$types1?>" data-exist="<?php echo in_array($k, $order) ? 'yes' : 'no'; ?>"><span class="glyphicon glyphicon-plus"></span>Order Now</button>
                                                         
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                            
                                        <?php echo form_close(); ?>    
                                    </div>  
                                    <div class="initial initial-<?php echo $k; ?>">
                                        <?php echo form_open('', array('class' => 'form-'.$k)); ?>
                                            <div class="row">
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
                            
                        <?php endforeach; ?>
                    </div>  
                
                <?php endif; endforeach; ?>  
            </ul>
            <?php if($take_away!=1) { ?>
            <li>
                <a href="javascript:void(0);" id="add_budget">Add Budget</a>
            </li>
			<?php } ?>
            <div class="add_budget_wrapper"> 
                <br/>
                <p>Enter your budget!</p>
                <?php echo form_open('', array('class' => 'budget')); ?>
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <input type="hidden" name="table_id" value="<?php echo $table_id; ?>">
                    <div class="row">
                        <div class="col-xs-4">Budget</div>
                        <div class="col-xs-8">
                            <input type="text" name="budget" value="<?=(int)$order_budget->budget?>" class="form-control" id="budget">
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <br/>
                    <button class="btn btn-success addBud">Add Budget</button>
                    <div class="clearfix"></div>
                    <br/>
                <?php echo form_close(); ?>
            </div>
            <?php 
				if($take_away==0) {
			?>
            <li>
                <a href="javascript:void(0);" id="<?php echo $take_away == 0 ? '' : 'view_bill'?>" class="<?php echo $take_away == 0 ? 'paynow' : ''?>">View Bill</a>
                <?php echo form_open('', array('class' => 'pay_form', 'style' => 'display:none')); ?>
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <input type="hidden" name="table_id" value="<?php echo $table_id; ?>">
                    <input type="hidden" name="update_order" class="update_order" value="">
                <?php echo form_close(); ?>
            </li>   
			<?php } else if($take_away==1) { ?>
			<li>
					 <?php echo form_open('', array('class' => 'pay_form', 'style' => 'display:none')); ?>
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <input type="hidden" name="table_id" value="<?php echo $table_id; ?>">
                    <input type="hidden" name="update_order" class="update_order" value="">
					<?php echo form_close(); ?>
					<a href="#" class="paynow" id="view_bill">View Bill</a>
                
            </li>  
			<?php } ?>
            <div class="pay_bill_wrapper">
				<!-- BILL WILL APPEND TO THIS DIV < DON'T DELETE IT -->
			</div>
            <?php if($take_away !=1) { ?>
            <li class="call_waiter_open"><a href="javascript:void(0);">Call Clerk</a></li>
			<?php } else { ?> 
			<!--<li class="call_waiter_open"><a href="javascript:void(0);">Delivery</a></li>-->
			<?php } ?>
            <div class="call_waiter_wrapper hidden">
                
                <?php echo form_open('', array('class' => 'call_waiter_form')); ?>
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <input type="hidden" name="table_id" value="<?php echo $table_id; ?>">
                    <input type="hidden" name="update_order" class="update_order" value="">
                    <span class="timer"></span>
                    <textarea name="waiter_message" class="form-control waiter_msg"></textarea><br/>
                    <div class="call_waiter btn btn-success">Call waiter</div>
                <?php echo form_close(); ?>
            </div>
            <?php } // end open/close ?>
            
        </ul>
        <div class="clearfix"></div>
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
			
			var estTime = new Date();
			var mydate = new Date(estTime.toLocaleString("en-US", {timeZone: "<?php echo $this->config->item('timezone')?>"}));
			
			// var h1 = mydate.getHours();
			// var m1 = mydate.getMinutes();
			// var s1 = mydate.getSeconds();
			
            //var start  = ((h1*60*60+m1*60+s1)*1000);
			var start = Date.parse(mydate);
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
                timer =  minutes + ":" + seconds; 

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
        
        function request_bill() {
            var data 	= $('.pay_form').serializeArray(); 
            //var tip     = $(".tip").val();//////
			var tip     = $(".tip_perc").val();
			console.log(tip);
            var payment = $("input[name=payop]:checked").val();
			var changefor = $('#changeB').val();

            if(typeof payment === typeof undefined && payment > 3){
                swal({   title: "Error",   text: 'Please select a payment option!',   type: "error", html : true});
            }
			var option = $("input[name=opt]:checked").val();
			var email = $('#bemail').val();
            data.push({ name: "tip", value: tip });
            data.push({ name: "payment", value: payment });
			data.push({name:'options', value: option});
			data.push({name: 'email', value: email});
			data.push({name: 'changefor', value: changefor});
			console.log(tip);
			if(payment== 1) {
				if(tip=="" || tip < 0) {
					swal({   title: "Error",   text: 'Please ad tip even 0',   type: "error", html : true});
					return false;
				}
				if(changefor=="" || changefor < 0) {
					swal({   title: "Error",   text: 'Please add change for even 0',   type: "error", html : true});
					return false;
				}
			}
			// return false;
            var request = $.ajax({
                url: "<?php echo site_url('customer/request_bill/'); ?>/",
                method: "POST",
                data: data,
                dataType: "html"
            });
            request.done(function( msg ) {
                var d = jQuery.parseJSON(msg);
                if (d.status == 1) {
                    $("#paynow").click();
                    if(payment == 3) {
                        location.href = 'http://zapper.com/';
                    } else {
                        swal({   title: "Success",   text: d.msg,   type: "success"});
                        $(".request_bill_btn").addClass('request_done_btn').addClass('disabled').html('Bill requested');
                        $('.request_done_btn').removeClass('request_bill_btn');
						$('.paynow').trigger('click');
                    }    
                } else if(d.status == 2) {
                    swal({   title: "Error",   text: d.msg, type : 'error',  html: true })
                } else {
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
			//var tip = $('.tip_perc').val();
			console.log(tip);
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
        var backgroundInterval = setInterval(check_session,10000);
        
        $(document).ready(function(){
           
            $(document).on('click', '.request_done_btn', function(){
                swal({   title: "Please wait",   text: 'Bill already requested.',   type: "error"});
            });
           
            $(document).on('click', '.authorize', function(e){
				e.preventDefault();
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

                        window.location.reload(true);
                    }
                    else{
                        swal({   title: "Error",   text: d.msg,   type: "error"});

                        window.location.reload(true);
                    }    
                });

                request.fail(function( jqXHR, textStatus ) {
                    alert( "Request failed: " + textStatus );
                });
            });
            $(document).on('click', '.split', function() {
                var order_id = $(this).attr('data-id');
                $('.update_order').val(order_id);

                var data = $('.pay_form').serialize();
                var request = $.ajax({
                    url: "<?php echo site_url('customer/splits/'); ?>/",
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
            
            
            $(document).on('click', '.addBud', function(e) {                  
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
                
                setTimeout(function() {
                    window.location.reload();
                },2000);
            });
            
			<!------------------------!>
            $(document).on('click', '.request_bill_btn', function(){
				
					var optt = $("input[name=opt]:checked").val();
					if(optt=="1" || optt=="3") {
						var emal = $('#bemail').val();
						if(typeof emal==typeof undefined || emal=="") {
							//swal({   title: "Error",   text: 'Please Enter email!',   type: "error", html : true});
							toastr.error('Please enter email');
							return false;
						}
					}
			    
				request_bill();
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
                    clearInterval(timer); $('.form-'+meal+' .counter').html('');     
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
           
           
            $(document).on('click', '.addit', function(e) {
               e.preventDefault();
               var meal  = $(this).attr('data-id');
               var exist = $(this).attr('data-exist');
               var cat   = $(this).attr('data-category');
               var warning = $(this).attr('data-warning');
			   var types = $(this).attr('data-types');
               var cont  = true;
               
               //if (exist == 'yes'){
                 //  cont = confirm('Are you sure you want to order another?');                    
               //}    
				if(warning=="1") {
				   swal({   title: "Warning",   text: "Distance is too far for order delivery",   type: "error"});
                   //return false;
				}
               
               if(!cont)
                   return false;
               
               $('.glypph_'+meal).addClass('glyphicon-play');
               
               var data = $('.form-'+meal).serializeArray();
              
               data.push({ name: "category", value: cat });
			   data.push({name: 'temp_user', value: '0'});
               
               //var isValid = $(e.target).parents('form').isValid();
               //alert(isValid);
               //return;
			   
				var chkAr = false;
				var radId = $(this).data('radid');
				
				if(radId!="") {
					var radVal  = $(this).data('radval');
					var chkId   = radId.toString().split('_');
					var chkVal  = radVal.toString().split('_');
					var chkType = types.toString().split('_');
					$.each(chkId, function (i, val) {
						var type = chkType[i];
						if(type=="multi") {
							if ($('input[name="attrs[' + val + '][]"]:checked').length == 0) {
								swal({   title: "Error",   text: "Please select an attribute from "+chkVal[i],   type: "error"});
								chkAr = true;
								return false;
							}
						} else {
							if ($('input[name="attr[' + val + ']"]:checked').length == 0) {
								swal({   title: "Error",   text: "Please select an attribute from "+chkVal[i],   type: "error"});
								chkAr = true;
								return false;
							}
						}
						
					});
			    }
			   
			   
               // if(!isOneChecked('.form-'+meal))
               // {
                   // swal({   title: "Error",   text: "Please select an attribute",   type: "error"});
                   // return false;
               // }
				if(chkAr==false) {
				   // if mandatory buttons are selected
				   var request = $.ajax({
						url: "<?php echo site_url('customer/order_meal/0'); ?>",
						method: "POST",
						data: data,
						dataType: "html"
					});
				}

                request.done(function( msg ) {

                    var d = jQuery.parseJSON(msg);
                    if (d.status == 1){
						if(d.prices) {
							$('#remaining').html(parseFloat(d.prices).toFixed(2));
						}
                        $('.glypph_'+d.meal).removeClass('glyphicon-play');
                        $('.glypph_'+d.meal).addClass('glyphicon-ok green');
                        
                        $('.form-'+meal+' #details_id').val(d.oid);
                        $('.form-'+meal+' .addit').attr('data-exist', 'yes');
                        $('.form-'+meal+' .addit').html('<span class="glyphicon glyphicon-plus"></span>Processing..').prop('disabled', true);
                        $('.margin-'+d.meal).find( ".quantity" ).text(d.quantity+' Available');

                        setTimeout(function(){
                            $('.form-'+meal+' .addit').html('<span class="glyphicon glyphicon-plus"></span>Order Another').prop('disabled', false);
                        }, 10*1000);

                        var count = 10, timer = setInterval(function() {
                            $('.form-'+meal+' .counter').html(count--);
                            if(count == 0) { clearInterval(timer); $('.form-'+meal+' .counter').html(''); };
                        }, 1000);

                        $('.form-'+meal+' input[type=radio]').removeAttr('checked');
                        // Refresh the jQuery UI buttonset.                  
                    } else if(d.status == 0){
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
                          function(isConfirm) {
                            if (isConfirm) {
                               addMeal_continue(meal, data);
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
			function CapitlizeString(word) 
			{
				if(word) {
					return word.charAt(0).toUpperCase() + word.slice(1);
				}
			}
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
					var name 	= d.name;
                    var delivery_charge = parseFloat(d.delivery_charge);
                    var total   = 0;
                    var payed_by= 0;
					var total_taxes=0;
                    //var total_tax = 0;
					var total_tax = "<?=$this->config->item('vat')?>";
                    var tt = parseFloat(Number(1)+parseFloat(parseFloat(total_tax)/100));
                    if (typeof d.payed_by != "undefined") {
                        d.payed_by = 1;
                    }   
                    
                    var content = '<br/>'+d.authorize+'<br/>';  
                        content += '<table class="table"><tr><td><strong>Product</strong></td><td><strong>Qty</strong></td><td><strong>Price</strong></td><td><strong>Status</strong></td></tr>';
                    
                    var request_time = '';
					var attrt="";var timeP="";
					
                    $( data ).each(function( index, element ) {
						var expP = element.attribute.split('_');
						if(expP.length >0) {
							attrt = expP[0];
							timeP = expP[1];
						}
						if(element.contact_name) {
							var fName = element.contact_name;
						} else {
							var fName = element.customer_name;
						}
                        content += '<tr><td style="max-width:60px">'+element.name+'</br>'+attrt+'<br/>'+CapitlizeString(fName)+'<br>'+timeP+'</td><td>'+element.qty+'</td><td><?php echo CURRENCY_CODE; ?> '+element.price+'</td>';
						var ttr = parseFloat(element.price)/tt;
                        if(element.processed == 0){
                            content += '<td>Ordered</td>'
                        }
                        else if(element.processed == 1){
                            content += '<td>Delivered</td>'
                        }
                        else if(element.processed == 2){
                            content += '<td>Processed</td>'
                        } else if(element.processed == 3) {
							content += '<td>Transit</td>'
						} else if(element.processed==4) {
							content += '<td>Delivery completed</td>'
						}
						
                        content += '</tr>';
                        total_taxes   += parseFloat(element.price) - parseFloat(ttr);
                        total   += parseFloat(element.price);
                       
                        payed_by = element.payed_by;
						if(payed_by=="0" || payed_by== null) {
							request_time = (element.billrequest_time == '0000-00-00 00:00:00') ? '' : element.billrequest_time; 
							console.log(element.billrequest_time);
						}
                    });
                    
                    var timing = '';
                    timing     = start(request_time);
                    timing     = typeof timing == 'undefined' ? '' : timing;
                    var class_str  = timing == '' ? 'request_bill_btn' : 'request_done_btn';

                    content += '<tr><td><strong>TOTAL</strong></td><td>&nbsp;</td><td><strong><?php echo CURRENCY_CODE; ?> '+total.toFixed(2)+'</strong><input type="hidden" id="pr" value="'+total.toFixed(2)+'" /></td></tr>';  
                    content += '<tr><td><strong>VAT</strong></td><td>&nbsp;</td><td><strong><?php echo CURRENCY_CODE; ?> '+total_taxes.toFixed(2)+'</strong></td></tr>'; 
					
					if(d.type!=="") {
						content += '<tr><td><strong>Delivery Charge</strong></td><td>&nbsp;</td><td><strong><?php echo CURRENCY_CODE; ?> '+delivery_charge.toFixed(2)+'</strong><input type="hidden" id="dl" value="'+delivery_charge.toFixed(2)+'" /></td></tr>';  
					} else {
						delivery_charge='0';
					}

                    if(total != 0){
                        content += '<tr><td>Tip</td><td>&nbsp;</td><td><input type="hidden" class="form-control tip" name="tip" style="width:60px;" value="'+(d.tip == '0.00' ? '' : d.tip)+'"/><input type="number" class="form-control tip_perc" name="tip_perc" min="0" style="width:60px;" value="'+(d.tip == '0.00' ? '' : d.tip)+'"/><span id="percentage"></span></td></tr>';
                        content += '<tr><td>New Total</td><td>&nbsp;</td><td><?php echo CURRENCY_CODE; ?> <span id="new_total">'+(parseFloat(total)+parseFloat(d.tip)+parseFloat(delivery_charge)).toFixed(2)+'</span></td></tr>';
                    }
                    content += '</table>';
                  
                    if (master == 1 || payed_by == 0 || payed_by == null) {
                        content += '<strong>Pay Using</strong><br/>';
                        
                        content += '<table class="table"><tr>';
                        
                        <?php if($this->config->item('payment_mode') == 1 || $this->config->item('payment_mode') == 3): ?>
                            content += '<td><div class="radio"><label><input type="radio" data-payop="cash" name="payop" value="1" checked>Cash</label></div></td>' ;
                        <?php endif; ?>
                        
                        <?php if($this->config->item('payment_mode') == 2 || $this->config->item('payment_mode') == 3): ?>
                            content += '<td><div class="radio"><label><input type="radio" name="payop" value="2" data-payop="card">Card</label></div></td>' ;
                        <?php endif; ?> 
                            
                        content += '</tr>';
						<?php if($this->config->item('payment_mode') == 1 || $this->config->item('payment_mode') == 3): ?>
                            content += '<tr class="changeB"><td><label>Change For</label></td><td><input type="number" name="changeB" id="changeB" value="" style="width:60px" class="form-control" ></td></tr>' ;
                        <?php endif; ?>
						content+='</table>';
						content +='<p class="hidden">Remaining Budget: <?=(int)$remianingB?></p><hr><table class="table"><tr><td><input type="radio" class="opt" name="opt" value="1" /> Email</td><td><input type="radio" class="opt" name="opt" checked value="2" /> Print</td></tr><tr><td><input type="radio" class="opt" name="opt" value="3" /> Print & Email</td><td><input type="radio" name="opt" class="opt" value="4" /> None</td></tr><tr id="bhid" class="hidden"><td colspan="3"><input type="text" name="bemail" id="bemail" class="form-control" placeholder="Enter your email" /></td></tr></table>';
                        
                        if(class_str == 'request_done_btn') {
                            content += 'Already requested bill!';
                        } else {
                            content += '<button class="btn btn-success '+class_str+'">Request Bill '+timing+'</button><br/><br/>';
                        }
                        content += '<br/><br/><br/>';
                        content += '';
                    } else if(name) {
						content += '<span><b>'+name+'</b> has added you to their bill.</span><br/><br/><br/>';
                    } else {
						content += '<button class="btn btn-success '+class_str+'">Request Bill '+timing+'</button><br/><br/>';
					}
                    $('.pay_bill_wrapper').html(content);
                });
                
                request.fail(function( jqXHR, textStatus ) {
                    alert( "Request failed: " + textStatus );
                });
                
           });
		   
		   $(document).on('change','.opt',function(e){
				var name = $(this).val();
				if(name=="1" || name=="3") {
					$('#bhid').removeClass('hidden');
				} else {
					$('#bhid').addClass('hidden');
					$('#bemail').val('');
				}
			});
		   
            $(document).on('keyup', '.tip_perc', function() {
                var tip   = ($(this).val()); 
                var price = $("#pr").val();
                var del   = $('#dl').val();
                if(isNaN(tip) || isNaN(del) || isNaN(price)) {
                    $("#percentage").text("");
                    $("#new_total").text('');
                } else {
                    //var perc  = (parseFloat(tip)*parseFloat(price)/100);
                    //perc      = perc.toFixed(2);
                    $(".tip").val(parseFloat(tip).toFixed(2));
                    var finald = (parseFloat(del) + parseFloat(price)+parseFloat((tip))).toFixed(2);
                    $("#new_total").text(isNaN(finald) ? (parseFloat(price)+0).toFixed(2) : finald); 
                }
            });

            $(document).on("blur", "#budget", function(){ 

                var budget = ($(this).val()); 

                   //budget     = (parseInt(budget)).toFixed(2) ;
				   budget     = parseInt(budget);
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
		
		function addMeal_continue(meal, data)
        {
			// param is 3 just for continueing order after budget exceed warning other wise param is 0 or 1 
            var request = $.ajax({
                    url: "<?php echo site_url('customer/order_meal/3'); ?>",
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
		
        $(document).ready(function(){
			showYesPopup();
			showConfirmPopup();
		}); 
		setInterval(showYesPopup,2000);
		setInterval(showConfirmPopup,3000); 
		setInterval(showOrderNotify,4000); 
		function showYesPopup() {
			 var data = $('.pay_form').serialize();
			$.ajax({
				url:'<?=site_url("customer/ajax")?>',
				type:'POST', 
				dataType:'JSON',
				data:{'action':'getYesPopup','data':data},
				success:function(res) {
					if(res!="") { 
						var name = res.name;
						var id = res.id;
						var body = '<p>'+name+' want to add you to its bill ? yes/no</p>';
						body+='<input type="hidden" id="poid" value="'+id+'" />';
						$('#pbody').html(body);
						$('#popup').modal('show');
					}
				}
			});
		}
		
		function showConfirmPopup() {
			 var data = $('.pay_form').serialize();

			$.ajax({
				url:'<?=site_url("customer/ajax")?>',
				type:'POST',
				dataType:'JSON',
				data:{'action':'showConfirmPopup','data':data},
				success:function(res) {
					if(res) {
						var name = res.name;
						var body = '<p>'+name+'</p>';
						$('#cbody').html(body);
						$('#confirmpopup').modal('show');
						
					}
				}
			});
		}
		
		function showOrderNotify() {
			var oid = "<?php echo $order_id;?>";
			$.ajax({
				url:'<?=site_url("customer/ajax")?>',
				type:'POST',
				dataType:'JSON',
				data:{'action':'showOrderNotify','oid':oid},
				success:function(res) {
					if(res) {
						swal({   title: "Success",   text: res,   type: "success"});
						res="";
					}
				}
			});
		}
		
		$(document).on('click','#pyes',function(){
			var id = $('#poid').val();
			if(id=="") {
				toastr.error('something is missing, reload the page');
				return false;
			}
			$.ajax({
				url:'<?=site_url("customer/ajax")?>',
				type:'POST',
				dataType:'JSON',
				data:{'action':'yes','id':id},
				success:function(res) { 
					if(res=="1") {
						$('#pbody').html("");
						$('#popup').modal('hide');
						 swal({   title: "Success",   text: "Thankyou for your confirmation",   type: "success"});
						 $('.paynow').trigger('click');
					} else {
						toastr.error(res);
					}
				}
			});
		});
		
		
		$(document).on('click','#pno',function(){
			var id = $('#poid').val();
			if(id=="") {
				toastr.error('something is missing, reload the page');
				return false;
			}
			$.ajax({
				url:'<?=site_url("customer/ajax")?>',
				type:'POST',
				dataType:'JSON',
				data:{'action':'no','id':id},
				success:function(res) { 
					if(res=="1") {
						$('#pbody').html("");
						$('#popup').modal('hide');
						 swal({   title: "Success",   text: "Thankyou for your confirmation",   type: "success"});
						 $('.paynow').trigger('click');
					} else {
						toastr.error(res);
					}
				}
			});
		});
		
		$(document).on('click','#okk',function(){
			$('.paynow').trigger('click');
		});
		
		$(document).on('click','.radio_class',function(){
			var price= $(this).data('radio');
			var key  = $(this).data('prc');
			var val  = $('.prc-'+key).html();
			var orig = $('.prc-'+key).data('orig');
			if( parseFloat(price) > 0 ) {
				var fin = parseFloat(orig) + parseFloat(price);
			} else {
				var fin = parseFloat(orig);
			}
			var pr = $('.prc-'+key).html(fin.toFixed(2));
		});
		
		$(document).on('change','input[name=payop]',function(e) {
			var opt = $(this).data('payop');
			if(opt=="card") {
				$('.changeB').hide();
				$('#changeB').val('');
			} else if(opt=="cash") {
				$('.changeB').show();
			}
		});
		
		<?php 
		if(($orderinfo['type']=='delivery' || $orderinfo['type']=='collection') && $open=true) {
		?>
			$(document).ready(function() {
				 swal({   title: "Notice",   text: "Order will only be processed, once the \"Request bill\" button is selected",   type: "success"}); 
			});
		<?php } ?>
        
    </script> 
    <div class="clearfix"></div>
    <div class="footer">
        <img src="<?php echo base_url('assets/images/ordering.png') ?>" style="display:block;margin:auto;width:150px"/>
    </div>
</div>
		<div id="popup" class="modal fade" role="dialog">
		  <div class="modal-dialog modal-sm">
			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Alert Bill Addition</h4>
			  </div>
				<div class="modal-body">
					<div id="pbody"></div>
					<center><a href="#" id="pyes" class="btn  btn-success">Yes</a> <a href="#" id="pno" class="btn  btn-primary">No</a></center>
				</div>
			</div> 

		  </div>
		</div>
		<div id="confirmpopup" class="modal fade" role="dialog">
		  <div class="modal-dialog modal-sm">
			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Alert Bill Addition</h4>
			  </div>
				<div class="modal-body">
					<div id="cbody"></div></br>
					<center><a href="#" id="okk" data-dismiss="modal" class="btn btn-success">Okay</a></center>
				</div>
			</div> 

		  </div>
		</div>
<div class="clearfix"></div>

