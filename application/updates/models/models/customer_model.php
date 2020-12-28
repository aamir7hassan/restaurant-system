<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Customer_Model extends CI_Model
{

    /**
     * @method table_exist to check a table with specified table name exist
     * @param type $key
     * @return boolean
     */
    function table_exists($key)
    {
        $this->db->where('id', $key);
        $query = $this->db->get($this->config->item('SITE_ID') . 'tables');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function close_table()
    {
        $order_id   = $this->input->post('order_id');
        $data       = array('active' => 0, 'released_time' => Date('Y-m-d H:i:s'));

        $this->db->where(array('id' => $order_id))->or_where(array('payed_by' => $order_id));
        $result = $this->db->update($this->config->item('SITE_ID') . 'orders', $data);

        return $result;
    }

    /**
     * @param string $email email to text exist or not
     */

    public function single($email = "")
    {
        $query = $this->db
            ->select("*")
            ->where("email", $email)
            ->get($this->config->item('SITE_ID') . 'users');
        if ($query->num_rows() > 0) {
            $row = $query->result();
            return $row;
        }
        return FALSE;
    }

    public function check_kitchen_status()
    {

        if ($this->config->item('order_volume_active') == 1) {

            $categories = $this->config->item('order_volume_categories') ? json_decode($this->config->item('order_volume_categories')) : array();

            if (!is_array($categories) || count($categories) == 0)
                $categories = array(0);

            $cat_list = implode(",", $categories);

            $query  = "SELECT count(od.id) total FROM `" . $this->config->item('SITE_ID') . "orders` o JOIN `" . $this->config->item('SITE_ID') . "order_details` od ON od.`order_id` = o.id JOIN `" . $this->config->item('SITE_ID') . "meal_categories` mc ON mc.`meal_id` = od.meal_id WHERE `category_id` in(" . $cat_list . ") AND o.active = 1 AND od.processed = 0";
            $result = $this->db->query($query);

            if ($result->row()->total >= $this->config->item('order_volume_warning_value'))
                return FALSE;
            else
                return TRUE;
        }
        return TRUE;
    }

    public function _is_active($order_id)
    {

        $sql = $this->db->select('id')->from($this->config->item('SITE_ID') . 'orders')->where(array('active' => 1, 'id' => $order_id))->get();
        if ($sql->num_rows() > 0)
            return TRUE;
        return FALSE;
    }

    public function _is_paybill($order_id)
    {

        $sql = $this->db->select('id')->from($this->config->item('SITE_ID') . 'orders')->where(array('status' => 'paybill', 'id' => $order_id))->get();
        if ($sql->num_rows() > 0)
            return TRUE;
        return FALSE;
    }

    public function _is_selfpay($order_id)
    {

        $sql = $this->db->select('id')->from($this->config->item('SITE_ID') . 'orders')->where(array('active' => 1, 'id' => $order_id, 'self_payment' => 1))->get();
        if ($sql->num_rows() > 0)
            return TRUE;
        return FALSE;
    }

    public function save_comment()
    {
        if ($this->input->post('comment')) {
            $data['comment']    = $this->input->post('comment');
            $data['order_id']   = $this->input->post('order_id');
            $data['meal_id']    = $this->input->post('meal_id');

            return $this->db->insert($this->config->item('SITE_ID') . 'comments', $data);
        }
    }

    public function reserve_table($name)
    {
        if ($this->input->post('option') == 'takeaway') {
            $name = 'Take Away';
            $cell = $this->input->post('cell');
            $this->db->where('name', $cell);
            $query = $this->db->get($this->config->item('SITE_ID') . 'tables');

            if ($query->num_rows() > 0) {
                $result = $query->row();
                $table_id = $result->id;
            } else {
                $this->db->insert($this->config->item('SITE_ID') . 'tables', array('name' => $cell, 'unique' => $cell, 'seats' => 100, 'virtual' => 1));
                $table_id = $this->db->insert_id();
            }

            $waiters = $this->db->query('SELECT u.id FROM `' . $this->config->item('SITE_ID') . 'users` u JOIN ' . $this->config->item('SITE_ID') . 'users_groups nug ON nug.user_id = u.id JOIN ' . $this->config->item('SITE_ID') . 'groups g ON g.id = nug.group_id WHERE nug.group_id = 3');

            if ($waiters->num_rows() > 0) {
                $waiter = $waiters->result();

                $this->db->where(array('table_id' => $table_id, 'waiter_id' => 1));
                $relation = $this->db->get($this->config->item('SITE_ID') . 'waiter_table_relation');
                
                if ($relation->num_rows() == 0) {
                    $this->db->insert($this->config->item('SITE_ID') . 'waiter_table_relation', array('table_id' => $table_id, 'waiter_id' => 1));
                }

                foreach ($waiter as $w) {
                    $this->db->where(array('table_id' => $table_id, 'waiter_id' => $w->id));
                    $relation = $this->db->get($this->config->item('SITE_ID') . 'waiter_table_relation');
                    if ($relation->num_rows() == 0) {
                        $this->db->insert($this->config->item('SITE_ID') . 'waiter_table_relation', array('table_id' => $table_id, 'waiter_id' => $w->id));
                    }
                }
            }
        } else {
            $table_id  = $this->input->post('table');
        }
        $this->db->where('id', $table_id);
        $query = $this->db->get($this->config->item('SITE_ID') . 'tables');
        if ($query->num_rows() > 0) {
            $table = $query->row();
            $total_seats = $table->seats;

            $data  = array();

            $this->db->where(array('table_id' => $table->id, 'active' => 1));
            $query = $this->db->get($this->config->item('SITE_ID') . 'orders');

            if ($query->num_rows() >= $total_seats)
                return false;

            $this->db->where(array('table_id' => $table->id, 'active' => 1, 'master' => 1));
            $query = $this->db->get($this->config->item('SITE_ID') . 'orders');

            if ($query->num_rows() > 0) {

                $this->db->where(array('table_id' => $table->id, 'active' => 1, 'master' => 1));
                $query = $this->db->update($this->config->item('SITE_ID') . 'orders', array('self_payment' => 1));

                $data['master'] = 0;
            } else {
                $data['master'] = 1;
            }
            //$data['master']         = 1;
            $data['table_id']       = $table->id;
            $data['customer_name']  = $name;
            $data['active']         = 1;
            $data['under_18']       = $this->input->post('over_18');
            $user_id = $this->ion_auth->get_user_id();
            if (is_numeric($user_id))
                $data['user_id'] = $user_id;

            $this->db->insert($this->config->item('SITE_ID') . 'orders', $data);
            $order_id = $this->db->insert_id();
            //echo $this->db->last_query(); die;
            return array('order_id' => $order_id, 'table_id' => $table_id);
        } else {
            return false;
        }
    }

    public function get_notice_data()
    {

        $table_id = $this->input->post("table_id");
        $order_id = $this->input->post("order_id");

        $query = $this->db->select('id, table_id, message, date')->from($this->config->item('SITE_ID') . 'waiter_notice')->where(array('date >=' => Date('Y-m-d'), 'status' => 1, "order_id" => $order_id, "table_id" => $table_id))->order_by('id', 'desc')->limit(1)->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return FALSE;
    }


    public function call_waiter()
    {
        $data['order_id'] = $this->input->post('order_id');
        $data['table_id'] = $this->input->post('table_id');
        $data['message']  = empty($this->input->post('waiter_message')) ? 'Please give attention to table' : $this->input->post('waiter_message');

        $result = $this->db->insert($this->config->item('SITE_ID') . 'waiter_notice', $data);
        if ($result) {
            return array('status' => 1, "msg" => 'Request processed. Please wait!');
        } else {
            return array('status' => 0, "msg" => 'Request Failed.');
        }
    }

    public function pay_bill_details($order_id)
    {

        $query = 'SELECT o.id, o.`customer_name`, o.`price`, o.`self_payment`, o.`table_id`, od.`meal_id`, od.`price`, m.name FROM `' . $this->config->item('SITE_ID') . 'orders` o JOIN ' . $this->config->item('SITE_ID') . 'order_details od ON od.order_id = o.id JOIN ' . $this->config->item('SITE_ID') . 'meals m ON m.id = od.meal_id  WHERE `active` = 1 AND (o.`id` = ' . $order_id . ' OR o.`payed_by` = ' . $order_id . ')  ORDER BY o.id ASC';
        $query          = $this->db->query($query);

        if ($query->num_rows() > 0) {

            if ($query->num_rows() == 1) {

                $result = $query->row();

                if (is_null($result->order_id))
                    return FALSE;
            }
            return $query->result();
        }
        return FALSE;
    }

    public function cancel_order()
    {
        $details_id = $this->input->post('details_id');
        $query      = $this->db->query('SELECT * FROM ' . $this->config->item('SITE_ID') . 'order_details WHERE (order_time > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 10 SECOND)) AND id = ' . $details_id);
        if ($query->num_rows() > 0) {

            $order_details       = $query->row();
            $total_meal_price    = $order_details->price;
            $order_id            = $order_details->order_id;
            //$this->db->where('id', $details_id);
            $this->db->where('order_id', $order_id);
            $this->db->where('order_time', $order_details->order_time);
            $this->db->where('meal_id', $order_details->meal_id);

            if ($this->db->delete($this->config->item('SITE_ID') . 'order_details')) {
                $this->db->where('id', $order_id);
                $this->db->set('price', 'price - ' . (float) $total_meal_price, FALSE);
                $this->db->update($this->config->item('SITE_ID') . 'orders');
                return array('status' => 1, 'msg' => 'Order is removed successfully!');
            }
        }
        return array('status' => 0, 'msg' => 'Sorry your order is being processed. Please call your waitron.');
    }

    public function view_orders()
    {
        $order_id       = $this->input->post('order_id');
        $query_string   = 'SELECT o.id as oid, od.`id` as order_id, m.name, m.price FROM `' . $this->config->item('SITE_ID') . 'orders` as o LEFT JOIN ' . $this->config->item('SITE_ID') . 'order_details as od ON o.id = od.order_id LEFT JOIN ' . $this->config->item('SITE_ID') . 'meals as m ON m.id = od.`meal_id` WHERE   o.`active` = 1 AND  o.id = ' . $order_id;

        $query          = $this->db->query($query_string);

        if ($query->num_rows() > 0) {

            if ($query->num_rows() == 1) {

                $result = $query->row();

                if (is_null($result->order_id))
                    return FALSE;
            }
            return $query->result();
        }
        return FALSE;
    }

    public function check_attributes($meal_id, $attributes)
    {

        if (!is_array($attributes) || count($attributes) == 0) {
            $attributes = array(0);
        }

        $query = $this->db->select('a.name, a.required')->from($this->config->item('SITE_ID') . 'attributes a')->join($this->config->item('SITE_ID') . 'meal_attributes ma', 'a.id = ma.attribute_id')->where(array('ma.meal_id' => $meal_id, 'a.required' => 1))->where_not_in('a.id', $attributes)->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $msg    = array();
            foreach ($result as $re) {
                $msg[] = $re->name;
            }
        }
        return array('status' => TRUE);
    }

    public function order_meal($order_id)
    {
        $meal_id    = $this->input->post('meal_id');
        $order_id   = $this->input->post('order_id');
        $price      = $this->input->post('price');
        $attr       = $this->input->post('attr');
        $attrs      = $this->input->post('attrs');
        $qty        = $this->input->post('qty');
        $comments   = $this->input->post('comment');
        $category   = $this->input->post('category');

        if ($qty < 1 && $qty > 10)
            $qty = 1;

        $data       = array();


        $attribute_names = array();
        $attribute_price = array();
        $attrib_total    = 0;

        if (isset($attr) && $attr) {
            foreach ($attr as $attr_id => $attr_name) {
                $query = $this->db->select('*')->from($this->config->item('SITE_ID') . 'attributes')->where('id', $attr_id)->get();

                if ($query->num_rows() > 0) {
                    $attribute  = $query->row();

                    $attributes = json_decode($attribute->values);
                    foreach ($attributes as $attrib) {
                        if ($attrib->name == $attr_name) {
                            $attribute_names[] = $attrib->name;
                            $attribute_price[] = $attrib->price;
                            $attrib_total      += $attrib->price;
                        }
                    }
                }
            }
        }

        if (isset($attrs) && $attrs) {
            foreach ($attrs as $attr_id => $attr_parts) {
                $query = $this->db->select('*')->from($this->config->item('SITE_ID') . 'attributes')->where('id', $attr_id)->get();

                if ($query->num_rows() > 0) {
                    $attribute  = $query->row();

                    $attributes = json_decode($attribute->values);
                    foreach ($attr_parts as $attr_name) {
                        foreach ($attributes as $attrib) {
                            if ($attrib->name == $attr_name) {
                                $attribute_names[] = $attrib->name;
                                $attribute_price[] = $attrib->price;
                                $attrib_total      += $attrib->price;
                            }
                        }
                    }
                }
            }
        }

        $meal_price                 = 0;
        $total_meal_price           = number(($price + $attrib_total));
        $data['attribute']          = json_encode($attribute_names);
        $data['meal_price']         = number($price * 1);
        $data['attribute_price']    = number($attrib_total);
        $data['attribute_price_log'] = json_encode($attribute_price);
        $data['meal_id']            = $meal_id;
        $data['order_id']           = $order_id;
        $data['price']              = $total_meal_price;
        $data['qty']                = 1;
        $data['comments']           = $comments;
        $data['category']           = $category;
        $insert_id                  = 0;

        for ($i = 1; $i <= $qty; $i++) {
            $result      = $this->db->insert($this->config->item('SITE_ID') . 'order_details', $data);
            $insert_id   = $this->db->insert_id();
            $meal_price += $total_meal_price;
        }

        if ($result) {
            $this->db->where('id', $order_id);
            $this->db->set('price', 'price + ' . (float) $meal_price, FALSE);
            $this->db->update($this->config->item('SITE_ID') . 'orders');
            //echo $this->db->last_query();
        }

        return $insert_id;
    }

    public function pay_bill()
    {
        $tip          = $this->input->post('tip');
        $payment      = $this->input->post('payment');

        $order_id = $this->input->post('order_id');

        $query = $this->db->query('SELECT master, table_id, self_payment from ' . $this->config->item('SITE_ID') . 'orders WHERE id =' . $order_id . ' AND active = 1');

        if ($query->num_rows() > 0) {
            $master = $query->row();

            if ($master->self_payment == 1) {

                //if ($master->master == 1)
                //{
                $query_string = 'SELECT od.`price` FROM `' . $this->config->item('SITE_ID') . 'orders` as o JOIN ' . $this->config->item('SITE_ID') . 'order_details as od ON od.`order_id` = o.id WHERE o.active=1 AND ( o.id = ' . $order_id . ' OR o.payed_by = ' . $order_id . ' ) ';
                //}
                //else
                //{
                //$query_string = 'SELECT od.`price` FROM `'.$this->config->item('SITE_ID').'orders` as o JOIN '.$this->config->item('SITE_ID').'order_details as od ON od.`order_id` = o.id WHERE ( o.id = '.$order_id.' ) ';
                //}
                $query = $this->db->query($query_string);

                if ($query->num_rows() > 0) {
                    $result = $query->result();
                    foreach ($result as $re) {
                        $price += $re->price;
                    }
                    $data       = array();

                    $data['price']      = $price;
                    $data['status']     = 'paybill';
                    $data['billrequest_time']      = Date('Y-m-d H:i:s');
                    $data['tip']                   = number($tip);
                    $data['payment_method']        = $payment;

                    $this->db->where('id', $order_id);
                    $this->db->update($this->config->item('SITE_ID') . 'orders', $data);

                    // echo $this->db->last_query();

                    return array('status' => 1, "msg" => 'Request processed. Please wait!');
                } else {
                    return array('status' => 0, "msg" => 'Sorry. No processed orders found!');
                }
            } else {
                return array('status' => 0, "msg" => 'Sorry. You are not the master user to pay the bill!');
            }
        } else {
            return array('status' => 0, "msg" => 'Sorry! Your order is not found!');
        }
    }

    public function check_requested($update_order = '')
    {
        if (empty($update_order))
            $update_order = $this->input->post('update_order');

        $query = $this->db->query('SELECT id FROM ' . $this->config->item('SITE_ID') . 'orders WHERE (status = "paybill" OR self_payment = 0) AND active = 1 AND id = ' . $update_order);

        if ($query->num_rows() > 0) {
            return TRUE;
        }
        return FALSE;
    }


    public function authorize()
    {
        $order_id     = $this->input->post('order_id');
        $table_id     = $this->input->post('table_id');
        $update_order = $this->input->post('update_order');

        /**$this->db->where("(`active` = 1 AND `table_id` = $table_id AND  `id` = $update_order AND `status` = '' ) OR (`active` = 1 AND `table_id` = $table_id AND `payed_by` = $update_order AND `status` = '')", null, false);**/


        $this->db->where(array('id' => $update_order, 'table_id' => $table_id));

        return $this->db->update($this->config->item('SITE_ID') . 'orders', array('self_payment' => 0, 'payed_by' => $order_id));
    }

    public function get_view_status()
    {
        $query = $this->db->query('SELECT display FROM accounts WHERE display = 1 AND sku = "' . rtrim($this->config->item('SITE_ID'), '_') . '"');
        if ($query->num_rows() > 0) {
            return TRUE;
        }
        return FALSE;
    }


    public function get_package()
    {        
        $query = $this->db->select('sku, name, surname, restaurant_name, packages')->from('accounts')->where('sku', rtrim($this->config->item('SITE_ID'), '_'))->get();
        if($query->num_rows() > 0){
            return $query->row();
        }
        return FALSE;
    }

    public function get_meals_menu()
    {
        $take_away_val  = $this->session->userdata('take_away') ? $this->session->userdata('take_away') : 0;
        $take_away      = $take_away_val == 1 ? 'm.take_away=1' : '(m.take_away=1 OR m.take_away=0)';
        
        $query      = $this->db->query('SELECT m.out_of_stock, c.`id` as cid, a.id as aid, c.name as cname, m.id as mid, m.`name` as mname, m.special, m.special_days, m.special_from, m.special_to, m.`description`, m.`price`,m.`quantity`, a.required, a.name as aname, a.values, a.type  FROM `' . $this->config->item('SITE_ID') . 'categories` as c LEFT JOIN `' . $this->config->item('SITE_ID') . 'meal_categories` as mc ON mc.`category_id` = c.id LEFT JOIN `' . $this->config->item('SITE_ID') . 'meals` as m ON m.id = mc.meal_id LEFT JOIN ' . $this->config->item('SITE_ID') . 'meal_attributes as ma ON ma.meal_id = m.id LEFT JOIN ' . $this->config->item('SITE_ID') . 'attributes as a ON a.id = ma.attribute_id WHERE m.active=1 AND ' . $take_away . ' ORDER BY c.sort, m.sort, a.sort  ASC');
        return $query->result();
    }

    public function get_meals_menu_search()
    {
        $take_away_val  = $this->session->userdata('take_away') ? $this->session->userdata('take_away') : 0;
        $take_away      = $take_away_val == 1 ? 'm.take_away=1' : '(m.take_away=1 OR m.take_away=0)';
        $q          = $this->input->post('q');
        $query = $this->db->query('SELECT m.out_of_stock, c.`id` as cid, a.id as aid, c.name as cname, m.id as mid, m.`name` as mname, m.special, m.`description`, m.`price`, a.required, a.name as aname, a.values, a.type  FROM `' . $this->config->item('SITE_ID') . 'categories` as c INNER JOIN `' . $this->config->item('SITE_ID') . 'meal_categories` as mc ON mc.`category_id` = c.id INNER JOIN `' . $this->config->item('SITE_ID') . 'meals` as m ON m.id = mc.meal_id LEFT JOIN ' . $this->config->item('SITE_ID') . 'meal_attributes as ma ON ma.meal_id = m.id LEFT JOIN ' . $this->config->item('SITE_ID') . 'attributes as a ON a.id = ma.attribute_id WHERE m.active=1 AND ' . $take_away . ' AND (m.`name` like "%' . $q . '%" OR a.`name` like "%' . $q . '%" OR a.`values` like "%' . $q . '%" )  ORDER BY c.sort, m.sort, a.sort  ASC');
        //echo $this->db->last_query();
        return $query->result();
    }

    public function get_orders($order_id)
    {
        $query = $this->db->select('od.meal_id, od.price, o.budget')->from($this->config->item('SITE_ID') . 'orders as o')->join($this->config->item('SITE_ID') . 'order_details as od', 'od.order_id = o.id')->where('o.id', $order_id)->where('o.active', 1)->get();
        return $query->result();
    }

    public function get_budget($order_id)
    {
        $query = $this->db->query('SELECT budget FROM `' . $this->config->item('SITE_ID') . 'orders` WHERE `id`= '.$order_id);
        return $query->row();
    }

    public function _is_inbudget($ordet_id)
    {

        $price = $this->input->post('price');
        $qty   = $this->input->post('qty');

        $price = ($qty * $price);

        $query = $this->db->select('budget')->from($this->config->item('SITE_ID') . 'orders')->where(array('id' => $ordet_id, 'active' => 1))->get();
        if ($query->num_rows() > 0) {
            $result = $query->row();

            if (isset($result->budget) && $result->budget > 0) {

                $this->db->select_sum('price');
                $query = $this->db->where(array('order_id' => $ordet_id))->get($this->config->item('SITE_ID') . 'order_details');

                $eprice = $query->row();

                if (!isset($eprice->price)) {
                    return TRUE;
                }

                if (($eprice->price + $price) <= $result->budget) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function add_budget()
    {
        $order_id = $this->input->post('order_id');
        $table_id = $this->input->post('table_id');

        $data['budget'] = $this->input->post('budget');

        $this->db->where(array('id' => $order_id, 'table_id' => $table_id));
        return $this->db->update($this->config->item('SITE_ID') . 'orders', $data);
    }


    public function add_address()
    {
        $name     = $this->input->post('name') ? $this->input->post('name') : '';
        $address  = $this->input->post('address') ? $this->input->post('address') : '';
        $table_id = $this->input->post('table_id');
        $order_id = $this->input->post('order_id');

        $this->db->where('id', $order_id);
        $this->db->update($this->config->item('SITE_ID') . 'orders', array('delivery_charge' => $this->config->item('delivery_fee') ? $this->config->item('delivery_fee') : 0));

        $this->db->where('id', $table_id);
        return $this->db->update($this->config->item('SITE_ID') . 'tables', array('address' => $name . ', ' . $address));

        return true;
    }

    public function details()
    {

        $order_id = $this->input->post('order_id');
        $table_id = $this->input->post('table_id');

        //if ($this->_is_master($order_id)){

        $query = 'SELECT o.id, o.billrequest_time, o.delivery_charge, o.`customer_name`, o.tip,  o.`price`, o.`self_payment`, o.`table_id`, od.`meal_id`, o.payed_by, od.`price`, od.qty, od.processed, od.attribute, od.order_time, od.process_time, m.name, o.payment_method FROM `' . $this->config->item('SITE_ID') . 'orders` o JOIN ' . $this->config->item('SITE_ID') . 'order_details od ON od.order_id = o.id JOIN ' . $this->config->item('SITE_ID') . 'meals m ON m.id = od.meal_id  WHERE o.`active` = 1 AND o.`table_id` = ' . $table_id . '  ORDER BY o.id ASC';
        $return['master'] = 1;

        //}
        // else{
        //   $query = 'SELECT o.id, o.billrequest_time, o.`customer_name`, o.tip, o.`price`, o.`self_payment`, o.`table_id`, od.`meal_id`, o.payed_by, od.`price`, m.name, od.qty, od.order_time, od.process_time,o.payment_method FROM `'.$this->config->item('SITE_ID').'orders` o JOIN '.$this->config->item('SITE_ID').'order_details od ON od.order_id = o.id JOIN '.$this->config->item('SITE_ID').'meals m ON m.id = od.meal_id  WHERE o.`active` = 1 AND o.`id` = '.$order_id.'  ORDER BY o.id ASC';
        //   $return['master'] = 0;

        //}

        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            $return['authorize'] = '';
            $return['tip']       = '0.00';


            if ($return['master'] == 1) {

                $auth = '';
                $already = $total = array();

                foreach ($query->result() as $result) {
                    $total[$result->id] = ($total[$result->id] + $result->price);
                }

                foreach ($query->result() as $result) {

                    $payed_by_id = $result->payed_by;
                    $return['payment_option']  = $result->payment_method;
                    $return['delivery_charge'] = $result->delivery_charge;
                    if ($result->payed_by == $order_id && $result->id != $order_id && (in_array($result->id, $already) === FALSE)) {
                        $already[] = $result->id;
                        $return['authorize'] .= ''; //'<tr><td>'.$result->customer_name.' | Bill amount: R'.$total[$result->id].'</td><td> <button class="btn btn-secondary btn-sm unauthorize" data-id="'.$result->id.'">Yes</button></td></tr>';
                    }
                    if ($result->id != $order_id && (in_array($result->id, $already) === FALSE)) {
                        $already[] = $result->id;
                        $return['authorize'] .= '<tr><td>' . $result->customer_name . ' | Bill amount: R' . number($total[$result->id]) . '</td><td><button class="btn btn-primary btn-sm authorize" data-id="' . $result->id . '">Yes</button><br/></td><td></td></tr>';
                    }
                    if ($result->id == $order_id || $result->payed_by == $order_id) {
                        $time            = time_took($result->order_time, $result->process_time);

                        $attributes      = $result->attribute;
                        $attribute       = json_decode($attributes);

                        $attr_text       = implode(", ", $attribute);

                        $result->name   .= empty($attr_text) ? '' : ' (' . $attr_text . ') ';
                        $result->name   .= ($result->id == $order_id) ? ' (' . $result->customer_name . ') ' : ' (' . $result->customer_name . ') ';
                        $result->name   .= ' (' . $time . ')';
                        $return['tip']   = $result->tip;
                        $payable[] = $result;
                    }
                }
            } else {
                $payable = array();
                foreach ($query->result() as $result) {
                    $payed_by_id     = $result->payed_by;
                    $attributes      = $result->attribute;
                    $attribute       = json_decode($attributes);


                    $return['tip']       = $result->tip;
                    $time  = time_took($result->order_time, $result->process_time);
                    $result->name   .= implode(", ", $attribute) . ' (' . $time . ')';
                    $payable[] = $result;
                }
            }
            //$payed_by_id = 76;
            if (!empty($payed_by_id)) {
                $query = $this->db->select('customer_name')->from($this->config->item('SITE_ID') . 'orders')->where('id', $payed_by_id)->get();
                if ($query->num_rows() > 0) {
                    $data                = $query->row();
                    $customer_authorized = $data->customer_name;
                } else {
                    $customer_authorized = '';
                }
            }

            if (!empty($return['authorize'])) {
                $return['authorize'] = '<table class="table"><tr><td colspan="2"><strong>Add to your bill?</strong></td></tr>' . $return['authorize'] . '</table>';
            }

            $return['customer_authorized'] = isset($customer_authorized) ? $customer_authorized : '';
            $return['data'] = $payable;
            return $return;
        }
        return array('authorize' => '');
    }

    public function get_vat()
    {
        $query = $this->db->query('SELECT * FROM `' . $this->config->item('SITE_ID') . 'settings` WHERE `index`="vat"');
        return $query->row();
    }

    public function update_email()
    {
        $email    = $this->input->post('email');
        $order_id = $this->input->post('order_id');

        $this->db->where('id', $order_id);
        return $this->db->update($this->config->item('SITE_ID') . 'orders', array('email' => $email));

        return true;
    }
}
