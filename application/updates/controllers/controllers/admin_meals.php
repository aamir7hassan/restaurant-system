<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class to deal with all product related details
 */

class Admin_Meals extends App_Controller
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
    
    /**
     * @method type new_categorys(type $paramName) Category create page
     */
    
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
                    redirect('admin/meals');die;
                }
                else if($formSubmit == 'formSaveCloseNew'){
                    redirect('admin/meals/new_category');die;
                }
                else {
                    redirect( 'admin/meals/new_category/'.$result );
                    die;
                }
            }
            else
            {
                $this->session->set_flashdata('app_error', 'Sorry! Unable to '.strtolower($title).' category');
                redirect(site_url( 'admin/meals/new_category/'));
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
                'old'   => array( 'old' => $this->oldname )
            );
        }
        $this->render_page('admin/meals/new_category', $data );
    }
    
    
        /**
     * @method type available(type $paramName) meals available
     */
    
    public function available($id = ""){
        
        $this->page_title    = 'Admin';
        $this->assets_css    = array( 'dataTables.bootstrap.css' );  
        $this->assets_js     = array( 'jquery.dataTables.js', 'dataTables.bootstrap.js' );  
        
        $this->template->set_layout('admin'); 
        
        $meals               =     $this->meals_model->get_meals();
        
        $this->render_page('admin/meals/meals', array('meals' => $meals));
    }
    
    
    public function new_meal($id = ''){
        $this->oldname = ''; $cid = '';
        $title               = 'Create';
                
        if (!empty($id)){
            $title           = 'Update';
            $meals        = $this->meals_model->get_meals('m.id', $id);          
            $checked      = $this->meals_model->get_checked($id);
            if (!$meals)
                show_404 ();
            $this->oldname = $meals->name;
        }
        $attributes = $this->meals_model->get_attributes();       
        $this->page_title    = 'Admin';
        $this->assets_css    = array();  
        $this->assets_js     = array( 'ckeditor.js', 'parselyjs.js' );
        $this->template->set_layout('admin');
        $this->form_validation->set_rules('name', 'Product Name', 'trim|required|xss_clean|min_length[3]|max_length[150]');
        $this->form_validation->set_rules('description', 'Description', 'trim|xss_clean|min_length[5]');
        $this->form_validation->set_rules('price', 'Price', 'trim|xss_clean|required|number');
        $this->form_validation->set_rules('quantity', 'Quantity', 'trim|xss_clean|required|number');
        $this->form_validation->set_rules('category', 'Category', 'trim|xss_clean|required|integer');
        $this->form_validation->set_rules('sort', 'Sort order', 'trim|xss_clean|required|integer');
        $this->form_validation->set_rules('special', 'Special', 'trim|xss_clean|integer');
        $this->form_validation->set_rules('special_from_hour', 'Special from hours', 'trim|xss_clean|integer');
        $this->form_validation->set_rules('special_from_minutes', 'Special from minutes', 'trim|xss_clean|integer');
        $this->form_validation->set_rules('special_to_hour', 'Special to hours', 'trim|xss_clean|integer');
        $this->form_validation->set_rules('special_to_minutes', 'Special to minutes', 'trim|xss_clean|integer');
        
        if ($this->form_validation->run() == true){ 
            $formSubmit = $this->input->post('submitForm'); 
            $result = $this->meals_model->create_meal($id);
            if( $result){
               $this->session->set_flashdata('app_success', 'Product '.strtolower($title).'d successfully!' );
               if($formSubmit == 'formSaveClose'){
                    redirect('admin/meals/available');die;
                }
                else if($formSubmit == 'formSaveCloseNew'){
                    redirect('admin/meals/new_meal/');die;
                }
                else {
                    redirect( 'admin/meals/new_meal/'.$result );
                    die;
                }
            }
            else{
                $this->session->set_flashdata('app_error', 'Sorry! Unable to '.strtolower($title).' product');
                redirect(site_url( 'admin/meals/new_meal/'));
            }
        }
        else{   
            $cats = $attrs = array('' => '--SELECT--');
            $categories = $this->meals_model->get_categories();
            if(is_array($categories) && count($categories) > 0):
                foreach ($categories as $category){
                    $cats[$category->cid]   = $category->cname;
                }
            endif;
//            foreach ($attributes as $attribute){
//                $attrs[$attribute->cid]   = $attribute->name;
//            }
            $data = array(
                
                "head_title"                        => $title,
                "special"                           => isset($meals->special) ? $meals->special : $this->form_validation->set_value('special'),
                "name"  => array(
                    'name'                          => 'name',
                    'id'                            => 'category_name',
                    'type'                          => 'text',
                    'value'                         => isset($meals->name) ? $meals->name : $this->form_validation->set_value('name'),
                    'class'                         => 'form-control',
                    'placeholder'                   => 'Product name',
                    'data-parsley-length'           => '[3, 150]',
                    'required'                      => '',
                ),
                "description"  => array(
                    'name'        => 'description',
                    'id'          => 'description',
                    'value'       => isset($meals->description) ? $meals->description : $this->form_validation->set_value('description'),
                    'rows'        => '5',
                    'cols'        => '10'
                ),
                "price"  => array(
                    'name'                          => 'price',
                    'type'                          => 'text',
                    'value'                         => isset($meals->price) ? $meals->price : $this->form_validation->set_value('price'),
                    'class'                         => 'form-control',
                    'placeholder'                   => 'Product price:',
                    'required'                      => '',
                    'data-parsley-type'             => 'number' 
                ),
                "quantity"  => array(
                    'name'                          => 'quantity',
                    'type'                          => 'text',
                    'value'                         => isset($meals->quantity) ? $meals->quantity : $this->form_validation->set_value('quantity'),
                    'class'                         => 'form-control',
                    'placeholder'                   => 'Product quantity:',
                    'required'                      => '',
                    'data-parsley-type'             => 'number' 
                ),
                "sort"  => array(
                    'name'                          => 'sort',
                    'id'                            => 'sort',
                    'type'                          => 'number',
                    'value'                         => isset($meals->sort) ? $meals->sort : $this->form_validation->set_value('sort'),
                    'class'                         => 'form-control',                     
                ),
                'categories'                        => $cats,
//                'attributes'                        => $attrs,
                'id'                                => array( 'meal_id' => $id ),
                'old'                               => array( 'old' => $this->oldname ),
                'meal_id'                           => $id,
                'cid'                               => isset($meals->cid) ? $meals->cid : "",
                'attributes'                        => $attributes,
                'checked'                           => isset($checked) ? $checked : array(),
                'takeaway'                          => isset($meals->take_away) ? $meals->take_away : $this->form_validation->set_value('takeaway'),   
                'special_days'                      => json_decode($meals->special_days),
                'special_from'                      => $meals->special_from,
                'special_to'                        => $meals->special_to
            );
        }
        $this->render_page('admin/meals/new_meal', $data );    
    }
    
    public function status($meal_id="") {
        if(!is_numeric($meal_id))
            show_404 ();
        
        $this->meals_model->update_status($meal_id);
        redirect('admin/meals/available');
    }
    
    public function stock($meal_id="") {
        if(!is_numeric($meal_id))
            show_404 ();
        
        $this->meals_model->update_stock($meal_id);
        redirect('admin/meals/available');
    }
    
    public function attributes($id = '', $at_id = '')
    {
        if (empty($id))
            show_404 ();
        
        if (!empty($at_id))
        {
            $title               = 'Update';
            $update_attr         = $this->meals_model->get_meal_attributes($id, $at_id);         
            
            if(!$update_attr)
                show_404 ();
        }
        
        
        $title               = 'Create';
        
        $meal   = $this->meals_model->check_meal( $id );  
        if (!$meal)
            show_404 ();
        
        $this->page_title    = 'Admin';

        $this->assets_css    = array( 'dataTables.bootstrap.css' );  
        $this->assets_js     = array( 'jquery.dataTables.js', 'dataTables.bootstrap.js', 'parselyjs.js' );
        
        $this->template->set_layout('admin');
        
        $this->form_validation->set_rules('attributes', 'Attribute', 'trim|required|xss_clean|integer');
        $this->form_validation->set_rules('value', 'Value', 'trim|xss_clean|required');
        
        if ($this->form_validation->run() == true)
        { 
            $result = $this->meals_model->create_attribute($id);
            if( $result)
            {
               $this->session->set_flashdata('app_success', 'Product '.strtolower($title).'d successfully!' );
               redirect( site_url( 'admin/meals/attributes/'.$id ) ); 
            }
            else
            {
                $this->session->set_flashdata('app_error', 'Sorry! Unable to '.strtolower($title).' product');
                redirect(site_url( 'admin/meals/attributes/'.$id));
            }
            
        }
        else
        {   
            $existing   = $this->meals_model->get_existing_attributes($id);          
            
            $attrs      = array('' => '--SELECT--');
            
            $attributes = $this->meals_model->get_attributes();            
            
            foreach ($attributes as $attribute){
                $attrs[$attribute->id]   = $attribute->name;
            }
//            foreach ($attributes as $attribute){
//                $attrs[$attribute->cid]   = $attribute->name;
//            }
            
            
            $data = array(
                
                "head_title"                        => $title,                
                "value"  => array(
                    'name'                          => 'value',
                    'type'                          => 'text',
                    'value'                         => isset($update_attr->value) ? $update_attr->value : $this->form_validation->set_value('value'),
                    'class'                         => 'form-control',
                    'placeholder'                   => 'Attribute value',
                    'required'                      => '',
                ),
                'existing'                          => $existing,
                'attributes'                        => $attrs,
//                'attributes'                        => $attrs,
                'meal_id'                           => $id,
                'id'                                => array( 'meal_id' => $id ),
                'at_id'                             => $update_attr->id
            );
        }
        $this->render_page('admin/meals/attributes', $data );    
    }
    

    /**
     * @method exists
     * @param type $name
     */
    
    public function exists($name)
    {
        $oldname  = $this->input->post( 'old' );
        
        if (strtolower($name) == strtolower($oldname)) 
        {
            echo 1;
            die;
        }
        
        $result = $this->meals_model->get_categories($name, 'name');  
        if ($result === FALSE || count($result) == 0){
            echo 1; die;
        }
        echo 0; die;
    }
    
    /**
     * @method __unique
     * @param type $aname
     * @return boolean
     */
    
    public function _unique_name($aname) {

        if ($aname == $this->oldname) 
        {
            return true;
        }

        if ($this->meals_model->get_categories($aname, 'name')) {

            $this->form_validation->set_message('_unique_name', 'The category name must be unique');

            return false;
        }

        return true;
    }
    
    /**
     * 
     * @param type $id
     */
    
    public function delete($id)
    {
        if( empty($id) || !is_numeric($id) )
            show_404 ();
        
        $result = $this->meals_model->delete($id);
        
        if ($result){
            $this->session->set_flashdata('app_success', 'Category removed successfully!');
            redirect(site_url( 'admin/meals/'));
        }
        else{
            $this->session->set_flashdata('app_error', 'Sorry! Unable to delete category!');
            redirect(site_url( 'admin/meals/'));
        }
    }
    
    
    /**
     * 
     * @param type $id
     */
    
    public function delete_meal($id)
    {
        if( empty($id) || !is_numeric($id) )
            show_404 ();
        
        $result = $this->meals_model->delete_meal($id);
        
        if ($result){
            $this->session->set_flashdata('app_success', 'Product removed successfully!');
            redirect(site_url( 'admin/meals/available'));
        }
        else{
            $this->session->set_flashdata('app_error', 'Sorry! Unable to delete product!');
            redirect(site_url( 'admin/meals/available'));
        }
    }
    
    public function delete_attribute($id, $attr_id)
    {
        if (empty($id) || empty($attr_id))
            show_404 ();
        
        $result = $this->meals_model->remove_attributes($id, $attr_id);
        if ($result){
            $this->session->set_flashdata('app_success', 'Attribute removed successfully!');
            redirect(site_url( 'admin/meals/attributes/'.$id));
        }
        else{
            $this->session->set_flashdata('app_error', 'Sorry! Unable to delete attribute!');
            redirect(site_url( 'admin/meals/attributes/'.$id));
        }
    }
    
}    
    