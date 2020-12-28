<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class to deal with all product related details
 */

class Waiters extends App_Controller
{

    public $oldname;

    public function __construct()
    {
        parent::__construct();
        $this->load->library("lib_log");
        $this->load->model("waiter_model");
        $this->load->model("customer_model");
        $this->load->model("settings_model");
        //$this->admins_only();
    }

    /**
     * @method type meals(type $paramName) Meals page
     */

    public function test($show = 'All')
    {
        $user    = $this->ion_auth->logged_in();
        $user_id = $this->ion_auth->get_user_id();
        if (!$user || !$this->ion_auth->is_waiter($user_id)) {
            // $this->session->set_flashdata('app_error', 'Sorry. You dont have the permission to access this contents!');
            redirect('waiters/login');
            die;
        }
        $this->page_title    = 'Waiter View';
        $this->assets_css    = array('bootstrap.css', 'waiter.css');
        if (!$this->input->is_ajax_request()) {
            $this->template->set_layout('waiter');
        } else {
            $this->template->set_layout('ajax');
        }
        $orders = $this->waiter_model->get_order_details($show);
        $notice = $this->waiter_model->get_notice_data();
        $user   = $this->ion_auth_model->user()->row();

        $this->render_page('waiter/test', array('notices' => $notice,  'orders' => $orders, 'name' => $user->first_name));
    }

    public function index($show = 'All')
    {
        $user    = $this->ion_auth->logged_in();
        $user_id = $this->ion_auth->get_user_id();

        if (!$user || !$this->ion_auth->is_waiter($user_id)) {
            // $this->session->set_flashdata('app_error', 'Sorry. You dont have the permission to access this contents!');
            redirect('waiters/login');
            die;
        }

        $this->page_title    = 'Waiter View';
        $this->assets_css    = array('bootstrap.css', 'waiter.css', 'sweetalert.css');
        $this->assets_js     = array('sweetalert.min.js', 'jquery-1.11.1.min.js', 'bootstrap.min.js');

        if (!$this->input->is_ajax_request()) {
            $this->template->set_layout('waiter');
        } else {
            $this->template->set_layout('ajax');
        }

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('option', 'Option', 'required|trim|xss_clean');

        if ($this->input->post('option') == 'takeaway') {
            $this->form_validation->set_rules('cell', 'Cell no', 'required|trim');
            $this->form_validation->set_rules('waiter_code', 'Waiter code', 'required|trim|callback_check_waiter_code');
            $name = $this->input->post('cell');
            $waiter_code = $this->input->post('waiter_code');
            $this->session->set_userdata('take_away', 1);
        } else if ($this->input->post('option') == 'normal') {
            $this->form_validation->set_rules('customer_name', 'Customer Name', 'required|trim');
            $this->form_validation->set_rules('table', 'Location Number', 'required|trim|integer|callback_table_exists');
            $this->form_validation->set_rules('waiter_code', 'Waiter code', 'required|trim|callback_check_waiter_code');
            $name = $this->input->post('customer_name');
            $waiter_code = $this->input->post('waiter_code');
            $this->form_validation->set_message('check_waiter_code', 'The waiter code is incorrect.');

            $this->session->set_userdata('take_away', 0);
        }

        $waiter_code = $this->input->post('waiter_code');

        $waiter_check = $this->waiter_model->check_waiter_code_exists($waiter_code);


        if ($this->form_validation->run() !== FALSE) {
            if ($waiter_check) {
                $this->load->model("customer_model");
                $waiter_id = $this->check_waiter_code($waiter_code);

                $result = $this->customer_model->reserve_table($name, $waiter_id);

                if (isset($result['order_id']) && is_numeric($result['order_id']) > 0) {
                    $save_data = array(
                        'table_id' => $result['table_id'],
                        'order_id' => $result['order_id'],
                    );
                    $this->session->set_userdata($save_data);
                    $this->session->set_userdata('table_no', $result['table_id']);
                    redirect('waiters/menu/order_' . $result['order_id'] . '/table_' . $result['table_id'], 'refresh');
                } else {
                    $this->session->set_flashdata('app_error', 'Unable to reserve location. Location is full.');
                    redirect(site_url('waiters/index'));
                }
            } else {
                echo '<script>alert("The waiter code is incorrect.");</script>';
                #$code_error = $this->session->set_flashdata('app_error', 'The waiter code is incorrect.');
            }
        }


        $orders = $this->waiter_model->get_order_details($show);

        if ($show != 'All') {
            $this->waiter_model->unset_notifications($show);
        }

        $notice = $this->waiter_model->get_notice_data();
        $debug = $this->waiter_model->set_notifications();
        $this->waiter_model->set_notifications();
        $notifications = $this->waiter_model->get_notifications();
        $user   = $this->ion_auth_model->user()->row();
        
        $package = $this->customer_model->get_package();

        $this->render_page('waiter/index', array('notices' => $notice,  'orders' => $orders, 'name' => $user->first_name, 'notifications' => $notifications, 'package' => $package));
    }

    public function get_ordering($show = 'All')
    {
        $user    = $this->ion_auth->logged_in();
        $user_id = $this->ion_auth->get_user_id();

        if (!$user || !$this->ion_auth->is_waiter($user_id)) {
            // $this->session->set_flashdata('app_error', 'Sorry. You dont have the permission to access this contents!');
            redirect('waiters/login');
            die;
        }

        $this->page_title    = 'Waiter View';
        $this->assets_css    = array('bootstrap.css', 'waiter.css', 'sweetalert.css');
        $this->assets_js     = array('sweetalert.min.js', 'jquery-1.11.1.min.js', 'bootstrap.min.js');

        if (!$this->input->is_ajax_request()) {
            $this->template->set_layout('waiter');
        } else {
            $this->template->set_layout('ajax');
        }

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('option', 'Option', 'required|trim|xss_clean');

        if ($this->input->post('option') == 'takeaway') {
            $this->form_validation->set_rules('cell', 'Cell no', 'required|trim');
            $this->form_validation->set_rules('waiter_code', 'Waiter code', 'required|trim|callback_check_waiter_code');
            $name = $this->input->post('cell');
            $waiter_code = $this->input->post('waiter_code');
            $this->session->set_userdata('take_away', 1);
        } else if ($this->input->post('option') == 'normal') {
            $this->form_validation->set_rules('customer_name', 'Customer Name', 'required|trim');
            $this->form_validation->set_rules('table', 'Location Number', 'required|trim|integer|callback_table_exists');
            $this->form_validation->set_rules('waiter_code', 'Waiter code', 'required|trim|callback_check_waiter_code');
            $name = $this->input->post('customer_name');
            $waiter_code = $this->input->post('waiter_code');
            $this->form_validation->set_message('check_waiter_code', 'The waiter code is incorrect.');

            $this->session->set_userdata('take_away', 0);
        }

        $waiter_code = $this->input->post('waiter_code');

        $waiter_check = $this->waiter_model->check_waiter_code_exists($waiter_code);


        if ($this->form_validation->run() !== FALSE) {
            if ($waiter_check) {


                $this->load->model("customer_model");
                $waiter_id = $this->check_waiter_code($waiter_code);

                $result = $this->customer_model->reserve_table($name, $waiter_id);

                if (isset($result['order_id']) && is_numeric($result['order_id']) > 0) {
                    $save_data = array(
                        'table_id' => $result['table_id'],
                        'order_id' => $result['order_id'],
                    );
                    $this->session->set_userdata($save_data);
                    $this->session->set_userdata('table_no', $result['table_id']);
                    redirect('waiters/menu/order_' . $result['order_id'] . '/table_' . $result['table_id'], 'refresh');
                } else {
                    $this->session->set_flashdata('app_error', 'Unable to reserve location. Location is full.');
                    redirect(site_url('waiters/index'));
                }
            } else {
                echo '<script>alert("The waiter code is incorrect.");</script>';
                #$code_error = $this->session->set_flashdata('app_error', 'The waiter code is incorrect.');
            }
        }


        $orders = $this->waiter_model->get_order_details($show);

        if ($show != 'All') {
            $this->waiter_model->unset_notifications($show);
        }

        $notice = $this->waiter_model->get_notice_data();
        $debug = $this->waiter_model->set_notifications();
        $this->waiter_model->set_notifications();
        $notifications = $this->waiter_model->get_notifications();
        $user   = $this->ion_auth_model->user()->row();

        $this->render_page('waiter/index', array('notices' => $notice,  'orders' => $orders, 'name' => $user->first_name, 'notifications' => $notifications));
    }

    public function check_manager_code($manager_code)
    {
        $this->load->model("settings_model");
        $manager_code_db = $this->settings_model->get_manager_code();
        if (isset($manager_code_db) && $manager_code_db->value == $manager_code) {
            return true;
        } else {
            return false;
        }
    }


    public function check_waiter_code($waiter_code)
    {
        $this->load->model("waiter_model");
        $waiter_code = $this->input->post('waiter_code');
        $waiter_id = $this->waiter_model->check_waiter_code_exists($waiter_code);

        if ($waiter_id) {
            return $waiter_id;
        } else {
            return 0;
        }
    }

    public function manager_code_validate()
    {
        $this->load->model("settings_model");
        $manager_code = $this->input->post('manager_code');
        $manager_code_db = $this->settings_model->get_manager_code();
        if (isset($manager_code_db) && $manager_code_db->value == $manager_code) {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => true)));
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => false, "error" => "Invalid code")));
        }
    }

    public function waiter_code_validate()
    {
        $this->load->model("waiter_model");
        $waiter_code = $this->input->post('code');
        $waiter_id = $this->waiter_model->check_waiter_code_exists($waiter_code);
        if ($waiter_id) {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => true, "waiter_id" => $waiter_id)));
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => false, "error" => "Invalid code")));
        }
    }

    public function menu($order_id, $table_id)
    {
        $oids = explode('_', $order_id);
        $tids = explode('_', $table_id);
        $data = array();
        $order_id = end($oids);
        $table_id = end($tids);
        $this->load->helper('cookie');
        $this->load->model("customer_model");

        /*
        if (!$this->customer_model->_is_active($order_id) || !$this->session->userdata('table_id') || !$this->session->userdata('order_id')) 
        {
            delete_cookie('_takki_order_id');
            delete_cookie('_takki_table_id');
            redirect(site_url('customer/'));
            die;
        }

        set_cookie('_takki_order_id', $order_id, '7200');
        set_cookie('_takki_table_id', $table_id, '7200');*/

        if (empty($order_id) || !is_numeric($order_id) || !is_numeric($table_id) || empty($table_id))
            show_404();

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

        $display = $this->customer_model->get_view_status();

        $this->render_page('waiter/menu', array('meals' => $meals, 'order_id' => $order_id, 'order' => $data, 'table_id' => $table_id, 'display' => $display, 'show_avail_stock' => $show_avail_stock, 'hide_empty_stock' => $hide_empty_stock));
    }

    public function waiter($order_id, $table_id)
    {

        if ($this->input->post('waiter_code')) {
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
            $this->form_validation->set_rules('waiter_code', 'Authorization code', 'required|trim|callback_check_waiter_code');
            $this->form_validation->set_message('check_waiter_code', 'The authorization code is incorrect.');

            if ($this->form_validation->run() !== FALSE) {
                $oids = explode('_', $order_id);
                $tids = explode('_', $table_id);
                $data = array();
                $order_id = end($oids);
                $table_id = end($tids);
                $this->load->model("customer_model");

                if (empty($order_id) || !is_numeric($order_id) || !is_numeric($table_id) || empty($table_id))
                    show_404();

                $this->page_title    = 'Waiter View';
                $this->assets_css    = array('bootstrap.css', 'waiter.css', 'sweetalert.css');
                $this->assets_js     = array('sweetalert.min.js', 'jquery-1.11.1.min.js', 'bootstrap.min.js');

                if (!$this->input->is_ajax_request()) {
                    $this->template->set_layout('waiter');
                }
                $meals               = $this->customer_model->get_meals_menu();
                $order               = $this->customer_model->get_orders($order_id);
                $order_details       = $this->waiter_model->get_order_detail($order_id);

                foreach ($order as $od) {
                    $data[] = $od->meal_id;
                }

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
                $display = $this->customer_model->get_view_status();
                $this->render_page('waiter/new', array('meals' => $meals, 'order_id' => $order_id, 'order' => $data, 'table_id' => $table_id, 'display' => $display, 'show_avail_stock' => $show_avail_stock, 'hide_empty_stock' => $hide_empty_stock, 'order_details' => $order_details));
            } else {
                $this->session->set_flashdata('app_error', 'validation error');
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->render_page('waiter/code');
        }
    }

    function table_exists($key)
    {
        $this->load->model("customer_model");

        $valid = $this->customer_model->table_exists($key);

        if (!$valid)
            $this->form_validation->set_message('table_exists', 'Sorry, The location you entered does not exist');

        return $valid;
    }

    function print_item($id)
    {
        ini_set('memory_limit', '256M');
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        
        $order = $this->waiter_model->get_order_detail($id); 
        $order_tot_price = $this->waiter_model->order_tot_price($id);
        $order_details = $this->waiter_model->order_details($id);

        $pdf->allow_charset_conversion=true;
        $pdf->charset_in='UTF-8';
        
        $pdf->autoLangToFont = true;
        $html = $this->load->view('waiter/slip', array('order' => $order, 'order_tot_price' => $order_tot_price, 'order_details' => $order_details), true);
        
        $pdf->WriteHTML($html);
        
        $output = 'itemreport' . date('Y_m_d_H_i_s') . '_.pdf';
        $pdf->Output("$output", 'I');
    }

    public function email_slip($id)
    {
        ini_set('memory_limit', '256M');
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        
        $order = $this->waiter_model->get_order_detail($id); 
        $order_tot_price = $this->waiter_model->order_tot_price($id);
        $order_details = $this->waiter_model->order_details($id);
        $get_email = $this->waiter_model->get_email($id);


        $pdf->allow_charset_conversion=true;
        $pdf->charset_in='UTF-8';
        
        $pdf->autoLangToFont = true;
        
        $pdfFilePath = $_SERVER["DOCUMENT_ROOT"]."/takki/application/attach/till_slip.pdf";

        $html = $this->load->view('waiter/slip', array('order' => $order, 'order_tot_price' => $order_tot_price, 'order_details' => $order_details), true);
        
        $this->load->helper('file');
        write_file('till_slip.pdf',$html);   

        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "F");

        $body = 'Hi '.$order_details->customer_name.', Thanks for visiting us at '.$this->config->item('company_name').', please see attached slip for the meal you had. ';

        $result = $this->email
                    ->from($this->config->item('primary_email'), $this->config->item('company_name'))
                    ->to($get_email->email)
                    ->subject("Here's your slip")
                    ->message($body)
                    ->attach($pdfFilePath)
                    ->send();

        $this->email->clear($pdfFilePath);


        $store_body = 'Hi '.$this->config->item('company_name').', please see attached slip for the meal ordered by '.$order_details->customer_name.' '.$order->email;

        $result = $this->email
                    ->from($this->config->item('primary_email'), $this->config->item('company_name'))
                    ->to($this->config->item('primary_email'))
                    ->subject("Here's a slip for : ".$order_details->customer_name)
                    ->message($store_body)
                    ->attach($pdfFilePath)
                    ->send();

        $this->email->clear($pdfFilePath);

        $result = $this->email
                    ->from($this->config->item('secondary_email'), $this->config->item('company_name'))
                    ->to($this->config->item('secondary_email'))
                    ->subject("Here's a slip for : ".$order_details->customer_name)
                    ->message($store_body)
                    ->attach($pdfFilePath)
                    ->send();

        $this->email->clear($pdfFilePath);

        redirect('waiters');
    }

    public function print_order($id)
    {
        if ($id) {

            $user    = $this->ion_auth->logged_in();
            $user_id = $this->ion_auth->get_user_id();
            if (!$user || !$this->ion_auth->is_waiter($user_id)) {
                // $this->session->set_flashdata('app_error', 'Sorry. You dont have the permission to access this contents!');
                redirect('waiters/login');
                die;
            }
            
            $order = $this->waiter_model->get_order_detail($id);

            //print_r($orders);
            $this->load->library("Pdf");
            $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetTitle('Order Details');
            $pdf->SetHeaderMargin(30);
            $pdf->SetTopMargin(20);
            $pdf->setFooterMargin(20);
            $pdf->SetAutoPageBreak(true);
            $pdf->SetAuthor('Takki');
            $pdf->SetDisplayMode('real', 'default');

            $pdf->AddPage();

            // create some HTML content
            $html = '<h1>Tax Invoice</h1>
        <table cellpadding="1" cellspacing="1" border="1" style="text-align:center;">';
            if ($order) {
                foreach ($order as $key => $val) {
                    $html .=  '<tr style="text-align:left;"><td>' . $key . '</td><td>' . $val . '</td></tr>';
                }
            }
            $html .= '</table>';

            // output the HTML content
            $pdf->writeHTML($html, true, false, true, false, '');
            // reset pointer to the last page
            $pdf->lastPage();

            //Close and output PDF document
            $pdf->Output('Order' . $id . '.pdf', 'I');
        } else {
            show_404();
        }
    }

    public function remove_order()
    {
        if (!$this->input->is_ajax_request())
            show_404();

        $user    = $this->ion_auth->logged_in();
        $user_id = $this->ion_auth->get_user_id();

        if (!$user || !$this->ion_auth->is_waiter($user_id)) {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0)));
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('oid', 'Order ID', 'required|integer');

            if ($this->form_validation->run() == FALSE) {
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, "order_id_error" => "Invalid order selected")));
                die;
            } else {
                $oid = $this->waiter_model->remove_order();
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 1, "order_id_error" => "")));
                die;
            }
        }
    }


    public function status_paid()
    {
        if (!$this->input->is_ajax_request())
            show_404();

        $user    = $this->ion_auth->logged_in();
        $user_id = $this->ion_auth->get_user_id();

        if (!$user || !$this->ion_auth->is_waiter($user_id)) {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0)));
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('order_id', 'Order ID', 'required|integer');

            if ($this->form_validation->run() == FALSE) {
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, "order_id_error" => "Invalid order selected")));
                die;
            } else {
                $this->waiter_model->remove_table();
                $this->waiter_model->status_paid();
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 1, "order_id_error" => "")));
                die;
            }
        }
    }

    public function release_table()
    {
        if (!$this->input->is_ajax_request())
            show_404();

        $user    = $this->ion_auth->logged_in();
        $user_id = $this->ion_auth->get_user_id();

        $table_name = $this->input->post('table_name');
        $orderId = $this->input->post('main_id');

        if (!$user || !$this->ion_auth->is_waiter($user_id)) {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0)));
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('main_id', 'Order ID', 'required|integer');

            if ($this->form_validation->run() == FALSE) {
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0)));
            } else {
                $this->waiter_model->remove_table();
                $this->waiter_model->release_table();
                $this->email_slip($orderId);
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 1)));
            }
        }
    }

    function update_order()
    {
        if (!$this->input->is_ajax_request())
            show_404();

        $user    = $this->ion_auth->logged_in();
        $user_id = $this->ion_auth->get_user_id();

        $data = $this->waiter_model->update_order();
        echo json_encode($data);
    }

    public function get_order()
    {
        if (!$this->input->is_ajax_request())
            show_404();

        $user    = $this->ion_auth->logged_in();
        $user_id = $this->ion_auth->get_user_id();
        $main_id = $this->input->post('main_id');
        if (!$user || !$this->ion_auth->is_waiter($user_id) || !$main_id) {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0)));
        } else {
            $order = $this->waiter_model->get_orders($main_id);
            $currency = $this->waiter_model->get_currency();
            $vat = $this->waiter_model->get_vat();

            echo json_encode(array("status" => 1, "order_id_error" => "", "order" => $order, "currency" => $currency, "vat" => $vat));
            //$this->output->set_content_type('application/json')->set_output(json_encode(array("status"=> 1, "order_id_error"=> "")));
            die;
        }
    }

    public function close_notice()
    {
        if (!$this->input->is_ajax_request())
            show_404();

        $user    = $this->ion_auth->logged_in();
        $user_id = $this->ion_auth->get_user_id();

        if (!$user || !$this->ion_auth->is_waiter($user_id)) {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0)));
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('notice_id', 'Notice ID', 'required|integer');

            if ($this->form_validation->run() == FALSE) {
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, "order_id_error" => "Invalid order selected")));
                die;
            } else {
                $oid = $this->waiter_model->close_notice();
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 1, "order_id_error" => "")));
                die;
            }
        }
    }

    public function close_table()
    {
        if (!$this->input->is_ajax_request())
            show_404();

        $user    = $this->ion_auth->logged_in();
        $user_id = $this->ion_auth->get_user_id();
        $orderId = $this->input->post('main_id');

        if (!$user || !$this->ion_auth->is_waiter($user_id)) {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0)));
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('order_id', 'Order ID', 'required|integer');

            if ($this->form_validation->run() == FALSE) {
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, "order_id_error" => "Invalid order selected")));
            } else {
                $oid = $this->waiter_model->close_table();
                $this->email_slip($orderId);
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 1, 'oid' => $oid)));
            }
        }
    }

    public function save_payment()
    {
        if (!$this->input->is_ajax_request())
            show_404();

        $user    = $this->ion_auth->logged_in();
        $user_id = $this->ion_auth->get_user_id();

        if (!$user || !$this->ion_auth->is_waiter($user_id)) {
            $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0)));
        } else {
            $main_id = $this->input->post('main_id');
            if (!is_numeric($main_id)) {
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 0, "order_id_error" => "Invalid order selected")));
            } else {
                $oid = $this->waiter_model->save_payment();
                $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 1, 'oid' => $oid)));
            }
        }
    }

    public function login()
    {

        $user    = $this->ion_auth->logged_in();
        $user_id = $this->ion_auth->get_user_id();

        if ($user && $this->ion_auth->is_waiter($user_id)) {
            redirect('waiters');
        }

        $this->page_title    = 'Clerk View';
        $this->assets_css    = array('bootstrap.css', 'waiter.css');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() !== FALSE) {

            $remember = (bool) $this->input->post('remember');
            if ($this->ion_auth->login(strtolower($this->input->post('email')), $this->input->post('password'), $remember)) {
                $this->waiter_model->record_login();
                redirect('waiters');
                die;
            } else {
                $this->session->set_flashdata('app_error', $this->ion_auth->errors());
                redirect('waiters/login');
            }
        }

        $this->template->set_layout('waiter');
        $this->render_page('waiter/login', array());
    }

    public function logout()
    {
        // log the user out

        $this->waiter_model->record_logout();
        $this->ion_auth->logout();

        //$this->session->set_flashdata('app_success', 'You have logged out successfully!');
        // redirect them back to the login page
        redirect('waiters/login');
    }

    public function username_check($str)
    {
        $exist = $this->waiter_model->get_waiter($str);
        if (!$exist) {
            $this->form_validation->set_message('username_check', 'Clerk doesnt exist');
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
