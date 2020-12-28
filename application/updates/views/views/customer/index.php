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
            <?php if ($package->packages == 'Option 2'){ ?>
                <div class="text-area button-area take_away_section">
                    <div class="col-xs-12 buttons btn-true" data-id="#take">Take Away</div>
                </div>
            <?php }else if($package->packages == 'Option 1'){?>
            <?php }else{ ?>
                <div class="text-area button-area take_away_section">
                    <div class="col-xs-6 buttons btn-true" data-id="#sit">Eat-In</div>
                    <div class="col-xs-6 buttons" data-id="#take">Take Away</div>
                </div>
            <?php } ?>

            <div class="clearfix"></div>
        </h1>
        
        <?php if ($package->packages == 'Option 2'){ ?>
            <div class="row" id="take">
                <?php if ($error = $this->session->flashdata('app_error')) : ?>
                    <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $error ?></div>
                <?php endif ?>
                <?php if ($success = $this->session->flashdata('app_success')) : ?>
                    <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $success ?></div>
                <?php endif ?>

                <?php echo form_open(''); ?>
                <div class="text-area">
                    <input type="text" name="cell" placeholder="Cell no" class="form-control" required="" value="<?php echo $this->input->post('cell') ? $this->input->post('cell') : ''; ?>">
                    <?php echo form_error('cell'); ?>
                </div>
                <!--
                    <div class="text-area">
                        <p>I am of the legal age to consume alcohol. <input type="checkbox" name="over_18" /></p>
                    </div>
                    -->
                <div class="text-area">
                    <button class="submit btn" type="submit" name="">GET ORDERING</button>
                </div>

                <input type="hidden" name="option" value="takeaway" />
                <input type="hidden" name="over_18" class="over_18" value="0" />
                <?php echo form_error('option'); ?>
                <?php echo form_close(); ?>
                <div class="clearfix"></div>
            </div>
        <?php }else if($package->packages == 'Option 1'){?>
            <div style="padding: 30px">
                <a href="<?php echo site_url('customer/view_menu/'); ?>" class="btn btn-block btn-primary">View Menu</a>
            </div>
        <?php }else {?>
            <div class="row" id="sit" style="padding-bottom: 60px;">
                <?php if ($error = $this->session->flashdata('app_error')) : ?>
                    <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $error ?></div>
                <?php endif ?>
                <?php if ($success = $this->session->flashdata('app_success')) : ?>
                    <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $success ?></div>
                <?php endif ?>

                <?php echo form_open(''); ?>
                <div class="text-area">
                    <input type="text" name="name" placeholder="Enter your name" class="form-control" required="" value="<?php echo $this->input->post('name') ? $this->input->post('name') : $name; ?>">
                    <?php echo form_error('name'); ?>
                </div>

                <div class="text-area">
                    <input type="number" name="table" placeholder="Enter table number" class="form-control" required="" value="<?php echo !empty($qr_id) ? $qr_id : $this->input->post('table'); ?>">
                    <?php echo form_error('table'); ?>
                </div>
                <!--
                    <div class="text-area">
                        <p>I am of the legal age to consume alcohol. <input type="checkbox" name="over_18" /></p>
                    </div>
                    -->
                <div class="text-area">
                    <button class="submit btn" type="submit" name="">GET ORDERING</button>
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

                <?php echo form_open(''); ?>
                <div class="text-area">
                    <input type="text" name="cell" placeholder="Cell no" class="form-control" required="" value="<?php echo $this->input->post('cell') ? $this->input->post('cell') : ''; ?>">
                    <?php echo form_error('cell'); ?>
                </div>
                <!--
                    <div class="text-area">
                        <p>I am of the legal age to consume alcohol. <input type="checkbox" name="over_18" /></p>
                    </div>
                    -->
                <div class="text-area">
                    <button class="submit btn" type="submit" name="">GET ORDERING</button>
                </div>

                <input type="hidden" name="option" value="takeaway" />
                <input type="hidden" name="over_18" class="over_18" value="0" />
                <?php echo form_error('option'); ?>
                <?php echo form_close(); ?>
                <div class="clearfix"></div>
            </div>
        <?php } ?>

        <div class="footer">
            <img src="<?php echo base_url('assets/images/poweredby.jpg') ?>" />
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function() {

                jQuery(".submit").on("click", function(e) {
                    var confirm = jQuery('.confirmation').html();
                    e.preventDefault();
                    swal({
                        title: "",
                        text: confirm,
                        html: true,
                        showConfirmButton: false
                    });
                });

                jQuery(document).on('click', ".btn-yes, .btn-no", function() {
                    var over_18 = jQuery(this).text() === 'Yes' ? 1 : 0;
                    jQuery('.over_18').val(over_18);
                    var form_container = jQuery("#sit").is(':visible') ? "#sit" : "#take";
                    jQuery(form_container + ' form').submit();
                });

                jQuery('.take_away_section div').click(function() {
                    jQuery('.take_away_section .buttons').removeClass('btn-true');
                    jQuery(this).addClass('btn-true');
                    jQuery('#sit, #take').hide();
                    jQuery(jQuery(this).attr('data-id')).show();
                });
            });
        </script>
    </div>
    <div class="confirmation hide" style="max-width: 350px">
        <h2 style="font-size: 23px;">I am of the legal age to consume alcohol</h2>
        <div class="btn-group">
            <button type="button" class="btn btn-yes">Yes</button>
            <button type="button" class="btn btn-no">No</button>
        </div>
    </div>
</div>