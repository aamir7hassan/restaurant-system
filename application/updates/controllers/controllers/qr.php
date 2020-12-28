<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class to deal with all meals related details
 */

class Qr extends App_Controller
{
    
    public $oldname;

    public function __construct()
    {
        parent::__construct();        
    }
    public function index($id)
    {
        return $this->get($id);
    }


    public function get($id){ 
        
        if (empty($id))
            return;
        require  APPPATH . 'third_party/phpqrcode/qrlib.php';
        QRcode::png(base_url(rtrim($this->config->item('SITE_ID'),"_").'/qr/store_loader/'.$id));
    }
    
    public function get_table($id){
        
        if (empty($id))
            return;
        
        require  APPPATH . 'third_party/phpqrcode/qrlib.php';
        QRcode::png(base_url(rtrim($this->config->item('SITE_ID'),"_").'/qr/table_loader/'.$id));
    }
    
    public function table_loader($table_unique=NULL){ 
        //if(empty($table_unique))
         //   redirect (rtrim($this->config->item('SITE_ID'),"_")); 
        
        $this->load->model('table_model');
        $table_id = $this->table_model->get_table_id($table_unique); 
        
        redirect('customer/index/'.$table_id, 'refresh');
    }
    
    public function store_loader($id=NULL){ 
        //if(empty($table_unique))
         //   redirect (rtrim($this->config->item('SITE_ID'),"_")); 
        
        $this->load->model('account_model');
        $table_id = $this->account_model->get_account_by_unique($id); 
        if($table_id)
            redirect(base_url($table_id->sku.'/customer'));
    }
}    