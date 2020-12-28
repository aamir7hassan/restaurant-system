<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class to deal with all product related details
 */

class Admin_Settings extends App_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("lib_log");
        $this->load->model("settings_model");
        $this->load->model("waiter_model");
        $this->owners_only();
    }

    public function index()
    {

        $this->page_title    = 'Admin';
        $this->assets_css    = array('dataTables.bootstrap.css');
        $this->assets_js     = array('jquery.dataTables.js', 'dataTables.bootstrap.js');

        $this->load->model('foods_model');
        $foods = $this->foods_model->get_foods();

        $this->template->set_layout('admin');
        $this->form_validation->set_rules('currency_code', 'Currency code', 'trim|xss_clean');
        $this->form_validation->set_rules('payment_mode', 'Payment Option', 'required|is_natural_no_zero');

        if ($this->form_validation->run() == true) {
            $result = $this->settings_model->add_settings();

            if ($result) {
                $this->session->set_flashdata('app_success', 'Settings updated successfully.');
                redirect(site_url('admin/settings/'));
            } else {
                $this->session->set_flashdata('app_error', 'Settings update failed.');
                redirect(site_url('admin/settings/'));
            }
        }
        $this->render_page('admin/settings/index', array('current' => 'basic', 'foods' => $foods, 'accounts' => $accounts));
    }

    public function import()
    {
        $this->page_title    = 'Admin';
        $this->assets_css    = array();
        $this->assets_js     = array('parsely-remote.min.js');
        $this->template->set_layout('admin');
        $this->form_validation->set_rules('csv', 'Import file', 'trim');
        if ($this->form_validation->run() == true) {
            $data = array();
            if ($_FILES['csv']['error'] == 0) {
                $name    = $_FILES['csv']['name'];
                $ext     = strtolower(end(explode('.', $_FILES['csv']['name'])));
                $type    = $_FILES['csv']['type'];
                $tmpName = $_FILES['csv']['tmp_name'];
                if ($ext === 'csv') {
                    $data    = array_map('str_getcsv', file($tmpName));

                    $message = $this->settings_model->import_menu($data);
                    $this->session->set_flashdata('app_success', $message);
                    redirect('admin/settings/import');
                } else {
                    $this->session->set_flashdata('app_error', 'Sorry! Only csv files are allowed');
                    redirect('admin/settings/import');
                }
            } else {
                $this->session->set_flashdata('app_error', 'Sorry! Only csv files are allowed');
                redirect('admin/settings/import');
            }
        }
        $this->render_page('admin/settings/import', array('current' => 'import'));
    }

    public function export()
    {
        $data     = $this->settings_model->get_meals();
        $export   = array();
        $export[] = array('IND', 'Index', 'Name', 'Description', 'Price', 'Take away', 'Special', 'Special Days', 'Special from', 'Special to', 'Active', "Out of stock");
        if ($data !== FALSE) {
            foreach ($data as $item) {
                $export[] = array('meals_' . $item->id, $item->index, $item->name, $item->description, $item->price, $item->take_away, $item->special, $item->special_days, $item->special_from, $item->special_to, $item->active, $item->out_of_stock);
            }
        }

        $data     = $this->settings_model->get_categories();
        $export[] = array('IND', 'Index', 'Name', 'Sort');
        if ($data !== FALSE) {
            foreach ($data as $item) {
                $export[] = array('categories_' . $item->id, $item->index, $item->name, $item->sort);
            }
        }

        $data     = $this->settings_model->get_attributes();
        $export[] = array('IND', 'Index', 'Type', 'Name', 'Values', 'Required', 'Sort');
        if ($data !== FALSE) {
            foreach ($data as $item) {
                $export[] = array('attributes_' . $item->id, $item->index, $item->type, $item->name, $item->values, $item->required, $item->sort);
            }
        }

        $data     = $this->settings_model->get_meal_categories();
        $export[] = array('IND', 'Meal ID', 'Category ID');
        if ($data !== FALSE) {
            foreach ($data as $item) {
                $export[] = array('meal-categories_' . $item->id, $item->meal_id, $item->category_id);
            }
        }

        $data     = $this->settings_model->get_meal_attributes();
        $export[] = array('IND', 'Meal ID', 'Attribute ID');
        if ($data !== FALSE) {
            foreach ($data as $item) {
                $export[] = array('meal-attributes_' . $item->id, $item->meal_id, $item->attribute_id);
            }
        }

        array_to_csv_download($export);
    }

    public function info()
    {
        $this->page_title    = 'Admin';
        $this->assets_css    = array('dataTables.bootstrap.css');
        $this->assets_js     = array('jquery.dataTables.js', 'dataTables.bootstrap.js');

        $this->template->set_layout('admin');

        $this->form_validation->set_rules('company_name', 'Company name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('trading_as', 'Trading name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
        $this->form_validation->set_rules('company_registration', 'Company registration', 'trim|xss_clean');
        $this->form_validation->set_rules('vat', 'VAT', 'trim|xss_clean');
        $this->form_validation->set_rules('telephone_no', 'Telephone no', 'trim|required|xss_clean');
        $this->form_validation->set_rules('primary_email', 'Primary email', 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('secondary_email', 'Secondary email', 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('trading_as', 'Trading name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('contact_person', 'Contact person', 'trim|required|xss_clean');
        $this->form_validation->set_rules('thankyou_message', 'Thank you message', 'trim|xss_clean');
        $this->form_validation->set_rules('opened', 'Opened since', 'trim|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean|min_length[5]');

        if ($this->form_validation->run() == true) {
            $result = $this->settings_model->add_settings();
            $result = $this->settings_model->update_password();
            $result = $this->settings_model->update_email();
            $result = $this->settings_model->update_primary_email();
            $result = $this->settings_model->update_sec_email();

            if ($result) {
                $this->session->set_flashdata('app_success', 'Settings added successfully.');
                redirect(site_url('admin/settings/info'));
            } else {
                $this->session->set_flashdata('app_error', 'Settings update failed. Please verify primary email is not exist on users table');
                redirect(site_url('admin/settings/info'));
            }
        }

        $this->render_page('admin/settings/info', array('current' => 'info'));
    }

    public function order_volume()
    {

        $this->page_title    = 'Admin';
        $this->assets_css    = array('dataTables.bootstrap.css');
        $this->assets_js     = array('jquery.dataTables.js', 'dataTables.bootstrap.js');

        $this->template->set_layout('admin');

        $this->form_validation->set_rules('order_volume_active', 'Volume active', 'trim|xss_clean|required|integer');
        $this->form_validation->set_rules('order_volume_warning_value', 'Volume warning value', 'trim|xss_clean|required|integer');

        if ($this->form_validation->run() == true) {
            $result = $this->settings_model->add_settings();
            if ($result) {
                $this->session->set_flashdata('app_success', 'Order volume updated successfully.');
                redirect(site_url('admin/settings/order_volume'));
            } else {
                $this->session->set_flashdata('app_error', 'Order volume update failed.');
                redirect(site_url('admin/settings/order_volume'));
            }
        }
        $categories = $this->settings_model->categories();
        $this->render_page('admin/settings/order_volume', array('categories' => $categories, 'current' => 'order_volume'));
    }

    public function style()
    {

        $this->page_title    = 'Admin';
        $this->assets_css    = array('dataTables.bootstrap.css');
        $this->assets_js     = array('jquery.dataTables.js', 'dataTables.bootstrap.js');

        $this->template->set_layout('admin');

        $this->form_validation->set_rules('system_colour', 'System colour', 'trim|xss_clean');
        if ($this->form_validation->run() == true) {
            if (!isset($_FILES['images']))
                $result = true;
            else
                $result = $this->upload_files($_FILES['images'], IMAGE_PATH);

            if ($result) {
                $result = $this->settings_model->add_settings($result);
                if ($result) {
                    $this->session->set_flashdata('app_success', 'Style updated successfully.');
                    redirect(site_url('admin/settings/style'));
                } else {
                    $this->session->set_flashdata('app_error', 'Style update failed.');
                    redirect(site_url('admin/settings/style'));
                }
            } else {
                $this->session->set_flashdata('app_error', 'Style update failed.');
                redirect(site_url('admin/settings/style/'));
            }
        }

        $this->render_page('admin/settings/style', array('current' => 'style'));
    }

    public function qr_code()
    {
        $this->page_title    = 'Admin';
        $this->assets_css    = array('dataTables.bootstrap.css');
        $this->assets_js     = array('jquery.dataTables.js', 'dataTables.bootstrap.js');

        $this->template->set_layout('admin');
        $unique = $this->settings_model->get_account_by_sku();

        $this->render_page('admin/settings/qr_code', array('current' => 'qr_code', 'unique' => $unique ? $unique->unique : ''));
    }

    public function update_qr()
    {

        $result = $this->settings_model->update_qr(rtrim($this->config->item('SITE_ID'), "_"));

        if ($result) {
            $this->session->set_flashdata('app_success', 'QR code updated!');
            redirect(site_url('admin/settings/qr_code/'));
        } else {
            $this->session->set_flashdata('app_error', 'Sorry! Unable to update qr code!');
            redirect(site_url('admin/table/qr_code/'));
        }
    }

    private function upload_files($files, $path = IMAGE_PATH, $title = '')
    {
        $config = array(
            'upload_path'   => IMAGE_LOC,
            'allowed_types' => 'jpg|gif|png|jpeg',
            'overwrite'     => 1,
        );

        $this->load->library('upload', $config);
        $images = array();
        $flag = 0;

        foreach ($files['name'] as $key => $image) {

            if (!empty($image)) {
                $flag = 1;
                $_FILES['images[]']['name'] = $files['name'][$key];
                $_FILES['images[]']['type'] = $files['type'][$key];
                $_FILES['images[]']['tmp_name'] = $files['tmp_name'][$key];
                $_FILES['images[]']['error'] = $files['error'][$key];
                $_FILES['images[]']['size'] = $files['size'][$key];

                $fileName            = $image;

                $config['file_name'] = $fileName;

                $this->upload->initialize($config);

                if ($this->upload->do_upload('images[]')) {
                    $data = $this->upload->data();
                    $images[$key]        = $data['file_name'];
                    if ($key == 'store_background')
                        $this->do_resize_bg($fileName);
                    else
                        $this->do_resize_logo($fileName);
                } else {
                    $error = array('error' => $this->upload->display_errors());
                    die(implode(', ', $error));
                }
            }
        }

        if ($flag == 0)
            return TRUE;
        return count($images) > 0 ? $images : FALSE;
    }

    private function do_resize_logo($filename, $folder = "assets/images/", $width = 347)
    {
        //$filename = $this->input->post('new_val');
        $source_path = "assets/images/" . $filename;
        $target_path = $folder;
        $config_manip = array(
            'image_library' => 'gd2',
            'source_image' => $source_path,
            'new_image' => $target_path,
            'maintain_ratio' => TRUE,
            'create_thumb' => TRUE,
            'thumb_marker' => '',
        );
        $this->load->library('image_lib');
        // Set your config up
        $this->image_lib->initialize($config_manip);

        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
        }
        // clear //
        $this->image_lib->clear();
        return;
    }

    private function do_resize_bg($filename, $folder = "assets/images/backgrounds/", $width = 700)
    {
        //$filename = $this->input->post('new_val');
        $source_path = "assets/images/" . $filename;

        list($width, $height) = getimagesize($source_path);
        $height_perc = (700 / $height);


        $width = (($height_perc * $width));

        $target_path = $folder;
        $config_manip = array(
            'image_library' => 'gd2',
            'source_image' => $source_path,
            'new_image' => $target_path,
            'maintain_ratio' => FALSE,
            'create_thumb' => TRUE,
            'thumb_marker' => '',
            'height' => 700,
            'width' =>     $width
        );
        $this->load->library('image_lib');
        // Set your config up
        $this->image_lib->initialize($config_manip);

        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
        }
        // clear //
        $this->image_lib->clear();
        return;
    }


    public function colour_codes()
    {
        $this->page_title    = 'Admin';
        $this->assets_css    = array('dataTables.bootstrap.css');
        $this->assets_js     = array('jquery.dataTables.js', 'dataTables.bootstrap.js');

        $this->template->set_layout('admin');
        $this->form_validation->set_rules('new_order_colour', 'New order colour', 'trim|xss_clean');

        if ($this->form_validation->run() == true) {
            $formSubmit = $this->input->post('submitForm');
            $result = $this->settings_model->add_settings();

            $this->session->set_flashdata('app_success', 'Settings updated successfully');

            if ($formSubmit == 'formSaveClose')
                redirect('admin');
            else
                redirect('admin/settings/colour_codes');
        }

        $this->render_page('admin/settings/colour_codes', array('current' => 'colour_codes'));
    }

    public function manager_code()
    {
        $this->page_title    = 'Admin';

        $this->assets_css    = array('dataTables.bootstrap.css');
        $this->assets_js     = array('jquery.dataTables.js', 'dataTables.bootstrap.js');

        $this->template->set_layout('admin');

        $this->form_validation->set_rules('manager_code', 'Manager code', 'trim|required|alpha_numeric|min_length[6]');

        if ($this->form_validation->run() == true) {
            $formSubmit = $this->input->post('submitForm');
            $result = $this->settings_model->add_settings();

            $this->session->set_flashdata('app_success', 'Settings updated successfully');

            if ($formSubmit == 'formSaveClose')
                redirect('admin');
            else
                redirect('admin/settings/manager_code');
        }

        $this->render_page('admin/settings/manager_code', array('current' => 'manager_code'));
    }

    public function new_food($id = '')
    {
        $this->page_title    = 'Admin';
        $this->assets_css    = array();
        $this->assets_js     = array();
        $title               = 'Add';
        $food                = (object) array();
        if (is_numeric($id)) {
            $food = $this->foods_model->get_foods($id);
            if (!$food)
                show_404();
            $title = 'Update';
        }

        $this->form_validation->set_rules('food_name', 'Food Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('id', 'Food ID', 'trim|xss_clean|integer');

        if ($this->form_validation->run() == true) {
            $formSubmit = $this->input->post('submitForm');
            $result = $this->foods_model->update($id);
            if ($result) {
                $this->session->set_flashdata('app_success', 'New food type ' . strtolower($title) . 'd successfully!');
                if ($formSubmit == 'formSaveClose') {
                    redirect('admin/foods');
                    die;
                } else if ($formSubmit == 'formSaveCloseNew') {
                    redirect('admin/foods/new_food');
                    die;
                } else {
                    redirect('admin/foods/new_food/' . $id);
                    die;
                }
            } else {
                $this->session->set_flashdata('app_error', 'Sorry! Unable to ' . strtolower($title) . ' food type');
                redirect(site_url('admin/foods/new_food/'));
            }
        }
        $data = array(
            'food'      => $food,
            'title'     => $title,
        );
        $this->template->set_layout('admin');
        $this->render_page('admin/foods/new_food', $data);
    }


    public function waiter_codes()
    {
        $this->page_title    = 'Admin';

        $this->assets_css    = array('dataTables.bootstrap.css');
        $this->assets_js     = array('jquery.dataTables.js', 'dataTables.bootstrap.js');

        $this->template->set_layout('admin');

        $waiter_codes = $this->waiter_model->waiter_codes();
        $this->form_validation->set_rules('waiter_code', 'Authorisation code', 'trim|required|alpha_numeric|min_length[4]');
        $this->form_validation->set_rules('waiter_name', 'Waiter Name', 'trim|required|min_length[3]');

        if ($this->form_validation->run() == true) {
            $formSubmit = $this->input->post('submitForm');
            $result = $this->waiter_model->add_waiter_code();

            if ($result) {
                $this->session->set_flashdata('app_success', 'Waiter code added successfully');
            } else {
                $this->session->set_flashdata('app_error', 'Sorry! Unable to add waiter code');
            }

            if ($formSubmit == 'formSaveClose')
                redirect('admin');
            else
                redirect('admin/settings/waiter_codes');
        }

        $this->render_page('admin/settings/waiter_codes', array('current' => 'waiter_codes', 'waiter_codes' => $waiter_codes));
    }

    public function waiter_code($id = "")
    {
        $title               = 'Create';

        if (!empty($id)) {
            $title           = 'Update';
            $attribute       = $this->waiter_model->waiter_codes($id, 'id');
            if (!$attribute)
                show_404();
        }


        $this->page_title    = 'Waiter Code';
        $this->assets_css    = array();
        $this->assets_js     = array('parsely-remote.min.js');
        $this->template->set_layout('admin');

        $this->form_validation->set_rules('name', 'Waiter Name', 'required');
        $this->form_validation->set_rules('unique', 'Authorization Code', 'required');

        if ($this->form_validation->run() == true) {
            $formSubmit = $this->input->post('submitForm');
            $result = $this->waiter_model->waiter_code($id);

            $this->session->set_flashdata('app_success', 'Waiter code ' . strtolower($title) . 'd successfully!');
            if ($formSubmit == 'formSaveClose') {
                redirect('admin/settings/waiter_codes');
                die;
            } else if ($formSubmit == 'formSaveCloseNew') {
                redirect('admin/settings/waiter_code');
                die;
            } else {
                redirect('admin/settings/waiter_code' . $result);
                die;
            }
        } else {
            $data = array(
                "name"  => array(
                    'name'                          => 'name',
                    'id'                            => 'name',
                    'type'                          => 'text',
                    'value'                         => isset($attribute->name) ? $attribute->name : $this->form_validation->set_value('name'),
                    'class'                         => 'form-control',
                ),
                "unique"  => array(
                    'name'                          => 'unique',
                    'id'                            => 'unique',
                    'type'                          => 'text',
                    'value'                         => isset($attribute->unique) ? $attribute->unique : $this->form_validation->set_value('unique'),
                    'class'                         => 'form-control',
                ),
            );
        }
        $this->render_page('admin/settings/waiter_code', $data);
    }

    public function delete_code($id)
    {
        if (empty($id) || !is_numeric($id))
            show_404();

        $result = $this->waiter_model->delete_code($id);

        if ($result) {
            $result = $this->session->set_flashdata('app_success', 'Authorisation Code removed successfully!');
        } else {
            $result = $this->session->set_flashdata('app_danger', 'Authorisation Code cannot be deleted!');
        }

        redirect('admin/settings/waiter_codes');
    }

    public function slips()
    {
        $this->page_title    = 'Admin';

        $this->assets_css    = array('dataTables.bootstrap.css');
        $this->assets_js     = array('jquery.dataTables.js', 'dataTables.bootstrap.js');

        $this->template->set_layout('admin');

        $this->form_validation->set_rules('slip_thank_you_message', 'Thank you message', 'trim|required');
        $this->form_validation->set_rules('slip_address', 'Address', 'trim|required');

        
        if ($this->form_validation->run() == true) {
            $result = $this->settings_model->add_settings();
            
            if ($result) {
                $this->session->set_flashdata('app_success', 'Slip settings added successfully.');
                redirect(site_url('admin/settings/slips'));
            } else {
                $this->session->set_flashdata('app_error', 'Slip settings update failed. Please verify primary email is not exist on users table');
                redirect(site_url('admin/settings/slips'));
            }
        }

        $this->render_page('admin/settings/slips', array('current' => 'slips'));
    }
}
