<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports_Model extends CI_Model
{
    public function get_waiter_details($search=""){
        $query = "SELECT l.id, l.waiter_id, `login`,`logout`,l.`date`, u.email FROM `".$this->config->item('SITE_ID')."waiter_logs` l JOIN `".$this->config->item('SITE_ID')."users` u ON u.id = l.waiter_id ORDER BY l.date desc";
        $result = $this->db->query($query);
        if($result->num_rows() > 0){
            return $result->result();
        }
        return FALSE;
    }
    
    public function get_detailed_shift_data($id, $date){
        
        $where_array = array("wr.waiter_id" => $id);
        if($date != ""){
            $where_array['DATE(wr.date) >='] = $date;
        }
        $query = $this->db->select('od.price, od.tip, od.payment_method')->from($this->config->item('SITE_ID')."orders od")->join($this->config->item('SITE_ID')."waiter_order_relation wr", "wr.order_id = od.id")->where($where_array)->get(); 
        
        if($query->num_rows() > 0){
            $data = $query->result();
            return $data;
        }
        return FALSE;
    }

    public function get_table_details($search=""){
        $query = "SELECT t.name,`reserved_time`,`released_time`,`price`,`reserved_time` FROM `".$this->config->item('SITE_ID')."orders` o JOIN  `".$this->config->item('SITE_ID')."tables` t ON t.id = o.table_id WHERE status='paid' ORDER BY `reserved_time` DESC";
        $result = $this->db->query($query);
        if($result->num_rows() > 0){
            return $result->result();
        }
        return FALSE;
    }
    
    public function get_turnover_details($search=""){
        $query = "SELECT t.name, sum(`price`) as total, DATE(`reserved_time`) as date FROM `".$this->config->item('SITE_ID')."orders` o JOIN `".$this->config->item('SITE_ID')."tables` t ON t.id = o.table_id WHERE `status` = 'paid' GROUP BY `table_id`, DATE(`reserved_time`)";

        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->result();
        }
        return FALSE;
    }

    public function getUserDetails(){
 
        $response = array();
        
        $this->db->select('username, first_name, email');
        $q = $this->db->get($this->config->item('SITE_ID').'users');

        $response = $q->result_array();
     
        return $response;
    }
    
    public function get_waiter_sale_group(){

        $query = "SELECT o.user_id,o.tip, sum(o.price) as price, avg(o.released_time - o.reserved_time) as avg_time, u.email as email FROM ".$this->config->item('SITE_ID')."orders o JOIN ".$this->config->item('SITE_ID')."users u on o.user_id = u.id WHERE o.status = 'paid' and o.price !=0.00 GROUP BY u.id";

        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->result();
        }
        return FALSE;
    }

    public function get_waiter_sales(){

        $query = "SELECT o.user_id, sum(o.price) as price, avg(o.released_time - o.reserved_time) as avg_time, u.email as email FROM ".$this->config->item('SITE_ID')."orders o JOIN ".$this->config->item('SITE_ID')."users u on o.user_id = u.id WHERE o.status = 'paid' and o.price !=0.00 GROUP BY u.id";

        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->result();
        }
        return FALSE;
    }

    public function get_waiter_sale($id){        
        $query = "SELECT * FROM ".$this->config->item('SITE_ID')."orders WHERE status='paid' and price !=0.00 and user_id = ".$id;

        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->result();
        }
        return FALSE;
    }

    public function get_product_sale_group(){

        $query = "SELECT o.meal_id, sum(o.price) as price, o.category, u.name as meal_name FROM ".$this->config->item('SITE_ID')."order_details o JOIN ".$this->config->item('SITE_ID')."meals u on o.meal_id = u.id WHERE o.price !=0.00 GROUP BY u.id";

        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->result();
        }
        return FALSE;
    }

    public function get_product_sales(){

        $query = "SELECT o.meal_id, o.price, o.category, o.order_time, u.name as meal_name FROM ".$this->config->item('SITE_ID')."order_details o JOIN ".$this->config->item('SITE_ID')."meals u on o.meal_id = u.id";

        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->result();
        }
        return FALSE;
    }


    public function get_product_sale($id){        
        $query = "SELECT * FROM ".$this->config->item('SITE_ID')."order_details WHERE price !=0.00 and meal_id = ".$id;

        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->result();
        }
        return FALSE;
    }
    

    public function get_table_sale_group(){

        $query = "SELECT o.table_id, sum(o.price) as price, avg(o.released_time - o.reserved_time) as avg_time, u.name FROM ".$this->config->item('SITE_ID')."orders o JOIN ".$this->config->item('SITE_ID')."tables u on o.table_id = u.id WHERE o.price !=0.00 GROUP BY u.id";

        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->result();
        }
        return FALSE;
    }

    public function get_table_sales(){

        $query = "SELECT o.table_id, o.price, o.reserved_time, u.name FROM ".$this->config->item('SITE_ID')."orders o JOIN ".$this->config->item('SITE_ID')."tables u on o.table_id = u.id WHERE price != 0.00";

        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->result();
        }
        return FALSE;
    }

    public function get_table_sale($id){        
        $query = "SELECT * FROM ".$this->config->item('SITE_ID')."orders WHERE price !=0.00 and table_id = ".$id;

        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->result();
        }
        return FALSE;
    }

    public function get_cash_up()
    {
        $query = "SELECT u.id as waiter_id,u.first_name, u.last_name, o.reserved_time as date, date(o.reserved_time) as date_link, o.released_time, o.billrequest_time , t.name as table_name, o.customer_name, m.name as product, od.category, o.price, o.tip, o.payment_method FROM ".$this->config->item('SITE_ID')."orders o INNER JOIN ".$this->config->item('SITE_ID')."users u ON o.user_id = u.id INNER JOIN ".$this->config->item('SITE_ID')."order_details od ON o.id = od.order_id INNER JOIN ".$this->config->item('SITE_ID')."meals m ON m.id = od.meal_id INNER JOIN ".$this->config->item('SITE_ID')."tables t ON t.id = o.table_id where o.price != 0.00 and u.first_name != 'Super' GROUP BY u.id";

        $result = $this->db->query($query);

        if($result->num_rows() > 0)
        {
            return $result->result();
        }

        return FALSE;
    }
    
    public function cash_up($id){        
        $query = "SELECT u.first_name, u.last_name, o.reserved_time as date, date(o.reserved_time) as date_link, o.released_time, o.billrequest_time , t.name as table_name, o.customer_name, m.name as product, od.category, o.price, o.tip, o.payment_method FROM ".$this->config->item('SITE_ID')."orders o INNER JOIN ".$this->config->item('SITE_ID')."users u ON o.user_id = u.id INNER JOIN ".$this->config->item('SITE_ID')."order_details od ON o.id = od.order_id INNER JOIN ".$this->config->item('SITE_ID')."meals m ON m.id = od.meal_id INNER JOIN ".$this->config->item('SITE_ID')."tables t ON t.id = o.table_id where o.price != 0.00 and o.payment_method != 'NULL' and u.id = ".$id;

        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->result();
        }
        return FALSE;
    }

    public function get_total_price($id){

        $query = "SELECT SUM(price) as total_price from ".$this->config->item('SITE_ID')."orders where price != 0.00 and payment_method != 'NULL' and user_id = ".$id;

        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->row();
        }
        return FALSE;
    }

    public function get_total_tip($id){

        $query = "SELECT SUM(tip) as total_tip from ".$this->config->item('SITE_ID')."orders where price != 0.00 and payment_method != 'NULL' and user_id = ".$id;

        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->row();
        }
        return FALSE;
    }

    public function get_float($id){

        $query = "SELECT waiter_float from ".$this->config->item('SITE_ID')."users where id = ".$id;

        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->row();
        }
        return FALSE;
    }

    public function get_cash_payment($id){

        $query = "SELECT SUM(price) as total_cash_price, payment_method from ".$this->config->item('SITE_ID')."orders where payment_method = 1 and user_id = ".$id;

        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->row();
        }
        return FALSE;
    }
    
}

