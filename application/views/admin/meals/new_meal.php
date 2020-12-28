<div id="page-inner">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <?php echo form_open('', array('class' => 'form-new', "id" => "meal", 'data-parsley-validate' => '')); ?>
        <div class="row">
                <div class="col-md-6">
                    <h2><?php echo $head_title; ?> Products</h2>   
                </div>
                <div class="col-md-6"><br/><br/>
                    <div class="pull-right nav-area">
                        <button type="submit" name="submitForm" class="btn btn-info btn-sm" value="formSave">
                            <span class="glyphicon glyphicon-share"></span>
                            Save
                        </button>
                        <button type="submit" name="submitForm" class="btn btn-info btn-sm " value="formSaveCloseNew">
                            <span class="glyphicon glyphicon-share"></span>
                            Save and add another
                        </button>
                        <button type="submit" name="submitForm" class="btn btn-info btn-sm " value="formSaveClose">
                            <span class="glyphicon glyphicon-share"></span>
                            Save and Close
                        </button>
                        <a href="<?php echo site_url('admin/meals/available'); ?>" class="btn btn-success btn-sm">
                            <i class="fa fa-reply"></i>
                            Back
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>       
        <hr />
        <div class="row">
            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php echo $head_title; ?> Product
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="product_nav">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="<?php echo site_url('admin/meals/new_meal/'.$meal_id); ?>"><span class="glyphicon glyphicon-list-alt"></span>Basic Details</a></li>
                                        <!-- <li><a href="<?php //echo site_url('admin/meals/attributes/'.$meal_id); ?>"><span class="glyphicon glyphicon-link"></span>Attributes</a></li> -->
                                    </ul>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>          
                        <div class="product_content_area">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Products name:</label>
                                            <?php echo form_input($name) ?>
                                            <?php echo form_error('name') ?>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                 <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Products description:</label>
                                            <?php echo form_textarea( $description ); ?>
                                            <?php echo form_error('description') ?>
                                            <script>
                                                // Replace the <textarea id="editor1"> with a CKEditor
                                                // instance, using default configuration.
                                                CKEDITOR.replace( 'description' );
                                            </script>
                                        </div>    
                                    </div>
                                    <div class="clearfix"></div>
                                </div> 
                                 <div class="form-group">
                                    <label>Sort order:</label>
                                    <?php echo form_input($sort) ?>
                                    <?php echo form_error('sort') ?>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Products price:</label>
                                            <?php echo form_input( $price ); ?>
                                            <?php echo form_error('price') ?>
                                        </div>    
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
								<div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
											<input type="hidden" name="available_stock" value="0" />
                                            <label><input type="checkbox"<?php echo $show_available=="1"?"checked":"";?> name="available_stock" id="available_stock" value="1" /> Show Available Stock</label>
                                        </div>    
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="row" id="aqty">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Products quantity:</label>
                                            <?php echo form_input( $quantity ); ?>
                                            <?php echo form_error('quantity') ?>
                                        </div>    
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Category:</label>
                                            <?php  echo form_dropdown('category', $categories, !empty($cid) ? $cid : $this->input->post('category'), 'class="form-control" required="" autocomplete="off"'); ?>
                                            <?php echo form_error('price') ?>
                                        </div>    
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Take away:</label>
                                            <input type="checkbox" name="takeaway" value="1" <?php echo $takeaway == 1 ? 'checked' : '' ?> />
                                            <?php echo form_error('takeaway') ?>
                                        </div>    
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Special:</label>
                                            <input type="checkbox" name="special" value="1" <?php echo $special == 1 ? 'checked' : '' ?> />
                                            <?php echo form_error('special') ?>
                                        </div>    
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Special to run on days:</label>
                                            <ul class="list-inline">
                                                <li><input type="checkbox" name="special_days[]" value="7" <?php echo in_array(7, $special_days) ? 'checked' : '' ?> />Sunday</li>
                                                <li><input type="checkbox" name="special_days[]" value="1" <?php echo in_array(1, $special_days) ? 'checked' : '' ?> />Monday</li>
                                                <li><input type="checkbox" name="special_days[]" value="2" <?php echo in_array(2, $special_days) ? 'checked' : '' ?> />Tuesday</li>
                                                <li><input type="checkbox" name="special_days[]" value="3" <?php echo in_array(3, $special_days) ? 'checked' : '' ?> />Wednesday</li>
                                                <li><input type="checkbox" name="special_days[]" value="4" <?php echo in_array(4, $special_days) ? 'checked' : '' ?> />Thursday</li>
                                                <li><input type="checkbox" name="special_days[]" value="5" <?php echo in_array(5, $special_days) ? 'checked' : '' ?> />Friday</li>
                                                <li><input type="checkbox" name="special_days[]" value="6" <?php echo in_array(6, $special_days) ? 'checked' : '' ?> />Saturday</li>
                                            </ul>
                                            
                                            <?php echo form_error('special_days') ?>
                                        </div>    
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <?php
                                    $specials_from = json_decode($special_from, true);
                                    $specials_to    = json_decode($special_to, true);
                                ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Special time from:</label>
                                            <ul class="list-inline">
                                                <li>Hours:</li>
                                                <li>
                                                    <select name="special_from_hour" class="form-control">
                                                        <?php for($i=0; $i<=23; $i++): ?>
                                                        <option <?php echo isset($specials_from['hour']) && $specials_from['hour'] == $i ? "selected" : ""; ?> value="<?php echo $i; ?>"><?php echo $i < 10 ? '0'.$i : $i; ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                    <?php echo form_error('special_from_hour') ?>
                                                </li>
                                                <li>Minutes:</li>
                                                <li>
                                                    <select name="special_from_minutes" class="form-control">
                                                        <?php for($i=0; $i<=59; $i++): ?>
                                                        <option <?php echo isset($specials_from['minute']) && $specials_from['minute'] == $i ? "selected" : ""; ?> value="<?php echo $i; ?>"><?php echo $i < 10 ? '0'.$i : $i; ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                    <?php echo form_error('special_from_minutes') ?>
                                                </li>
                                            </ul>
                                        </div>    
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Special time to:</label>
                                            <ul class="list-inline">
                                                <li>Hours:</li>
                                                <li>
                                                    <select name="special_to_hour" class="form-control">
                                                        <?php for($i=0; $i<=23; $i++): ?>
                                                        <option <?php echo isset($specials_to['hour']) && $specials_to['hour'] == $i ? "selected" : ""; ?> value="<?php echo $i; ?>"><?php echo $i < 10 ? '0'.$i : $i; ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                    <?php echo form_error('special_to_hour') ?>
                                                </li>
                                                <li>Minutes:</li>
                                                <li>
                                                    <select name="special_to_minutes" class="form-control">
                                                        <?php for($i=0; $i<=59; $i++): ?>
                                                        <option <?php echo isset($specials_to['minute']) && $specials_to['minute'] == $i ? "selected" : ""; ?> value="<?php echo $i; ?>"><?php echo $i < 10 ? '0'.$i : $i; ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                    <?php echo form_error('special_to_minutes') ?>
                                                </li>
                                            </ul>
                                        </div>    
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Attributes:</label>

                                        </div>
                                    </div>    
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                    <?php if( isset($attributes) && count($attributes) > 0): ?>
                                        <?php foreach ($attributes as $attr): ?>
                                            <div class="col-md-6">
                                                <?php echo $attr->name; ?>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="checkbox" class="" name="attribute[]" value="<?php echo $attr->id; ?>" <?php echo in_array($attr->id, $checked) ? 'checked' : ''?>><br/><br/>
                                            </div>
                                            <div class="clearfix"></div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span>No attributes created!</span>
                                    <?php endif; ?>
                                    </div>    
                                </div>

                                <div class="row">
                                    <br/><br/>
                                    <div class="col-md-4">
                                         <?php echo form_hidden($id); ?>  
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                        </div>    
                    </div>    
                </div>
                </div>
                <!--End Advanced Tables -->
            </div>
        <?php echo form_close(); ?>    
    </div>
    <script type="text/javascript">
		$(document).on('click','#available_stock',function(e){
			console.log('0');
			if($('#available_stock').is(':checked')) {
				$('#aqty').show();
				
			} else {
				$('#aqty').hide();
				$('#aqty').val("");
			}
		});
        $( "#date_from, #date_to" ).datepicker({ dateFormat: 'yy-mm-dd' });
        window.Parsley.addAsyncValidator('validateCategory', function (xhr) {
        console.log(xhr.responseText); // jQuery Object[ input[name="q"] ]

        if (xhr.responseText == 0) {
            return false;
        } else {
            return true;
        }
                
        }, '<?php echo site_url('admin/categories/exists/{value}');?>', { "type": "POST", "dataType": "json", "data": $('#category').serialize() } );
		
		
    </script>
</div>
