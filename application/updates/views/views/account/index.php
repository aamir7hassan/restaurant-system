<div class="row">
    <div class="col-sm-8 col-sm-offset-2 text">
        <h1><strong>MyTakki</strong>Create your account</h1>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-sm-offset-3 form-box">
        <div class="form-top">
            <div class="form-top-left">
                <h3>Create your restaurant here</h3>
                <p>Enter your details below</p>
            </div>
            <div class="form-top-right">
                <i class="fa fa-lock"></i>
            </div>
        </div>
        <div class="form-bottom">
            <?php echo form_open('', array('class' => 'form-signin login-form', "id" => "loginform")); ?>
            
                <div class="form-group">
                    <label class="sr-only" for="form-username">Name</label>                        
                    <input type="text" name="name" placeholder="Name" class="form-username form-control" id="form-username">
                    <?php echo form_error('name') ?>
                </div>
            
                <div class="form-group">
                    <label class="sr-only" for="form-username">Surname</label>                        
                    <input type="text" name="surname" placeholder="Surname" class="form-username form-control" id="form-username">
                    <?php echo form_error('surname') ?>
                </div>
            
                <div class="form-group">
                    <label class="sr-only" for="form-username">Resturant name</label>                        
                    <input type="text" name="restaurant_name" placeholder="Resturant name" class="form-username form-control" id="form-username">
                    <?php echo form_error('restaurant_name') ?>
                </div>
            
                <div class="form-group">
                    <label class="sr-only" for="form-username">Email</label>                        
                    <input type="text" name="email" placeholder="Email" class="form-username form-control" id="form-username">
                    <?php echo form_error('email') ?>
                </div>
                
                <div class="form-group">
                    <label class="sr-only" for="form-username">Phone</label>                        
                    <input type="text" name="phone" placeholder="Phone" class="form-username form-control" id="form-username">
                    <?php echo form_error('phone') ?>
                </div>
            
                <div class="form-group">
                    <label class="sr-only" for="form-username">City</label>                        
                    <input type="text" name="city" placeholder="City" class="form-username form-control" id="form-username">
                    <?php echo form_error('city') ?>
                </div>
            
                <div class="form-group">
                    <label class="sr-only" for="form-username">sku</label>                        
                    <input type="text" name="sku" placeholder="SKU" class="form-username form-control" id="form-username">
                    <?php echo form_error('sku') ?>
                </div>
            
                <div class="form-group">
                    <label class="sr-only" for="form-username">Password</label>                        
                    <input type="password" name="password" placeholder="Password" class="form-control" id="">
                    <?php echo form_error('password') ?>
                </div>
            
                <div class="form-group">
                    <label class="sr-only" for="form-username">Comments</label>                        
                    <input type="text" name="comments" placeholder="Comments" class="form-control" id="">
                    <?php echo form_error('comments') ?>
                </div>
            
                <button type="submit" class="btn">Create Account!</button>
            <?php echo form_close(); ?>
        </div>
    </div>
    
    <script type="text/javascript">
        $(document).ready(function(){
            $.backstretch([
                    "<?php echo base_url('/assets/images/backgrounds/2.jpg') ?>"
                    , "<?php echo base_url('/assets/images/backgrounds/3.jpg') ?>"
                    , "<?php echo base_url('/assets/images/backgrounds/1.jpg') ?>"
                   ], {duration: 3000, fade: 750});
        });
    </script>
    
</div>

    
    

