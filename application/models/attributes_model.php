<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attributes_Model extends CI_Model
{
    
    
    /**
     * @method get_attributes - get all attributes available
     * @param int $id attribute id conditional
     * @return array with attributes 
     * called from admin/attributes
     */
    
    public function get_attributes($id = '', $by = ''){  
        
        $this->db->select('id, required, name, type, values, date, sort');   
        
        //$this->db->where('type', '');
         
        if (!empty($id)):
            $this->db->where( $by, $id );
        endif;
        
        $query = $this->db->get($this->config->item('SITE_ID').'attributes');
        
        if (!empty($id)):
            return $query->row();
        endif;
        
        return $query->result();
        
    }
    
    
    /**
     * @method type create_news(type $paramName) Create new attribute / Update if id exists
     * @return ID on success and false on failure!
     * called on update and create
     */
    
    public function create_new()
    {
        $id         = $this->input->post('attribute_id');
        $name       = $this->input->post('attribute_name');
        $attributes = $this->input->post('attribute_values');
        $prices     = $this->input->post('attribute_prices');
        $sort       = $this->input->post('sort');
        $type       = $this->input->post('select_option');
        
        $attribute_array = array();
        
        foreach ($attributes as $key => $attr){  
            if(!empty($attr)){
             
                $attribute_array[$key]['name']  = trim($attr); 
                $attribute_array[$key]['price'] = is_numeric($prices[$key]) ? number($prices[$key]) : '';
            }
        }
        
        $data['name']  = $name;
        $data['sort']  = $sort;
        $data['type']  = $type;
        $data['required'] = $this->input->post('mandatory') ? 1 : 0;
        
        if(count($attribute_array) > 0)
            $data['values'] = json_encode($attribute_array);
        
        if (empty($id)) 
        {
            $data['index'] = strtolower( str_replace( ' ', '_', $name) );
            $this->db->insert($this->config->item('SITE_ID').'attributes', $data);
            
            return $this->db->insert_id();
        }
        else{
            $this->db->where('id', $id);
            $update = $this->db->update($this->config->item('SITE_ID').'attributes', $data); 
            
            if ($update){
                return $id;
            }
            return FALSE;
        }

    }
    
    /**
     * @method type deletes(type $paramName) Delete an attribute
     * @param int $id Id of attribute to delete
     * @return boolean True on success False on failure
     */
    
    public function delete($id)
    {
        return  $this->db->delete($this->config->item('SITE_ID').'attributes', array('id' => $id)); 
    }
    
}