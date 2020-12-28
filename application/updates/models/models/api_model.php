<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_Model extends CI_Model{
    
    public function get_stores($distance=array(), $get_stores = "", $start=NULL, $limit=NULL){
        /*
        if(!empty($get_stores)){
            $store_type = explode(',', $get_stores);
            $ind = 1;
            foreach ($store_type as $type){
                if($ind == 1)
                    $this->db->like('shop_type', $type);
                $this->db->or_like('shop_type', $type);
                ++$ind;
            }
        }*/
        
        if(count($distance) == 0){
            $this->db->where(array('status' => 1, 'delete' => 0));
            if(!is_NULL($limit))
                $this->db->limit($limit, $start);
            $query  = $this->db->get('accounts'); 
        }
        else{
            $limit_text      = !is_NULL($limit) ? 'LIMIT '.$start.', '.$limit : '';
            $distance_filter = '';
            if(isset($distance['more_than']) && !empty($distance['more_than'])){
                $distance_filter = ' distance >= '.$distance['more_than'].' ';
            }
            else if(isset($distance['less_than']) && !empty($distance['less_than'])){
                $distance_filter = ' distance <= '.$distance['less_than'].' ';
            }
            else{
                $distance_filter = ' distance <= 50 ';
            }

            $query = $this->db->query('SELECT *, ( 3959 * acos ( cos ( radians('.$distance['lat'].') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('.$distance['lng'].') ) + sin ( radians('.$distance['lat'].') ) * sin( radians( lat ) ) ) ) AS distance FROM accounts where status=1 and `delete`=0 HAVING '.$distance_filter.' ORDER BY distance');
        }
        
        $result = array();
        if($query->num_rows() > 0){
            return $query->result();
        }
        return $result;
    }
    
    public function get_full_store($distance=array()){
        
        if(count($distance) == 0){
            $this->db->where(array('status' => 1, 'delete' => 0));
            $query  = $this->db->get('accounts');
        }
        else{ 
            $query = $this->db->query('SELECT *, ( 3959 * acos( cos( radians('.$distance['lat'].') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('.$distance['lng'].') ) + sin( radians('.$distance['lat'].') ) * sin( radians( lat ) ) ) ) AS distance FROM accounts HAVING distance < '.$distance['more_than'].' ORDER BY distance');
        }
        
        return $query->num_rows();  
    }
    
    public function get_full_store_search($distance=array()){
        
        if(count($distance) == 0){
            $this->db->where(array('status' => 1, 'delete' => 0));
            $query  = $this->db->get('accounts');
        }
        else{ 
            $query = $this->db->query('SELECT *, ( 3959 * acos( cos( radians('.$distance['lat'].') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('.$distance['lng'].') ) + sin( radians('.$distance['lat'].') ) * sin( radians( lat ) ) ) ) AS distance FROM accounts HAVING distance < '.$distance['more_than'].' ORDER BY distance');
        }
        
        return $query->num_rows();  
    }
    
    public function check_store($sku){
        $this->db->where(array('sku' => $sku, 'status' => 1, 'delete' => 0));
        $query = $this->db->get('accounts');
        if($query->num_rows() > 0)
            return TRUE;
        return FALSE;
    }
    
    public function get_details($sku){
        if (!$this->db->table_exists($sku.'_settings') ){
            return FALSE;
        }
        
        $query = $this->db->get($sku.'_settings');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return FALSE;
    }
    
    public function get_categories($sku){
        if (!$this->db->table_exists($sku.'_categories') ){
            return FALSE;
        }
        $query = $this->db->get($sku.'_categories');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return array();
    }
    
    public function get_meals($sku){
        if (!$this->db->table_exists($sku.'_meals') ){
            return FALSE;
        }
        $query = $this->db->get($sku.'_meals');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return array();
    }
    
    public function update_views($id){
        $this->db->where('sku', $id);
        $this->db->set('views', 'views+1', FALSE);
        return $this->db->update('accounts');
    }
}