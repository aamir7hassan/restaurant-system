<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class to deal with all product related details
 */

class Customer extends App_Controller
{

    public $oldname;

    public function __construct()
    {
        parent::__construct();
        $this->load->library("lib_log");
        $this->load->model("customer_model");
    }

    /**
     * @method type meals(type $paramName) Meals page
     */

    public function index($id = '')
    {
        if ($this->session->userdata('table_id')) {
            $table = $this->session->userdata('table_id');
            $order = $this->session->userdata('order_id');
            if ($this->customer_model->_is_active($order)) {
                redirect(site_url('customer/menu/order_' . $order . '/table_' . $table));
                die;
            }
        }
        if (get_cookie('_takki_order_id') != NULL && get_cookie('_takki_table_id') != NULL) {
            $table = get_cookie('_takki_table_id');
            $order = get_cookie('_takki_order_id');

            if ($this->customer_model->_is_active($order)) {
                redirect(site_url('customer/menu/order_' . $order . '/table_' . $table));
                die;
            } else {
                delete_cookie('_takki_table_id');
                delete_cookie('_takki_order_id');
            }
        }
        $this->page_title    = 'Customer View';
        $this->assets_css    = array('bootstrap.css', 'customer.css');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('option', 'Option', 'required|trim|xss_clean');

        if ($this->input->post('option') == 'takeaway') {
			if($this->input->post('choice')=='delivery' || $this->input->post('choice')=='1') {
				$this->form_validation->set_rules('cell1', 'Contact number', 'required|trim');
			} else if($this->input->post('choice')=='collection') {
				$this->form_validation->set_rules('cell2', 'Contact number', 'required|trim');
			}
            $name = $this->input->post('cell');
            $this->session->set_userdata('take_away', 1);
        } else {
            $this->form_validation->set_rules('name', 'Name', 'required|trim');
            $this->form_validation->set_rules('table', 'Location Number', 'required|trim|integer|callback_table_exists');
            $name = $this->input->post('name');
            $this->session->set_userdata('take_away', 0);
        }
        if ($this->form_validation->run() !== FALSE) {
				// $tid = $this->input->post('table'); // table id
				// $this->db->where('id', $tid);
				// $query = $this->db->get($this->config->item('SITE_ID') . 'tables');
				// if ($query->num_rows() <= 0) {
					// die('no table exists');
				// } else {
					// die('table exists');
				// }
				// die;
			
            $result = $this->customer_model->reserve_table($name);
			
            if (isset($result['order_id']) && is_numeric($result['order_id']) > 0) {
                $save_data = array(
                    'table_id'  => $result['table_id'],
                    'order_id'  => $result['order_id'],
                );
                $this->session->set_userdata($save_data);
                $this->session->set_userdata('table_no', $result['table_id']);
                redirect('customer/menu/order_' . $result['order_id'] . '/table_' . $result['table_id'], 'refresh');
            } else {
                $this->session->set_flashdata('app_error', 'Unable to reserve location. Location is full.');
                redirect(site_url('customer'));
            }
            redirect('customer/scan_qr');
            die;
        }

        $name = '';

        if ($this->ion_auth->logged_in())
            $user   = $this->ion_auth_model->user()->row();

        if (isset($user))
            $name = $user->first_name;
		
		$sku = trim($this->config->item('SITE_ID'),'_');
		$accounts = accounts($sku);
		
        $package  = $this->customer_model->get_package();
		
		$fromTime = json_decode($this->config->item('work_hours_from'));
		$toTime   = json_decode($this->config->item('work_hours_to'),true);
		$timezone = $this->config->item('timezone');
		if(!empty($timezone)) {
			$timeObject = new DateTime($timezone);
		} else {
			$timeObject = new DateTime();
		}
		$timestamp = $timeObject->getTimeStamp();
		$curTime = $timeObject->setTimestamp($timestamp)->format('H:i A');
		$curDay = date('l');
		$status = false;
		if(is_array($fromTime) && is_array($toTime)) {
			foreach($fromTime as $f=>$time) {
				$froms = explode('_',$time);
				$tos   = explode('_',$toTime[$f]);
				if(strtolower($froms[0])== strtolower($curDay) && strtolower($tos[0]) == strtolower($curDay)) {
					$time1 = $froms[1];
					$time2 = $tos[1];
					$start = date('H:i A',strtotime($time1.":00"));
					$end   = date('H:i A',strtotime($time2.":00"));
					if (($start < $curTime) && ($curTime < $end)) {
						$status = TRUE;
						break;
					}
				}
			}
		}
		
		$this->render_page('customer/index', array('name' => $name, 'qr_id' => $id, 'package' => $package, 'accounts' => $accounts, 'status'=>$status));
    }

    public function login()
    {
        //if($this->ion_auth->logged_in())
        // redirect('customer');

        if ($this->session->userdata('table_id')) {
            $table = $this->session->userdata('table_id');
            $order = $this->session->userdata('order_id');

            if ($this->customer_model->_is_active($order)) {
                redirect(site_url('customer/menu/order_' . $order . '/table_' . $table), 'refresh');
                die;
            }
        }

        if (get_cookie('_takki_order_id') != NULL && get_cookie('_takki_table_id') != NULL) {
            $table = get_cookie('_takki_table_id');
            $order = get_cookie('_takki_order_id');

            if ($this->customer_model->_is_active($order)) {
                redirect(site_url('customer/menu/order_' . $order . '/table_' . $table), 'refresh');
                die;
            } else {
                delete_cookie('_takki_table_id');
                delete_cookie('_takki_order_id');
            }
        }

        $this->page_title    = 'Login';
        $this->assets_css    = array('bootstrap.css', 'customer.css');
        $this->assets_js     = array('parselyjs.js');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('table', 'Table', 'required|integer');

        if ($this->form_validation->run() !== FALSE) {
            if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'), false)) {
                $users  = $this->ion_auth->user()->row();
                $result = $this->customer_model->reserve_table($users->first_name);
                if (is_numeric($result) && $result > 0) {
                    $save_data = array(
                        'table_id'  => $this->input->post('table'),
                        'order_id'  => $result,
                    );
                    $this->session->set_userdata($save_data);
                    $this->session->set_userdata('table_no', $this->input->post('table'));
                    redirect('customer/menu/order_' . $result . '/table_' . $this->input->post('table'), 'refresh');
                }
                $this->session->set_flashdata('app_success', 'Welcome! Please order your items now');
                redirect('customer');
                die;
            } else {
                $this->session->set_flashdata('app_error', $this->ion_auth->errors());
                redirect('customer/login');
            }
        }

        $this->data['email'] = array(
            'name'                          => 'email',
            'id'                            => 'email',
            'type'                          => 'text',
            'value'                         => $this->session->userdata('user_register_email') ? $this->session->userdata('user_register_email') : $this->form_validation->set_value('email'),
            'class'                         => 'form-control',
            'data-parsley-length'           => '[3, 100]',
            'required'                      => '',
            'placeholder'                   => 'Email'
        );

        $this->data['password'] = array(
            'name'  => 'password',
            'id'    => 'password',
            'type'  => 'password',
            'value' => $this->form_validation->set_value('password'),
            'class'                         => 'form-control',
            'data-parsley-length'           => '[3, 100]',
            'required'                      => '',
            'placeholder'                   => 'Password'
        );

        require_once APPPATH . 'third_party/facebook/src/Facebook/autoload.php';
        $facebook = $this->config->load('facebook', TRUE);
        $fb = new Facebook\Facebook([
            'app_id'                => $facebook['app_id'],
            'app_secret'            => $facebook['secret'],
            'default_graph_version' => 'v2.5',
        ]);

        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email']; // optional
        $loginUrl = $helper->getLoginUrl(site_url('customer/login_fb'), $permissions);
        $this->data['fb_url'] = $loginUrl;
        $this->render_page('customer/login', $this->data);
    }


    public function login_fb()
    {
        require_once APPPATH . 'third_party/facebook/src/Facebook/autoload.php';
        $facebook = $this->config->load('facebook', TRUE);
        $fb = new Facebook\Facebook([
            'app_id'                => $facebook['app_id'],
            'app_secret'            => $facebook['secret'],
            'default_graph_version' => 'v2.5',
        ]);
        $helper = $fb->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            $this->session->set_flashdata('app_error', 'Graph returned an error: ' . $e->getMessage());
            redirect('customer/login');
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            $this->session->set_flashdata('app_error', 'Facebook SDK returned an error: ' . $e->getMessage());
            redirect('customer/login');
            exit;
        }

        if (isset($accessToken)) {
            $this->session->set_userdata('facebook_access_token', (string) $accessToken);
            $fb->setDefaultAccessToken($accessToken);

            try {
                $response = $fb->get('/me?locale=en_US&fields=id,name,email,first_name,last_name,gender');
                $userNode = $response->getGraphUser();
            } catch (Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                $this->session->set_flashdata('app_error', 'Graph returned an error: ' . $e->getMessage());
                redirect('customer/login');
                exit;
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                $this->session->set_flashdata('app_error', 'Facebook SDK returned an error: ' . $e->getMessage());
                redirect('customer/login');
                exit;
            }

            $user = $this->customer_model->single($userNode['email']);
            if ($user !== FALSE) {
                if ($this->ion_auth->login($userNode['email'], '', TRUE, TRUE)) {
                    if (empty($path))
                        redirect('customer');
                    else
                        redirect($path);
                } else {
                    $this->session->set_flashdata('app_error', $this->ion_auth->errors());
                    redirect('customer/login');
                    die;
                }
            } else {
                $additional_data = array(
                    'first_name'    => $userNode['first_name'],
                    'last_name'     => $userNode['last_name'],
                    'title'         => $userNode['gender'] == 'male' ? 'Mr' : 'Mrs'
                );

                $result = $this->ion_auth->register('', '', $userNode['email'], $additional_data, $group_ids = array(4));
                if ($result == FALSE) {

                    $this->session->set_flashdata('app_error', 'Failed to login with facebook!');
                    redirect('customer/login');
                    exit;
                } else {
                    if ($this->ion_auth->login($userNode['email'], '', TRUE, TRUE)) {
                        if (empty($path))
                            redirect('customer');
                        else
                            redirect($path);
                    } else {
                        $this->session->set_flashdata('app_error', $this->ion_auth->errors());
                        redirect('customer/login');
                        die;
                    }
                }
            }
        }
    }



    public function signup()
    {
        if ($this->session->userdata('table_id')) {

            $table = $this->session->userdata('table_id');
            $order = $this->session->userdata('order_id');

            if ($this->customer_model->_is_active($order)) {
                redirect(site_url('customer/menu/order_' . $order . '/table_' . $table), 'refresh');
                die;
            }
        }

        if (get_cookie('_takki_order_id') != NULL && get_cookie('_takki_table_id') != NULL) {
            $table = get_cookie('_takki_table_id');
            $order = get_cookie('_takki_order_id');

            if ($this->customer_model->_is_active($order)) {
                redirect(site_url('customer/menu/order_' . $order . '/table_' . $table), 'refresh');
                die;
            } else {
                delete_cookie('_takki_table_id');
                delete_cookie('_takki_order_id');
            }
        }


        $this->page_title    = 'Signup';
        $this->assets_css    = array('bootstrap.css', 'customer.css');
        $this->assets_js     = array('parselyjs.js');

        $this->form_validation->set_rules('first_name', 'Name', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[' . $this->config->item('SITE_ID') . 'users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]|max_length[50]');

        if ($this->form_validation->run() == true) {
            $username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
            $email    = $this->input->post('email');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
            );
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, array($this->config->item('customer_index', 'ion_auth')))) {
            $this->session->set_userdata('user_register_email', $email);

            $this->session->set_flashdata('app_success', 'Welcome, you are now registered!');
            redirect("customer/login", 'refresh');
        } else {

            if ($this->ion_auth->errors()) {

                $error_data = str_replace($this->ion_auth->errors(), 'The email field must contain an unique value', 'This email is already taken');


                $this->session->set_flashdata('app_error', $error_data);
                redirect("customer/signup");
            }
            //display the create user form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = array(
                'name'                          => 'first_name',
                'id'                            => 'first_name',
                'type'                          => 'text',
                'class'                         => 'form-control',
                'data-parsley-length'           => '[3, 100]',
                'required'                      => '',
                'value'                         => $this->form_validation->set_value('first_name'),
                'placeholder'                   => 'Name'
            );
            $this->data['email'] = array(
                'name'                          => 'email',
                'id'                            => 'email',
                'type'                          => 'text',
                'value'                         => $this->form_validation->set_value('email'),
                'class'                         => 'form-control',
                'data-parsley-length'           => '[3, 100]',
                'required'                      => '',
                'placeholder'                   => 'Email'
            );

            $this->data['password'] = array(
                'name'  => 'password',
                'id'    => 'password',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password'),
                'class'                         => 'form-control',
                'data-parsley-length'           => '[3, 100]',
                'required'                      => '',
                'placeholder'                   => 'Password'
            );

            $this->render_page('customer/signup', $this->data);
        }
    }

    public function scan_qr()
    {
        /*
        if( !$this->session->userdata('customer_name'))
        {
            $this->session->set_flashdata('error', 'Please enter your name!');
            redirect('customer');die;
        }*/

        $this->page_title    = 'Customer View';
        $this->assets_css    = array('bootstrap.css', 'customer.css');


        $this->render_page('customer/scan_qr', array());
    }


    public function close()
    {
        $this->session->unset_userdata('table_id');
        $this->session->unset_userdata('order_id');
        redirect('customer');
    }


    public function check_session()
    {
		
        if (!$this->customer_model->_is_active($this->session->userdata('order_id'))) {
            echo json_encode(array('status' => 0));
        } else {
            echo json_encode(array('status' => 1));
        }
        die;
    }


    public function menu($order_id, $table_id)
    {
        $oids = explode('_', $order_id);
        $tids = explode('_', $table_id);
        $data = array();
        $order_id = end($oids);
        $table_id = end($tids);
		
        $this->load->helper('cookie');

        if (!$this->customer_model->_is_active($order_id) || !$this->session->userdata('table_id') || !$this->session->userdata('order_id')) {
            delete_cookie('_takki_order_id');
            delete_cookie('_takki_table_id');
            redirect(site_url('customer/'));
            die;
        }

        set_cookie('_takki_order_id', $order_id, '7200');
        set_cookie('_takki_table_id', $table_id, '7200');

        if (empty($order_id) || !is_numeric($order_id) || !is_numeric($table_id) || empty($table_id))
            show_404();

        $this->page_title    = 'Customer View';
        $this->assets_css    = array('bootstrap.css', 'customer.css');
        $meals               = $this->customer_model->get_meals_menu();
        $order               = $this->customer_model->get_orders($order_id);
        $order_budget        = $this->customer_model->get_budget($order_id);
		$prices=0;
		
        foreach ($order as $od) {
            $data[] = $od->meal_id;
			$prices = $prices+$od->price;
        }

        $this->load->model("settings_model");

        $stock_show_options = $this->settings_model->get_stock_quantity_options();
        $show_avail_stock = true; // can set from config;
        $hide_empty_stock = true; // can set in config;

        if ($stock_show_options) {
            foreach ($stock_show_options as $key => $value) {
                if ($value->index == 'show_avail_stock') {
                    $show_avail_stock = $value->value;
                } elseif ($value->index == 'hide_empty_stock') {
                    $hide_empty_stock = $value->value;
                }
            }
        }
		
		$ordersinfo = $this->dev_model->getData($this->config->item('SITE_ID') . 'orders','row_array',$args=['where'=>['id'=>$order_id]]);
		

        $display = $this->customer_model->get_view_status();
        $package = $this->customer_model->get_package();
		
		$fromTime = json_decode($this->config->item('work_hours_from'),true);
		$toTime   = json_decode($this->config->item('work_hours_to'),true);
		$timezone = $this->config->item('timezone');
		if(!empty($timezone)) {
			$timeObject = new DateTime($timezone);
		} else {
			$timeObject = new DateTime();
		}
		$timestamp = $timeObject->getTimeStamp();
		$curTime = $timeObject->setTimestamp($timestamp)->format('H:i A');
		$curDay = date('l');
		$status = false;
		if(is_array($fromTime) && is_array($toTime)) {
			foreach($fromTime as $f=>$time) {
				$froms = explode('_',$time);
				$tos   = explode('_',$toTime[$f]);
				if(strtolower($froms[0])== strtolower($curDay) && strtolower($tos[0]) == strtolower($curDay)) {
					$time1 = $froms[1];
					$time2 = $tos[1];
					$start = date('H:i A',strtotime($time1.":00"));
					$end   = date('H:i A',strtotime($time2.":00"));
					if (($start < $curTime) && ($curTime < $end)) {
						$status = TRUE;
						break;
					}
				}
			}
		}
		
        $this->render_page('customer/menu', array('meals' => $meals, 'order_id' => $order_id, 'order' => $data, 'table_id' => $table_id, 'display' => $display, 'show_avail_stock' => $show_avail_stock, 'hide_empty_stock' => $hide_empty_stock, 'package' => $package, 'order_budget' => $order_budget, 'prices'=> $prices, 'orderinfo' => $ordersinfo, 'status' => $status));
    }

    public function search_meal()
    {
        if (!$this->input->is_ajax_request())
            show_404();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('q', 'Search', 'required|trim|xss_clean');
        $this->form_validation->set_rules('table_id', 'Table Number', 'required|trim|integer');
        $this->form_validation->set_rules('order_id', 'Order Number', 'required|trim|integer');
        if ($this->form_validation->run() == FALSE) {
            echo 'Please enter search term';
            die;
        } else {
            if (!$this->customer_model->_is_active($this->input->post('order_id'))) {
                echo 'Order closed';
                die;
            }
            $order_id = $this->input->post('order_id');
            $table_id = $this->input->post('table_id');
            $this->assets_css    = array();
            $meals               = $this->customer_model->get_meals_menu_search();
            $order               = $this->customer_model->get_orders($order_id);
            foreach ($order as $od) {
                $data[] = $od->meal_id;
            }
            $this->load->view('customer/search', array('meals' => $meals, 'order_id' => $order_id, 'order' => $data, 'table_id' => $table_id));
        }
    }

    public function comment()
    {
        if (!$this->input->is_ajax_request())
            show_404();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('order_id', 'Order ID', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('meal_id', 'Product ID', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('comment', 'Comment', 'required|trim|max_length[60]');

        if ($this->form_validation->run() == FALSE) {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, "msg" => "Invalid comment")));
        } else {
            if (!$this->customer_model->_is_active($this->input->post('order_id'))) {
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, 'orders' => FALSE)));
                die;
            }
            $orders = $this->customer_model->save_comment();
            if ($orders)
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 1)));
            else
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0)));
        }
    }

    public function address_save()
    {
        if (!$this->input->is_ajax_request())
            show_404();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('order_id', 'Order ID', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('table_id', 'Table ID', 'required|is_natural_no_zero');

        if ($this->form_validation->run() == FALSE) {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, "msg" => "Invalid order selected")));
        } else {
            if (!$this->customer_model->_is_active($this->input->post('order_id'))) {
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, 'orders' => FALSE)));
                die;
            }
            $orders = $this->customer_model->add_address();
            if ($orders)
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 1)));
            else
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0)));
        }
    }

    public function view_orders()
    {
        if (!$this->input->is_ajax_request())
            show_404();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('order_id', 'Order ID', 'required|is_natural_no_zero');

        if ($this->form_validation->run() == FALSE) {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, "msg" => "Invalid order selected")));
        } else {
            if (!$this->customer_model->_is_active($this->input->post('order_id'))) {
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, 'orders' => FALSE)));
                die;
            }
            $orders = $this->customer_model->view_orders();
            if ($orders)
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 1, 'orders' => $orders)));
            else
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, 'orders' => FALSE)));
        }
    }

    public function add_budget()
    {
        if (!$this->input->is_ajax_request())
            show_404();
		
        $this->load->library('form_validation');
        $this->form_validation->set_rules('order_id', 'Order ID', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('table_id', 'Location ID', 'required|is_natural_no_zero');
        //$this->form_validation->set_rules('budget', 'Budget', 'required|decimal');
		$this->form_validation->set_rules('budget', 'Budget', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, "msg" => validation_errors(), "meal" => $meal)));
        } else {
            if (!$this->customer_model->_is_active($this->input->post('order_id'))) {
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, 'msg' => 'Your order is not active!', 'orders' => FALSE)));
                die;
            }
            $orders = $this->customer_model->add_budget();
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => $orders ? 1 : 0, 'msg' => $orders ? 'Budget added successfully!' : 'Failed add budget!', 'oid' => $oid, "meal" => $meal)));
        }
    }

    public function validate_attributes($meal_id)
    {
        $attr_ids   = array();
        $attrs      = $this->input->post('attrs');
        foreach ($attrs as $key => $attr) {
            $attr_ids[] = $key;
        }

        $result = $this->customer_model->check_attributes($meal_id, $attr_ids);

        if (!$result['status']) {
            $this->form_validation->set_message('validate_attributes', $result['msg']);
        }
        return $result['status'];
    }

    public function order_meal($force = 0)
    {
		$catName = $this->input->post('category');
        if (!$this->input->is_ajax_request())
            show_404();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('meal_id', 'Product ID', 'required|is_natural_no_zero|callback_validate_attributes');
        $this->form_validation->set_rules('order_id', 'Order ID', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('qty', 'Quantity', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('price', 'Price', 'required|decimal');
        $this->form_validation->set_rules('tip', 'Tip', 'decimal');
        $meal = $this->input->post('meal_id');
        $attr = $this->input->post('attr');
        $meal_qty = $this->input->post('qty');
        if ($this->form_validation->run() == FALSE) 
        {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, "msg" => validation_errors(), "meal" => $meal)));
        } 
        else 
        {
            $this->load->model("meals_model");

            if (!$this->customer_model->check_kitchen_status($catName) && $force == 0)
            {
                $return = array("status" => 3, 'msg' => 'We are experiencing a high volume of orders please expect delays. Proceed?', 'orders' => FALSE);
            }

            if (!$this->customer_model->_is_active($this->input->post('order_id'))) 
            {
                $return = array("status" => 0, 'msg' => 'Your order is closed already!', 'orders' => FALSE);
            }

            if ($this->customer_model->check_requested($this->input->post('order_id'))) 
            {
                $return = array("status" => 0, 'msg' => 'Your bill is already processed. You cant order items now. Please contact waiter!');
            }

            if (!$this->meals_model->check_quantity($meal, $meal_qty)) 
            {
                $return = array("status" => 0, 'msg' => 'Insufficient quantity. Please contact waiter!');
            }

            if (!isset($return)) {
				$ress = $this->customer_model->_is_inbudget($this->input->post('order_id'));
				//var_dump($ress);die;
				if($force=="3") {
					$ress = true;
				}
				
				
                if ($ress) // if($ress || $force=1) 
                {
					$orderId = $this->input->post('order_id');
					$oid    = $this->customer_model->order_meal($attr);
					$price = $this->input->post('price');
					$qty   = $this->input->post('qty');
					//$priceF = $price*$qty;
                    $meal = $this->input->post('meal_id');
                    $meal_data = $this->meals_model->get_meal($meal);
					$query = $this->db->select('budget')->from($this->config->item('SITE_ID') . 'orders')->where(array('id' => $orderId, 'active' => 1))->get();
					
					if ($query->num_rows() > 0) {
						$result = $query->row();
						
						if (isset($result->budget) && $result->budget > 0) {
							$Recs = $this->dev_model->getData($this->config->item('SITE_ID') . 'order_details','all_array',$args=['where'=>['order_id'=>$orderId]]);
							
							if(count($Recs)>0) {
								foreach($Recs as $k=>$val) {
									$calcP[] = $val['qty'] * $val['meal_price'];
								}
							}
							$priceF = array_sum($calcP);
							if($result->budget >= $priceF) {
								$priceFF = abs($result->budget - $priceF);
							} else {
								$priceFF = $priceF -  $result->budget;
							}
						}
					}
					
                    if ($meal_data->quantity) {
                        $attr_array['quantity'] = ($meal_data->quantity - $meal_qty);
                        $update_status = $this->meals_model->update_meal_attr($meal, $attr_array);
                    }
                    $return = array("status" => 1, 'oid' => $oid, "meal" => $meal, "quantity" => $attr_array['quantity'], 'msg' => 'Product ordered successfully!','prices' => $priceFF);
                } else {
					$return = array("status" => 2, 'msg' => 'Budget Reached!');
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($return));
        }
    }

    public function authorize()
    {
        if (!$this->input->is_ajax_request())
            show_404();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('order_id', 'Order ID', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('table_id', 'Location ID', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('update_order', 'Change order', 'required|is_natural_no_zero');

        if ($this->form_validation->run() == FALSE) {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, "meal_id_error" => "Invalid order selected")));
        } else {
            if (!$this->customer_model->_is_active($this->input->post('order_id'))) {
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0)));
                die;
            }

            /**$check_bill_requested = $this->customer_model->check_requested();

            if ($check_bill_requested) {
                $return = array('status' => 0, 'msg' => 'He is already requested for bill');
            } else {
                $result = $this->customer_model->authorize();
                if ($result)
                    $return = array('status' => 1, 'msg' => 'Authorized successfully!');
                else
                    $return = array('status' => 0, 'msg' => 'Authorization failed!');
            }**/

            $result = $this->customer_model->authorize();
            if ($result)
                $return = array('status' => 1, 'msg' => 'Authorized successfully!');
            else
                $return = array('status' => 0, 'msg' => 'Authorization failed!');

            $this->output->set_content_type('application/json')->set_output(json_encode($return));
        }
    }
	
	public function splits()
    {
        if (!$this->input->is_ajax_request())
            show_404();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('order_id', 'Order ID', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('table_id', 'Location ID', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('update_order', 'Change order', 'required|is_natural_no_zero');

        if ($this->form_validation->run() == FALSE) {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, "meal_id_error" => "Invalid order selected")));
        } else {
            if (!$this->customer_model->_is_active($this->input->post('order_id'))) {
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0)));
                die;
            }

            /**$check_bill_requested = $this->customer_model->check_requested();

            if ($check_bill_requested) {
                $return = array('status' => 0, 'msg' => 'He is already requested for bill');
            } else {
                $result = $this->customer_model->authorize();
                if ($result)
                    $return = array('status' => 1, 'msg' => 'Authorized successfully!');
                else
                    $return = array('status' => 0, 'msg' => 'Authorization failed!');
            }**/

            $result = $this->customer_model->splits();
            if ($result)
                $return = array('status' => 1, 'msg' => 'Authorized successfully!');
            else
                $return = array('status' => 0, 'msg' => 'Authorization failed!');

            $this->output->set_content_type('application/json')->set_output(json_encode($return));
        }
    }

    public function pay_bill()
    {
        if (!$this->input->is_ajax_request())
            show_404();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('order_id', 'Order ID', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('table_id', 'Location ID', 'required|is_natural_no_zero');

        if ( $this->form_validation->run() == FALSE ) {
            $return = array("status" => 0, "msg" => "Invalid order selected");
        } else {
            if (!$this->customer_model->_is_active($this->input->post('order_id'))) {
                $return = array("status" => 0, 'msg' => 'Your session end');
            }
            if (!isset($return)) {
				
                $return = $this->customer_model->details();
				
                if(!$this->customer_model->_is_selfpay($this->input->post('order_id'))) {
					$re = $this->dev_model->getData($this->config->item('SITE_ID') . 'orders','row_array',$args=['where'=>['id'=>$this->input->post('order_id')]]);
					if(!is_null($re)) {
						$payed = $re['payed_by'];
						$r = $this->dev_model->getData($this->config->item('SITE_ID') . 'orders','row_array',$ags=['where'=>['id'=>$payed],'select'=>['customer_name']]);
						if(!is_null($r)) {
							$return['name'] = $r['customer_name'];
						} else {
							$return['name'] = "";
						}
					}
                    $return['payed_by']  = 1;
                    $return['authorize'] = '';
                    $return['master']    = 0;
                }
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($return));
    }

    public function send_bill()
    {
		
        if (!$this->input->is_ajax_request())
            show_404();

        ini_set('memory_limit', '256M');
        $this->load->library('pdf');
        $pdf = $this->pdf->load();

        $userId = $this->ion_auth->get_user_id();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('order_id', 'Order ID', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('table_id', 'Location ID', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $return = array("status" => 0, "msg" => "Invalid order selected");
        } else {
            if (!$this->customer_model->_is_active($this->input->post('order_id'))) {
                $return = array("status" => 0, 'msg' => 'Your session end');
            }

            if (!$this->customer_model->_is_selfpay($this->input->post('order_id'))) {
                $return = array("status" => 0, 'msg' => 'Your order billpayment is already authorized!');
            }

            if (!isset($return)) {
                $this->customer_model->update_email();
                $return = $this->customer_model->details();
                $price  = 0;

                $pdf->allow_charset_conversion=true;
                $pdf->charset_in='UTF-8';
                
                $pdf->autoLangToFont = true;
                
                $pdfFilePath = $_SERVER["DOCUMENT_ROOT"]."/takki/application/attach/customer_bill.pdf";

                $message = 'Hello <br/><br/><p>Below please find your itemised bill from ' . $this->config->item('company_name') . '.</p><table><tr><td><strong>Product</strong><br/></td><td><strong>Qty</strong></td><td><strong>Price</strong></td></tr>';
                foreach ($return['data'] as $data) {
                    $message .= '<tr><td>' . $data->name . '<br/></td><td>' . $data->qty . '</td><td>' . $data->price . '</td></tr>';
                    $price   += $data->price;
                }
                $message .= '<tr><td>Tip</td><td></td><td>' . number($return['tip']) . '</td></tr><tr><td><strong>Total</strong></td><td>&nbsp;</td><td><strong>R' . number($price + $return['tip']) . '</strong></td></tr></table>';
                $message .= '<p>Payment type: ' . ($return['payment_option'] == 1 ? 'Cash' : ($return['payment_option'] == 2) ? 'Card' : '') . '</p>';
                $message .= '<br/><br/>Thank you for visiting us.<br/><br/>';
                $message .= 'Trading name: ' . $this->config->item('company_name') . "<br/>";
                $message .= 'Company Registration #: ' . $this->config->item('company_registration') . "<br/>";
                $message .= 'VAT #: ' . $this->config->item('vat') . "<br/>";
                $message .= 'Telephone number: ' . $this->config->item('telephone_no') . "<br/>";

                $message .= '<br/>Regards,<br/>' . $this->config->item('company_name');

                $config['mailtype'] = 'html';

                $body = 'Hello Thanks for visiting us at '.$this->config->item('company_name').', please see attached bill you requested';


                $from_email = $this->config->item('primary_email');
                $to_email   = $this->input->post('email');

                $this->load->helper('file');
                write_file('customer_bill.pdf',$message);   

                $pdf->WriteHTML($message);
                $pdf->Output($pdfFilePath, "F");

                $result = $this->email
                            ->from($from_email, $this->config->item('company_name'))
                            ->to($to_email)
                            ->subject("Itemised bill")
                            ->message($body)
                            ->attach($pdfFilePath)
                            ->send();
                            
                if ($this->email->send()){
                    $return = array("status" => 1, 'msg' => 'Email sent');
                    $this->email->clear($pdfFilePath);
                }else{
                    $return = array("status" => 0, 'msg' => 'Email not sent');
                    $this->email->clear($pdfFilePath);
                }
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($return));
    }

    public function view_waiter_call()
    {
        if (!$this->input->is_ajax_request())
            show_404();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('order_id', 'Order ID', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('table_id', 'Location ID', 'required|is_natural_no_zero');

        if ($this->form_validation->run() == FALSE) {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, "msg" => "Invalid order selected")));
        } else {
            if (!$this->customer_model->_is_active($this->input->post('order_id'))) {
                $result = array("status" => 0, 'msg' => 'Your session end');
            }
            if (!isset($result)) {
                $notice = $this->customer_model->get_notice_data();
            }

            if ($notice)
                $data = array('msg' => $notice->message, 'time' => time_took($notice->date, Date('Y-m-d H:i:s')));
            else
                $data = array('msg' => '', 'time' => '');

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }


    public function call_waiter()
    {
        if (!$this->input->is_ajax_request())
            show_404();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('order_id', 'Order ID', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('table_id', 'Location ID', 'required|is_natural_no_zero');

        if ($this->form_validation->run() == FALSE) {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, "msg" => "Invalid order selected")));
        } else {
            if (!$this->customer_model->_is_active($this->input->post('order_id'))) {
                $result = array("status" => 0, 'msg' => 'Your session end');
            }
            if (!isset($result)) {
                $result = $this->customer_model->call_waiter();
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($result));
        }
    }

    public function cancel_order()
    {
        if (!$this->input->is_ajax_request())
            show_404();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('details_id', 'Details ID', 'required|is_natural_no_zero');

        if ($this->form_validation->run() == FALSE) {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, 'msg' => 'The order is processed. Please call the waitron.')));
        } else {
            $result = $this->customer_model->cancel_order();
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
        }
    }



    public function request_bill()
    {
		
        if (!$this->input->is_ajax_request())
            show_404();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('order_id', 'Order ID', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('payment', 'Payment method', 'required');
        //$this->form_validation->set_rules('tip', 'Tip', 'decimal');

        if ($this->form_validation->run() == FALSE) {
            $return = array("status" => 2, "msg" => validation_errors());
        } else {
            if (!$this->customer_model->_is_active($this->input->post('order_id'))) {
                $return = array("status" => 0, 'msg' => 'Your session end');
            }

            if (!$this->customer_model->_is_selfpay($this->input->post('order_id'))) {
                $return = array("status" => 0, 'msg' => 'Your order bill payment is already authorized!');
            }
			
            if (!isset($return)) {
                $return = $this->customer_model->pay_bill();
                $this->send_bill();
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($return));
    }


    function table_exists($key)
    {
		
        $valid = $this->customer_model->table_exists($key);

        if (!$valid)
            $this->form_validation->set_message('table_exists', 'Sorry, The location you entered doesnot exist');

        return $valid;
    }

    public function view_menu()
    {
        $data = array();
        $this->page_title    = 'Customer View';
        $this->assets_css    = array('bootstrap.css', 'customer.css');
        $meals               = $this->customer_model->get_meals_menu();
        $order               = $this->customer_model->get_orders($order_id);
        foreach ($order as $od) {
            $data[] = $od->meal_id;
        }
        $this->load->model("settings_model");
        $stock_show_options = $this->settings_model->get_stock_quantity_options();
        $show_avail_stock = true; // can set from config;
        $hide_empty_stock = true; // can set in config;
        if ($stock_show_options) {
            foreach ($stock_show_options as $key => $value) {
                if ($value->index == 'show_avail_stock') {
                    $show_avail_stock = $value->value;
                } elseif ($value->index == 'hide_empty_stock') {
                    $hide_empty_stock = $value->value;
                }
            }
        }
		
		$fromTime = json_decode($this->config->item('work_hours_from'),true);
		$toTime   = json_decode($this->config->item('work_hours_to'),true);
		$timezone = $this->config->item('timezone');
		if(!empty($timezone)) {
			$timeObject = new DateTime($timezone);
		} else {
			$timeObject = new DateTime();
		}
		$timestamp = $timeObject->getTimeStamp();
		$curTime = $timeObject->setTimestamp($timestamp)->format('H:i A');
		$curDay = date('l');
		$status = false;
		if(is_array($fromTime) && is_array($toTime)) {
			foreach($fromTime as $f=>$time) {
				$froms = explode('_',$time);
				$tos   = explode('_',$toTime[$f]);
				if(strtolower($froms[0])== strtolower($curDay) && strtolower($tos[0]) == strtolower($curDay)) {
					$time1 = $froms[1];
					$time2 = $tos[1];
					$start = date('H:i A',strtotime($time1.":00"));
					$end   = date('H:i A',strtotime($time2.":00"));
					if (($start < $curTime) && ($curTime < $end)) {
						$status = TRUE;
						break;
					}
				}
			}
		}
		
		
        $display = $this->customer_model->get_view_status();
        $package = $this->customer_model->get_package();
        $this->render_page('customer/view_menu', array('meals' => $meals, 'order_id' => $order_id, 'order' => $data,  'display' => $display, 'show_avail_stock' => $show_avail_stock, 'hide_empty_stock' => $hide_empty_stock, 'package' => $package, 'status'=>$status));
    }
	
	function ajax() {
		if($this->input->post('action') == 'coordToAddress') {
			$post = $this->input->post();
			$lat = $post['lat'];
			$lng = $post['lng'];
			$url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&key='.$this->config->item('google_key');
			
			$json = @file_get_contents($url);
			$data=json_decode($json);
			$status = $data->status;
			if($status=="OK")
			{
				echo json_encode($data->results[0]->formatted_address);
			} else {
				echo "error";
			}
			die;
		} // end coordToAddress
		
		if($this->input->post('action') == 'select_deselect') {
			$post = $this->input->post();
			$oid = $post['oid'];
			$opt = $post['opt'];
			$user_id = $this->ion_auth->get_user_id();
			if($opt=="sel") {
				$r = $this->dev_model->updateData($this->config->item('SITE_ID') . 'orders',['allocated'=>"1",'allocated_user'=>$user_id],$args=['where'=>['id'=>$oid]]);
			} else if($opt=="des") {
				$res = $this->dev_model->getData($this->config->item('SITE_ID') . 'orders','row_array',$args=['select'=>['allocated_user'],'where'=>['id'=>$oid]]);
				if($res!=null && $res['allocated_user']==$user_id) {
					$r = $this->dev_model->updateData($this->config->item('SITE_ID') . 'orders',['allocated'=>"0",'allocated_user'=>""],$args=['where'=>['id'=>$oid]]);
				} else {
					echo json_encode('Only driver can deselect who selected the order');die;
				}
			}
			echo $r ? json_encode('1'):json_encode('Error found, try again');die;
		}  // select_deselect
		
		if($this->input->post('action') == 'go') {
			$user_id = $this->ion_auth->get_user_id();
			$res = $this->dev_model->getData($this->config->item('SITE_ID') . 'orders','all_array',$args=['select'=>['allocated_user'],'where'=>['allocated_user'=>$user_id,'active'=>'1','allocated'=>'1'],'select'=>['id']]);
			
			if(count($res)==0) {
				echo json_encode('Please order first');die;
			} else {
				$ids = array_column($res,'id');
				foreach($ids as $key=>$val) {
					$r = $this->dev_model->updateData($this->config->item('SITE_ID') . 'order_details',['processed'=>'3'],$args=['where'=>['order_id'=>$val]]);
					$r = $this->dev_model->updateData($this->config->item('SITE_ID') . 'orders',['order_start'=>'3'],$args=['where'=>['id'=>$val]]);
				}
				echo $r?json_encode('1'):json_encode('Error,try again by reloading page');die;
			}
		} // go
		
		if($this->input->post('action') == 'confirm_passcode') {
			$post = $this->input->post();
			$oid = $post['oid'];
			$code = $post['code'];
			$user_id = $this->ion_auth->get_user_id();
			$res = $this->dev_model->getData($this->config->item('SITE_ID') . 'orders','row_array',$args=['select'=>['allocated_user'],'where'=>['allocated_user'=>$user_id,'active'=>'1','id'=>$oid],'select'=>['passcode']]);
			if($res!=null) {
				$dbcode = $res['passcode'];
				if($dbcode == $code) {
					echo json_encode('1');die;
				} else {
					echo json_encode('Passcode do not match, try again');die;
				}
			} else {
				echo json_encode('No record found, try again by reloading page');die;
			}
			
		} // confirm_passcode
		
		if($this->input->post('action') == 'getYesPopup') {
			$post = $this->input->post();
			$data = $post['data'];
			parse_str($data, $output);
			$order_id = $output['order_id'];
			$table_id = getTableId($output['table_id']);
			
			if(empty($order_id) || empty($table_id)) {
				echo json_encode('');die;
			}
			$r = $this->dev_model->getData($this->config->item('SITE_ID') . 'orders','row_array',$args=['where'=>['id'=>$order_id,'table_id'=>$table_id,'payed_by_confirm'=>'2','popup_shown'=>'0','active'=>'1']]);
			if($r!=null) {
				$id = $r['id'];
				$payer_id = $r['payed_by'];
				$this->dev_model->updateData($this->config->item('SITE_ID') . 'orders',['popup_shown'=>'1'],$args=['where'=>['id'=>$id]]);
				$res = $this->dev_model->getData($this->config->item('SITE_ID') . 'orders','row_array',$args=['where'=>['id'=>$payer_id,'active'=>'1']]);
				$result['name'] = isset($res['customer_name'])?$res['customer_name']:"";
				$result['id']	 = isset($id)?$id:"";
				echo json_encode($result);die;
			} else { 
				echo json_encode('');die; 
			} 
		} // getYesPopup
		
		if($this->input->post('action') == 'showConfirmPopup') { 
			$post = $this->input->post();
			$data = $post['data'];
			parse_str($data, $output);
			$order_id = $output['order_id']; 
			$table_id = getTableId($output['table_id']);
			
			if(empty($order_id) || empty($table_id)) {
				echo json_encode('');die;
			} 
			
			$r = $this->dev_model->getData($this->config->item('SITE_ID') . 'orders','row_array',$args=['where'=>['payed_by'=>$order_id,'popup_shown'=>'1','active'=>'1']]);
			
			if($r!=null) {
				$id = $r['id'];
				$confirm = $r['payed_by_confirm'];
				$name = ucwords($r['customer_name']);
				
				if($confirm=="1") {
					$this->dev_model->updateData($this->config->item('SITE_ID') . 'orders',['popup_shown'=>'2'],$args=['where'=>['id'=>$id,'active'=>'1']]);
					$res['name'] = $name." has accepted you to add his bill, click on View Bill";
				} else if($confirm=="0") {
					$this->dev_model->updateData($this->config->item('SITE_ID') . 'orders',['popup_shown'=>'0','payed_by'=>0,'payed_by_confirm'=>'0','self_payment'=>'1'],$args=['where'=>['id'=>$id,'active'=>'1']]);
					$res['name'] = $name." has denied you to add his bill";
				}
				
				echo json_encode($res);die;
			} else {
				echo json_encode('');die;
			}
		} // showConfirmPopup
		 
		if($this->input->post('action') == 'yes') {
			$id = $this->input->post('id');
			if(!id || empty($id)) {
				echo json_encode('Error occured, try again');die;
			}
			$chk = $this->dev_model->getData($this->config->item('SITE_ID') . 'orders','count_array',$args=['where'=>['id'=>$id]]);
			if($chk==0) {
				echo json_encode('Invalid order id, try again');die;
			} else {
				$r = $this->dev_model->updateData($this->config->item('SITE_ID') . 'orders',['payed_by_confirm'=>'1'],$args=['where'=>['id'=>$id]]);
				echo $r?json_encode('1'):json_encode('Error!,try again');die;
			}
		} // end yes
		
		if($this->input->post('action') == 'no') {
			$id = $this->input->post('id');
			if(!id || empty($id)) {
				echo json_encode('Error occured, try again');die;
			}
			$chk = $this->dev_model->getData($this->config->item('SITE_ID') . 'orders','count_array',$args=['where'=>['id'=>$id]]);
			if($chk==0) {
				echo json_encode('Invalid order id, try again');die;
			} else {
				$r = $this->dev_model->updateData($this->config->item('SITE_ID') . 'orders',['payed_by_confirm'=>'0','popup_shown'=>'1'],$args=['where'=>['id'=>$id]]);
				echo $r?json_encode('1'):json_encode('Error!,try again');die;
			}
		} // end no
		
		if($this->input->post('action') == 'showOrderNotify') {
			//die;
			$id = $this->input->post('oid');
			if(!$id || empty($id)) {
				echo json_encode('Error occured, try again');die;
			}
			$q = "select od.*,o.id from ".$this->config->item('SITE_ID')."order_details as od INNER JOIN ".$this->config->item('SITE_ID')."orders as o ON od.order_id = o.id where o.id=$id && o.user_waiter_confirm = 1 && od.temp_user > 0 ORDER BY od.id DESC limit 1";
			$chk = $this->dev_model->query($q,'row_array');
			if($chk==null) {
				//echo json_encode('Invalid order id, try again');die;
			} else {
				$cat="";
				$cat = $chk['category'];
				$r = $this->dev_model->updateData($this->config->item('SITE_ID') . 'orders',['user_waiter_confirm'=>2],$args=['where'=>['id'=>$id]]);
				if($r) {
					echo json_encode("Waiter placed ".($cat)." order ");
				} else {
					echo json_encode('');
				}
				$cat="";
				die;
			}
		} // end no
	}
	
}
