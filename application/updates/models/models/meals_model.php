<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Meals_Model extends CI_Model
{

        /**
     * @method get_categories - get all categories available
     * @param int $id category id conditional
     * @return array with categories
     * called from admin/meals/ index, new_category, exists, unique,
     */

    public function get_categories($id = '', $by = ''){

        $query = "SELECT c.id as cid, c.name as cname, c.index, c.date as cdate, c.sort FROM `".$this->config->item('SITE_ID')."categories` as c";

        if (empty($id))
        {
            if ($id == '0')
            {
                $query .= ' WHERE c.parent_id = ?';
                $query = $this->db->query( $query, array(0) );
            }
            else {
               $query = $this->db->query( $query );
            }

            if ($query->num_rows() > 0)
            {
                return $query->result();
            }
        }
        else
        {
            $query .= ' WHERE c.'.$by.' = ?';
            $query = $this->db->query( $query, array($id) );
            return $query->row();
        }
        return FALSE;
    }

    public function export_categories(){
        $query = $this->db->get($this->config->item('SITE_ID')."categories");
        if($query->num_rows() > 0){
            return $query->result();
        }
        return FALSE;
    }

        /**
     * @method type create_news(type $paramName) Create new category / Update if id exists
     * @return ID on success and false on failure!
     * called on update and create
     */

    public function create_new()
    {
        $id     = $this->input->post('category_id');
        $name   = $this->input->post('category_name');
        $sort   = $this->input->post('sort');

        $data['name']       = $name;
        $data['sort']       = $sort;

        if (empty($id))
        {
            $data['index']  = strtolower(preg_replace('/\s+/', '-', $name).uniqid('-cat-'));

            $this->db->insert($this->config->item('SITE_ID').'categories', $data);
            return $this->db->insert_id();
        }
        else{
            $this->db->where('id', $id);
            $update = $this->db->update($this->config->item('SITE_ID').'categories', $data);

            if ($update){
                return $id;
            }
            return FALSE;
        }

    }


    /**
     * @method type deletes(type $paramName) Delete an category
     * @param int $id Id of category to delete
     * @return boolean True on success False on failure
     */

    public function delete($id)
    {
        $this->db->where( 'id', $id );
        return  $this->db->delete($this->config->item('SITE_ID').'categories');
    }

     /**
     * @method type deletes(type $paramName) Delete an category
     * @param int $id Id of category to delete
     * @return boolean True on success False on failure
     */

    public function delete_meal($id)
    {
        $this->db->where( 'id', $id );
        return  $this->db->delete($this->config->item('SITE_ID').'meals');
    }

    public function update_categories($categories){
        $cat_names = $insert_array = array();
        foreach ($categories as $cats){
            $cat_names[]    = trim($cats[0]);
            $insert_array[] = array("index" => uniqid(), "name" => trim($cats[0]), "sort" => 1, "date" => Date("Y-m-d H:i:s") );
        }
        if(count($cat_names) > 0){
            $this->db->where_in('name', $cat_names);
            $query = $this->db->get($this->config->item('SITE_ID').'categories');
            if($query->num_rows() > 0){
                foreach ($query->result() as $result){
                    if (($key = array_search(trim($result->name), $cat_names)) !== false) {
                        unset($cat_names[$key]);
                        unset($insert_array[$key]);
                    }
                }
            }
            if(count($insert_array) > 0){
                $this->db->insert_batch($this->config->item('SITE_ID').'categories', $insert_array);
                //echo $this->db->last_query();
            }
            return;
        }
    }

        /**
     * @method get_attributes - get all attributes available
     * @param int $id attribute id conditional
     * @return array with attributes
     * called from admin/attributes
     */

    public function get_attributes($id = '', $by = ''){

        $this->db->select('id, name, date, values');

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
     *
     */
    public function get_meals($by = '', $id = '')
    {
        $query = $this->db->select('m.name, m.take_away, m.active, m.out_of_stock, m.special, m.special_days, m.special_from, m.special_to, m.description, c.id as cid, m.price, m.quantity, c.name as cname, m.id, m.date, m.sort')->from($this->config->item('SITE_ID').'meals as m')->join($this->config->item('SITE_ID').'meal_categories as mc', 'mc.meal_id = m.id')->join($this->config->item('SITE_ID').'categories as c', 'c.id = mc.category_id');

        if (!empty($id))
            $this->db->where($by, $id);

        $data = $this->db->get();

        if($data->num_rows() > 0)
            return empty ($id) ? $data->result() : $data->row();

        return FALSE;
    }
    /**
     *
     */
    public function get_meal($meal_id)
    {
      if($meal_id){
      $this->db->where('id =', $meal_id);
      $query = $this->db->get($this->config->item('SITE_ID').'meals');
        if($query->num_rows() > 0){
            return  $query->row();
          }
      }
        return FALSE;
    }
    /**
     *
     * @param type $meal_id
     * @return boolean
     */

    public function check_meal($meal_id)
    {
        $query = $this->db->select('name')->from($this->config->item('SITE_ID').'meals')->where('id', $meal_id)->get();
        if($query->num_rows() > 0)
            return TRUE;
        return FALSE;
    }
    /**
     *
     * @param type $meal_id
     * @param type quantity
     * @return boolean
     */
    public function check_quantity($meal, $meal_qty)
    {
      $this->db->select('id, quantity');
      $this->db->where('id =', $meal);
      $this->db->where('quantity >=', $meal_qty);
      $query = $this->db->get($this->config->item('SITE_ID').'meals');
        if($query->num_rows() > 0)
            return TRUE;
        return FALSE;
    }

    public function update_meal_attr($meal,$attr_array){

      if($meal  && !empty($attr_array)){
        $this->db->where('id =', $meal);
        return $this->db->update($this->config->item('SITE_ID').'meals', $attr_array);
      }
      return FALSE;

    }

    public function get_meal_attributes($meal_id, $attribute_id)
    {
        $query = $this->db->select('a.id, ma.value')->from($this->config->item('SITE_ID').'meal_attributes as ma')->join($this->config->item('SITE_ID').'attributes as a', 'a.id = ma.attribute_id')->where( array('ma.id' => $attribute_id, 'ma.meal_id' => $meal_id ))->get();

        if ($query->num_rows() > 0)
            return $query->row();
        return FALSE;

    }

    public function update_status($meal_id){
        $query = "UPDATE  ".$this->config->item('SITE_ID')."meals SET active = CASE WHEN active = 1 THEN 0 ELSE 1 END
        WHERE   id = ".$meal_id;

        return $this->db->query($query);
    }

    public function update_stock($meal_id){
        $query = "UPDATE  ".$this->config->item('SITE_ID')."meals SET out_of_stock = CASE WHEN out_of_stock = 1 THEN 0 ELSE 1 END
        WHERE   id = ".$meal_id;

        return $this->db->query($query);
    }

    public function remove_attributes($id, $attr_id)
    {
        $this->db->where(array('id' => $attr_id, 'meal_id' => $id));
        return $this->db->delete($this->config->item('SITE_ID').'meal_attributes');

    }

    /**
     *
     * @param type $meal_id
     * @return boolean
     */
    public function get_existing_attributes($meal_id)
    {
        $query = $this->db->select('a.name, ma.id, a.values')->from($this->config->item('SITE_ID').'attributes as a')->join($this->config->item('SITE_ID').'meal_attributes as ma', 'ma.attribute_id = a.id')->where('ma.meal_id', $meal_id)->get();

        if ($query->num_rows() > 0)
        {
            return $query->result();
        }
        return FALSE;
    }

    public function get_checked($id){
        $query = $this->db->select('attribute_id')->from($this->config->item('SITE_ID').'meal_attributes')->where('meal_id', $id)->get();
        if($query->num_rows() > 0){
            foreach ($query->result() as $rows)
                $result[] = $rows->attribute_id;
            return $result;
        }
        return array();
    }

    /**
     * @method to create meal
     */
    public function create_meal($meal_id)
    {
        $meal_name      = $this->input->post('name');
        $description    = $this->input->post('description');
        $price          = $this->input->post('price');
        $quantity       = $this->input->post('quantity');
        $category       = $this->input->post('category');
        $attributes     = $this->input->post('attribute');
        $sort           = $this->input->post('sort');
        $special_days   = $this->input->post('special_days');

        $special_from_hour      = $this->input->post('special_from_hour');
        $special_from_minutes   = $this->input->post('special_from_minutes');
        $special_to_hour        = $this->input->post('special_to_hour');
        $special_to_minutes     = $this->input->post('special_to_minutes');

        $meals          = $relation = array();

        $meals['name']  = $meal_name;
        $meals['index'] = strtolower(preg_replace('/\s+/', '-', $meal_name).uniqid('-meal-'));
        $meals['description'] = $description;
        $meals['price'] = $price;
        $meals['quantity'] = $quantity;
        $meals['sort']  = $sort;
        $meals['take_away']  = $this->input->post('takeaway') ? 1 : 0;
        $meals['special']    = $this->input->post('special')  ? 1 : 0;

        if($meals['special'] == 1){
            $meals['special_days'] = is_array($special_days) ? json_encode($special_days) : json_encode(array());
            $meals['special_from'] = json_encode(array('hour' => $special_from_hour, 'minute' => $special_from_minutes));
            $meals['special_to']   = json_encode(array('hour' => $special_to_hour, 'minute' => $special_to_minutes));
        }

        $this->db->trans_start();

        if (empty($meal_id))
        {
            $this->db->insert($this->config->item('SITE_ID').'meals', $meals);
            $meal_id = $this->db->insert_id();

            $relation['meal_id']     = $meal_id;
            $relation['category_id'] = $category;

            $this->db->insert($this->config->item('SITE_ID').'meal_categories', $relation);

        }
        else
        {
            $this->db->where('id', $meal_id);
            $this->db->update($this->config->item('SITE_ID').'meals', $meals);

            $this->db->where('meal_id', $meal_id);
            $this->db->update($this->config->item('SITE_ID').'meal_categories', array('category_id' => $category));
        }

        if(is_numeric($meal_id)){
            $this->db->where('meal_id', $meal_id);
            $delete = $this->db->delete($this->config->item('SITE_ID').'meal_attributes');
            if($delete && is_array($attributes) && count($attributes)){
                foreach ($attributes as $a){

                    $meal_attr = array();
                    if(is_numeric($a)){
                        $meal_attr['meal_id'] = $meal_id;
                        $meal_attr['attribute_id'] = $a;

                        $this->db->insert($this->config->item('SITE_ID').'meal_attributes', $meal_attr);
                    }
                }


            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return $meal_id;
        }
        else{
            return $meal_id;
        }

    }

    /**
     *
     */

    public function create_attribute($meal_id)
    {
        $attribute = $this->input->post('attributes');
        $value     = $this->input->post('value');

        $attr['meal_id']      = $meal_id;
        $attr['attribute_id'] = $attribute;
        $attr['value']        = $value;

        return $this->db->insert($this->config->item('SITE_ID').'meal_attributes', $attr);
    }
}
