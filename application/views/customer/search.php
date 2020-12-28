<?php
    
    $data = $atributes = array();
    foreach ($meals as $key => $meal)
    {
        $data[$meal->cid]['cname']                                 = isset($meal->cname) ? $meal->cname : "";
        if( !empty($meal->mname) )
        {
            $data[$meal->cid]['meal'][$meal->mid]['name']          = isset($meal->mname) ? $meal->mname : "";
            $data[$meal->cid]['meal'][$meal->mid]['description']   = isset($meal->description) ? $meal->description : "";
            $data[$meal->cid]['meal'][$meal->mid]['price']         = isset($meal->price) ? $meal->price : "";
            
            $atributes[$meal->cid]['meal']['attr'][$meal->aid][$meal->aname]  = isset($meal->value) ? $meal->value : "";
            
            if(!empty($meal->aname)){
                
                $values = json_decode($meal->values);
                if(isset($values) && is_array($values)){    
                    foreach ($values as $val){
                        $attribute[$meal->mid][$meal->aname][]       = $val;
                        $attribute[$meal->mid][$meal->aname]['id']   = $meal->aid;
                        $attribute[$meal->mid][$meal->aname]['type'] = $meal->type;
                    }    
                }
            }
            
        }    
    }
    
    //echo '<pre>';    var_dump($attribute);
?>
<ul class="menu_sub" style="display:block">
            <?php foreach ($data as $key => $det): ?>
            <?php if( count($det['meal']) > 0 ): ?>
                <div class="details_container" style="display:block"> 
                    <?php foreach ($det['meal'] as $k => $ml): ?>
                        <div class="details text-left">
                            <div class="row">
                                <div class="margins margin-<?php echo $k; ?>" style="display:none;">

                                    <div class="header_section">
                                        <div class="col-xs-8">
                                            <div class="price"><?php echo CURRENCY_CODE.' '.price_calc($ml['price']); ?></div>
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
                                                <?php if( isset( $attribute[$k] )) : $i = 1;  ?>
                                                    <?php foreach ( $attribute[$k] as $attr_text => $value): ?>
                                                        <div class="attr_area">
                                                            <h1><?php echo $attr_text; ?></h1>                                                                   

                                                            <?php foreach ($value as $v): ?>
                                                            <?php if(empty($v->name)) continue; ?>
                                                            <?php $price_details = (!empty($v->price) && $v->price > 0) ? " ( +".CURRENCY_CODE." ".price_calc($v->price)." ) " : "" ?>

                                                            <p>
                                                                <?php if( $value['type'] == 'multi'): ?>
                                                                    <input class="radio_class" type="checkbox" name="attrs[<?php echo $value['id'] ?>][]" value="<?php echo $v->name ?>"><?php echo $v->name.$price_details ; ?>
                                                                <?php else: ?>
                                                                    <input class="radio_class" type="radio" name="attr[<?php echo $value['id'] ?>]" value="<?php echo $v->name ?>"><?php echo $v->name.$price_details ; ?>
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

                                                    <button class="addit button" data-id="<?php echo $k; ?>" data-exist="<?php echo in_array($k, $order) ? 'yes' : 'no'; ?>"><span class="glyphicon glyphicon-plus"></span>Order Now</button>

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

                    <?php endforeach; ?>
                </div>  
            <?php endif; endforeach; ?>  
        </ul>