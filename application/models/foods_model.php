<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Foods_Model extends CI_Model
{
    
    public function get_foods($id=''){
        if(!empty($id) && is_numeric($id))
            $this->db->where('id', $id);
        
        $query = $this->db->get($this->config->item('SITE_ID').'food_types');
        if($query->num_rows() > 0){            
            return !empty($id) && is_numeric($id) ? $query->row() : $query->result();
        }
        return FALSE;
    }
    
    public function remove_food($id){
        if(!empty($id) && is_numeric($id)){
            $this->db->where('id', $id);
            return $this->db->delete($this->config->item('SITE_ID').'food_types');
        }
        return FALSE;
    }
    
    public function update($id=''){
        $data           = array();
        $data['name']   = $this->input->post('food_name');
        $data['status'] = 1;
        
        if(empty($id)){
            return $this->db->insert($this->config->item('SITE_ID').'food_types', $data);
        }
        else{
            $this->db->where('id', $id);
            return $this->db->update($this->config->item('SITE_ID').'food_types', $data);
        }
    }
}