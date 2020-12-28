<?php defined('BASEPATH') OR exit('No direct script access allowed');

 require FCPATH . 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


/**
 * Class to deal with all product related details
 */

class Admin_Reports extends App_Controller
{
    
    public $oldname;

    public function __construct()
    {
        parent::__construct();
        $this->load->library("lib_log");
        $this->load->model("reports_model");
        
        $this->owners_only();
    }

    public function waiter_sales($id = '', $date = '')
    {
        $this->page_title    = 'Waiters Sales';
        $this->assets_css    = array( 'dataTables.bootstrap.css' );  
        $this->assets_js     = array( 'jquery.dataTables.js', 'dataTables.bootstrap.js' );  
        
        $this->template->set_layout('reports');

        $waiter_sales          = $this->reports_model->get_waiter_details();  
        $waiter_sales_groups          = $this->reports_model->get_waiter_sale_group();   
        $get_cash_up = $this->reports_model->get_cash_up();   

        $all_array = array('current' => 'waiter_sales', 'waiter_sales_groups' => $waiter_sales_groups, 'waiter_sales' => $waiter_sales, 'get_cash_up' => $get_cash_up, 'get_total_tip' => $get_total_tip, 'get_total_price' => $get_total_price);

        $this->render_page('admin/reports/waiter_sales', $all_array);         
    }

    public function waiter_sale($id)
    {
        $this->page_title    = 'Waiter Sales';
        $this->assets_css    = array( 'dataTables.bootstrap.css' );  
        $this->assets_js     = array( 'jquery.dataTables.js', 'dataTables.bootstrap.js' );  
        
        $this->template->set_layout('reports');

        $shift_data = $this->reports_model->get_waiter_sale($id);
        $this->render_page('admin/reports/waiter_sale', array('shift_data' => $shift_data));          
    }

    public function cash_up($id)
    {
        $this->page_title    = 'Cash Up';
        $this->assets_css    = array( 'dataTables.bootstrap.css' );  
        $this->assets_js     = array( 'jquery.dataTables.js', 'dataTables.bootstrap.js' );  
        
        $this->template->set_layout('reports');
        
        $cash_ups = $this->reports_model->cash_up($id); 
        $get_total_tip = $this->reports_model->get_total_tip($id);
        $get_total_price = $this->reports_model->get_total_price($id);
        $get_float = $this->reports_model->get_float($id); 
        $get_cash_payment = $this->reports_model->get_cash_payment($id);

        $this->render_page('admin/reports/cash_up', array('cash_ups' => $cash_ups, 'get_total_tip' => $get_total_tip, 'get_total_price' => $get_total_price, 'get_float' => $get_float, 'get_cash_payment' => $get_cash_payment));        
    }

    public function product_sales($id = '', $date = '')
    {
        $this->page_title    = 'Products Sales';
        $this->assets_css    = array( 'dataTables.bootstrap.css' );  
        $this->assets_js     = array( 'jquery.dataTables.js', 'dataTables.bootstrap.js' );  
        
        $this->template->set_layout('reports');

        $get_product_sale_group = $this->reports_model->get_product_sale_group();  
        $get_product_sales = $this->reports_model->get_product_sales();   

        $this->render_page('admin/reports/product_sales', array('current' => 'product_sales', 'get_product_sale_group' => $get_product_sale_group, 'get_product_sales' => $get_product_sales));        
    }

    public function product_sale($id)
    {
        $this->page_title    = 'Product Sale';
        $this->assets_css    = array( 'dataTables.bootstrap.css' );  
        $this->assets_js     = array( 'jquery.dataTables.js', 'dataTables.bootstrap.js' );  
        
        $this->template->set_layout('reports');

        $shift_data = $this->reports_model->get_product_sale($id);
        $this->render_page('admin/reports/product_sale', array('shift_data' => $shift_data));          
    }

    public function table_sales()
    {
        $this->page_title    = 'Tables Sales';
        $this->assets_css    = array( 'dataTables.bootstrap.css' );  
        $this->assets_js     = array( 'jquery.dataTables.js', 'dataTables.bootstrap.js' );  
        
        $this->template->set_layout('reports');

        $get_table_sale_group = $this->reports_model->get_table_sale_group();   
        $get_table_sales = $this->reports_model->get_table_sales();   
        $this->render_page('admin/reports/table_sales', array('current' => 'table_sales', 'get_table_sale_group' => $get_table_sale_group, 'get_table_sales' => $get_table_sales));        
    }

    public function table_sale($id)
    {
        $this->page_title    = 'Table Sales';
        $this->assets_css    = array( 'dataTables.bootstrap.css' );  
        $this->assets_js     = array( 'jquery.dataTables.js', 'dataTables.bootstrap.js' );  
        
        $this->template->set_layout('reports');

        $shift_data = $this->reports_model->get_table_sale($id);
        $this->render_page('admin/reports/table_sale', array('shift_data' => $shift_data));          
    }
}