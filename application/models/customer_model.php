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
        //$this->db->where('id', $key);
		$this->db->where('name', $key);
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
        $data       = array('active' => 0, 'released_time' => date('Y-m-d H:i:s'));

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

    public function check_kitchen_status($catName)
    {
		
		$cat = $this->db->where('name',$catName)->get($this->config->item('SITE_ID') . "categories")->row_array();
		if(count($cat)>0) {
			$cat_id = $cat['id'];
			$qty = $cat['quantity'];
			if($cat['active']=="1") {
				 $query  = "SELECT count(od.id) total FROM `" . $this->config->item('SITE_ID') . "orders` o JOIN `" . $this->config->item('SITE_ID') . "order_details` od ON od.`order_id` = o.id JOIN `" . $this->config->item('SITE_ID') . "meal_categories` mc ON mc.`meal_id` = od.meal_id WHERE `category_id` =".$cat_id." AND o.active = 1 AND od.processed = 0";
				 //echo $query;
				$result = $this->db->query($query);

				if ($result->row()->total >= $qty)
					return FALSE;
				else
					return TRUE;
			}
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
		
        $sql = $this->db->select('id,status')->from($this->config->item('SITE_ID') . 'orders')->where(array('active' => 1, 'id' => $order_id, 'self_payment' => 1))->get();
        if ($sql->num_rows() > 0)
		{
			$res = $sql->row();
			$status = $res->status;
			if(is_null($status) || empty($status)) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
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
		if($this->input->post('option') == 'takeaway') {
			
			$this->db->where('id', $table_id);
		} else {
			$this->db->where('name', $table_id);
		}
		
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
            if (is_numeric($user_id)) {
                $data['user_id'] = $user_id;
			}
			
			
			$data['cell'] 			= $this->input->post('cell');
			if($this->input->post('option') == 'takeaway' || $this->input->post('choice')=="1")
			{
				$data['type'] = 'delivery';
				$data['cell'] 			= $this->input->post('cell1');
				
				$data['address'] 		= $this->input->post('address');
				$data['passcode'] 		= $this->input->post('passcode');
				if(empty($this->input->post('coords'))) {
					$cords = address2cords($this->input->post('address'));
					if(isset($cords['lat']) && !empty($cords['lat'])) {
						$data['coords']		= $cords['lat'].','.$cords['lng'];
					}
				} else {
					$data['coords']		= $this->input->post('coords');
				}
				$data['contact_name'] 	= strtolower($this->input->post('contact_name1'));
			}
			if($this->input->post('choice')=="delivery" || $this->input->post('choice')=="collection") {
				$data['type'] = $this->input->post('choice');
				$data['cell'] = $this->input->post('cell1');
			} 
			if($this->input->post('choice')=="collection") {
				$data['cell'] = $this->input->post('cell2');
				$data['address'] 		= "";
				$data['passcode'] 		= "";
				$data['coords']			= "";
				$data['contact_name'] 	= strtolower($this->input->post('contact_name'));
			}
			if($this->input->post('choice')=="delivery") {
				$data['contact_name'] 	= strtolower($this->input->post('contact_name1'));
			}
			
			
			if($this->input->post('option') == 'takeaway') {
				$contact = $data['contact_name'];
				$cell    = $data['cell'];
				$res = $this->dev_model->getData($this->config->item('SITE_ID') . 'orders','row_array',$args=['where'=>['contact_name'=>$contact,'cell'=>$cell,'active'=>'1']]);
				if($res!=null) {
					return array('order_id' => $res['id'], 'table_id' => $res['table_id']);
				}
			} else if($this->input->post('option') == 'normal') {
				$name = $this->input->post('name');
				$table = $this->input->post('table');
				if(!empty($name) && !empty($table)) {
					$res = $this->dev_model->getData($this->config->item('SITE_ID') . 'orders','row_array',$args=['where'=>['customer_name'=>$name,'table_id'=>$table,'active'=>'1']]);
					
					if($res!=null) {
						return array('order_id' => $res['id'], 'table_id' => $res['table_id']);
					}
				}
			}
			
			// echo "<pre>";
			// var_dump($data);die;
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

        $query = $this->db->select('id, table_id, message, date')->from($this->config->item('SITE_ID') . 'waiter_notice')->where(array('date >=' => date('Y-m-d'), 'status' => 1, "order_id" => $order_id, "table_id" => $table_id))->order_by('id', 'desc')->limit(1)->get();

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
		$userid 	= $this->input->post('temp_user');
		$attr_cats  = $this->input->post('main_cat');
		
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
                            $attribute_names[$attribute->name][] = $attrib->name;
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
                                $attribute_names[$attribute->name][] = $attrib->name;
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
		$data['attr_cats']			= json_encode($attr_cats);
        $data['meal_price']         = number($price * 1);
        $data['attribute_price']    = number($attrib_total);
        $data['attribute_price_log'] = json_encode($attribute_price);
        $data['meal_id']            = $meal_id;
        $data['order_id']           = $order_id;
		$data['temp_user']			= $userid;
        $data['price']              = $total_meal_price;
        $data['qty']                = 1;
        $data['comments']           = $comments;
        $data['category']           = $category;
        $insert_id                  = 0;
		
		//var_dump($data);die;
        for ($i = 1; $i <= $qty; $i++) {
            $result      = $this->db->insert($this->config->item('SITE_ID') . 'order_details', $data);
            $insert_id   = $this->db->insert_id();
            $meal_price += $total_meal_price;
        }

        if ($result) {
            $this->db->where('id', $order_id);
            $this->db->set('price', 'price + ' . (float) $meal_price, FALSE);
			if($userid > 0) {
				$this->db->set('user_waiter_confirm', 1, FALSE);
			}
            $this->db->update($this->config->item('SITE_ID') . 'orders');
            //echo $this->db->last_query();
        }

        return $insert_id;
    }

    public function pay_bill()
    {
		
        $tip          = $this->input->post('tip');
        $payment      = $this->input->post('payment');
		$option		  = $this->input->post('options');
		$email 		  = $this->input->post('email');
		$changefor    = $this->input->post('changefor');
		
        $order_id = $this->input->post('order_id');
		
        $query = $this->db->query('SELECT master, table_id, self_payment from ' . $this->config->item('SITE_ID') . 'orders WHERE id =' . $order_id . ' AND active = 1 ');

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
					$data['change_for'] = $changefor;
                    $data['status']     = 'paybill';
                    $data['billrequest_time']      = date('Y-m-d H:i:s');
                    $data['tip']                   = number($tip);
                    $data['payment_method']        = $payment;
					$data['options']		= $option;
					$data['email']			= $email;

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
		//var_dump($_POST);
		$table_id = getTableId($table_id);
		$res = $this->db->select('temp_user')->where('order_id',$update_order)->get($this->config->item('SITE_ID') . 'order_details')->row_array();
		if(count($res)>0) {
			$user = $res['temp_user'];
			if($user=="0") {
				$this->db->where(array('id' => $update_order, 'table_id' => $table_id));
				return $this->db->update($this->config->item('SITE_ID') . 'orders', array('self_payment' => 0, 'payed_by' => $order_id,'payed_by_confirm'=>'2'));
			} else {
				 $this->db->where(array('id' => $update_order, 'table_id' => $table_id));
				return $this->db->update($this->config->item('SITE_ID') . 'orders', array('self_payment' => 0, 'payed_by' => $order_id,'payed_by_confirm'=>'1'));
			}
		}        
    }
	
	public function splits() {
		$order_id     = $this->input->post('order_id');
        $table_id     = $this->input->post('table_id');
        $update_order = $this->input->post('update_order');
		$table_id = getTableId($table_id);
		
        $this->db->where(array('id' => $update_order, 'table_id' => $table_id));

        return $this->db->update($this->config->item('SITE_ID') . 'orders', array('self_payment' => 1, 'payed_by' => '','payed_by_confirm'=>'3','popup_shown'=> 0));
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
        
        $query      = $this->db->query('SELECT m.out_of_stock,m.show_available, c.`id` as cid, a.id as aid, c.name as cname, m.id as mid, m.`name` as mname, m.special, m.special_days, m.special_from, m.special_to, m.`description`, m.`price`,m.`quantity`, a.required, a.name as aname, a.values, a.type  FROM `' . $this->config->item('SITE_ID') . 'categories` as c LEFT JOIN `' . $this->config->item('SITE_ID') . 'meal_categories` as mc ON mc.`category_id` = c.id LEFT JOIN `' . $this->config->item('SITE_ID') . 'meals` as m ON m.id = mc.meal_id LEFT JOIN `' . $this->config->item('SITE_ID') . 'meal_attributes` as ma ON ma.meal_id = m.id LEFT JOIN `' . $this->config->item('SITE_ID') . 'attributes` as a ON a.id = ma.attribute_id WHERE m.active=1 AND ' . $take_away . ' ORDER BY c.sort, m.sort, a.sort  ASC');
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
		$cats = $this->input->post('main_cat');
		$attr = $this->input->post('attr');
		$attrs = $this->input->post('attrs');
        $price = ($qty * $price);
		if(isset($attrs) && $attrs) {
			$arr=[];
			foreach($attrs as $k=>$v) {
				foreach($v as $key=>$val) {
					$arr[]=$val;
				}
			}
		}
		
		if(isset($attr) && $attr) {
			if(isset($arr) && $arr) {
				$finalArr = $attr+$arr;
			} else {
				$finalArr = $attr;
			}
		} else {
			$finalArr = $arr;
		}
		
        $query = $this->db->select('budget')->from($this->config->item('SITE_ID') . 'orders')->where(array('id' => $ordet_id, 'active' => 1))->get();
        if ($query->num_rows() > 0) {
            $result = $query->row();
			$attrib_total    = 0;
			
			if (isset($cats) && $cats) {
				foreach ($cats as $attr_id => $attr_name) {
					$query = $this->db->select('*')->from($this->config->item('SITE_ID') . 'attributes')->where('id', $attr_id)->get();
					if ($query->num_rows() > 0) {
						$attribute  = $query->row();
						$attributes = json_decode($attribute->values);
						foreach($attributes as $attrib) {
							if(in_array($attrib->name,$finalArr)) {
								$attrib_total      += $attrib->price;
							}
						}
					}
				}
			}
			
			if (isset($result->budget) && $result->budget > 0) {
                $this->db->select_sum('price');
                $query = $this->db->where(array('order_id' => $ordet_id))->get($this->config->item('SITE_ID') . 'order_details');

                $eprice = $query->row();
                if (!isset($eprice->price)) {
                    //return TRUE;
					if($result->budget >= $price) {
						return TRUE;
					} else {
						return FALSE; 
					}
                }

                if (($eprice->price + $price + $attrib_total) <= $result->budget) {
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
		$tid = getTableId($table_id);
        $data['budget'] = $this->input->post('budget');
		
        $this->db->where(array('id' => $order_id, 'table_id' => $tid));
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
		$st = $this->db->select('*')->where('id',$order_id)->get($this->config->item('SITE_ID') . 'orders');
		$status = $st->row()->status;
		$typ = $st->row()->type;
		$custname = $st->row()->customer_name;
		$table_id = $this->input->post('table_id');
		if($typ=="") {
			$table_id = getTableId($table_id);
		}
        
        //if ($this->_is_master($order_id)){

        $q = 'SELECT distinct o.id, o.billrequest_time,o.type,o.allocated,o.payed_by_confirm, o.delivery_charge, o.`customer_name`,o.contact_name, o.tip,o.status,od.`temp_user`,o.`price`, o.`self_payment`, o.`table_id`, od.`meal_id`, o.payed_by, od.`price`,o.email,o.options, od.qty, od.processed, od.attribute,od.attr_cats, od.order_time, od.process_time, m.name, o.payment_method FROM `' . $this->config->item('SITE_ID') . 'orders` o JOIN ' . $this->config->item('SITE_ID') . 'order_details od ON od.order_id = o.id JOIN ' . $this->config->item('SITE_ID') . 'meals m ON m.id = od.meal_id  WHERE o.`active` = 1 && o.table_id='.$table_id.'  ORDER BY o.id ASC';
		//echo $q;
	
        $return['master'] = 1;
		//echo $query;
        //}
        // else{
        //   $query = 'SELECT o.id, o.billrequest_time,o.payed_by_confirm, o.`customer_name`, o.tip, o.`price`, o.`self_payment`, o.`table_id`, od.`meal_id`, o.payed_by, od.`price`, m.name, od.qty, od.order_time, od.process_time,o.payment_method FROM `'.$this->config->item('SITE_ID').'orders` o JOIN '.$this->config->item('SITE_ID').'order_details od ON od.order_id = o.id JOIN '.$this->config->item('SITE_ID').'meals m ON m.id = od.meal_id  WHERE o.`active` = 1 AND o.`id` = '.$order_id.'  ORDER BY o.id ASC';
        //   $return['master'] = 0;

        //}
			
        $query = $this->db->query($q);
		
        if ($query->num_rows() > 0) {
            $return['authorize'] = '';
            $return['tip']       = '0.00';
			$qq = 'select id,username from '.$this->config->item("SITE_ID").'users where role="waiter" || role = "driver"';
			$usersss = $this->db->query($qq);
			$users=[];
			if($usersss->num_rows() >0 ){
				$users = $usersss->result_array();
			}
			
            if ($return['master'] == 1) {
				
                $auth = '';
                $already = $total = array();

                foreach ($query->result() as $result) {
                    $total[$result->id] = ($total[$result->id] + $result->price);
                }
				//$unic = array_unique($query->result());
				$mic=[];
                foreach ($query->result() as $result) {
					
					$oid = $result->id;
                    $payed_by_id = $result->payed_by;
                    $return['payment_option']  = $result->payment_method;
                    //$return['delivery_charge'] = $result->delivery_charge;
					$return['delivery_charge'] = $this->config->item('delivery_fee');
					
                    if ($result->payed_by == $order_id && $result->id != $order_id && (in_array($result->id, $already) === FALSE)) {
                        $already[] = $result->id;
                        $return['authorize'] .= ''; 
                    }
					if($result->payed_by_confirm=="2" && $result->payed_by == $order_id) {
						if(!in_array($oid,$mic)) {
							$return['authorize'] .= '<tr><td>' . $result->customer_name . ' | Bill amount: R' . number($total[$result->id]) . '</td><td><button class="btn btn-primary btn-sm " data-id="' . $result->id . '">Pending</button><br/></td><td></td></tr>';
						}
					} 
					if(($result->payed_by_confirm=="1" && ($result->payed_by>0 || is_null($result->payed_by)) && $result->payed_by == $order_id ) ) {
						if($result->status=="" || $result->status==null) {
							if(!in_array($oid,$mic)) {
								$return['authorize'] .= '<tr><td>' . $result->customer_name . ' | Bill amount: R' . number($total[$result->id]) . '</td><td><button class="btn btn-primary btn-sm split" data-id="' . $result->id . '">Split</button><br/></td><td></td></tr>';
							}
						}
					} 
					if ($result->id != $order_id && (in_array($result->id, $already) === FALSE)) {
						$already[] = $result->id;
						if($result->payed_by_confirm!="2" && ($result->payed_by=="0" || is_null($result->payed_by))){
							
							if($result->status=="" || $result->status==null) {
								if(!in_array($oid,$mic)) {
									$return['authorize'] .= '<tr><td>' . $result->customer_name . ' | Bill amount: R' . number($total[$result->id]) . '</td><td><button class="btn btn-primary btn-sm authorize" data-id="' . $result->id . '">Yes</button><br/></td><td></td></tr>';
								}
							}
						}
                    }
					$mic[] = $result->id;
					if($result->type=='delivery' || $result->type=="collection") {
						$return['authorize'] ="";
					}
					
					if($result->temp_user != '0') {
						$user = (int)$result->temp_user;
						$k = array_search($user,array_column($users,'id'));
						
						if($k>0) {
							$result->customer_name = $users[$k]['username'];
						}	
					}
					
                    if($result->id == $order_id || $result->payed_by == $order_id) {
                        $time            = time_took($result->order_time, $result->process_time);
                        $attributes      = $result->attribute;
                        $attribute       = json_decode($attributes,true);
						
                        $attr_text       = implode(", ", $attribute);
						$attrCats = array_values(json_decode($result->attr_cats,true));
						$arr=[];
						
						if(count($attrCats)>0) {
							foreach($attrCats as $k=>$v) {
								if(array_key_exists($v,$attribute)) {
									$atr = implode(',',$attribute[$v]);
									$arr[] = $v.":".$atr;
								}
							}
						}
						
						$result->attribute = implode(',',$arr);
						$result->attribute .= '_'.$time;
						$return['tip']   = $result->tip;
						if($result->payed_by_confirm == "2") { // add bill needs to confirm from user
							continue;
						} else {
							$payable[] = $result;
						}
                    }
                }
				
            } else {
				
                $payable = array();
                foreach ($query->result() as $result) {
					$time  = time_took($result->order_time, $result->process_time);
                    $payed_by_id     = $result->payed_by;
                    $attributes      = $result->attribute;
                    $attribute       = json_decode($attributes);
					$result->attribute = implode(',',json_decode($attributes));
					$result->attribute .= '_'.$time;
                    $return['tip']       = $result->tip;
                    $payable[] = $result;
                }
            }
			
            
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
			$return['type'] = $typ;
			//$return['type'] = $custname;
			if($status=="paybill") {
				$return['authorize']='';
			}
			//$return['status'] = $status;
			//var_dump($return);die; 
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
