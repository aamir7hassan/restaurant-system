<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Takki_Model extends CI_Model
{
    
    public function users_today()
    {
        $sql = 'SELECT count(id) as total_customers FROM `'.$this->config->item('SITE_ID').'orders` WHERE `reserved_time` >= "' . date('Y-m-d').'"';
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {
            $result = $query->row();
            return $result->total_customers;
        }
        return 0;
    }
    
    public function users_total()
    {
        
        $from_date = $this->input->post("date_from");
        $to_date   = $this->input->post("date_to");
        
        $query_parts = array();
        
        $sql = 'SELECT count(id) as total_customers FROM `'.$this->config->item('SITE_ID').'orders` ';
        
        if(!empty($from_date) || !empty($to_date))
        {
            if(!empty($from_date)){
                $query_parts[] = " DATE(reserved_time) >= '".$from_date."'";
            }
            
            if(!empty($to_date)){
                
                $query_parts[] = " DATE(reserved_time) <= '".$to_date."'";
            }
            
            $sql .= ' WHERE '.implode(" AND ", $query_parts);
        }
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {
            $result = $query->row();
            return $result->total_customers;
        }
        return 0;
    }
    
     public function tables_total()
    {
        $from_date = $this->input->post("date_from");
        $to_date   = $this->input->post("date_to");
        
        $query_parts = array();
         
        $sql = 'SELECT count(id) as total_tables FROM `'.$this->config->item('SITE_ID').'tables`';
        
        if(!empty($from_date) || !empty($to_date))
        {
            if(!empty($from_date)){
                $query_parts[] = " DATE(date) >= '".$from_date."'";
            }
            
            if(!empty($to_date)){
                $query_parts[] = " DATE(date) <= '".$to_date."'";
            }
            
            $sql .= ' WHERE '.implode(" AND ", $query_parts);
        }
        
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {
            $result = $query->row();
            return $result->total_tables;
        }
        return 0;
    }
    
    public function waiter_total()
    {
        
        $from_date = $this->input->post("date_from");
        $to_date   = $this->input->post("date_to");
        
        $query_parts = array();
        
        $query = $this->db->select('u.first_name, u.last_name, u.username')->from($this->config->item('SITE_ID').'users as u')->join($this->config->item('SITE_ID').'users_groups as ug', 'ug.user_id = u.id')->join($this->config->item('SITE_ID').'groups g', 'g.id = ug.group_id')->where('g.id', $this->config->item('waiter_index', 'ion_auth'));
        
        if(!empty($from_date))
            $this->db->where('u.created_on >= ', strtotime ($from_date));
        
        if(!empty($to_date))
            $this->db->where('u.created_on <= ', strtotime ($to_date));
        
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    public function get_monthwise_data()
    {
        $from_date = $this->input->get("from_date");
        $to_date   = $this->input->get("to_date");
        
        $query_parts = array();
        
        $sql = 'SELECT sum(price) amount, MONTH(`reserved_time`) month, YEAR(`reserved_time`) year, MONTHNAME(`reserved_time`) name, count(id) as users from '.$this->config->item('SITE_ID').'orders WHERE YEAR(reserved_time) = "'.Date('Y-m-d').'" ';
    
        
        if(!empty($from_date) || !empty($to_date))
        {
            if(!empty($from_date)){
                $query_parts[] = " DATE(reserved_time) >= '".$from_date."'";
            }
            
            if(!empty($to_date)){
                $query_parts[] = " DATE(reserved_time) <= '".$to_date."'";
            }
            
            $sql .= ' AND '.implode(" AND ", $query_parts);
        }
        
        $sql .= " group by MONTH(reserved_time) ";
        
        $query = $this->db->query($sql);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return FALSE;
    }
    
}
    