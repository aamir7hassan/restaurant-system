<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class to deal with all meals related details
 */

class Admin_Foods extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("lib_log");
        $this->load->model("foods_model");
        $this->owners_only(); 
    }
    
    public function index(){
        $this->page_title    = 'Admin';
        $this->assets_css    = array( 'dataTables.bootstrap.css' );  
        $this->assets_js     = array( 'jquery.dataTables.js', 'dataTables.bootstrap.js' );  
        
        $this->template->set_layout('admin');  
        
        $foods          =     $this->foods_model->get_foods();
        
        $this->render_page('admin/foods/index', array( 'foods' => $foods ));
    }
    
    public function new_food($id=''){
        $this->page_title    = 'Admin';
        $this->assets_css    = array();  
        $this->assets_js     = array();  
        $title               = 'Add';
        $food                = (object)array();
        if(is_numeric($id)){
            $food = $this->foods_model->get_foods($id);
            if(!$food)
                show_404 ();
            $title = 'Update';
        }
        
        $this->form_validation->set_rules('food_name', 'Food Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('id', 'Food ID', 'trim|xss_clean|integer');
    
        if ($this->form_validation->run() == true){ 
            $formSubmit = $this->input->post('submitForm'); 
            $result = $this->foods_model->update($id);
            if( $result){
               $this->session->set_flashdata('app_success', 'New food type '.strtolower($title).'d successfully!' );
                if($formSubmit == 'formSaveClose'){
                    redirect('admin/foods');die;
                }
                else if($formSubmit == 'formSaveCloseNew'){
                    redirect('admin/foods/new_food');die;
                }
                else{
                    redirect( 'admin/foods/new_food/'.$id );
                    die;
                }
            }
            else{
                $this->session->set_flashdata('app_error', 'Sorry! Unable to '.strtolower($title).' food type');
                redirect(site_url( 'admin/foods/new_food/'));
            } 
        }
        $data = array(
            'food'      => $food,
            'title'     => $title,
        );
        $this->template->set_layout('admin');  
        $this->render_page('admin/foods/new_food', $data);
    }
    
    public function delete($id){
        $result = $this->foods_model->remove_food($id);
        if ($result){
            $this->session->set_flashdata('app_success', 'Food type removed successfully!');
            redirect(site_url( 'admin/foods/'));
        }
        else{
            $this->session->set_flashdata('app_error', 'Sorry! Unable to delete food type!');
            redirect(site_url( 'admin/foods/'));
        }
    }
}