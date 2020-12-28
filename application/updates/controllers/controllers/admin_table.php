<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class to deal with all meals related details
 */

class Admin_Table extends App_Controller
{
    
    public $oldname;

    public function __construct()
    {
        parent::__construct();
        $this->load->library("lib_log");
        $this->load->model("table_model");
        $this->owners_only();
        
    }

    public function index(){
        
        $this->page_title    = 'Admin';
        $this->assets_css    = array( 'dataTables.bootstrap.css' );  
        $this->assets_js     = array( 'jquery.dataTables.js', 'dataTables.bootstrap.js' );  
        
        $this->template->set_layout('admin');  
        
        $tables              =  $this->table_model->get_tables();
        $this->render_page('admin/tables/index', array( 'tables' => $tables ));
    }
    
    
    public function new_table($id = ""){
        
        $this->oldname = '';
        
        $title               = 'Create';
        
        if (!empty($id))
        {
            $title           = 'Update';
            $table           = $this->table_model->get_tables($id, 'id');          
            
            if (!$table)
                show_404 ();
        }
            
        $this->oldname = isset($table->name) ? $table->name : "";
        
        $this->page_title    = 'Admin';
        $this->assets_css    = array();  
        $this->assets_js     = array( 'parsely-remote.min.js' );  
        
        $this->template->set_layout('admin');
        
        $this->form_validation->set_rules('name', 'Location Number', 'trim|required|xss_clean|callback__unique_name');
        $this->form_validation->set_rules('seats', 'Seats', 'trim|xss_clean|integer|required');
        
        if ($this->form_validation->run() == true)
        { 
            $formSubmit = $this->input->post('submitForm'); 
            
            $result = $this->table_model->create_new($id);
            if( $result)
            {
               $this->session->set_flashdata('app_success', 'New location '.strtolower($title).'d successfully!' );
               
               if($formSubmit == 'formSaveClose'){
                    redirect('admin/table');die;
                }
                else if($formSubmit == 'formSaveCloseNew'){
                    redirect('admin/table/new_table');die;
                }
                else {
                    redirect( 'admin/table/new_table/'.$result );
                    die;
                }
               
            }
            else
            {
                $this->session->set_flashdata('app_error', 'Sorry! Unable to '.strtolower($title).' location');
                redirect(site_url( 'admin/table/new_table/'));
            }
            
        }
        else
        {          
            
            $data = array(
                
                        "head_title"      => $title,
                        "unique"          => isset($table->unique) ? $table->unique : '', 
                        "table_id"        => $id,
                        "name"  => array(
                            'name'                          => 'name',
                            'id'                            => 'name',
                            'type'                          => 'text',
                            'value'                         => isset($table->name) ? $table->name : $this->form_validation->set_value('name'),
                            'class'                         => 'form-control',
                            'placeholder'                   => 'Location Name',
                            'data-parsley-length'           => '[1, 100]',
                            'required'                      => '',
                            'data-parsley-remote'           => '',
                            'data-parsley-remote-options'   => '{ "type": "POST", "dataType": "jsonp", "data": { "token": "{value}" } }',
                            'data-parsley-remote-validator' => 'validateTable',
                            'data-parsley-remote-message'   => 'Location already exists!',
                            'parsley-remote-method'         => 'POST'
                        ),
                
                        "seats"  => array(
                            'name'                          => 'seats',
                            'id'                            => 'seats',
                            'type'                          => 'text',
                            'value'                         => isset($table->seats) ? $table->seats : $this->form_validation->set_value('seats'),
                            'class'                         => 'form-control',
                            'placeholder'                   => 'Number of seats',
                            'required'                      => '',
                            'data-parsley-type'             => 'number'
                        ),
                
                        'id'    => array( 'table_id' => $id ),
                        'old'   => array( 'old' => $this->oldname )
                    );
        }
        $this->render_page('admin/tables/new', $data );
    }
    
    public function update_qr($id=NULL){
        if(empty($id) || !is_numeric($id))
            show_404 ();
        $result = $this->table_model->update_qr($id);
        
        if ($result){
            $this->session->set_flashdata('app_success', 'QR code updated!');
            redirect(site_url( 'admin/table/new_table/'.$id));
        }
        else{
            $this->session->set_flashdata('app_error', 'Sorry! Unable to update qr code!');
            redirect(site_url( 'admin/table/new_table/'.$id));
        }
        
    }

    public function delete($id)
    {
        if( empty($id) || !is_numeric($id) )
            show_404 ();
        
        $result = $this->table_model->delete($id);
        
        if ($result){
            $this->session->set_flashdata('app_success', 'Location removed successfully!');
            redirect(site_url( 'admin/table/'));
        }
        else{
            $this->session->set_flashdata('app_error', 'Sorry! Unable to delete location!');
            redirect(site_url( 'admin/table/'));
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
        
        $result = $this->table_model->get_tables($name, 'name');  
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

        if ($this->table_model->get_tables($aname, 'name')) {

            $this->form_validation->set_message('_unique_name', 'The location name must be unique');

            return false;
        }

        return true;
    }
    
}    