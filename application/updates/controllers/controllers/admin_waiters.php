<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class to deal with all meals related details
 */

class Admin_Waiters extends App_Controller
{
    
    public $oldname;

    public function __construct()
    {
        parent::__construct();
        $this->load->library("lib_log");
        $this->load->model("waiter_model");
        $this->owners_only();
        
    }

    public function index(){
        
        $this->page_title    = 'Admin';
        $this->assets_css    = array( 'dataTables.bootstrap.css' );  
        $this->assets_js     = array( 'jquery.dataTables.js', 'dataTables.bootstrap.js' );  
        
        $this->template->set_layout('admin');  
        
        $waiters             =  $this->waiter_model->get_waiters();
        $this->render_page('admin/waiters/index', array( 'waiters' => $waiters ));
    }
    
    public function new_waiter()
    {
        $this->page_title    = 'Admin';
        $this->assets_css    = array( 'dataTables.bootstrap.css' );  
        $this->assets_js     = array( 'jquery.dataTables.js', 'dataTables.bootstrap.js' );  
        
        $this->template->set_layout('admin');  

        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique['.$this->config->item('SITE_ID').'users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]|max_length[250]|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', 'Repeat password', 'required');
        $this->form_validation->set_rules('waiter_float', 'Float', 'required|xss_clean');

        if ($this->form_validation->run() == true)
        {
            $username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
            $email    = $this->input->post('email');
            $password = $this->input->post('password');
            
            $formSubmit = $this->input->post('submitForm'); 
            
            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'username'   => strtolower($this->input->post('first_name')),
                'waiter_float'  => $this->input->post('waiter_float'),
            );
        }
        if ($this->form_validation->run() == true && $id = $this->ion_auth->register($username, $password, $email, $additional_data, array($this->config->item('waiter_index', 'ion_auth'))))
        {
            if(is_numeric($id))
            {
                $result = $this->waiter_model->create_table_relation($id);
            }
            
            $this->session->set_flashdata('app_success', $this->ion_auth->messages());
            
            if($formSubmit == 'formSaveClose'){
                redirect('admin/waiters');die;
            }
            else if($formSubmit == 'formSaveCloseNew'){
                redirect('admin/waiters/new_waiter');die;
            }
            else {
                redirect( 'admin/waiters/edit_waiter/'.$result );
                die;
            }
        }
        else
        {
            
            if($this->ion_auth->errors()){
                $this->session->set_flashdata('app_error', $this->ion_auth->errors());
                redirect("admin/waiters");
            }
            //display the create user form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            
            $tables              =  $this->waiter_model->tables();
            
            $this->data['tables']                = $tables;
            
            $this->data['first_name'] = array(
                'name'                          => 'first_name',
                'id'                            => 'first_name',
                'type'                          => 'text',
                'class'                         => 'form-control',
                'data-parsley-length'           => '[3, 100]',
                'required'                      => '',
                'value'                         => $this->form_validation->set_value('first_name'),
                'placeholder'                   => 'First Name'
            );
            $this->data['last_name'] = array(
                'name'                          => 'last_name',
                'id'                            => 'last_name',
                'type'                          => 'text',
                'value'                         => $this->form_validation->set_value('last_name'),
                'class'                         => 'form-control',
                'data-parsley-length'           => '[3, 100]',
                'required'                      => '',
                'placeholder'                   => 'Last Name'
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
                'data-parsley-length'           => '[3, 250]',
                'required'                      => '',
                'placeholder'                   => 'Password'
            );
            $this->data['password_confirm'] = array(
                'name'                       => 'password_confirm',
                'id'                         => 'password_confirm',
                'type'                       => 'password',
                'value'                      => $this->form_validation->set_value('password_confirm'),
                'class'                      => 'form-control',
                'placeholder'                => 'Repeat password',
                'data-parsley-equalto'       => '#password',
                'data-parsley-error-message' => 'This must be same as password field!'
            );
            $this->data['waiter_float'] = array(
                'name'                          => 'waiter_float',
                'id'                            => 'waiter_float',
                'type'                          => 'text',
                'value'                         => $this->form_validation->set_value('waiter_float'),
                'class'                         => 'form-control',
                'data-parsley-length'           => '[3, 100]',
                'required'                      => '',
                'placeholder'                   => 'Float'
            );

            $this->render_page('admin/waiters/new', $this->data);
        }
    }
    
    
    function edit_waiter($id)
    {
        if(empty($id))
            show_404();
        
        $this->page_title    = 'Admin';
        $this->assets_css    = array( 'dataTables.bootstrap.css' );  
        $this->assets_js     = array( 'jquery.dataTables.js', 'dataTables.bootstrap.js' );

        $this->load->library('encrypt');
        
        $this->template->set_layout('admin');  
        
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
                redirect('admin/login', 'refresh');
        }

        $user          = $this->ion_auth->user($id)->row();
        if(!$user){
            show_404();
        }
        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
        $this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('waiter_float', 'Float', 'required|xss_clean');

        if (isset($_POST) && !empty($_POST))
        {
            $data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'username'   => strtolower($this->input->post('first_name')),
                'waiter_float'  => $this->input->post('waiter_float'),
            );
            if ($this->input->post('password'))
            {
                $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

                $password = hash_new_password($this->input->post("password"));
                $pass = $this->input->post("password");

                $this->db->where("id", $user->id);
                $this->db->update($this->config->item('SITE_ID')."users", array("password" => $password, "pass" => $pass));
            }
            if ($this->form_validation->run() === TRUE)
            {
                $this->ion_auth->update($user->id, $data);
                
                $result = $this->waiter_model->create_table_relation($id);
            
                $formSubmit = $this->input->post('submitForm'); 
                
                $this->session->set_flashdata('app_success', "Clerk details updated!");
               
                if($formSubmit == 'formSaveClose'){
                    redirect('admin/waiters');die;
                }
                else if($formSubmit == 'formSaveCloseNew'){
                    redirect('admin/waiters/new_waiter');die;
                }
                else {
                    redirect( 'admin/waiters/edit_waiter/'.$id );
                    die;
                }
            }
        }
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        //pass the user to the view
        $this->data['user']   = $user;
        
        $associated_tables              =  $this->waiter_model->select_table_relation($id);
        $tables                         =  $this->waiter_model->tables($id);
        $related_tables                 = array();
        
        foreach ($associated_tables as $at)
        {
            $related_tables[] = $at->id;
        }
        
        $this->data['tables']                = $tables;
        $this->data['associated_tables']     = $related_tables;
        
        $this->data['first_name'] = array(
            'name'  => 'first_name',
            'id'    => 'first_name',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('first_name', $user->first_name),
            'class'                         => 'form-control',
            'data-parsley-length'           => '[3, 100]',
            'required'                      => '',
            'placeholder'                   => 'First Name'
        );
        $this->data['last_name'] = array(
            'name'  => 'last_name',
            'id'    => 'last_name',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('last_name', $user->last_name),
            'class'                         => 'form-control',
            'data-parsley-length'           => '[3, 100]',
            'required'                      => '',
            'placeholder'                   => 'First Name'
        );
        
        $this->data['email'] = array(
            'name'                          => 'email',
            'id'                            => 'email',
            'type'                          => 'text',
            'value'                         => $this->form_validation->set_value('email', $user->email),
            'class'                         => 'form-control',
            'data-parsley-length'           => '[3, 100]',
            'required'                      => '',
            'placeholder'                   => 'Email'
        );
        
        $this->data['password'] = array(
            'name' => 'password',
            'id'   => 'password',
            'type' => 'password',
            'value'                         => $this->form_validation->set_value('password', $user->password),
            'class'=> 'form-control',
            'data-parsley-length'           => '[5, 100]',
            'placeholder'                   => 'New password'
        );
        $this->data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id'   => 'password_confirm',
            'type' => 'password',
            'value'                         => $this->form_validation->set_value('password_confirm', $user->password),
            'class'                      => 'form-control',
            'placeholder'                => 'Repeat password',
            'data-parsley-equalto'       => '#password',
            'data-parsley-error-message' => 'This must be same as password field!',
            'placeholder'                   => 'Repeat new password'
        );
        $this->data['waiter_float'] = array(
            'name'  => 'waiter_float',
            'id'    => 'waiter_float',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('waiter_float', $user->waiter_float),
            'class'                         => 'form-control',
            'data-parsley-length'           => '[3, 100]',
            'required'                      => '',
            'placeholder'                   => 'Float'
        );

        $this->data['id'] = $id;
        $this->data['pass'] = $user->pass;
        
        $this->render_page('admin/waiters/edit_waiter', $this->data);
    }
    
    
    public function delete($id)
    {
        if( empty($id) || !is_numeric($id) )
            show_404 ();
        
        $result = $this->waiter_model->delete($id);
        
        if ($result){
            $this->session->set_flashdata('app_success', 'Clerk removed successfully!');
            redirect(site_url( 'admin/waiters/'));
        }
        else{
            $this->session->set_flashdata('app_error', 'Sorry! Unable to delete Clerk!');
            redirect(site_url( 'admin/waiters/'));
        }
    }

    
}    