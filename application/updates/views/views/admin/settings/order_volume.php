<div id="page-inner">
    <?php echo form_open('', array('class' => 'form-new', "id" => "attribute", 'data-parsley-validate' => '')); ?>
    <div class="row">
        <div class="col-md-6">
            <h2>Styles</h2>   
        </div>
        <div class="col-md-6">
            <div class="pull-right nav-area">
                <button type="submit" name="submitForm" class="btn btn-info btn-sm" value="formSave">
                    <span class="glyphicon glyphicon-share"></span>
                    Save
                </button>

                <button type="submit" name="submitForm" class="btn btn-info btn-sm " value="formSaveClose"> 
                    <span class="glyphicon glyphicon-share"></span>
                    Save and Close
                </button>

                <a href="<?php echo site_url('admin/'); ?>" class="btn btn-success btn-sm">
                    <i class="fa fa-reply"></i>
                    Back
                </a>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>       
    <hr />
    <?php require APPPATH.'views/admin/settings/navs.php'; ?>
    <div class="row">
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    Order Volume
                </div>
                <div class="panel-body">
                    <p>
                        <label>Active:</label>
                        <input type="radio" name="order_volume_active" value="1" required="" <?php echo $this->config->item('order_volume_active') == 1 ? "checked" : "" ?> > Yes
                        <input type="radio" name="order_volume_active" value="0" <?php echo $this->config->item('order_volume_active') == 0 ? "checked" : "" ?> > No
                        <span class="error"><?php echo form_error('order_volume_active'); ?></span>
                    </p>
                    <p>
                        <label>Order warning value:</label>
                        <select class="form-control" name="order_volume_warning_value" required="" style="max-width: 80px;">
                            <?php for($i=1; $i<=100; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $this->config->item('order_volume_warning_value') == $i ? "selected" : "" ?>><?php echo $i; ?></option>
                            <?php endfor;?>
                        </select>
                        <span class="error"><?php echo form_error('order_volume_warning_value'); ?></span>
                    </p>
                    <p>
                        <?php $included = $this->config->item('order_volume_categories') ? json_decode($this->config->item('order_volume_categories')) : array(); ?>
                        <label>Include categories: </label>
                        <div class="row">
                        <?php $index = 1; foreach($categories as $category): ?>
                            <?php 
                                if($index > 4):
                                    $index = 1;
                                    echo "</div><div class='row'>";
                                endif;
                               
                            ?>
                            <div class="col-sm-3">
                                <p>
                                    <input type="checkbox" name="order_volume_categories[]" value="<?php echo $category->id;?>" <?php echo in_array($category->id, $included) ? "checked" : "";?> />
                                    <?php echo $category->name; ?>
                                </p>
                            </div>
                        <?php ++$index; endforeach;?>
                        <?php if($index == 1): ?>
                        </div>
                        <?php endif;?>
                    </p>
                </div>
            </div>
            <!--End Advanced Tables -->
        </div>
    </div>
    <?php echo form_close(); ?>
</div>


