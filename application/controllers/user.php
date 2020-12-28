<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        redirect('user/login');
    }
    /*
     * @method login for user login
     */
    public function login($path = '')
    {  
        $this->template->set_layout('login');  
        
        $this->body_class[]     = 'login';
        $this->page_title       = 'Please sign in';
        $this->current_section  = 'login';

        $this->form_validation->set_rules('identity', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true)
        { 
            // check to see if the user is logging in
            // check for "remember me"
            $remember = (bool) $this->input->post('remember');
            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
            { 
                //$this->session->set_flashdata('app_success', $this->ion_auth->messages());
                if (empty($path))
                    redirect('admin');
                else
                    redirect ($path);
            }
            else
            { 
                $this->session->set_flashdata('app_error', $this->ion_auth->errors());
                redirect('user/login');
            }
        }
        else
        {  
            // the user is not logging in so display the login page
            // set the flash data error message if there is one
            $data['message']  = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $data['identity'] = array('name' => 'identity',
                'id'          => 'identity',
                'type'        => 'email',
                'value'       => $this->form_validation->set_value('identity'),
                'class'       => 'textfield',
                'placeholder' => 'Username',
                'required'    => ''
            );
            $data['password'] = array('name' => 'password',
                'id'          => 'password',
                'type'        => 'password',
                'class'       => 'textfield',
                'placeholder' => 'Password',
                'required'    => ''  
            );

            $this->render_page('user/login', $data);
        }
    }
    public function logout()
    {
        // log the user out
        $logout = $this->ion_auth->logout();
        $this->session->set_flashdata('app_success', 'You have logged out successfully!');
        // redirect them back to the login page
        redirect('user/login');
    }
    
    public function signup()
    {
        
        $this->template->set_layout('normal');  
        
        $this->page_title    = 'Signup';
        $this->assets_css    = array( 'bootstrap.css', 'waiter.css' ); 
        $this->assets_js     = array( 'parselyjs.js' );  

        
        
        $this->form_validation->set_rules('first_name', 'Name', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique['.$this->config->item('SITE_ID').'users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]|max_length[50]');

        if ($this->form_validation->run() == true)
        {
            $username = $this->input->post('first_name');
            $email    = $this->input->post('email');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
            );
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, array($this->config->item('customer_index', 'ion_auth'))))
        {
            $this->session->set_flashdata('app_success', $this->ion_auth->messages());
            redirect("user/signup");
        }
        else
        {
            
            if($this->ion_auth->errors()){
                
                $error_data = str_replace($this->ion_auth->errors(), 'The email field must contain an unique value', 'This email is already taken');
                
                
                $this->session->set_flashdata('app_error', $error_data);
                redirect("user/signup");
            }
            //display the create user form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = array(
                'name'                          => 'first_name',
                'id'                            => 'first_name',
                'type'                          => 'text',
                'class'                         => 'form-control',
                'data-parsley-length'           => '[3, 100]',
                'required'                      => '',
                'value'                         => $this->form_validation->set_value('first_name'),
                'placeholder'                   => 'Name'
            );
            $this->data['email'] = array(
                'name'                          => 'email',
                'id'                            => 'email',
                'type'                          => 'text',
                'value'                         => $this->form_validation->set_value('email'),
                'class'                         => 'form-control',
                'data-parsley-length'           => '[3, 100]',
                'required'                      => '',
                'placeholder'                   => 'Email'
            );

            $this->data['password'] = array(
                'name'  => 'password',
                'id'    => 'password',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password'),
                'class'                         => 'form-control',
                'data-parsley-length'           => '[3, 100]',
                'required'                      => '',
                'placeholder'                   => 'Password'
            );
           
            $this->render_page('user/signin', $this->data);
        }
    }
}

