<?php
/**
 * CodeIgniter Log Library
 *
 * @category   Applications
 * @package    CodeIgniter
 * @subpackage Libraries
 * @author     Bo-Yi Wu <appleboy.tw@gmail.com>
 * @license    BSD License
 * @link       http://blog.wu-boy.com/
 * @since      Version 1.0
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Lib_log
{
    /**
     * ci
     *
     * @param instance object
     */
    private $_ci;
    /**
     * log table name
     *
     * @param string
     */
    private $_log_table_name;
    public $levels = array(
        E_ERROR             => 'Error',
        E_WARNING           => 'Warning',
        E_PARSE             => 'Parsing Error',
        E_NOTICE            => 'Notice',
        E_CORE_ERROR        => 'Core Error',
        E_CORE_WARNING      => 'Core Warning',
        E_COMPILE_ERROR     => 'Compile Error',
        E_COMPILE_WARNING   => 'Compile Warning',
        E_USER_ERROR        => 'User Error',
        E_USER_WARNING      => 'User Warning',
        E_USER_NOTICE       => 'User Notice',
        E_STRICT            => 'Runtime Notice',
        E_RECOVERABLE_ERROR => 'Catchable error',
        E_DEPRECATED        => 'Runtime Notice',
        E_USER_DEPRECATED   => 'User Warning'
    );
    /**
     * constructor
     *
     */
    public function __construct()
    { 
        $this->_ci =& get_instance();
        set_error_handler(array($this, 'error_handler'));
        set_exception_handler(array($this, 'exception_handler'));
        // Load database driver
        $this->_ci->load->database();
        // Load config file
        $this->_ci->load->config('log');
        $this->_log_table_name = ($this->_ci->config->item('log_table_name')) ? $this->_ci->config->item('SITE_ID').$this->_ci->config->item('log_table_name') : $this->_ci->config->item('SITE_ID').'logs';
    
        if( $this->_ci->db->table_exists($this->_ci->config->item('SITE_ID').'users') === false)
        {
			echo '<html><head></head><body style="text-align: center;background-image: url(https://www.itl.cat/pngfile/big/157-1572191_hd-wallpapers-for-website-background-cool-background-image.jpg);background-repeat: no-repeat;background-size: cover;"><h1 style="font-family: cursive;text-align: center;position: absolute;top: 50%;left: 10%;right: 10%;color:#fff">There is no store created with the url you provided!</h1></body></html>';
             die;
        }
        
        $query = $this->_ci->db->select("status, delete")->from("accounts")->where("sku", rtrim($this->_ci->config->item('SITE_ID'), "_"))->get();
        
        $data  = $query->row();
        
        $api_uris = array('nuro/api/searchRestaurants', 'nuro/api/getRestaurants', 'nuro/api/increaseViewCount', 'nuro/api/time');
        $uri_part = uri_string();
        
        if(!in_array($uri_part, $api_uris)){
            if(!$data) {
				echo '<html><head></head><body style="text-align: center;background-image: url(https://www.itl.cat/pngfile/big/157-1572191_hd-wallpapers-for-website-background-cool-background-image.jpg);background-repeat: no-repeat;background-size: cover;"><h1 style="font-family: cursive;text-align: center;position: absolute;top: 50%;left: 10%;right: 10%;color:#fff">There is no store created with the url you provided!</h1></body></html>';
                die;
			}
            if(!isset($data->delete) || $data->delete == 1) {
				echo '<html><head></head><body style="text-align: center;background-image: url(https://www.itl.cat/pngfile/big/157-1572191_hd-wallpapers-for-website-background-cool-background-image.jpg);background-repeat: no-repeat;background-size: cover;"><h1 style="font-family: cursive;text-align: center;position: absolute;top: 50%;left: 10%;right: 10%;color:#fff">Your site is removed. Please contact admin!</h1></body></html>';
                die;
			}
            if(!isset($data->status) || $data->status == 0) {
				echo '<html><head></head><body style="text-align: center;background-image: url(https://www.itl.cat/pngfile/big/157-1572191_hd-wallpapers-for-website-background-cool-background-image.jpg);background-repeat: no-repeat;background-size: cover;"><h1 style="font-family: cursive;text-align: center;position: absolute;top: 50%;left: 10%;right: 10%;color:#fff">Your site is not activated. Please contact admin!</h1></body></html>';
				die;
			}
        }
    }
    /**
     * PHP Error Handler
     *
     * @param   int
     * @param   string
     * @param   string
     * @param   int
     * @return void
     */
    public function error_handler($severity, $message, $filepath, $line)
    {
                
        $data = array(
            'errno' => $severity,
            'errtype' => isset($this->levels[$severity]) ? $this->levels[$severity] : $severity,
            'errstr' => $message,
            'errfile' => $filepath,
            'errline' => $line,
            'user_agent' => $this->_ci->input->user_agent(),
            'ip_address' => $this->_ci->input->ip_address(),
            'time' => date('Y-m-d H:i:s')
        );
        $this->_ci->db->insert($this->_log_table_name, $data);
    }
    /**
     * PHP Error Handler
     *
     * @param   object
     * @return void
     */
    public function exception_handler($exception)
    {
        $data = array(
            'errno' => $exception->getCode(),
            'errtype' => isset($this->levels[$exception->getCode()]) ? $this->levels[$exception->getCode()] : $exception->getCode(),
            'errstr' => $exception->getMessage(),
            'errfile' => $exception->getFile(),
            'errline' => $exception->getLine(),
            'user_agent' => $this->_ci->input->user_agent(),
            'ip_address' => $this->_ci->input->ip_address(),
            'time' => date('Y-m-d H:i:s')
        );
        $this->_ci->db->insert($this->_log_table_name, $data);
    }
}
/* End of file Lib_log.php */