<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Waiter_Model extends CI_Model
{
    public function get_order_details($where = '')
    {
        $userId = $this->ion_auth->get_user_id();
        //AND (od.order_time <= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 10 SECOND))
        if ($where == 'new') {
            $query = $this->db->query('SELECT t.id, t.virtual, t.address, t.virtual, t.`name` as tname, od.category, od.`order_time` as order_time, od.qty, o.customer_name, o.under_18, o.user_id, o.delivery_charge, o.reserved_time, od.process_time, od.comments as comment, o.payment_method as mode, od.attribute, o.tip, od.id as oid, od.processed, o.status, o.id as order_id, o.price, m.`name` as mname, payed_by, self_payment as self, master, od.waiter_process_time, od.kitchen_left FROM `' . $this->config->item('SITE_ID') . 'tables` as t JOIN ' . $this->config->item('SITE_ID') . 'waiter_table_relation wt ON wt.table_id = t.id JOIN ' . $this->config->item('SITE_ID') . 'orders as o ON o.`table_id` = t.id AND o.active = 1 LEFT JOIN ' . $this->config->item('SITE_ID') . 'order_details as od ON o.id = od.order_id LEFT JOIN ' . $this->config->item('SITE_ID') . 'meals as m ON m.id = od.meal_id WHERE o.active = 1 AND od.processed = 0 AND wt.waiter_id = ' . $userId . ' ORDER BY t.name ASC, od.id desc');
        }
        elseif ($where == 'delivered') {
            $query = $this->db->query('SELECT t.id, t.virtual, t.address, t.virtual, t.`name` as tname, od.category, od.`order_time` as order_time, od.qty, o.customer_name, o.under_18, o.user_id, o.delivery_charge, o.reserved_time, od.process_time, od.comments as comment, o.payment_method as mode, od.attribute, o.tip, od.id as oid, od.processed, o.status, o.id as order_id, o.price, m.`name` as mname, payed_by, self_payment as self, master, od.waiter_process_time, od.kitchen_left FROM `' . $this->config->item('SITE_ID') . 'tables` as t JOIN ' . $this->config->item('SITE_ID') . 'waiter_table_relation wt ON wt.table_id = t.id JOIN ' . $this->config->item('SITE_ID') . 'orders as o ON o.`table_id` = t.id AND o.active = 1 LEFT JOIN ' . $this->config->item('SITE_ID') . 'order_details as od ON o.id = od.order_id LEFT JOIN ' . $this->config->item('SITE_ID') . 'meals as m ON m.id = od.meal_id WHERE o.active = 1 AND od.processed = 1 AND wt.waiter_id = ' . $userId . ' ORDER BY t.name ASC, od.id desc');
        }
        elseif ($where == 'waiting') {
            $query = $this->db->query('SELECT t.id, t.virtual, t.address, t.virtual, t.`name` as tname, od.category, od.`order_time` as order_time, od.qty, o.customer_name, o.under_18, o.user_id, o.delivery_charge, o.reserved_time, od.process_time, od.comments as comment, o.payment_method as mode, od.attribute, o.tip, od.id as oid, od.processed, o.status, o.id as order_id, o.price, m.`name` as mname, payed_by, self_payment as self, master, od.waiter_process_time, od.kitchen_left FROM `' . $this->config->item('SITE_ID') . 'tables` as t JOIN ' . $this->config->item('SITE_ID') . 'waiter_table_relation wt ON wt.table_id = t.id JOIN ' . $this->config->item('SITE_ID') . 'orders as o ON o.`table_id` = t.id AND o.active = 1 LEFT JOIN ' . $this->config->item('SITE_ID') . 'order_details as od ON o.id = od.order_id LEFT JOIN ' . $this->config->item('SITE_ID') . 'meals as m ON m.id = od.meal_id WHERE o.active = 1 AND od.processed = 2 AND wt.waiter_id = ' . $userId . ' ORDER BY t.name ASC, od.id desc');
        } else {
            $query = $this->db->query('SELECT t.id, t.virtual, t.address, t.virtual, t.`name` as tname, od.category, od.`order_time` as order_time, od.qty, o.customer_name, o.under_18, o.user_id, o.delivery_charge, o.reserved_time, od.process_time, od.comments as comment, o.payment_method as mode, od.attribute, o.tip, od.id as oid, od.processed, o.status, o.id as order_id, o.price, m.`name` as mname, payed_by, self_payment as self, master, od.waiter_process_time, od.kitchen_left FROM `' . $this->config->item('SITE_ID') . 'tables` as t JOIN ' . $this->config->item('SITE_ID') . 'waiter_table_relation wt ON wt.table_id = t.id JOIN ' . $this->config->item('SITE_ID') . 'orders as o ON o.`table_id` = t.id AND o.active = 1 LEFT JOIN ' . $this->config->item('SITE_ID') . 'order_details as od ON o.id = od.order_id LEFT JOIN ' . $this->config->item('SITE_ID') . 'meals as m ON m.id = od.meal_id WHERE o.active = 1 AND wt.waiter_id = ' . $userId . '  ORDER BY t.name ASC, od.id desc');
        }

        //    echo $this->db->last_query();
        if ($query) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_order_detail($orderId = '')
    {
        $userId = $this->ion_auth->get_user_id();

        if ($orderId) {
            $query = $this->db->query('SELECT o.email, t.id, t.virtual, t.address, t.virtual, t.name as tname, od.category, od.order_time as order_time, od.qty, o.customer_name, o.under_18, o.delivery_charge, o.reserved_time, od.process_time, od.comments as comment, o.payment_method as mode, od.attribute, o.tip, od.id as oid, od.processed, o.status, o.id as order_id, o.price, o.tendered, o.tendered_change, od.price as single_price, m.name as mname, payed_by, self_payment as self, master, od.waiter_process_time, od.kitchen_left FROM ' . $this->config->item('SITE_ID') . 'tables as t JOIN ' . $this->config->item('SITE_ID') . 'waiter_table_relation wt ON wt.table_id = t.id JOIN ' . $this->config->item('SITE_ID') . 'orders as o ON o.`table_id` = t.id AND o.active = 1 LEFT JOIN ' . $this->config->item('SITE_ID') . 'order_details as od ON o.id = od.order_id LEFT JOIN ' . $this->config->item('SITE_ID') . 'meals as m ON m.id = od.meal_id WHERE o.active = 1 AND wt.waiter_id = '.$userId.' AND o.id = '.$orderId.' ORDER BY t.name ASC, od.id desc');

            return $query->result();
        } else {
            return null;
        }
    }

    public function get_email($orderId = '')
    {
        $userId = $this->ion_auth->get_user_id();

        if ($orderId) {
            $query = $this->db->query('SELECT o.email FROM ' . $this->config->item('SITE_ID') . 'tables as t JOIN ' . $this->config->item('SITE_ID') . 'waiter_table_relation wt ON wt.table_id = t.id JOIN '. $this->config->item('SITE_ID').'orders as o ON o.table_id = t.id AND o.active = 1 LEFT JOIN ' . $this->config->item('SITE_ID') . 'order_details as od ON o.id = od.order_id WHERE o.active = 1 AND wt.waiter_id = '.$userId.' AND o.id ='.$orderId);

            return $query->row();
        } else {
            return null;
        }
    }

    public function get_waiter_email($orderId = '')
    {
        $userId = $this->ion_auth->get_user_id();

        if ($orderId) {
            $query = $this->db->query('SELECT u.email FROM ' . $this->config->item('SITE_ID') . 'tables as t JOIN ' . $this->config->item('SITE_ID') . 'waiter_table_relation wt ON wt.table_id = t.id JOIN ' . $this->config->item('SITE_ID') . 'orders as o ON o.table_id = t.id AND o.active = 1 JOIN ' . $this->config->item('SITE_ID') . 'users as u ON wt.waiter_id = u.id WHERE o.active = 1 AND wt.waiter_id = '.$userId.' AND o.id ='.$orderId);

            return $query->row();
        } else {
            return null;
        }
    }

    public function order_details($order_id){
        $query = $this->db->query('SELECT tip, payment_method, tendered, tendered_change, reserved_time, customer_name FROM ' .$this->config->item('SITE_ID') . 'orders WHERE id ='.$order_id);

        if ($query->num_rows() > 0)
        {
            return $query->row();
        }
    }

    public function order_tot_price($order_id){
        $data=$this->db
            ->select_sum('price')
            ->from($this->config->item('SITE_ID') . 'order_details')
            ->where('order_id', $order_id)
            ->get();
        return $data->result();
    }

    public function get_orders($orderId = '')
    {
        $userId = $this->ion_auth->get_user_id();
        if ($orderId) {
            $query = $this->db->query('SELECT t.id, t.virtual, t.address, t.virtual, t.`name` as tname, od.category, od.`order_time` as order_time, od.qty, od.price as meal_price, o.customer_name, o.under_18, o.delivery_charge, o.reserved_time, od.process_time, od.comments as comment, o.payment_method as mode, od.attribute, o.tip, od.id as oid, od.processed, o.status, o.id as order_id, o.price, m.`name` as mname, payed_by, self_payment as self, master, od.waiter_process_time, od.kitchen_left FROM `' . $this->config->item('SITE_ID') . 'tables` as t JOIN ' . $this->config->item('SITE_ID') . 'waiter_table_relation wt ON wt.table_id = t.id JOIN ' . $this->config->item('SITE_ID') . 'orders as o ON o.`table_id` = t.id AND o.active = 1 LEFT JOIN ' . $this->config->item('SITE_ID') . 'order_details as od ON o.id = od.order_id LEFT JOIN ' . $this->config->item('SITE_ID') . 'meals as m ON m.id = od.meal_id WHERE o.active = 1 AND wt.waiter_id = ' . $userId . ' AND o.id = ' . $orderId . ' ORDER BY t.name ASC, od.id desc');
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_currency()
    {
        $query = $this->db->query('SELECT * FROM `' . $this->config->item('SITE_ID') . 'settings` WHERE `index`="currency_code"');
        return $query->row();
    }

    public function get_vat()
    {
        $query = $this->db->query('SELECT * FROM `' . $this->config->item('SITE_ID') . 'settings` WHERE `index`="vat"');
        return $query->row();
    }


    public function remove_order()
    {
        $order_id = $this->input->post('oid');
        $query    = $this->db->select('price, order_id')->from($this->config->item('SITE_ID') . 'order_details')->where('id', $order_id)->get();

        if ($query->num_rows() <= 0) {
            return FALSE;
        }
        $order_details      = $query->row();
        $order_main_id      = $order_details->order_id;
        $meal_total_price   = $order_details->price;
        $this->db->where('id', $order_id);
        $res = $this->db->delete($this->config->item('SITE_ID') . 'order_details');

        $this->db->where('id', $order_main_id);
        $this->db->set('price', 'price - ' . (float) $meal_total_price, FALSE);
        $this->db->update($this->config->item('SITE_ID') . 'orders');

        // echo $this->db->last_query();

        return $res;
    }

    public function relate_waiter_with_order($order_id)
    {

        $this->record_login();

        $query = $this->db->select('order_id')->from($this->config->item('SITE_ID') . 'order_details')->where('id', $order_id)->get();
        if ($query->num_rows() > 0) {
            $order = $query->row();
            $query = $this->db->where('order_id', $order->order_id)->get($this->config->item('SITE_ID') . 'waiter_order_relation');
            if ($query->num_rows() == 0) {
                $user_id = $this->ion_auth->get_user_id();
                $this->db->insert($this->config->item('SITE_ID') . 'waiter_order_relation', array('order_id' => $order->order_id, 'waiter_id' => $user_id));
            }
        }
        return;
    }

    public function update_order()
    {
        $order_id = $this->input->post('order_id');
        $query = $this->db->select('processed')->from($this->config->item('SITE_ID') . 'order_details')->where('id', $order_id)->get();

        if ($query->num_rows() > 0) {
            $data = $query->row();
            $processed = $data->processed;

            if ($processed == 1) {
                $this->db->set('processed', 2);
                $this->db->set('waiter_process_time', Date('Y-m-d H:i:s'));

                $this->db->where('id', $order_id);
                $res = $this->db->update($this->config->item('SITE_ID') . 'order_details');
                return $res;
            } else if ($processed == 2) {
                $this->db->set('processed', 1);
                $this->db->set('kitchen_left', Date('Y-m-d H:i:s'));
                $this->db->set('process_time', Date('Y-m-d H:i:s'));

                $this->db->where('id', $order_id);
                $res = $this->db->update($this->config->item('SITE_ID') . 'order_details');
                return $res;
            } else if ($processed == 0) {
                $this->db->set('processed', 2);
                $this->db->set('waiter_process_time', Date('Y-m-d H:i:s'));

                $this->db->where('id', $order_id);
                $res = $this->db->update($this->config->item('SITE_ID') . 'order_details');
                return $res;
            }
        }
        return false;
    }

    public function close_table()
    {
        $order_id   = $this->input->post('order_id');
        $data       = array('active' => 0, 'released_time' => Date('Y-m-d H:i:s'));

        $this->db->where(array('id' => $order_id))->or_where(array('payed_by' => $order_id));
        $result = $this->db->update($this->config->item('SITE_ID') . 'orders', $data);
        return $result;
    }

    public function save_payment()
    {
        $order_id   = $this->input->post('main_id');
        $tip   = $this->input->post('tip');
        $pay_mode   = $this->input->post('pay_mode');
        $closed_by   = $this->input->post('closed_by');
        $tendered   = $this->input->post('tendered');
        $tendered_change   = $this->input->post('tendered_change');

        if ($pay_mode == 'card') {
            $payment_method = 2;
        } else {
            $payment_method = 1;
        }
        
        $data       = array('active' => 0, 'released_time' => Date('Y-m-d H:i:s'), 'status' => 'paid', 'tip' => $tip, 'payment_method' => $payment_method, 'closed_by' => $closed_by, 'tendered' => $tendered, 'tendered_change' => $tendered_change);

        $this->db->where(array('id' => $order_id));
        $result = $this->db->update($this->config->item('SITE_ID') . 'orders', array('status' => 'paid', 'tip' => $tip, 'released_time' => Date('Y-m-d H:i:s'), 'payment_method' => $payment_method, 'tendered' => $tendered, 'tendered_change' => $tendered_change));

        return $result;
    }

    public function get_notice_data()
    {
        $query = $this->db->select('id, table_id, message, date')->from($this->config->item('SITE_ID') . 'waiter_notice')->where(array('date >=' => Date('Y-m-d'), 'status' => 1))->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }
    public function set_notifications()
    {
        $waiter_id = $this->ion_auth->get_user_id();

        $query = $this->db->query('SELECT t.id, t.virtual, t.address, t.virtual, t.`name` as tname, od.category, od.`order_time` as order_time, od.qty, o.customer_name, o.under_18, o.user_id, o.delivery_charge, o.reserved_time, od.process_time, od.comments as comment, o.payment_method as mode, od.attribute, o.tip, od.id as oid, od.processed, o.status, o.id as order_id, o.price, m.`name` as mname, payed_by, self_payment as self, master, od.waiter_process_time, od.kitchen_left FROM `' . $this->config->item('SITE_ID') . 'tables` as t JOIN `' . $this->config->item('SITE_ID') . 'waiter_table_relation` wt ON wt.table_id = t.id JOIN `' . $this->config->item('SITE_ID') . 'orders` as o ON o.`table_id` = t.id AND o.active = 1 LEFT JOIN `' . $this->config->item('SITE_ID') . 'order_details` as od ON o.id = od.order_id LEFT JOIN `' . $this->config->item('SITE_ID') . 'meals` as m ON m.id = od.meal_id WHERE o.active = 1 AND wt.waiter_id = ' . $waiter_id . '  ORDER BY t.name ASC, od.id desc');
        // return $str = $this->db->last_query();

        if ($query && $query->num_rows() > 0) {
            $orders = $query->result();
            $debug = [];

            foreach ($orders as $order) {
                $status = 1;
                $type = " ";
                if ($order->reserved_time >=  date('Y-m-d H:i:s', strtotime('-5 minutes'))) {
                    $type = "new";
                } elseif ($order->kitchen_left !== Null && $order->kitchen_left != "0000-00-00 00:00:00") {
                    $type = "delivered";
                } elseif ($order->order_time <= date('Y-m-d H:i:s', strtotime('-25 minutes')) && ($order->kitchen_left == Null || $order->kitchen_left == "0000-00-00 00:00:00")) {
                    $type = "waiting";
                }
                $not_query = $this->db->select('*')->from($this->config->item('SITE_ID') . 'waiter_notifications')->where(array('order_id' => $order->oid, 'waiter_id' => $waiter_id))->get();
                if ($not_query->num_rows() > 0) {
                    $row = $not_query->row();
                    if ($row->type != $type) {
                        $this->db->where('id', $row->id);
                        $this->db->update($this->config->item('SITE_ID') . "waiter_notifications", array("order_id" => $order->oid, "table_id" => $order->id, "waiter_id" => $waiter_id, "type" => $type, "status" => $status, "date" => Date('Y-m-d')));
                    }
                } else {

                    if ($order->oid && $waiter_id && $order->id) {
                        $this->db->insert($this->config->item('SITE_ID') . "waiter_notifications", array("order_id" => $order->oid, "table_id" => $order->id, "waiter_id" => $waiter_id, "type" => $type, "status" => $status, "date" => Date('Y-m-d')));
                    }
                }
            }
            return $debug;
        }
        return FALSE;
    }
    public function get_notifications()
    {
        $not['new'] = '';
        $not['delivered'] = '';
        $not['waiting'] = '';

        $waiter_id = $this->ion_auth->get_user_id();

        $query = $this->db->select('type')->from($this->config->item('SITE_ID') . 'waiter_notifications')->where(array('status' => 1))->get();
        if ($query && $query->num_rows() > 0) {
            $notifications = $query->result();
            foreach ($notifications as $notification) {
                if ($notification->type == 'new') {
                    $not['new'] = 'notification';
                } elseif ($notification->type  == 'delivered') {
                    $not['delivered'] = 'notification';
                } elseif ($notification->type  == 'waiting') {
                    $not['waiting'] = 'notification';
                }
            }
        }
        return $not;
    }
    public function unset_notifications($show)
    {
        $waiter_id = $this->ion_auth->get_user_id();

        $this->db->where(array("waiter_id" => $waiter_id, "type" => $show, "status" => 1));
        $this->db->update($this->config->item('SITE_ID') . "waiter_notifications", array("status" => 0, "date" => Date('Y-m-d')));
    }
    public function close_notice()
    {
        $this->db->where('id', $this->input->post('notice_id'));
        $result = $this->db->update($this->config->item('SITE_ID') . 'waiter_notice', array('status' => 0));
        return $result;
    }

    public function get_waiter($id)
    {
        $query = $this->db->select('id')->from($this->config->item('SITE_ID') . 'users')->where('name', $name)->get();
        if ($query->num_rows() > 0) {
            return TRUE;
        }
        return FALSE;
    }

    public function record_login()
    {
        $user = $this->ion_auth->user()->row();

        $this->db->where(array('date' => Date('Y-m-d'), 'waiter_id' => $user->id));
        $query = $this->db->get($this->config->item('SITE_ID') . 'waiter_logs');
        if ($query->num_rows() > 0) {
            return;
        }
        $this->db->insert($this->config->item('SITE_ID') . 'waiter_logs', array('waiter_id' => $user->id, 'login' => Date('Y-m-d H:i:s'), 'date' => Date('y-m-d')));
    }

    public function record_logout()
    {
        $user = $this->ion_auth->user()->row();
        $this->db->where(array('date' => Date('Y-m-d'), 'waiter_id' => $user->id));
        $query = $this->db->get($this->config->item('SITE_ID') . 'waiter_logs');
        if ($query->num_rows() > 0) {
            $waiterlog_id = $query->row()->id;
            $this->db->where('id', $waiterlog_id);
            $this->db->update($this->config->item('SITE_ID') . 'waiter_logs', array('logout' => Date('Y-m-d H:i:s')));
        }
    }

    public function release_table()
    {
        $order_id = $this->input->post('main_id');
        $this->db->where('id', $order_id)->or_where('payed_by', $order_id);
        return $this->db->update($this->config->item('SITE_ID') . 'orders', array('active' => 0, 'released_time' => Date('Y-m-d H:i:s')));
    }

    public function remove_table()
    {
        $table_name = $this->input->post('table_name');

        $this->db->where(array('name' => $table_name, 'virtual' => 1));
        return $this->db->delete($this->config->item('SITE_ID') . 'tables');
    }


    public function status_paid()
    {
        $order_id = $this->input->post('order_id');
        $this->db->where('id', $order_id);
        return $this->db->update($this->config->item('SITE_ID') . 'orders', array('status' => 'paid'));
    }

    public function get_waiters()
    {
        $query = $this->db->select('u.first_name, u.last_name, u.username, u.email, u.id, u.waiter_float')->from($this->config->item('SITE_ID') . 'users as u')->join($this->config->item('SITE_ID') . 'users_groups as ug', 'ug.user_id = u.id')->join($this->config->item('SITE_ID') . 'groups g', 'g.id = ug.group_id')->where('g.id', $this->config->item('waiter_index', 'ion_auth'))->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->config->item('SITE_ID') . 'users');
    }

    public function tables($id = "")
    {
        $query = "SELECT t.id, t.name FROM `" . $this->config->item('SITE_ID') . "tables` t";

        $data  = $this->db->query($query);
        return $data->result();
    }


    public function select_table_relation($id)
    {
        $query = "SELECT t.id, t.name FROM `" . $this->config->item('SITE_ID') . "tables` t LEFT JOIN `" . $this->config->item('SITE_ID') . "waiter_table_relation` wt ON wt.table_id = t.id";

        if (!empty($id)) {
            $query .= " WHERE wt.waiter_id = " . $id;
        }

        $data  = $this->db->query($query);
        return $data->result();
    }

    public function create_table_relation($user_id)
    {
        $tables = $this->input->post('tables');

        $this->db->where("waiter_id", $user_id);
        $result = $this->db->delete($this->config->item('SITE_ID') . "waiter_table_relation");
        if ($result) {

            foreach ($tables as $table) {
                $this->db->insert($this->config->item('SITE_ID') . "waiter_table_relation", array("waiter_id" => $user_id, "table_id" => $table));
            }
        }
        return true;
    }

    public function waiter_codes($id = '', $by = '')
    {

        $this->db->select('id, name, unique');

        //$this->db->where('type', '');

        if (!empty($id)) :
            $this->db->where($by, $id);
        endif;

        $query = $this->db->get($this->config->item('SITE_ID') . 'waiters');

        if (!empty($id)) :
            return $query->row();
        endif;

        return $query->result();
    }

    public function waiter_code($id = NULL)
    {
        $name   = $this->input->post("name");
        $unique = $this->input->post('unique');

        if ($id) {
            $this->db->where("id", $id);
            $this->db->update($this->config->item('SITE_ID') . "waiters", array("name" => $name, "unique" => $unique));
            return $result ? $id : FALSE;
        }
        return $result ? $id : FALSE;
    }

    public function delete_code($id)
    {
        $this->db->where('id', $id);
        return  $this->db->delete($this->config->item('SITE_ID') . 'waiters');
    }

    public function add_waiter_code()
    {
        $name = $this->input->post('waiter_name');
        $code = $this->input->post('waiter_code');

        $this->db->insert($this->config->item('SITE_ID') . "waiters", array("name" => $name, "unique" => $code));

        return true;
    }
    public function check_waiter_code_exists($code)
    {
        $query = $this->db->select('id')->from($this->config->item('SITE_ID') . 'waiters')->where('unique', $code)->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->id;
        }
        return false;
    }
}
