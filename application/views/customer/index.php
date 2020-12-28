<style>
	.nav-pills > li.active > a, .nav-pills > li.active > a:hover, .nav-pills > li.active > a:focus {
		color: #fff;
		background-color: #008000;
	}
	.newd {
	   padding: 0px 37px;
	}
	.clc {width:100% !important}
	.custom-header-area {
		padding: 120px 0 0px 0;
	}
	button.submit {
		background: #008000;
		border: 1px solid #008000;
		border-radius: 0;
		color: #FFF;
	}
	.input-group-addon:last-child {
		border-left: 0;
		border: 1px solid green;
	}
</style>
<div class="container text-center col-lg-12">
    <div class="">
        <div class="row custom-header-area">
            <div class="col-md-12">
                <br />
                <?php if ($this->config->item("store_logo")) : ?>
                    <img src="<?php echo base_url('assets/images/' . $this->config->item("store_logo")); ?>" class="header-img img-responsive" />
                <?php else : ?>
                    <img src="<?php echo base_url('assets/images/takkilogo.png'); ?>" class="header-img img-responsive">
                <?php endif; ?>

            </div>
        </div>
        <?php 
		
			$adminDel = $this->config->item('delivery_show');
			$driver = $this->config->item('driver');
			$superDel = $accounts['delivery'];
			$reservation = $accounts['reservation'];
			
		?>
        <div class="row"> 
            <?php if($status==FALSE) {}else if ($package->packages == 'Option 2'){ ?>
                <!--<div class="text-area button-area take_away_section">
					<div class="col-xs-12 buttons btn-true" data-id="#take">Take Away</div>
                </div>-->
            <?php } else if($package->packages == 'Option 1') { ?>
            <?php } else { ?>
                <div class="text-area button-area take_away_section">
                    <div class="col-xs-6 buttons btn-true" data-id="#sit">Eat-In</div>
                    <div class="col-xs-6 buttons" data-id="#take">Take Away</div>
					<?php 
						if($package->packages=="Option 4" && $reservation=="1") {
					?>
					<br><div class="col-xs-12 buttons" data-id="#reserve">Reservation</div>
					<?php } ?>
                </div>
            <?php } ?>

            <div class="clearfix"></div>
        </h1>
        
        <?php if($status==FALSE){?>
			<div style="padding: 30px">
                <a href="<?php echo site_url('customer/view_menu/'); ?>" class="btn btn-block btn-primary">View Menu</a>
            </div>
		<?php } else if ($package->packages == 'Option 2') { ?>
            <div class="row" id="take">
                <?php if ($error = $this->session->flashdata('app_error')) : ?>
                    <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $error ?></div>
                <?php endif ?>
                <?php if ($success = $this->session->flashdata('app_success')) : ?>
                    <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $success ?></div>
                <?php endif ?>

                <div class="row">
					<div class="col-md-12 newd">
					<ul class="nav nav-pills nav-justified">
						<?php 
							$styleS="";
							//if($adminDel=="1" && $superDel=="1") {
								$styleS="active";
						?>
						<li class="active"><a data-toggle="pill" class="choice" data-tb="dell" href="#dell">Delivery</a></li>
						<?php //} ?>
						<?php 
							if($this->config->item('collection_show')=="1") {
						?>
						<li><a data-toggle="pill" class="choice" data-tb="calc" href="#calc">Collection</a></li>
						<?php } ?>
					</ul><?php echo form_open(''); ?>
					<div class="tab-content">
						<div id="dell" class="tab-pane fade in <?php echo $styleS;?>" ><br/>
							<div class="text-area">
								<input type="text" class="form-control clc" name="contact_name1" autocomplete="off" placeholder="Contact name" id="contact_name" value="" />
								<?php echo form_error('contact_name'); ?>
							</div>
							<div class="text-area">
								<input type="text" name="cell1" autocomplete="off" placeholder="Cell no" class="form-control clc" value="<?php echo $this->input->post('cell1') ? $this->input->post('cell1') : ''; ?>">
								<?php echo form_error('cell1'); ?>
							</div>
							<div class="text-area">
								<input type="text" name="address" id="address" autocomplete="off" placeholder="Address" class="form-control clc"  value="<?php echo $this->input->post('address') ? $this->input->post('address') : ''; ?>">
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

                <input type="hidden" name="option" value="takeaway" />
                <input type="hidden" name="over_18" class="over_18" value="0" />
                <?php echo form_error('option'); ?>
                <?php echo form_close(); ?>
                <div class="clearfix"></div>
            </div>
        <?php } else if($package->packages == 'Option 1') { ?>
            <div style="padding: 30px">
                <a href="<?php echo site_url('customer/view_menu/'); ?>" class="btn btn-block btn-primary">View Menu</a>
            </div>
        <?php } else { ?>
            <div class="row" id="sit" style="padding-bottom: 0px;">
                <?php if ($error = $this->session->flashdata('app_error')) : ?>
                    <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $error ?></div>
                <?php endif ?>
                <?php if ($success = $this->session->flashdata('app_success')) : ?>
                    <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $success ?></div>
                <?php endif ?>

                <?php echo form_open(''); ?>
				
                <div class="text-area">
                    <input type="text" name="name" autocomplete="off" placeholder="Enter your name" class="form-control" required="" value="<?php echo $this->input->post('name') ? $this->input->post('name') : $name; ?>">
                    <?php echo form_error('name'); ?>
                </div>

                <div class="text-area">
                    <input type="number" name="table" autocomplete="off" placeholder="Enter table number" class="form-control" required="" value="<?php echo !empty($qr_id) ? $qr_id : $this->input->post('table'); ?>">
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
				<br/>
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
					</ul><?php echo form_open(''); ?>
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
								<?php if($adminDel=="1" && $superDel && $driver=="1") { ?>
								<div class="input-group">
									<input type="text" name="address" id="address" autocomplete="off" placeholder="Address" class="form-control clc" value="<?php echo $this->input->post('address') ? $this->input->post('address') : ''; ?>" >
									<span class="input-group-addon loc"><i title="Add my location" class="glyphicon glyphicon-map-marker" style="cursor:pointer"></i></span>
								</div>
								<?php } else { ?>
									<input type="text" name="address" id="address" autocomplete="off" placeholder="Address" class="form-control clc"  value="<?php echo $this->input->post('address') ? $this->input->post('address') : ''; ?>" >
								<?php } ?>
								<?php echo form_error('address'); ?>
							</div>
							<?php if($adminDel=="1" && $superDel && $driver=="1") { ?>
							<div class="text-area"> 
								<div class="input-group">
									<input type="password" name="passcode" autocomplete="off" placeholder="4 digit code" class="form-control clc" required="" value="<?php echo $this->input->post('passcode') ? $this->input->post('passcode') : ''; ?>">
									<span class="input-group-addon"><i title="Use this number when accepting delivery" class="glyphicon glyphicon-question-sign" style="cursor:pointer"></i></span>									
								<?php echo form_error('passcode'); ?>
								</div>
							</div>
							<?php } ?>
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
        <?php } ?>
		<p style="color:#008000;margin-bottom:8rem"></br>Orders with same name and table number will be combined.</br>We assume same user with same Name and Table number.</p>
		<div id="map"></div>
        <div class="footer">
            <img src="<?php echo base_url('assets/images/ordering.png') ?>" style="display:block;margin:auto;width:150px;" />
        </div>
        <script type="text/javascript">
			$(document).on('click','.choice',function(){
				var choice = $(this).attr('data-tb');
				if(choice=="calc") {
					$('#choice').val('collection');
				} else if(choice=='dell') {
					$('#choice').val('delivery');
				}
			});
            jQuery(document).ready(function() {

                jQuery(".submit").on("click", function(e) {
					var opt = $('.btn-true').data('id');
					if(opt=='#sit') {
						var confirm = jQuery('.confirmation').html();
						e.preventDefault();
						swal({
							title: "",
							text: confirm,
							html: true,
							showConfirmButton: false
						});
					}
                    
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
				jQuery('.take_away_section_new div').click(function() {
                    jQuery('.take_away_section_new .buttons').removeClass('btn-true');
                    jQuery(this).addClass('btn-true');
                    jQuery('#dell, #calc').hide();
                    jQuery(jQuery(this).attr('data-id')).show();
                });
            });
        </script>
		<script>
      // Note: This example requires that you consent to location sharing when
      // prompted by your browser. If you see the error "The Geolocation service
      // failed.", it means you probably did not give permission for the browser to
      // locate you.
	  $(document).on('click','.loc',function(e){
		  initMap();
	  });
      var map, infoWindow;
      function initMap() {
        // Try HTML5 geolocation.
        if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
				var lat = position.coords.latitude;
				var lng = position.coords.longitude;
				$.ajax({
					url:'<?php echo site_url("customer/ajax/")?>',
					type:'POST',
					dataType:'JSON',
					data:{'action':'coordToAddress','lat':lat,'lng':lng},
					success:function(res) {
						$('#address').val(res).prop('readonly','readonly');
						$('#coords').val(lat+","+lng);
					}
				});
			console.log(position.coords.latitude+"  =  "+position.coords.longitude);
          }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
          });
        } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
        }
      }

      function handleLocationError(browserHasGeolocation, infoWindow, []) {
       
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');
      }
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