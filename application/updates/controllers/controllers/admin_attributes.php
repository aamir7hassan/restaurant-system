<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class to deal with all meals related details
 */

class Admin_Attributes extends App_Controller
{
    
    public $oldname;

    public function __construct()
    {
        parent::__construct();
        $this->load->library("lib_log");
        $this->load->model("attributes_model");
        $this->owners_only();
        
    }

    public function index(){
        
        $this->page_title    = 'Admin';
        $this->assets_css    = array( 'dataTables.bootstrap.css' );  
        $this->assets_js     = array( 'jquery.dataTables.js', 'dataTables.bootstrap.js' );  
        
        $this->template->set_layout('admin');  
        
        $attributes          =     $this->attributes_model->get_attributes();
        
        $this->render_page('admin/attributes/index', array( 'attributes' => $attributes ));
    }
    
    
    public function new_attribute($id = ""){
        $this->oldname      = '';
        $title               = 'Create';
        
        if (!empty($id)){
            $title           = 'Update';
            $attribute       = $this->attributes_model->get_attributes($id, 'id');     
            if (!$attribute)
                show_404 ();
        }
            
        $this->oldname = isset($attribute->name) ? $attribute->name : "";
        $this->page_title    = 'Admin';
        $this->assets_css    = array();  
        $this->assets_js     = array( 'parsely-remote.min.js' );  
        $this->template->set_layout('admin');
        $this->form_validation->set_rules('attribute_name', 'Attribute Name', 'trim|required|xss_clean|min_length[3]|callback__unique_name');
        $this->form_validation->set_rules('attribute_id', 'Attribute ID', 'trim|xss_clean|integer');
        $this->form_validation->set_rules('sort', 'Sort order', 'trim|xss_clean|integer');
        $this->form_validation->set_rules('select_option', 'Select option', 'trim|xss_clean|required');
        
        if ($this->form_validation->run() == true){ 
            $formSubmit = $this->input->post('submitForm'); 
            $result = $this->attributes_model->create_new();
            if( $result){
               $this->session->set_flashdata('app_success', 'New attribute '.strtolower($title).'d successfully!' );
                if($formSubmit == 'formSaveClose'){
                    redirect('admin/attributes');die;
                }
                else if($formSubmit == 'formSaveCloseNew'){
                    redirect('admin/attributes/new_attribute');die;
                }
                else{
                    redirect( 'admin/attributes/new_attribute/'.$result );
                    die;
                }
            }
            else{
                $this->session->set_flashdata('app_error', 'Sorry! Unable to '.strtolower($title).' attribute');
                redirect(site_url( 'admin/attributes/new_attribute/'));
            } 
        }
        else{     
            $data = array(
                        "head_title"      => $title,
                        "name"  => array(
                            'name'                          => 'attribute_name',
                            'id'                            => 'attribute_name',
                            'type'                          => 'text',
                            'value'                         => isset($attribute->name) ? $attribute->name : $this->form_validation->set_value('attribute_name'),
                            'class'                         => 'form-control',
                            'placeholder'                   => 'Attribute name',
                            'data-parsley-length'           => '[3, 25]',
                            'required'                      => '',
                            'data-parsley-remote'           => '',
                            'data-parsley-remote-options'   => '{ "type": "POST", "dataType": "jsonp", "data": { "token": "{value}" } }',
                            'data-parsley-remote-validator' => 'validateAttribute',
                            'data-parsley-remote-message'   => 'Attribute already exists!',
                            'parsley-remote-method'         => 'POST'
                        ),
                        "sort"  => array(
                            'name'                          => 'sort',
                            'id'                            => 'sort',
                            'type'                          => 'number',
                            'value'                         => isset($attribute->sort) ? $attribute->sort : $this->form_validation->set_value('sort'),
                            'class'                         => 'form-control',                     
                        ),
                        'id'    => array( 'attribute_id' => $id ),
                        'old'   => array( 'old' => $this->oldname ),
                        'select_option' => isset($attribute->type) ? $attribute->type : $this->form_validation->set_value('select_option'),
                        'mandatory' => isset($attribute->required) ? $attribute->required : $this->form_validation->set_value('required')
                   
                    );
            $data['attributes'] = isset($attribute->values) ? json_decode($attribute->values) : json_decode((object)array());            
        }
        $this->render_page('admin/attributes/new', $data );
    }
    
    
    public function delete($id)
    {
        if( empty($id) || !is_numeric($id) )
            show_404 ();
        
        $result = $this->attributes_model->delete($id);
        
        if ($result){
            $this->session->set_flashdata('app_success', 'Attribute removed successfully!');
            redirect(site_url( 'admin/attributes/'));
        }
        else{
            $this->session->set_flashdata('app_error', 'Sorry! Unable to delete attribute!');
            redirect(site_url( 'admin/attributes/'));
        }
    }

    



    public function exists($name)
    {
        $oldname  = $this->input->post( 'old' );
        
        if (strtolower($name) == strtolower($oldname)) 
        {
            echo 1;
            die;
        }
        
        $result = $this->attributes_model->get_attributes($name, 'name');  
        if ($result === FALSE || count($result) == 0){
            echo 1; die;
        }
        echo 0; die;
    }
    
    
    public function _unique_name($aname) {

        if ($aname == $this->oldname) 
        {
            return true;
        }

        if ($this->attributes_model->get_attributes($aname, 'name')) {

            $this->form_validation->set_message('_unique_name', 'The attribute name must be unique');

            return false;
        }

        return true;
    }

}  