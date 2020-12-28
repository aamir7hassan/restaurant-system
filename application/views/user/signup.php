<!---->
<div class="container">
    <ol class="breadcrumb">
        <li><a href="index.html">Home</a></li>
        <li class="active">Account</li>
    </ol>
    <div class="registration">
	<div class="registration_left">
            <h2>new user? <span> create an account </span></h2>
            <!-- [if IE] 
                   < link rel='stylesheet' type='text/css' href='ie.css'/>  
            [endif] -->  

            <!-- [if lt IE 7]>  
                   < link rel='stylesheet' type='text/css' href='ie6.css'/>  
            <! [endif] -->  
            <script>
                   (function() {

                   // Create input element for testing
                   var inputs = document.createElement('input');

                   // Create the supports object
                   var supports = {};

                   supports.autofocus   = 'autofocus' in inputs;
                   supports.required    = 'required' in inputs;
                   supports.placeholder = 'placeholder' in inputs;

                   // Fallback for autofocus attribute
                   if(!supports.autofocus) {

                   }

                   // Fallback for required attribute
                   if(!supports.required) {

                   }

                   // Fallback for placeholder attribute
                   if(!supports.placeholder) {

                   }

                   // Change text inside send button on submit
                   var send = document.getElementById('register-submit');
                   if(send) {
                           send.onclick = function () {
                                   this.innerHTML = '...Sending';
                           }
                   }

            })();
            </script>
            <div class="registration_form"> 
			 <!-- Form -->
                <?php echo form_open('create-new-user', array('class' => 'form-signin', "id" => "signup", 'data-parsley-validate' => '')); ?>
                    <div>
                        <label>
                            <h5>Title<span class="error">*</span>:</h5>
                            <?php echo form_dropdown( "title", $title, $this->form_validation->set_value('title'), 'class="textfield"' ) ?>
                            <?php echo form_error('title') ?>
                        </label>
                    </div>
                    <div>
                        <label>
                            <h5>First Name<span class="error">*</span>:</h5>
                            <?php echo form_input($name) ?>
                            <?php echo form_error('name') ?>
                        </label>
                    </div>
                    <div>
                        <label>
                            <h5>Last Name<span class="error">*</span>:</h5>
                            <?php echo form_input($surname) ?>
                            <?php echo form_error('surname') ?>
                        </label>
                    </div>
                    <div>
                        <label>
                            <h5>User Name<span class="error">*</span>:</h5>
                            <?php echo form_input($username) ?>
                            <?php echo form_error('username') ?>
                        </label>
                    </div>
                    <div>
                        <label>
                            <h5>Email<span class="error">*</span>:</h5>
                            <?php echo form_input($email) ?>
                            <?php echo form_error('email') ?>
                        </label>
                    </div>
                    <div>
                        <label>
                            <h5>Phone:</h5>
                            <?php echo form_input($phone) ?>
                            <?php echo form_error('phone') ?>
                        </label>
                    </div>
                    <div>
                        <label>
                            <h5>Password<span class="error">*</span>:</h5>
                            <?php echo form_input($password) ?>
                            <?php echo form_error('password') ?> 
                        </label>
                    </div>    
                    <div>
                        <label>
                            <h5>Repeat Password<span class="error">*</span>:</h5>
                            <?php echo form_input($re_password) ?>
                            <?php echo form_error('re_password') ?>
                        </label>
                    </div>     
                    <div>
                        <label>
                            <?php echo $cap_img; ?>
                            <h5>Captcha Code<span class="error">*</span>:</h5>
                           <?php echo form_input($captcha) ?>
                           <?php echo form_error('captcha') ?>
                        </label>
                    </div>     
                         
                    <!--     
                    <div class="sky_form1">
                        <ul>
                            <li><label class="radio left"><input type="radio" name="radio" checked=""><i></i>Male</label></li>
                            <li><label class="radio"><input type="radio" name="radio"><i></i>Female</label></li>
                            <div class="clearfix"></div>
                        </ul>
                    </div>	
                    -->						
                    <div>
                        <input type="submit" value="create an account" id="register-submit">
                    </div>
                    <div class="sky-form">
                        <label class="checkbox"><input type="checkbox" name="terms" required="" data-parsley-error-message="Please agree our policies to continue!"><i></i>i agree to poscommerce.com &nbsp;<a class="terms" href="#"> terms of service</a> </label>
                    </div>
                <?php echo form_close(); ?>
				<!-- /Form -->
            </div>
        </div>
        <div class="registration_left">
            <h2>existing user</h2>
            <div class="registration_form">
            <!-- Form -->
                <?php echo form_open('login', array('class' => 'form-signin', "id" => "loginform", 'data-parsley-validate' => '')); ?>
                    <div>
                        <label>
                            <h5>Email:</h5>
                            <input placeholder="Email" type="email" tabindex="3" name="identity" required>
                        </label>
                    </div>
                    <div>
                        <label>
                            <h5>Password:</h5>
                            <input placeholder="Password" type="password" tabindex="4" name="password" required>
                        </label>
                    </div>						
                    <div>
                        <input type="submit" value="sign in" id="register-submit">
                    </div>
                    <div class="forget">
                        <a href="#">forgot your password</a>
                    </div>
                </form>
            <!-- /Form -->
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<script type="text/javascript">
    
    $('#signup').parsley();
    
    window.Parsley.addAsyncValidator('validateEmail', function (xhr) {
        console.log(this.$element); // jQuery Object[ input[name="q"] ]

        return 404 === xhr.status;
    }, '<?php echo site_url('user/exist/{value}');?>' );


</script>
<!-- end registration -->