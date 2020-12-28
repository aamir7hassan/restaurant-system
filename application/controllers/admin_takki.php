<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class to deal with all product related details
 */

class Admin_Takki extends App_Controller
{
    
    public $oldname;

    public function __construct()
    {
        parent::__construct();
        $this->load->library("lib_log");
        $this->load->model("takki_model");
        $this->owners_only();
        
    }

    public function index(){
        
        $this->page_title    = 'Admin';
        $this->assets_css    = array( 'dataTables.bootstrap.css', 'style.css', 'morris.css' );  
        $this->assets_js     = array( 'jquery.dataTables.js', 'dataTables.bootstrap.js', 'hisrc.js', 'raphael-2.1.0.min.js' );  
        
        $this->template->set_layout('admin');  
        
        $users_today  = $this->takki_model->users_today();
        $users_total  = $this->takki_model->users_total();
        $tables_total = $this->takki_model->tables_total();  
        $total_waiter = $this->takki_model->waiter_total();
        
        $this->render_page('admin/dashboard/index', array('users_today' => $users_today, 'users_total' => $users_total, 'tables_total' => $tables_total, 'total_waiter' => $total_waiter ));
    }
    
   
    
    public function area(){
        
        $data   = $this->takki_model->get_monthwise_data();
        $result = $output = array();
        
        if(is_array($data) && count($data) > 0){
            foreach ($data as $d){
                $result['y'] = $d->name.' - '.$d->year;
                $result['a'] = $d->users;
                $result['b'] = $d->amount;

                $output[] = $result;
            }
        }
        echo json_encode($output);
        die;
    }
    
    public function donut(){
        
        $data   = $this->takki_model->get_monthwise_data();
        $result = $output = array();
        $total  = 0;
        if(is_array($data) && count($data) > 0){
            foreach ($data as $d){
                $total += $d->users;
                $result['label'] = $d->name;
                $result['value'] = $d->users;
                $output[] = $result;
            }
        }
        
        
        foreach ($output as $k =>  $ou){
            $output[$k]['value'] = round(( $ou['value']/$total * 100 ),2);
        }
        
        echo json_encode($output);
        die;
    }
    
    function logout()
    {
        if($this->ion_auth->logged_in())
        {
            $this->ion_auth->logout();
            redirect('admin', 'refresh'); 
        }

    }
}    