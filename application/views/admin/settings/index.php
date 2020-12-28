<?php

?>
<style>
	.icheckbox_square-green {
		margin-bottom:5px;
	}
	body {
		font-family:gibson;
	}
</style>
<div id="page-inner">
    <?php echo form_open('', array('class' => 'form-new', "id" => "attribute", 'data-parsley-validate' => '')); ?>
    <div class="row">
        <div class="col-md-6">
            <h2>Settings</h2>   
        </div>
        <div class="col-md-6"></div>
        <div class="clearfix"></div>
    </div>       
    <hr />
    <?php require APPPATH.'views/admin/settings/navs.php'; ?>
    <div class="row">
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    Basic Settings
					
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Currency code</label>
                            <input type="text" name="currency_code" class="form-control" placeholder="Currency code" required="" value="<?php echo $this->config->item('currency_code'); ?>">
                            <?php echo form_error('currency_code'); ?>
                        </div>
                        
                        <div class="form-group">
                            <label>Timezone</label>
                            
                            <?php $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);?>
                            
                            <select name="timezone" class="form-control">
                                <option value="">---SELECT---</option>
                                <?php foreach ($tzlist as $zone): ?>
                                <option value="<?php echo $zone; ?>" <?php echo ($zone==$this->config->item('timezone')) ? 'selected' : ''; ?> ><?php echo $zone; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo form_error('timezone'); ?>
                        </div>
                        <?php 
							$from = json_decode($this->config->item('work_hours_from'));
							$tos = json_decode($this->config->item('work_hours_to'));
							
						?>
                        <div class="form-group">
	
							<div class="row">
								<div class="col-md-3">
								<label>Week Day</label>
								<?php $week_days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'); 
									foreach($week_days as $k=>$v) {
								?>
									<p><?php echo $v ?></p>
									<?php } ?>
								</div>
								<div class="col-md-2">
									<label>Open Time</label>
									<?php for($a=0;$a<7;$a++) { ?>
										 <p><select name="work_hours_from[]" required="">
											<option value="0">00:00 Hrs</option>
											<?php for($i=1; $i<=23; $i++): ?>
												<option value="<?php echo $week_days[$a]."_".$i; ?>" <?php echo in_array($week_days[$a]."_".$i, $from ) ? 'selected' : ''; ?> ><?php echo ($i<10) ? '0'.$i : $i; ?>:00 Hrs</option>
											<?php endfor; ?>
											<?php echo form_error('work_hours_from'); ?>
										</select></p>
									<?php } ?>
								</div>
								<div class="col-md-2">
									<label>Close Time</label>
									<?php for($a=0;$a<7;$a++) { ?>
										<p><select name="work_hours_to[]" required="">
											<option value="0">00:00 Hrs</option>
											<?php for($i=1; $i<=23; $i++): ?>
												<option value="<?php echo $week_days[$a]."_".$i; ?>" <?php echo in_array($week_days[$a]."_".$i, $tos ) ? 'selected' : ''; ?> ><?php echo ($i<10) ? '0'.$i : $i; ?>:00 Hrs</option>
											<?php endfor; ?>
											<?php echo form_error('work_hours_to'); ?>
										</select></p>
									<?php } ?>
								</div>
							</div>
                        </div>
                        
                        
                        <div class="form-group">
                            <label>Type of business</label>
                            <br/>
                            <?php 
                            
                                $btypes = $this->config->item('business_type');                                                        
                                $parts  = json_decode($btypes);    
								/***
								
									whenever we add new or alter any category (type of business)
									then also updated in API function.
									path function = super/ince/functions/getOptions
									These values are hardcoded there
								
								***/
                            ?>
                            <div class="row">
                                <div class="col-sm-3">
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Kids friendly', $parts ) ? 'checked' : ''; ?> value="Kids friendly"> <span class="gibson 17x">Kids friendly</span><br/>
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Pub and Bar', $parts ) ? 'checked' : ''; ?> value="Pub and Bar"> <span class="gibson 17x">Pub and Bar</span><br/>
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Bakery + Cafés Casual', $parts ) ? 'checked' : ''; ?> value="Bakery + Cafés Casual"><span class="gibson 17x"> Bakery + Cafés Casual</span><br/>
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Quick bite', $parts ) ? 'checked' : ''; ?> value="Quick bite"><span class="gibson 17x"> Quick bite</span><br/>
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Play area', $parts ) ? 'checked' : ''; ?> value="Play area"><span class="gibson 17x"> Play area</span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Breakfast', $parts ) ? 'checked' : ''; ?> value="Breakfast"><span class="gibson 17x"> Breakfast</span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Lunch', $parts ) ? 'checked' : ''; ?> value="Lunch"><span class="gibson 17x"> Lunch</span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Dinner', $parts ) ? 'checked' : ''; ?> value="Dinner"><span class="gibson 17x"> Dinner</span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Fast Food', $parts ) ? 'checked' : ''; ?> value="Fast Food"><span class="gibson 17x"> Fast Food</span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Fine Dining', $parts ) ? 'checked' : ''; ?> value="Fine Dining"><span class="gibson 17x"> Fine Dining</span><br/>
                                </div>
                                <div class="col-sm-3">
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Coffee Shops', $parts ) ? 'checked' : ''; ?> value="Coffee Shops"><span class="gibson 17x"> Coffee Shops</span><br/>
                                    
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Coffee shop', $parts ) ? 'checked' : ''; ?> value="Coffee shop"><span class="gibson 17x"> Coffee shop</span><br/>
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Drinks + Nightlife', $parts ) ? 'checked' : ''; ?> value="Drinks + Nightlife"><span class="gibson 17x"> Drinks + Nightlife </span><br/>
									
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Beer Garden', $parts ) ? 'checked' : ''; ?> value="Beer Garden"><span class="gibson 17x"> Beer Garden</span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Bar', $parts ) ? 'checked' : ''; ?> value="Bar"> <span class="gibson 17x">Bar </span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Bistro', $parts ) ? 'checked' : ''; ?> value="Bistro"><span class="gibson 17x"> Bistro </span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Food Court', $parts ) ? 'checked' : ''; ?> value="Food Court"><span class="gibson 17x"> Food Court</span> <br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Drive through', $parts ) ? 'checked' : ''; ?> value="Drive through"><span class="gibson 17x"> Drive through </span><br/>
                                </div>
                                <div class="col-sm-3">
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Live entertainment', $parts ) ? 'checked' : ''; ?> value="Live entertainment"><span class="gibson 17x"> Live entertainment</span><br/>
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Outside area', $parts ) ? 'checked' : ''; ?> value="Outside area"><span class="gibson 17x"> Outside area</span><br/>
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Cocktail bar', $parts ) ? 'checked' : ''; ?> value="Cocktail bar"><span class="gibson 17x"> Cocktail bar</span><br/>
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Lounge', $parts ) ? 'checked' : ''; ?> value="Lounge"><span class="gibson 17x"> Lounge</span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Brasserie', $parts ) ? 'checked' : ''; ?> value="Brasserie"> <span class="gibson 17x">Brasserie</span><br/>
									
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Dining', $parts ) ? 'checked' : ''; ?> value="Dining"><span class="gibson 17x"> Dining</span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Wi-fi', $parts ) ? 'checked' : ''; ?> value="Wi-fi"> <span class="gibson 17x">Wi-fi </span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Food Truck', $parts ) ? 'checked' : ''; ?> value="Food Truck"><span class="gibson 17x"> Food Truck</span> <br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Road House', $parts ) ? 'checked' : ''; ?> value="Road House"> <span class="gibson 17x">Road House</span> <br/>
                                </div>
                                <div class="col-sm-3">
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Live sport', $parts ) ? 'checked' : ''; ?> value="Live sport"><span class="gibson 17x"> Live sport</span><br/>
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Gambling', $parts ) ? 'checked' : ''; ?> value="Gambling"> <span class="gibson 17x">Gambling</span><br/>
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Pet friendly', $parts ) ? 'checked' : ''; ?> value="Pet friendly"> <span class="gibson 17x">Pet friendly</span><br/>
									
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Social Club', $parts ) ? 'checked' : ''; ?> value="Social Club"> <span class="gibson 17x">Social Club </span><br/>
                                    
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Deli', $parts ) ? 'checked' : ''; ?> value="Deli"><span class="gibson 17x"> Deli</span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Dessert Parlor', $parts ) ? 'checked' : ''; ?> value="Dessert Parlor"><span class="gibson 17x"> Dessert Parlor</span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Fast Casual', $parts ) ? 'checked' : ''; ?> value="Fast Casual"> <span class="gibson 17x">Fast Casual </span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="business_type[]" <?php echo in_array('Kiosk', $parts ) ? 'checked' : ''; ?> value="Kiosk"> <span class="gibson 17x">Kiosk</span> <br/>
									
                                </div>
                                <div class="clearfix"></div>
                            </div>
                             <?php echo form_error('business_type'); ?>
                        </div>
                        
                        <div class="form-group">
                            <label>Type of food</label><br>
								<?php 
									$btypes1 = $this->config->item('food_type');                                                        
									$parts1  = json_decode($btypes1); 
								?>
								<div class="row">
                                <div class="col-sm-3">
                                   
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="food_type[]" <?php echo in_array('Curry', $parts1 ) ? 'checked' : ''; ?> value="Curry"><span class="gibson 17x"> Curry</span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="food_type[]" <?php echo in_array('Pastry', $parts1 ) ? 'checked' : ''; ?> value="Pastry"> <span class="gibson 17x">Pastry</span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="food_type[]" <?php echo in_array('Drinks Only', $parts1 ) ? 'checked' : ''; ?> value="Drinks Only"> <span class="gibson 17x">Drinks Only</span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="food_type[]" <?php echo in_array('Fish and Chips', $parts1 ) ? 'checked' : ''; ?> value="Fish and Chips"> <span class="gibson 17x">Fish and Chips</span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="food_type[]" <?php echo in_array('Buffet', $parts1 ) ? 'checked' : ''; ?> value="Buffet"> <span class="gibson 17x">Buffet</span><br/>
                                </div>
                                <div class="col-sm-3">
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="food_type[]" <?php echo in_array('Asian', $parts1 ) ? 'checked' : ''; ?> value="Asian"> <span class="gibson 17x">Asian </span><br/>
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="food_type[]" <?php echo in_array('Indian', $parts1 ) ? 'checked' : ''; ?> value="Indian"> <span class="gibson 17x">Indian </span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="food_type[]" <?php echo in_array('Japanese', $parts1 ) ? 'checked' : ''; ?> value="Japanese"> <span class="gibson 17x">Japanese </span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="food_type[]" <?php echo in_array('European', $parts1 ) ? 'checked' : ''; ?> value="European"> <span class="gibson 17x">European</span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="food_type[]" <?php echo in_array('Chinese', $parts1 ) ? 'checked' : ''; ?> value="Chinese"><span class="gibson 17x"> Chinese </span><br/>
                                </div>
                                <div class="col-sm-3">
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="food_type[]" <?php echo in_array('Seafood', $parts1 ) ? 'checked' : ''; ?> value="Seafood"> <span class="gibson 17x">Seafood </span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="food_type[]" <?php echo in_array('Sushi', $parts1 ) ? 'checked' : ''; ?> value="Sushi"> <span class="gibson 17x">Sushi</span> <br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="food_type[]" <?php echo in_array('Grill and burgers', $parts1 ) ? 'checked' : ''; ?> value="Grill and burgers"><span class="gibson 17x"> Grill and burgers</span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="food_type[]" <?php echo in_array('Steak house', $parts1 ) ? 'checked' : ''; ?> value="Steak house"><span class="gibson 17x"> Steak house </span><br/>
									
                                </div>
                                <div class="col-sm-3">
                                    <input type="checkbox" class="state icheckbox icheckbox_square-green" name="food_type[]" <?php echo in_array('Italian', $parts1 ) ? 'checked' : ''; ?> value="Italian"><span class="gibson 17x"> Italian</span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="food_type[]" <?php echo in_array('Healthy food', $parts1 ) ? 'checked' : ''; ?> value="Healthy food"><span class="gibson 17x"> Healthy food</span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="food_type[]" <?php echo in_array('Vegetarian', $parts1 ) ? 'checked' : ''; ?> value="Vegetarian"><span class="gibson 17x"> Vegetarian</span><br/>
									<input type="checkbox" class="state icheckbox icheckbox_square-green" name="food_type[]" <?php echo in_array('Thai food', $parts1 ) ? 'checked' : ''; ?> value="Thai food"> <span class="gibson 17x">Thai food</span><br/>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <?php echo form_error('food_type'); ?>
                        </div>
                        <input type="hidden" name="delivery_show" value="0">
                        <input type="hidden" name="collection_show" value="0">
                        <div class="form-group">
                            <label >Collection</label>
                            <input type="checkbox" class="js-switch switchery-small" name="collection_show" value="1" <?php echo $this->config->item('collection_show') == 1 ? "checked" : "" ?>>
                        </div>
                         
                        <div class="form-group">
                            <label>Delivery</label>
                            <input type="checkbox" class="js-switch switchery-small" name="delivery_show" value="1" <?php echo $this->config->item('delivery_show') == 1 ? "checked" : "" ?>>
							 
                        </div>
						<?php 
							if(driver_allowed()) {
						?>
							<div class="form-group">
								<label>Driver</label>
								<input type="hidden"  name="driver" value="0" >
								<input type="checkbox" class="js-switch switchery-small" name="driver" value="1" <?php echo $this->config->item('driver') == 1 ? "checked" : "" ?>>
								
							</div>
						<?php } ?>
						<div class="form-group">
                            <label>Delivery fee</label>
                            <input type="number" name="delivery_fee" class="form-control" placeholder="Delivery fee" required="" value="<?php echo ($this->config->item('delivery_fee')); ?>">
                            <?php echo form_error('delivery_fee'); ?>
                        </div>
						<div class="form-group">
                            <label>Delivery Distance (Km)</label>
                            <input type="number" name="delivery_distance" class="form-control" placeholder="Delivery distance allowed" required="" value="<?php echo ($this->config->item('delivery_distance')); ?>">
                            <?php echo form_error('delivery_distance'); ?>
                        </div>
                    </div>    
                </div>
            </div>
            <!--End Advanced Tables --> 
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="panel panel-default">
                <div class="panel-heading">
                Product stock quantity options
                </div>
                <input type="hidden" name="show_avail_stock" value="0">
                <input type="hidden" name="hide_empty_stock" value="0">
                <div class="panel-body">
                    <div class="col-md-8">
                        <!--<div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-xs-6">
                                    Show available stock to customer
                                </div>
                                <div class="col-sm-3 col-xs-6">
                                    <input type="checkbox" class="js-switch switchery-small" name="show_avail_stock" value="1" <?php echo $this->config->item('show_avail_stock') == 1 ? "checked" : "" ?> />
                                </div>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-xs-6">
                                    Hide product when stock is 0
                                </div>
                                <div class="col-sm-3 col-xs-6">
                                    <input type="checkbox" class="js-switch switchery-small" name="hide_empty_stock" value="1" <?php echo $this->config->item('hide_empty_stock') == 1 ? "checked" : "" ?> />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <div class="row">
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    Payment Settings
                </div>
                <div class="panel-body">
                    <div class="col-md-8">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-3 col-xs-6">
                                    Cash
                                </div>
                                <div class="col-sm-5 col-xs-6">
                                    <input type="radio" class="state icheckbox iradio_square-green" name="payment_mode" value="1" <?php echo $this->config->item('payment_mode') == 1 ? "checked" : "" ?> />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="checkbox" class="js-switch switchery-small" name="payment_method" value="1" <?php echo $this->config->item('payment_method') == 1 ? "checked" : "" ?> /> &nbsp;&nbsp;Masterpass
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-3 col-xs-6">
                                    Card
                                </div>
                                <div class="col-sm-5 col-xs-6">
                                    <input type="radio" class="state icheckbox iradio_square-green" name="payment_mode" value="2" <?php echo $this->config->item('payment_mode') == 2 ? "checked" : "" ?> />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="checkbox" class="js-switch switchery-small" name="payment_method" value="2" <?php echo $this->config->item('payment_method') == 2 ? "checked" : "" ?> /> &nbsp;&nbsp;Paypal
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-3 col-xs-6">
                                    Cash and Card
                                </div>
                                <div class="col-sm-5 col-xs-6">
                                    <input type="radio" class="state icheckbox iradio_square-green" name="payment_mode" value="3" <?php echo $this->config->item('payment_mode') == 3 ? "checked" : "" ?> />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="checkbox" class="js-switch switchery-small" name="payment_method" value="3" <?php echo $this->config->item('payment_method') == 3 ? "checked" : "" ?> /> &nbsp;&nbsp;sid EFT
                                </div>
                            </div>
                        </div> 
                        <?php echo form_error('payment_mode'); ?>
                        <div class="form-group">
                            <button class="btn btn-info" type="submit">Update</button> 
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <?php echo form_close(); ?>
</div>
<script>
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
			elems.forEach(function(html) {
			  var switchery = new Switchery(html, { size: 'small',color:'#1b7e5a' });
			});
	$('.icheckbox').iCheck({
		checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
	});
</script>
