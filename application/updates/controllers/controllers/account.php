<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class to deal with all product related details
 */

class Account extends App_Controller
{
    public function index()
    {
        redirect('account/create');
    }


    public function create()
    {
        
        $this->page_title    = 'Admin';
        $this->assets_css    = array( 'bootstrap.css', 'waiter.css' );  
        
        $this->template->set_layout('login');  
        
        $this->form_validation->set_rules('name', 'Your Name', 'trim|required|xss_clean|min_length[3]');
        $this->form_validation->set_rules('surname', 'Surname', 'trim|xss_clean|required');
        $this->form_validation->set_rules('restaurant_name', 'Restaurant Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|xss_clean|required');
        $this->form_validation->set_rules('city', 'City', 'trim|xss_clean|required');
        $this->form_validation->set_rules('comments', 'Comments', 'trim|xss_clean|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean|required');
        $this->form_validation->set_rules('sku', 'Sku', 'trim|xss_clean|required|is_unique[accounts.sku]');
        
        if ($this->form_validation->run() == true)
        { 
            $this->load->model('account_model');
            $this->load->model('ion_auth_model');
            
            $password = $this->ion_auth_model->hash_code($this->input->post('password'));
            
            $account_id = $this->account_model->create($password);
            if($account_id != FALSE)
            {
                $site          = $this->input->post("sku");
                $new_location  = FCPATH.'..'.DIRECTORY_SEPARATOR.$site;
                $exist_assets  = FCPATH.'assets'.DIRECTORY_SEPARATOR;
                $new_assets    = $new_location.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR;

                if (!file_exists($new_location)) 
                {
                    mkdir($new_location, 0755, true);

                    $this->recurse_copy($exist_assets, $new_assets);
                    $file = new SplFileObject(FCPATH.'index.php');

                    while (!$file->eof()) {
                        $file->fgets();
                    }

                    $file = fopen($new_location.DIRECTORY_SEPARATOR.'index.php',"w");
                    fwrite($file, $this->index_file($site));
                    fclose($file);

                    copy(FCPATH.'.htaccess', $new_location.DIRECTORY_SEPARATOR.'.htaccess');

                    $user_name = $site;
                    $password  = $password;

                    ob_end_clean();

                    $this->load->model('account_model');            
                    if($this->account_model->create_db($site, $user_name, $password))
                    {
                        $sampleData = array(
                        'database' => array(
                            'hostname' => 'localhost',
                            'username' => $user_name,
                            'database' => $site,
                            'password' => $password,
                        ),
                        'site' => array(
                            'title' => $this->input->post('restaurant_name'),
                            'email' => $this->input->post('email'),
                            'logo'  => 'logo'
                        ));
                        if (write_ini_file($sampleData,  FCPATH.'..'.DIRECTORY_SEPARATOR.$site.DIRECTORY_SEPARATOR.$site.'.ini', true))
                        {
                            $this->session->set_flashdata('app_success', 'Account created successfully');
                            redirect('account/created_account/'.$account_id);
                        }
                        else {
                            deleteDirectory($new_location);
                            $this->account_model->delete($account_id);
                        }
                    }
                    else 
                    {
                        deleteDirectory($new_location);
                    }
                }
            }
        } 
        $this->render_page('account/index', array() );
    }
    
    public function created_account($id){
        
        $this->page_title    = 'Admin';
        $this->assets_css    = array( 'bootstrap.css', 'waiter.css' );  
        
        $this->template->set_layout('login');
        
        if(empty($id))
            show_404 ();
        $this->load->model('account_model');
        $account = $this->account_model->get_account($id);
        if(!$account)
            show_404 ();
        
        $this->render_page('account/created_account', array('account' => $account) );
    }

    
    private function recurse_copy($src,$dst) { 
        $dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    $this->recurse_copy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    } 
    
    public function index_file($site)
    {
        return '<?php
define("SITE", "'.$site.'");
/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */
	define("ENVIRONMENT", "development");
/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */

if (defined("ENVIRONMENT"))
{
	switch (ENVIRONMENT)
	{
		case "development":
			error_reporting(E_ALL);
		break;
	
		case "testing":
		case "production":
			error_reporting(0);
		break;

		default:
			exit("The application environment is not set correctly.");
	}
}

/*
 *---------------------------------------------------------------
 * SYSTEM FOLDER NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" folder.
 * Include the path if the folder is not in the same  directory
 * as this file.
 *
 */
	$system_path = "../restaurent/system";

/*
 *---------------------------------------------------------------
 * APPLICATION FOLDER NAME
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * folder then the default one you can set its name here. The folder
 * can also be renamed or relocated anywhere on your server.  If
 * you do, use a full server path. For more info please see the user guide:
 * http://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 *
 */
	$application_folder = "../restaurent/application";

/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * --------------------------------------------------------------------
 *
 * Normally you will set your default controller in the routes.php file.
 * You can, however, force a custom routing by hard-coding a
 * specific controller class/function here.  For most applications, you
 * WILL NOT set your routing here, but it"s an option for those
 * special instances where you might want to override the standard
 * routing in a specific front controller that shares a common CI installation.
 *
 * IMPORTANT:  If you set the routing here, NO OTHER controller will be
 * callable. In essence, this preference limits your application to ONE
 * specific controller.  Leave the function name blank if you need
 * to call functions dynamically via the URI.
 *
 * Un-comment the $routing array below to use this feature
 *
 */
	// The directory name, relative to the "controllers" folder.  Leave blank
	// if your controller is not in a sub-folder within the "controllers" folder
	// $routing["directory"] = "";

	// The controller class file name.  Example:  Mycontroller
	// $routing["controller"] = "";

	// The controller function you wish to be called.
	// $routing["function"]	= "";


/*
 * -------------------------------------------------------------------
 *  CUSTOM CONFIG VALUES
 * -------------------------------------------------------------------
 *
 * The $assign_to_config array below will be passed dynamically to the
 * config class when initialized. This allows you to set custom config
 * items or override any default config values found in the config.php file.
 * This can be handy as it permits you to share one application between
 * multiple front controller files, with each file containing different
 * config values.
 *
 * Un-comment the $assign_to_config array below to use this feature
 *
 */
	// $assign_to_config["name_of_config_item"] = "value of config item";



// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */

	// Set the current directory correctly for CLI requests
	if (defined("STDIN"))
	{
		chdir(dirname(__FILE__));
	}

	if (realpath($system_path) !== FALSE)
	{
		$system_path = realpath($system_path)."/";
	}

	// ensure there"s a trailing slash
	$system_path = rtrim($system_path, "/")."/";

	// Is the system path correct?
	if ( ! is_dir($system_path))
	{
		exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
	}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
	// The name of THIS file
	define("SELF", pathinfo(__FILE__, PATHINFO_BASENAME));

	// The PHP file extension
	// this global constant is deprecated.
	define("EXT", ".php");

	// Path to the system folder
	define("BASEPATH", str_replace("\\\", "/", $system_path));

	// Path to the front controller (this file)
	define("FCPATH", str_replace(SELF, "", __FILE__));

	// Name of the "system folder"
	define("SYSDIR", trim(strrchr(trim(BASEPATH, "/"), "/"), "/"));


	// The path to the "application" folder
	if (is_dir($application_folder))
	{
		define("APPPATH", $application_folder."/");
	}
	else
	{
		if ( ! is_dir(BASEPATH.$application_folder."/"))
		{
			exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
		}

		define("APPPATH", BASEPATH.$application_folder."/");
	}

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 *
 */
require_once BASEPATH."core/CodeIgniter.php";

/* End of file index.php */
/* Location: ./index.php */';

        }
    }
?>
