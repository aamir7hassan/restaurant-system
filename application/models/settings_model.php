<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings_Model extends CI_Model
{
    public function add_settings($images)
    {
        $formValues = $this->input->post(NULL, TRUE);
		
        if (isset($images) && is_array($images))
            $formValues = array_merge($formValues, $images);

        foreach ($formValues as $key => $data) {
            if ($key == 'address') {

                $address                    = $data; // Google HQ
                $prepAddr                   = str_replace(' ', '+', $address);
                $api_key                    = 'AIzaSyCFZ2z5T_Dg3pSqFeIxmiJGtvTgA8SY8z0';
                $url                        = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $prepAddr . '&key='.$api_key;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                $geoloc = json_decode(curl_exec($ch), true);

                $location_exist = $geoloc['results'][0]['formatted_address'];
                
                $work_location['lat']       = '';
                $work_location['lng']       = '';

                if ($location_exist) {
                    $latitude                   = $geoloc['results'][0]['geometry']['location']['lat'];
                    $longitude                  = $geoloc['results'][0]['geometry']['location']['lng'];
                    $work_location['lat']       =    $latitude;
                    $work_location['lng']       = $longitude;
                }

                $this->db->where('sku', rtrim($this->config->item('SITE_ID'), '_'));
                $this->db->update('accounts', $work_location);
            } else if ($key == 'trading_as') {
                $this->db->where('sku', rtrim($this->config->item('SITE_ID'), '_'));
                $this->db->update('accounts', array('restaurant_name' => $data));
            } else if ('primary_email' == $key) {
                $this->db->where('sku', rtrim($this->config->item('SITE_ID'), '_'));
                $this->db->update('accounts', array('email' => $data));

                //$this->db->query("DELETE FROM `".$this->config->item('SITE_ID')."users` WHERE id IN(SELECT user_id FROM `".$this->config->item('SITE_ID')."users_groups` WHERE group_id = 1) AND email != 'ettiene@takki.co.za' AND email != '".$data."'" );
                // echo $this->db->last_query(); die;
                $sql   = "SELECT * FROM `" . $this->config->item('SITE_ID') . "users_groups` g join `" . $this->config->item('SITE_ID') . "users` u ON u.id = g.user_id  WHERE `group_id` = 1 ORDER BY u.id ASC LIMIT 1";
                $query = $this->db->query($sql);

                if ($query->num_rows() > 0) {
                    $user = $query->row();
                    $userId = $user->user_id;
                    $result = $this->ion_auth->update($userId, array('email' => $data));
                }
                /*else{
                    $query = $this->db->query("INSERT INTO `".$this->config->item('SITE_ID')."users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`, `site`) VALUES
                       ('', '', 'adam', '15da07717fcc3ec1dc2463c38d867a5de00375bf', NULL, '".$data."', NULL, NULL, NULL, '8e163ec68d9e695621d8d9eb11d2768ea8611553', 0, 1516951365, 1, 'Super ', 'Admin', 'Nuro', NULL, '');

                       ");
                    $userId = $this->db->insert_id();
                    $result = $this->db->query("INSERT INTO `mug_and_bean_58f5f0c016d9d_58f61bdd5bb81_groups`(`id`, `user_id`, `group_id`) VALUES ('', $userId, 1)");
                             
                }*/



                if (!$result)
                    return $result;
            } else if ('telephone_no' == $key) {
                $this->db->where('sku', rtrim($this->config->item('SITE_ID'), '_'));
                $this->db->update('accounts', array('phone' => $data));
            }

            $items = array('index' => $key, 'value' => is_array($data) ? json_encode($data) : $data);

            $this->db->where('index', $key);
            $total = $this->db->count_all_results($this->config->item('SITE_ID') . 'settings');

            if ($total == 0) {
                $this->db->insert($this->config->item('SITE_ID') . 'settings', $items);
            } else {
                $this->db->where('index', $key);
                $this->db->update($this->config->item('SITE_ID') . 'settings', $items);
            }

            if ('business_type' == $key) {
                $this->db->where('sku', rtrim($this->config->item('SITE_ID'), '_'));
                $this->db->update('accounts', array('shop_type' => implode(',', $data)));
            }
        }
        return true;
    }

    public function get_meals()
    {
        $query = $this->db->query('SELECT `id`,`index`,`name`,`description`,`price`,`take_away`,`special`,`special_days`,`special_from`,`special_to`,`active`,`out_of_stock` FROM `' . $this->config->item('SITE_ID') . 'meals` WHERE 1');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function get_categories()
    {
        $query = $this->db->query('SELECT `id`,`index`,`name`,`sort` FROM `' . $this->config->item('SITE_ID') . 'categories` WHERE 1');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function get_meal_categories()
    {
        $query = $this->db->query('SELECT `id`,`meal_id`,`category_id` FROM `' . $this->config->item('SITE_ID') . 'meal_categories` WHERE 1');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function get_stock_quantity_options()
    {
        $query = $this->db->query('SELECT `index`,`value` FROM `' . $this->config->item('SITE_ID') . 'settings` WHERE `index` = "show_avail_stock" OR  `index` = "hide_empty_stock" ');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function get_manager_code()
    {
        $query = $this->db->query('SELECT `index`,`value` FROM `' . $this->config->item('SITE_ID') . 'settings` WHERE `index` = "manager_code" ');
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return FALSE;
    }

    public function get_meal_attributes()
    {
        $query = $this->db->query('SELECT `id`,`meal_id`,`attribute_id` FROM `' . $this->config->item('SITE_ID') . 'meal_attributes` WHERE 1');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function get_meals_cats()
    {
        $query = $this->db->query("SELECT m.`name` as mname, m.`description` mdesc, m.price, c.name as cname FROM `" . $this->config->item('SITE_ID') . "meals` m JOIN " . $this->config->item('SITE_ID') . "meal_categories mc ON mc.`meal_id` = m.id JOIN " . $this->config->item('SITE_ID') . "categories c ON mc.`category_id` = c.id ORDER BY mname ASC");
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function get_attributes()
    {
        $query = $this->db->query('SELECT `id`,`index`,`type`,`name`,`values`,`required`,`sort` FROM `' . $this->config->item('SITE_ID') . 'attributes` WHERE 1');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function import_menu($items)
    {
        $products = $categories = array();
        $index    = 0;
        $message  = 'Menu update failed';
        foreach ($items as $item) {

            $row_type      = explode('_', $item[0]);
            $row_type_id   = strtolower(str_replace('-', '_', $row_type[0]));
            $allowed_types = array('id', 'ind', 'meals', 'categories', 'attributes', 'meal_categories', 'meal_attributes');

            if (in_array($row_type_id, $allowed_types)) {

                if ($row_type_id == 'ind')
                    continue;

                if ($index == 0) {
                    /*
                    $this->db->where('id >=', 1);
                    $this->db->delete($this->config->item('SITE_ID')."meals");
                    $this->db->where('id >=', 1);
                    $this->db->delete($this->config->item('SITE_ID')."attributes");
                    $this->db->where('id >=', 1);
                    $this->db->delete($this->config->item('SITE_ID')."categories");
                    $this->db->where('id >=', 1);
                    $this->db->delete($this->config->item('SITE_ID')."meal_attributes");
                    $this->db->where('id >=', 1);
                    $this->db->delete($this->config->item('SITE_ID')."meal_categories");
                     * 
                     */ }

                $meals = $attributes = $categories = $meal_categories = $meal_attributes = array();
                $meal_id = $category_id = $attribute_id = 0;
                if ($row_type_id == 'meals') {
                    $message  = 'Menu update done';
                    $meal_id = end($row_type);
                    if (empty($item[2]))
                        continue;

                    $meals['index']         = $item[1];
                    $meals['name']          = $item[2];
                    $meals['description']   = $item[3];
                    $meals['price']         = $item[4];
                    $meals['take_away']     = $item[5];
                    $meals['special']       = $item[6];
                    $meals['special_days']  = $item[7];
                    $meals['special_from']  = $item[8];
                    $meals['special_to']    = $item[9];
                    $meals['active']        = $item[10];
                    $meals['out_of_stock']  = $item[11];

                    // echo $meals['name']."<br/>";

                    $m_query = $this->db->where('index', $meals['index'])->get($this->config->item('SITE_ID') . "meals");

                    if ($m_query->num_rows() == 0) {
                        $this->db->insert($this->config->item('SITE_ID') . "meals", $meals);
                        $meal_ids[$meal_id] = $this->db->insert_id();
                    } else {
                        $this->db->where('index', $meals['index']);
                        $this->db->update($this->config->item('SITE_ID') . "meals", $meals);
                        $meal_ids[$meal_id] = $m_query->row()->id;
                    }

                    // echo $this->db->last_query()."<br/><br/><br/>";
                }

                if ($row_type_id == 'categories') {
                    $category_id       = end($row_type);
                    $categories['index']    = $item[1];
                    $categories['name']     = $item[2];
                    $categories['sort']     = $item[3];

                    $c_query = $this->db->where('index', $categories['index'])->get($this->config->item('SITE_ID') . "categories");
                    if ($c_query->num_rows() == 0) {
                        $this->db->insert($this->config->item('SITE_ID') . "categories", $categories);
                        $category_ids[$category_id] = $this->db->insert_id();
                    } else {
                        $this->db->where('index', $categories['index']);
                        $this->db->update($this->config->item('SITE_ID') . "categories", $categories);
                        $category_ids[$category_id] = $c_query->row()->id;
                    }
                }

                if ($row_type_id == 'attributes') {
                    $attribute_id       = end($row_type);
                    $attributes['index']    = $item[1];
                    $attributes['type']     = $item[2];
                    $attributes['name']     = $item[3];
                    $attributes['values']   = $item[4];
                    $attributes['required'] = $item[5];
                    $attributes['sort']     = $item[6];

                    $a_query = $this->db->where('index', $attributes['index'])->get($this->config->item('SITE_ID') . "attributes");
                    if ($a_query->num_rows() == 0) {
                        $this->db->insert($this->config->item('SITE_ID') . "attributes", $attributes);
                        $attribute_ids[$attribute_id] = $this->db->insert_id();
                    } else {
                        $this->db->where('index', $attributes['index']);
                        $this->db->update($this->config->item('SITE_ID') . "attributes", $attributes);
                        $attribute_ids[$attribute_id] = $a_query->row()->id;
                    }
                }

                if ($row_type_id == 'meal_categories') {
                    // $meal_categories['id']           = end($row_type);
                    $meal_categories['meal_id']      = $meal_ids[$item[1]];
                    $meal_categories['category_id']  = $category_ids[$item[2]];

                    $mc_query = $this->db->where('meal_id', $meal_categories['meal_id'])->where('category_id', $meal_categories['category_id'])->get($this->config->item('SITE_ID') . "meal_categories");
                    if ($mc_query->num_rows() == 0) {
                        $this->db->insert($this->config->item('SITE_ID') . "meal_categories", $meal_categories);
                    }
                }

                if ($row_type_id == 'meal_attributes') {
                    // $meal_attributes['id']            = end($row_type);
                    $meal_attributes['meal_id']       = $meal_ids[$item[1]];
                    $meal_attributes['attribute_id']  = $attribute_ids[$item[2]];
                    $ma_query = $this->db->where('meal_id', $meal_attributes['meal_id'])->where('attribute_id', $meal_attributes['attribute_id'])->get($this->config->item('SITE_ID') . "meal_attributes");
                    if ($ma_query->num_rows() == 0) {
                        $this->db->insert($this->config->item('SITE_ID') . "meal_attributes", $meal_attributes);
                    }
                }
                $message = 'Menu updated successfully.';
                ++$index;
            } else { }
        }

        return $message;
    }

    public function remove_banner($banner)
    {
        $this->db->where('index', $banner);
        return $this->db->update($this->config->item('SITE_ID') . 'settings', array('value' => ''));
    }

    public function update_password()
    {
        $password = $this->input->post("password");
        $user_id = $this->input->post("user_id");

        if ($password && $password != '') {
            $password = hash_new_password($password);
            $this->db->where("id", $user_id);
            return $this->db->update($this->config->item('SITE_ID') . "users", array("password" => $password));
        }
        return true;
    }

    public function update_email()
    {
        $primary_email = $this->input->post("primary_email");
        $user_id = $this->input->post("user_id");

        $this->db->where("id", $user_id);
        return $this->db->update($this->config->item('SITE_ID') . "users", array("email" => $primary_email));
    }

    public function update_sec_email()
    {
        $secondary_email = $this->input->post("secondary_email");

        $this->db->where('index', 'secondary_email');
        return $this->db->update($this->config->item('SITE_ID') . 'settings', array('value' => $secondary_email));
    }

    public function update_primary_email()
    {
        $primary_email = $this->input->post("primary_email");

        $this->db->where('index', 'primary_email');
        return $this->db->update($this->config->item('SITE_ID') . 'settings', array('value' => $primary_email));
    }

    public function get_account_by_sku()
    {
        $query = $this->db->select('unique')->from('accounts')->where('sku', rtrim($this->config->item('SITE_ID'), "_"))->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return FALSE;
    }

    public function update_qr()
    {
        $unique_id = uniqid('-STORE-');
        $this->db->where('sku', rtrim($this->config->item('SITE_ID'), "_"));
        return $this->db->update('accounts', array('unique' => $unique_id));
    }

    public function categories()
    {
        $query  = "SELECT `id`,`name` FROM `" . $this->config->item('SITE_ID') . "categories` ORDER BY `sort` ASC";
        $result = $this->db->query($query);
        if ($result->num_rows() > 0)
            return $result->result();
        return FALSE;
    }
}
