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
                    <div class="col-md-8">
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
                        
                        <div class="form-group">
                            <label>work hours</label>
                            <p>
                                From : 
                                <select name="work_hours_from" required="">
                                    <option value="0">00:00 Hrs</option>
                                    <?php for($i=1; $i<=23; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php echo $this->config->item('work_hours_from') == $i ? 'selected' : ''; ?> ><?php echo ($i<10) ? '0'.$i : $i; ?>:00 Hrs</option>
                                    <?php endfor; ?>
                                </select>
                                <?php echo form_error('work_hours_from'); ?>
                                To : 
                                <select name="work_hours_to" required="">
                                    <option value="0">00:00 Hrs</option>
                                    <?php for($i=1; $i<=23; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php echo $this->config->item('work_hours_to') == $i ? 'selected' : ''; ?> ><?php echo ($i<10) ? '0'.$i : $i; ?>:00 Hrs</option>
                                    <?php endfor; ?>
                                </select>
                                <?php echo form_error('work_hours_to'); ?>
                            </p>
                        </div>
                        
                        <div class="form-group">
                            <label>Week days</label>
                            <p>
                                From : 
                                <?php $week_days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'); ?>
                                <select name="work_week_from" required="">
                                    <?php for($i=0; $i<=6; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php echo $this->config->item('work_week_from') == $i ? 'selected' : ''; ?> ><?php echo $week_days[$i] ?></option>
                                    <?php endfor; ?>
                                </select>
                                <?php echo form_error('work_week_from'); ?>
                                To : 
                                <select name="work_week_to" required="">
                                    <?php for($i=0; $i<=6; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php echo $this->config->item('work_week_to') == $i ? 'selected' : ''; ?> ><?php echo $week_days[$i]; ?></option>
                                    <?php endfor; ?>
                                </select>
                                <?php echo form_error('work_week_to'); ?>
                            </p>
                            
                        </div>
                        
                        <div class="form-group">
                            <label>Type of business</label>
                            <br/>
                            <?php 
                            
                                $btypes = $this->config->item('business_type');                                                        
                                $parts  = json_decode($btypes);                               
                            ?>
                            <div class="row">
                                <div class="col-sm-3">
                                    <input type="checkbox" name="business_type[]" <?php echo in_array('Kids friendly', $parts ) ? 'checked' : ''; ?> value="Kids friendly">Kids friendly<br/>
                                    <input type="checkbox" name="business_type[]" <?php echo in_array('Pub and Bar', $parts ) ? 'checked' : ''; ?> value="Pub and Bar">Pub and Bar<br/>
                                    <input type="checkbox" name="business_type[]" <?php echo in_array('Bakery', $parts ) ? 'checked' : ''; ?> value="Bakery">Bakery<br/>
                                    <input type="checkbox" name="business_type[]" <?php echo in_array('Quick bite', $parts ) ? 'checked' : ''; ?> value="Quick bite">Quick bite<br/>
                                    <input type="checkbox" name="business_type[]" <?php echo in_array('Play area', $parts ) ? 'checked' : ''; ?> value="Play area">Play area<br/>
                                </div>
                                <div class="col-sm-3">
                                    <input type="checkbox" name="business_type[]" <?php echo in_array('Casual Dining', $parts ) ? 'checked' : ''; ?> value="Casual Dining">Casual Dining<br/>
                                    <input type="checkbox" name="business_type[]" <?php echo in_array('Fine Dining', $parts ) ? 'checked' : ''; ?> value="Fine Dining">Fine Dining<br/>
                                    <input type="checkbox" name="business_type[]" <?php echo in_array('Coffee shop', $parts ) ? 'checked' : ''; ?> value="Coffee shop">Coffee shop<br/>
                                    <input type="checkbox" name="business_type[]" <?php echo in_array('Take away', $parts ) ? 'checked' : ''; ?> value="Take away">Take away   <br/>                       
                                </div>
                                <div class="col-sm-3">
                                    <input type="checkbox" name="business_type[]" <?php echo in_array('Live entertainment', $parts ) ? 'checked' : ''; ?> value="Live entertainment">Live entertainment<br/>
                                    <input type="checkbox" name="business_type[]" <?php echo in_array('Outside area', $parts ) ? 'checked' : ''; ?> value="Outside area">Outside area<br/>
                                    <input type="checkbox" name="business_type[]" <?php echo in_array('Cocktail bar', $parts ) ? 'checked' : ''; ?> value="Cocktail bar">Cocktail bar<br/>
                                    <input type="checkbox" name="business_type[]" <?php echo in_array('Lounge', $parts ) ? 'checked' : ''; ?> value="Lounge">Lounge<br/>
                                </div>
                                <div class="col-sm-3">
                                    <input type="checkbox" name="business_type[]" <?php echo in_array('Live sport', $parts ) ? 'checked' : ''; ?> value="Live sport">Live sport<br/>
                                    <input type="checkbox" name="business_type[]" <?php echo in_array('Gambling', $parts ) ? 'checked' : ''; ?> value="Gambling">Gambling<br/>
                                    <input type="checkbox" name="business_type[]" <?php echo in_array('Pet friendly', $parts ) ? 'checked' : ''; ?> value="Pet friendly">Pet friendly<br/>
                                    <input type="checkbox" name="business_type[]" <?php echo in_array('Wi-fi', $parts ) ? 'checked' : ''; ?> value="Wi-fi">Wi-fi <br/>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                             <?php echo form_error('business_type'); ?>
                        </div>
                        
                        <div class="form-group">
                            <label>Type of food</label>
                            <select name="food_type" class="form-control">
                                <option value="">--SELECT--</option>
                                
                                <?php
                                    if(is_array($foods)):
                                        foreach ($foods as $food){
                                            $checked = $food->name == $this->config->item('food_type') ? 'selected="true"' : '';
                                            echo '<option value="'.$food->name.'" '.$checked.'>'.$food->name.'</option>';
                                        }
                                    endif;
                                ?>    
                            </select>
                            <?php echo form_error('food_type'); ?>
                        </div>
                        <input type="hidden" name="delivery_show" value="0">
                        <input type="hidden" name="collection_show" value="0">
                        <div class="form-group">
                            <label>Collection</label>
                            <input type="checkbox" name="collection_show" value="1" <?php echo $this->config->item('collection_show') == 1 ? "checked" : "" ?>>
                        </div>
                        
                        <div class="form-group">
                            <label>Delivery</label>
                            <input type="checkbox" name="delivery_show" value="1" <?php echo $this->config->item('delivery_show') == 1 ? "checked" : "" ?>>
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
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-xs-6">
                                    Show available stock to customer
                                </div>
                                <div class="col-sm-3 col-xs-6">
                                    <input type="checkbox" name="show_avail_stock" value="1" <?php echo $this->config->item('show_avail_stock') == 1 ? "checked" : "" ?> />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-xs-6">
                                    Hide product when stock is 0
                                </div>
                                <div class="col-sm-3 col-xs-6">
                                    <input type="checkbox" name="hide_empty_stock" value="1" <?php echo $this->config->item('hide_empty_stock') == 1 ? "checked" : "" ?> />
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
                                <div class="col-sm-3 col-xs-6">
                                    <input type="radio" name="payment_mode" value="1" <?php echo $this->config->item('payment_mode') == 1 ? "checked" : "" ?> />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-3 col-xs-6">
                                    Card
                                </div>
                                <div class="col-sm-3 col-xs-6">
                                    <input type="radio" name="payment_mode" value="2" <?php echo $this->config->item('payment_mode') == 2 ? "checked" : "" ?> />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-3 col-xs-6">
                                    Cash and Card
                                </div>
                                <div class="col-sm-3 col-xs-6">
                                    <input type="radio" name="payment_mode" value="3" <?php echo $this->config->item('payment_mode') == 3 ? "checked" : "" ?> />
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
