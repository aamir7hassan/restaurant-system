<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Table_Model extends CI_Model
{
    
    
    /**
     * @method get_tables - get all tables available
     */
    
    public function get_tables($id = '', $by = ''){
        
        $this->db->select('id, name, seats, qr_code, unique, date');
                 
        if (!empty($id)):
            $this->db->where( $by, $id );
        endif;
		$this->db->where('virtual','0');
        
        $query = $this->db->get($this->config->item('SITE_ID').'tables');
        
        if (!empty($id)):
            return $query->row();
        endif;
        
        return $query->result();
        
    }
    
    /**
     * @method type create_news(type $paramName) Create new table / Update if id exists
     * @return ID on success and false on failure!
     * called on update and create
     */
    
    public function create_new($id = "")
    {
        $name     = $this->input->post('name');
        $seats    = $this->input->post('seats');
        
        $data['name']   = $name;
        $data['seats']  = $seats;

        if (empty($id)) 
        {
            $data['unique'] = strtolower( str_replace( ' ', '-', $name) ).  uniqid('-');
            $this->db->insert($this->config->item('SITE_ID').'tables', $data);
            
            $id = $this->db->insert_id();
            
            $this->db->insert($this->config->item('SITE_ID').'waiter_table_relation', array('waiter_id' => 1, 'table_id' => $id));
            
            return $id;
        }
        else{
            $this->db->where('id', $id);
            $update = $this->db->update($this->config->item('SITE_ID').'tables', $data); 
            
            if ($update){
                return $id;
            }
            return FALSE;
        }

    }
    
    public function update_qr($id)
    {
        $unique_id = uniqid('-LOC-');
        $this->db->where('id', $id);
        return $this->db->update($this->config->item('SITE_ID').'tables', array('unique' => $unique_id));
    }
        /**
     * @method type deletes(type $paramName) Delete an attribute
     * @param int $id Id of attribute to delete
     * @return boolean True on success False on failure
     */
    
    public function delete($id)
    {
        return  $this->db->delete($this->config->item('SITE_ID').'tables', array('id' => $id)); 
    }
    
    public function get_table_id($unique){
        $this->db->where('unique', $unique);
        $query = $this->db->get($this->config->item('SITE_ID').'tables');
        
        if($query->num_rows() > 0){
            $result = $query->row();
            return $result->id;
        }
        return false;
    }
}