<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class to deal with all product related details
 */

class Admin_Categories extends App_Controller
{
    
    public $oldname;

    public function __construct()
    {
        parent::__construct();
        $this->load->library("lib_log");
        $this->load->model("meals_model");
        $this->owners_only();
        
    }

    /**
     * @method type meals(type $paramName) Meals page
     */
    
    public function index($id = '')
    {
        $this->page_title    = 'Admin';
        $this->assets_css    = array( 'dataTables.bootstrap.css' );  
        $this->assets_js     = array( 'jquery.dataTables.js', 'dataTables.bootstrap.js' );  
        
        $this->template->set_layout('admin'); 
        
        $categories          =     $this->meals_model->get_categories();
        
        $this->render_page('admin/meals/index', array('categories' => $categories));
    }
    
    public function new_category($id = ""){
        
        $this->oldname = '';
        
        $title               = 'Create';
                
        if (!empty($id))
        {
            $title           = 'Update';
            $category        = $this->meals_model->get_categories($id, 'id');          
            
            if (!$category)
                show_404 ();
            
            $this->oldname = $category->cname;
        }
            
        
        $this->page_title    = 'Admin';
        $this->assets_css    = array();  
        $this->assets_js     = array( 'parsely-remote.min.js' );  
        
        $this->template->set_layout('admin');
        
        $this->form_validation->set_rules('category_name', 'Category Name', 'trim|required|xss_clean|min_length[3]|callback__unique_name');
        $this->form_validation->set_rules('category_id', 'Category ID', 'trim|xss_clean|integer');
        $this->form_validation->set_rules('sort', 'Sort order', 'trim|xss_clean|integer');
        
        if ($this->form_validation->run() == true)
        { 
            $formSubmit = $this->input->post('submitForm'); 
            
            $result = $this->meals_model->create_new();
            if( $result)
            {
                $this->session->set_flashdata('app_success', 'New category '.strtolower($title).'d successfully!' );
               
                if($formSubmit == 'formSaveClose'){
                    redirect('admin/categories');die;
                }
                else if($formSubmit == 'formSaveCloseNew'){
                    redirect('admin/categories/new_category');die;
                }
                else {
                    redirect( 'admin/categories/new_category/'.$result );
                    die;
                }
            }
            else
            {
                $this->session->set_flashdata('app_error', 'Sorry! Unable to '.strtolower($title).' category');
                redirect(site_url( 'admin/categories/new_category/'));
            }
            
        }
        else
        {          
            

            $data = array(
                "head_title"                        => $title,
                "name"  => array(
                    'name'                          => 'category_name',
                    'id'                            => 'category_name',
                    'type'                          => 'text',
                    'value'                         => isset($category->cname) ? $category->cname : $this->form_validation->set_value('category_name'),
                    'class'                         => 'form-control',
                    'placeholder'                   => 'Category name',
                    'data-parsley-length'           => '[3, 25]',
                    'required'                      => '',
                    'data-parsley-remote'           => '',
                    'data-parsley-remote-options'   => '{ "type": "POST", "dataType": "jsonp", "data": { "token": "{value}" } }',
                    'data-parsley-remote-validator' => 'validateCategory',
                    'data-parsley-remote-message'   => 'Category already exists!'                            
                ),
                "sort"  => array(
                    'name'                          => 'sort',
                    'id'                            => 'sort',
                    'type'                          => 'number',
                    'value'                         => isset($category->sort) ? $category->sort : $this->form_validation->set_value('sort'),
                    'class'                         => 'form-control',                     
                ),
                'id'                                => array( 'category_id' => $id ),
                'old'   => array( 'old' => $this->oldname ),
				"quantity"  => array(
                    'name'                          => 'quantity',
                    'id'                            => 'quantity',
                    'type'                          => 'number',
                    'value'                         => isset($category->quantity) ? $category->quantity : $this->form_validation->set_value('quantity'),
                    'class'                         => 'form-control',                     
                ),
            );
        }
        $this->render_page('admin/meals/new_category', $data );
    }
    
    public function export(){
        $categories = $this->meals_model->export_categories();
        $this->array_to_csv_download($categories);
        exit;
    }
    
    public function import(){
        $this->page_title    = 'Admin';
        $this->assets_css    = array();  
        $this->assets_js     = array( 'parsely-remote.min.js' );          
        $this->template->set_layout('admin');        
        $this->form_validation->set_rules('csv', 'Import file', 'trim');
        if ($this->form_validation->run() == true){
            $data = array();
            if($_FILES['csv']['error'] == 0){
                $name    = $_FILES['csv']['name'];
                $ext     = strtolower(end(explode('.', $_FILES['csv']['name'])));
                $type    = $_FILES['csv']['type'];
                $tmpName = $_FILES['csv']['tmp_name'];
                if($ext === 'csv'){
                    $data    = array_map('str_getcsv', file($tmpName));
                    $this->meals_model->update_categories($data);
                    $this->session->set_flashdata('app_success', 'Categories imported successfully!');
                    redirect('admin/categories');
                }
                else{
                    $this->session->set_flashdata('app_error', 'Sorry! Only csv files are allowed');
                    redirect('admin/categories/import');
                }
            }
            else{
                $this->session->set_flashdata('app_error', 'Sorry! Only csv files are allowed');
                redirect('admin/categories/import');
            }
        }
        
        $this->render_page('admin/import/index', array() );
    }
    
    private function  array_to_csv_download($array, $filename = "export.csv", $delimiter=";") {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'";');
        $f = fopen('php://output', 'w');
        foreach ($array as $line) {
            fputcsv($f, array($line->name), $delimiter);
        }
    } 
    
}