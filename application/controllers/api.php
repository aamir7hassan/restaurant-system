<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends App_Controller{
    
    public function __construct(){
        parent::__construct();  
        $this->load->model('api_model');
    }
    /**
    public function getRestaurants(){
        $result     = array( "success" => 0, "data" => array(), "errmsg" => "Operation failed");
        $input      = file_get_contents('php://input');
        $data       = json_decode($input, true);
        
        if(isset($data) && is_array($data) && count($data) > 0){
            $total_stores   = $this->api_model->get_full_store();
            $start          = isset($data['page']) && is_numeric($data['page']) ? (($data['page']-1)*10) : 0;
            $stores         = $this->api_model->get_stores(array(), array(), $start, 10);
            $index          = 1;
            $data_result    = array();
            
            foreach ($stores as $store){
                $sku            = $store->sku;
                $settings_array = array();
                $settings       = $this->api_model->get_details($sku);
                foreach ($settings as $setting){
                    $settings_array[$setting->index] = $setting->value;
                }

                $data_result[$index]['id']            = $sku;
                $data_result[$index]['trading_name']  = isset($settings_array['trading_as']) ? $settings_array['trading_as'] : '';
                $data_result[$index]['food_type']     = isset($settings_array['food_type']) ? $settings_array['food_type'] : '';
                $data_result[$index]['menu_url']      = base_url($store->sku.'/customer.html');
                $data_result[$index]['Views']         = $store->views;
                $data_result[$index]['image_url']     = isset($settings_array['store_logo']) ? base_url('/assets/images/'.$settings_array['store_logo']) : '';
                
                $contact_details                      = array();
                $work_information                     = array();
                $work_location                        = array();

                $contact_details['email']             = isset($settings_array['primary_email']) ? $settings_array['primary_email'] : '';    
                $contact_details['phone']             = isset($settings_array['telephone_no']) ? $settings_array['telephone_no'] : '';    
                $contact_details['website']           = isset($settings_array['website']) ? $settings_array['website'] : ''; 

                $data_result[$index]['contact_info']  = $contact_details;
                $week_day_start                       = isset($settings_array['work_week_from'])  ? (int)$settings_array['work_week_from'] : 1;   
                $week_day_to                          = isset($settings_array['work_week_to'])    ? (int)$settings_array['work_week_to'] : 5;                
                $work_hour_start                      = isset($settings_array['work_hours_from']) ? $settings_array['work_hours_from'] : '0';     
                $work_hour_end                        = isset($settings_array['work_hours_to'])   ? $settings_array['work_hours_to'] : '23';

                $work_hour_start_pretty               = date("g:i A", strtotime($work_hour_start.":00"));
                $work_hour_to_pretty                  = date("g:i A", strtotime($work_hour_end.":00"));
                $work_information['trading_hours']    = $work_hour_start_pretty.' - '.$work_hour_to_pretty;
                $week_day_today                       = date( "w", strtotime())-1;
                $current_hour                         = Date('H');
                $open                                 = FALSE;  
                
                if(($current_hour >= $work_hour_start && $current_hour < $work_hour_end)){
                    $open = TRUE;
                }
                
                $work_information['isOpen']           = $open;   
                
                $data_result[$index]['work_info']     = $work_information;
                $data_result[$index]['business_type'] = isset($settings_array['business_type']) ? json_decode($settings_array['business_type']) : array();

                $work_location['address']       = isset($settings_array['address'])       ? $settings_array['address'] : '';


                $address                    = $work_location['address']; // Google HQ
                $prepAddr                   = str_replace(' ', '+', $address);
                $api_key                    = 'AIzaSyCFZ2z5T_Dg3pSqFeIxmiJGtvTgA8SY8z0';
                $url                        = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $prepAddr . '&key='.$api_key;
                #$geocode                    = $curl->get('https://maps.googleapis.com/maps/api/geocode/json?address=' . $prepAddr . '&key='.$api_key);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                $geoloc = json_decode(curl_exec($ch), true);

                $location_exist = $geoloc['results'][0]['formatted_address'];
                
                $work_location['lat']       = '';
                $work_location['lng']       = '';

                if ($location_exist) {
                    $latitude                   = $geoloc['results'][0]['geometry']['location']['lat'];
                    $longitude                  = $geoloc['results'][0]['geometry']['location']['lng'];
                    $work_location['lat']       = $latitude;
                    $work_location['lng']       = $longitude;
                }

                $data_result[$index]['location']        = $work_location;    
                $categories                 = $this->api_model->get_categories($sku);
                $meals                      = $this->api_model->get_meals($sku);
                $cats_array = $meals_array  = array();

                foreach ($categories as $cats){
                    if(empty($cats->name))
                        continue;
                    $cats_array[]     = $cats->name; 
                }
                
                foreach ($meals as $meal){
                    if(empty($cats->name))
                        continue;
                    $meals_array[]    = $meal->name; 
                }
                $data_result[$index]['categories'] = $cats_array;
                $data_result[$index]['tag_line']   = isset($settings_array['tag_line']) ? $settings_array['tag_line'] : '';
                $data_result[$index]['meals']      = $meals_array;
                
                ++$index;
            }

            //$data_result['pagination'] = array('total' => $total_stores, 'per_page' => 10); 
            $data_results = array_values($data_result);

            $result = array('success' => 1, 'data' => $data_results, 'errmsg' => '');
            //$result = array('data' => $data_results);
        }
        header('Content-Type: application/json');
        //$json = json_encode($result);
        //$noJson = trim($json, "{}");
        die(json_encode($result));
    }
    */

    public function getRestaurants(){
        $result     = array( "success" => 0, "data" => array(), "errmsg" => "Operation failed.");
        $distance   = array();
        $input      = file_get_contents('php://input');
        $data       = json_decode($input, true);

        if(isset($data) && is_array($data) && count($data) > 0) {
            if(isset($data['distance']) && (isset($data['distance']['more_than']) || isset($data['distance']['less_than'])) && isset($data['distance']['latitude']) && isset($data['distance']['longitude']) &&
                    !empty($data['distance']['latitude']) && !empty($data['distance']['longitude'])
                    )
            { 
                $distance['more_than']  = isset($data['distance']['more_than']) ? $data['distance']['more_than'] : '';
                $distance['less_than']  = isset($data['distance']['less_than']) ? $data['distance']['less_than'] : '';
                $distance['lat']        = $data['distance']['latitude'];
                $distance['lng']        = $data['distance']['longitude'];
            }
                   
            $business_options = isset($data['business_options']) ? $data['business_options'] : array();
            //$total_stores   = $this->api_model->get_full_store($distance);
            // $start          = isset($data['page']) && is_numeric($data['page']) ? (($data['page']-1)*10) : 0;
            $stores         = $this->api_model->get_stores($distance, $business_options);                
            $index          = 0;

            $data_result    = array();
            foreach ($stores as $store){
                $sku            = $store->sku;
                $settings_array = array();
                $settings       = $this->api_model->get_details($sku);
                foreach ($settings as $setting){
                    $settings_array[$setting->index] = $setting->value;
                }
                
                $categories                 = $this->api_model->get_categories($sku);
                $meals                      = $this->api_model->get_meals($sku);
                $cats_array = $meals_array  = array();
                $business_types             = isset($settings_array['business_type']) ? json_decode($settings_array['business_type']) : array();
                $address_details            = isset($settings_array['address'])       ? $settings_array['address'] : '';
                $business_types             = array_map('strtolower', $business_types);

                foreach ($categories as $cats){
                    if(empty($cats->name))
                        continue;
                    $cats_array[]     = strtolower($cats->name); 
                }
                foreach ($meals as $meal){
                    if(empty($cats->name))
                        continue;
                    $meals_array[]    = strtolower($meal->name); 
                }
                
                
                if(isset($data['address']) && is_array($data['address']) && count($data['address']) > 0){
                    $status = array();
                    foreach ($data['address'] as $query){ 
                        if(strpos(strtolower($address_details), strtolower($query)) !== FALSE){ 
                            $status[] = 1;
                        }
                    }
                    if(count($status) != count($data['address'])){
                        continue;
                    }
                }
                
                if(isset($data['queries']) && is_array($data['queries']) && count($data['queries']) > 0){
                    $status = array();
                    foreach ($data['queries'] as $query){ 
                        if(isset($settings_array['trading_as']) && strpos(strtolower($settings_array['trading_as']), strtolower($query)) !== FALSE){ 
                            $status[] = 1;
                        }
                    }
                    if(count($status) == 0){
                        continue;
                    }
                }
                
                if(isset($data['categories']) && is_array($data['categories']) && count($data['categories']) > 0){
                    $status = array();
                    foreach ($data['categories'] as $query){ 
                        if(check_in_array(strtolower($query), $cats_array)){ 
                            $status[] = 1;
                        }
                    }
                    if(count($status) != count($data['categories'])){
                        continue;
                    }
                }
                
                if(isset($data['business_types']) && is_array($data['business_types']) && count($data['business_types']) > 0){
                    $status = array();
                    foreach ($data['business_types'] as $query){ 
                        if(check_in_array(strtolower($query), $business_types)){ 
                            $status[] = 1;
                        }
                    }
                    if(count($status) != count($data['business_types'])){
                        continue;
                    }
                }
                
                $data_result[$index]['id']            = $sku;
                $data_result[$index]['trading_name']  = isset($settings_array['trading_as']) ? $settings_array['trading_as'] : '';
                $data_result[$index]['food_type']     = isset($settings_array['food_type']) ? $settings_array['food_type'] : '';
                $data_result[$index]['menu_url']      = base_url($store->sku.'/customer.html');
                $data_result[$index]['Views']         = $store->views;
                $data_result[$index]['image_url']     = isset($settings_array['store_logo']) ? base_url('/assets/images/'.$settings_array['store_logo']) : '';
                
                $contact_details                      = array();
                $work_information                     = array();
                $work_location                        = array();
                
                $contact_details['email']             = isset($settings_array['primary_email']) ? $settings_array['primary_email'] : '';    
                $contact_details['phone']             = isset($settings_array['telephone_no']) ? $settings_array['telephone_no'] : '';    
                $contact_details['website']           = isset($settings_array['website']) ? $settings_array['website'] : ''; 
                
                $data_result[$index]['contact_info']  = (object)$contact_details;
                $week_day_start                       = isset($settings_array['work_week_from'])  ? (int)$settings_array['work_week_from'] : 1;   
                $week_day_to                          = isset($settings_array['work_week_to'])    ? (int)$settings_array['work_week_to'] : 5;                
                $work_hour_start                      = isset($settings_array['work_hours_from']) ? $settings_array['work_hours_from'] : '0';     
                $work_hour_end                        = isset($settings_array['work_hours_to'])   ? $settings_array['work_hours_to'] : '23';
                
                $work_hour_start_pretty               = date("g:i A", strtotime($work_hour_start.":00"));
                $work_hour_to_pretty                  = date("g:i A", strtotime($work_hour_end.":00"));
                $work_information['working_hours']    = $work_hour_start_pretty.' - '.$work_hour_to_pretty;
                $week_day_today                       = date( "w", strtotime())-1;
                $current_hour                         = Date('H');
                $open                                 = FALSE; 
                if(
                    ($current_hour >= $work_hour_start && 
                        $current_hour < $work_hour_end) && 
                            ($week_day_today >= $week_day_start 
                                && $week_day_today <= $week_day_to) ){
                    $open = TRUE;
                }
                $work_information['isOpen']           = $open;   
                $data_result[$index]['work_info']     = (object)$work_information;
                $data_result[$index]['business_type'] =  array_map('ucwords', $business_types);
                
                $work_location['address']             = $address_details;
                    

                $address                    = $work_location['address']; // Google HQ
                $prepAddr                   = str_replace(' ', '+', $address);
                $api_key                    = 'AIzaSyCFZ2z5T_Dg3pSqFeIxmiJGtvTgA8SY8z0';
                $url                        = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $prepAddr . '&key='.$api_key;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                $geoloc = json_decode(curl_exec($ch), true);

                $location_exist = $geoloc['results'][0]['formatted_address'];
                
                $work_location['lat']       = '';
                $work_location['lng']       = '';

                if ($location_exist) {
                    $latitude                   = $geoloc['results'][0]['geometry']['location']['lat'];
                    $longitude                  = $geoloc['results'][0]['geometry']['location']['lng'];
                    $work_location['lat']       = $latitude;
                    $work_location['lng']       = $longitude;
                }


                $data_result[$index]['location']        = (object)$work_location; 
                
                $data_result[$index]['tag_line']   = isset($settings_array['tag_line']) ? $settings_array['tag_line'] : '';
                $data_result[$index]['categories'] = array_map('ucwords', $cats_array);
                $data_result[$index]['meals']      = array_map('ucwords', $meals_array);
                // $data_result[$index]['pagination'] = array('total' => $total_stores, 'per_page' => 10);
                ++$index;
            } 
            
            $result = array('success' => 1, 'data' => $data_result, 'errmsg' => '');
        }
        header('Content-Type: application/json');
        die(json_encode($result));
    }

    public function time(){
        
        die(timezone_location_get());
        
        $result     = array( "success" => 0, "data" => array("Date" => Date('Y-m-d H:i:s')), "errmsg" => "");
        header('Content-Type: application/json');
        die(json_encode($result));
    }

    public function searchRestaurants(){
        $result     = array( "success" => 0, "data" => array(), "errmsg" => "Operation failed.");
        $distance   = array();
        $input      = file_get_contents('php://input');
        $data       = json_decode($input, true);

        if(isset($data) && is_array($data) && count($data) > 0){
            if(isset($data['distance']) && (isset($data['distance']['more_than']) || isset($data['distance']['less_than'])) && isset($data['distance']['latitude']) && isset($data['distance']['longitude']) &&
                    !empty($data['distance']['latitude']) && !empty($data['distance']['longitude'])
                    )
            { 
                $distance['more_than']  = isset($data['distance']['more_than']) ? $data['distance']['more_than'] : '';
                $distance['less_than']  = isset($data['distance']['less_than']) ? $data['distance']['less_than'] : '';
                $distance['lat']        = $data['distance']['latitude'];
                $distance['lng']        = $data['distance']['longitude'];
            }
                   
            $business_options = isset($data['business_options']) ? $data['business_options'] : array();
            //$total_stores   = $this->api_model->get_full_store($distance);
            // $start          = isset($data['page']) && is_numeric($data['page']) ? (($data['page']-1)*10) : 0;
            $stores         = $this->api_model->get_stores($distance, $business_options);                
            $index          = 0;

            $data_result    = array();
            foreach ($stores as $store){
                $sku            = $store->sku;
                $settings_array = array();
                $settings       = $this->api_model->get_details($sku);
                foreach ($settings as $setting){
                    $settings_array[$setting->index] = $setting->value;
                }
                
                $categories                 = $this->api_model->get_categories($sku);
                $meals                      = $this->api_model->get_meals($sku);
                $cats_array = $meals_array  = array();
                $business_types             = isset($settings_array['business_type']) ? json_decode($settings_array['business_type']) : array();
                $address_details            = isset($settings_array['address'])       ? $settings_array['address'] : '';
                $business_types             = array_map('strtolower', $business_types);

                foreach ($categories as $cats){
                    if(empty($cats->name))
                        continue;
                    $cats_array[]     = strtolower($cats->name); 
                }
                foreach ($meals as $meal){
                    if(empty($cats->name))
                        continue;
                    $meals_array[]    = strtolower($meal->name); 
                }
                
                
                if(isset($data['address']) && is_array($data['address']) && count($data['address']) > 0){
                    $status = array();
                    foreach ($data['address'] as $query){ 
                        if(strpos(strtolower($address_details), strtolower($query)) !== FALSE){ 
                            $status[] = 1;
                        }
                    }
                    if(count($status) != count($data['address'])){
                        continue;
                    }
                }
                
                if(isset($data['queries']) && is_array($data['queries']) && count($data['queries']) > 0){
                    $status = array();
                    foreach ($data['queries'] as $query){ 
                        if(isset($settings_array['trading_as']) && strpos(strtolower($settings_array['trading_as']), strtolower($query)) !== FALSE){ 
                            $status[] = 1;
                        }
                    }
                    if(count($status) == 0){
                        continue;
                    }
                }
                
                if(isset($data['categories']) && is_array($data['categories']) && count($data['categories']) > 0){
                    $status = array();
                    foreach ($data['categories'] as $query){ 
                        if(check_in_array(strtolower($query), $cats_array)){ 
                            $status[] = 1;
                        }
                    }
                    if(count($status) != count($data['categories'])){
                        continue;
                    }
                }
                
                if(isset($data['business_types']) && is_array($data['business_types']) && count($data['business_types']) > 0){
                    $status = array();
                    foreach ($data['business_types'] as $query){ 
                        if(check_in_array(strtolower($query), $business_types)){ 
                            $status[] = 1;
                        }
                    }
                    if(count($status) != count($data['business_types'])){
                        continue;
                    }
                }
                
                $data_result[$index]['id']            = $sku;
                $data_result[$index]['trading_name']  = isset($settings_array['trading_as']) ? $settings_array['trading_as'] : '';
                $data_result[$index]['food_type']     = isset($settings_array['food_type']) ? $settings_array['food_type'] : '';
                $data_result[$index]['menu_url']      = base_url($store->sku.'/customer.html');
                $data_result[$index]['Views']         = $store->views;
                $data_result[$index]['image_url']     = isset($settings_array['store_logo']) ? base_url('/assets/images/'.$settings_array['store_logo']) : '';
                
                $contact_details                      = array();
                $work_information                     = array();
                $work_location                        = array();
                
                $contact_details['email']             = isset($settings_array['primary_email']) ? $settings_array['primary_email'] : '';    
                $contact_details['phone']             = isset($settings_array['telephone_no']) ? $settings_array['telephone_no'] : '';    
                $contact_details['website']           = isset($settings_array['website']) ? $settings_array['website'] : ''; 
                
                $data_result[$index]['contact_info']  = (object)$contact_details;
                $week_day_start                       = isset($settings_array['work_week_from'])  ? (int)$settings_array['work_week_from'] : 1;   
                $week_day_to                          = isset($settings_array['work_week_to'])    ? (int)$settings_array['work_week_to'] : 5;                
                $work_hour_start                      = isset($settings_array['work_hours_from']) ? $settings_array['work_hours_from'] : '0';     
                $work_hour_end                        = isset($settings_array['work_hours_to'])   ? $settings_array['work_hours_to'] : '23';
                
                $work_hour_start_pretty               = date("g:i A", strtotime($work_hour_start.":00"));
                $work_hour_to_pretty                  = date("g:i A", strtotime($work_hour_end.":00"));
                $work_information['working_hours']    = $work_hour_start_pretty.' - '.$work_hour_to_pretty;
                $week_day_today                       = date( "w", strtotime())-1;
                $current_hour                         = Date('H');
                $open                                 = FALSE; 
                if(
                    ($current_hour >= $work_hour_start && 
                        $current_hour < $work_hour_end) && 
                            ($week_day_today >= $week_day_start 
                                && $week_day_today <= $week_day_to) ){
                    $open = TRUE;
                }
                $work_information['isOpen']           = $open;   
                $data_result[$index]['work_info']     = (object)$work_information;
                $data_result[$index]['business_type'] =  array_map('ucwords', $business_types);
                
                $work_location['address']             = $address_details;
                    

                $address                    = $work_location['address']; // Google HQ
                $prepAddr                   = str_replace(' ', '+', $address);
                $api_key                    = 'AIzaSyCFZ2z5T_Dg3pSqFeIxmiJGtvTgA8SY8z0';
                $url                        = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $prepAddr . '&key='.$api_key;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                $geoloc = json_decode(curl_exec($ch), true);

                $location_exist = $geoloc['results'][0]['formatted_address'];
                
                $work_location['lat']       = '';
                $work_location['lng']       = '';

                if ($location_exist) {
                    $latitude                   = $geoloc['results'][0]['geometry']['location']['lat'];
                    $longitude                  = $geoloc['results'][0]['geometry']['location']['lng'];
                    $work_location['lat']       = $latitude;
                    $work_location['lng']       = $longitude;
                }


                $data_result[$index]['location']        = (object)$work_location; 
                
                $data_result[$index]['tag_line']   = isset($settings_array['tag_line']) ? $settings_array['tag_line'] : '';
                $data_result[$index]['categories'] = array_map('ucwords', $cats_array);
                $data_result[$index]['meals']      = array_map('ucwords', $meals_array);
                // $data_result[$index]['pagination'] = array('total' => $total_stores, 'per_page' => 10);
                ++$index;
            } 
            
            $result = array('success' => 1, 'data' => $data_result, 'errmsg' => '');
        }
        header('Content-Type: application/json');
        die(json_encode($result));
    }
    
    public function increaseViewCount(){
        $result     = array( "success" => 0, "errmsg" => "Failed to update");
        $distance   = array();
        $input      = file_get_contents('php://input');
        $data       = json_decode($input, true);
        if(isset($data) && is_array($data) && count($data) > 0){
            $restaurant_id = $data['restaurant_id'];
            $out = $this->api_model->update_views($restaurant_id);
            if($out)
                $result     = array( "success" => 1, "errmsg" => "");
             
        }
        header('Content-Type: application/json');
        die(json_encode($result));
    }
    
   
}