
<div class="login_sec">
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li class="active">Login</li>
        </ol>
        <h2>Login</h2>
        <div class="col-md-6 log">			 
            <p>Welcome, please enter the folling to continue.</p>
            <p>If you have previously Login with us, <span>click here</span></p>
            <?php echo form_open('', array('class' => 'form-signin', "id" => "loginform")); ?>
                <h5>User Name:</h5>	
                <?php echo form_input($identity); ?>
                <?php echo form_error('identity') ?>
                <h5>Password:</h5>
                <?php echo form_input($password) ?>
                <?php echo form_error('password') ?>
                <h5><?php echo form_checkbox('remember', '1', FALSE, 'id="remember"') ?> Keep me logged in</h5>
                <input type="submit" value="Login">
                 <a href="#">Forgot Password ?</a>
            <?php echo form_close() ?>				 
        </div>
        <div class="col-md-6 login-right">
            <h3>NEW REGISTRATION</h3>
            <p>By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.</p>
            <a class="acount-btn" href="<?php echo site_url('create-new-user'); ?>">Create an Account</a>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!---->