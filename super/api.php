<?php 
	header('Content-Type: application/json');
	session_start(); 
	$con =  mysqli_connect ('takki-db.cgqeqdugauga.us-east-2.rds.amazonaws.com','admin' ,'Temp1234' ,'takki' ) or die(mysqli_error());
	
	if(!$con) {
	   die('disconnected');
	}
	mysqli_set_charset($con, "utf8");
	if(isset($_REQUEST['param'])) {
		$param = $_GET['param'];
		if($param == "searchRestaurants") {
			$result = searchRestaurants();
		} else if($param == "getRestaurants") {
			$result = getRestaurants();
		} else if($param == "increaseViewCount") {
			$result = increaseViewCount();
		} else if($param == "getOptions") {
			$result = getOptions();
		} else if(is_null($result)) {
			$result     = array( "success" => 0, "errmsg" => "Failed to update");
		} else {
			$result     = json_encode(array( "success" => 0, "errmsg" => "Failed to update"));
		}
		echo $result;
	}
	
	// ALL API FUNCTIONS
	
	function getRestaurants() {
		
		global $con;
		$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://".$_SERVER['HTTP_HOST'].'/';
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
			$stores         = get_stores($distance, $business_options);                
			$index          = 0;
			$data_result    = array();
			$reserv = FALSE;$menu = FALSE;$takeaway = FALSE;$post=FALSE;
			$delMap = FALSE;$seated = FALSE;$full = FALSE;$del=FALSE;$online=FALSE;
            while ($stored  = mysqli_fetch_assoc($stores)) {
				$store = (object)$stored;
                $sku            = $store->sku;
                $settings_array = array();
                $settings       = get_details($sku);
				
				if($settings!==false) {
					while($settingg = mysqli_fetch_assoc($settings)) {
						$setting = (object)$settingg;
						$settings_array[$setting->index] = $setting->value;
					}
				}
                if(isset($settings_array['delivery_show']) && $settings_array['delivery_show'] == "1") {
					$del = TRUE;
				}
                $categories                 = get_categories($sku);
                $meals                      = get_meals($sku);
				
                $cats_array = $meals_array  = array();
                $business_types             = isset($settings_array['business_type']) ? json_decode($settings_array['business_type']) : array();
                $address_details            = isset($settings_array['address'])       ? $settings_array['address'] : '';
                $business_types             = array_map('strtolower', $business_types);
				if($categories!==false) {
					while($catss = mysqli_fetch_assoc($categories)){
						$cats = (object)$catss;
						if(empty($cats->name))
							continue;
						$cats_array[]     = strtolower($cats->name); 
					}
				}
				if($meals!==false) {
					while ($mealss = mysqli_fetch_assoc($meals)){
						$meal = (object)$mealss;
						if(empty($meal->name))
							continue;
						$meals_array[]    = strtolower($meal->name); 
					}
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
				if($store->reservation=="1") {
					$reserv = TRUE; 
				}
				
				//if($store->delivery=="1" && $del) {
				if($store->delivery=="1") {	
					$delMap = TRUE;
				}
				if(isset($settings_array['timezone'])) {
					$timezone = $settings_array['timezone'];
					if(!empty($timezone)) {
						date_default_timezone_set($timezone);
					}
				}
				
				if($store->packages == "Option 1") {
					$menu = TRUE;
				} else if($store->packages == "Option 2") {
					$takeaway = TRUE;
					$menu = TRUE;
				} else if($store->packages == "Option 3") {
					$seated = TRUE;
					$takeaway = TRUE;
					$menu = TRUE;
				} else if($store->packages == "Option 4") {
					$seated = TRUE;
					$takeaway = TRUE;
					$menu = TRUE;
					$pos = TRUE;
				}
                
                $data_result[$index]['id']            = $sku;
				$data_result[$index]['reservation']	  = $reserv;
				$data_result[$index]['delivery']	  = $del;
				$data_result[$index]['delivery_with_map'] = $delMap;
				$data_result[$index]['seated']	  	  = $seated;
				$data_result[$index]['takeaway']	  = $takeaway;
				$data_result[$index]['menu']	      = $menu;
				$data_result[$index]['online']	      = $online;
                $data_result[$index]['trading_name']  = isset($settings_array['trading_as']) ? $settings_array['trading_as'] : '';
                $data_result[$index]['food_type']     = isset($settings_array['food_type']) ? $settings_array['food_type'] : '';
                $data_result[$index]['menu_url']      = $link.$store->sku.'/customer.html';
                $data_result[$index]['Views']         = $store->views;
                $data_result[$index]['image_url']     = isset($settings_array['store_logo']) ? $link.'assets/images/'.$settings_array['store_logo'] : '';
                
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
				$week_day_today                       = date( "w")-1; 
                $current_hour                         = date('H');
                $open                                 = FALSE; 
				$work_information['working_hours'] = []; 
				if(isset($settings_array['work_hours_from']) && $settings_array['work_hours_from']!='["0","0","0","0","0","0","0"]') {
					$froms = json_decode($settings_array['work_hours_from']);
					$tos   = json_decode($settings_array['work_hours_to']);
				
				
					$timestamp = time();
					$currentTime = date('H:i');
					
					if(count($froms)>1) { 
						$work_information['from'] = $froms;
						$work_information['to'] = $tos;
						foreach($froms as $k=>$v) {
							$starts = explode('_',$v);
							$ends   = explode('_',$tos[$k]);
							
							$work_hour_start_pretty = date("h:i A", strtotime($starts[1].":00"));
							$work_hour_to_pretty = date("h:i A", strtotime($ends[1].":00"));
							$work_information['working_hours'][$starts[0]] = $work_hour_start_pretty.' - '.$work_hour_to_pretty;
							$work_hour_start = $starts[1].":00";
							$work_hour_end   = $ends[1].":00";
							$week_day_start  = date('w',strtotime($starts[0]));
							$week_day_to     = date('w',strtotime($ends[0]));
							$startTime = date('H:i',strtotime($work_hour_start));
							$endTime =  date('H:i',strtotime($work_hour_end));
							if($week_day_today == $week_day_start) {
								//if($current_hour >= $work_hour_start && $current_hour < $work_hour_end){
									
								if (($startTime <= $currentTime) && ($currentTime <= $endTime)) {
									$open  = true;
								}
								$ends=[];
							}
							$ends=[];
						}
					}
				}
				//var_dump($work_information['working_hours']);die;
				if(isset($work_information['working_hours']) && count($work_information['working_hours']) == 0) {
					$work_information['working_hours'] = (object)array();
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
				$work_location['lat']       = '';
				$work_location['lng']       = '';
				if($geoloc['status']=='REQUEST_DENIED' || !empty($geoloc['error_message'])) {
					$work_location['lat']       = '';
					$work_location['lng']       = '';
				} else {
					if(isset($geoloc['results'][0]['formatted_address'])) {
						$location_exist = $geoloc['results'][0]['formatted_address'];
						if ($location_exist) {
							$latitude                   = $geoloc['results'][0]['geometry']['location']['lat'];
							$longitude                  = $geoloc['results'][0]['geometry']['location']['lng'];
							$work_location['lat']       = $latitude;
							$work_location['lng']       = $longitude;
						}
					}
				}

				
                $data_result[$index]['location']        = (object)$work_location; 
                
                $data_result[$index]['tag_line']   = isset($settings_array['tag_line']) ? $settings_array['tag_line'] : '';
                $data_result[$index]['categories'] = array_map('ucwords', $cats_array);
                $data_result[$index]['meals']      = array_map('ucwords', $meals_array);
                // $data_result[$index]['pagination'] = array('total' => $total_stores, 'per_page' => 10);
                ++$index;
				$reserv = FALSE;$menu = FALSE;$takeaway = FALSE;$post=FALSE;
				$delMap = FALSE;$seated = FALSE;$full = FALSE;$del=FALSE;$online=FALSE;
            } 
			$result = array('success' => 1, 'data' => $data_result, 'errmsg' => '');
		}
		
        echo json_encode($result);
	} // getRestaurants

	function searchRestaurants() {
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		global $con;
		$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://".$_SERVER['HTTP_HOST'].'/';
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
			$service = isset($data['service']) ? $data['service']:null;
			$stores         = get_stores($distance, $business_options,null,null,$service);  
			
			$index          = 0;
			$data_result    = array();
			$reserv = FALSE;$menu = FALSE;$takeaway = FALSE;$post=FALSE;
			$delMap = FALSE;$seated = FALSE;$full = FALSE;$del=FALSE;$online=FALSE;
            while ($stored  = mysqli_fetch_assoc($stores)) {
				$store = (object)$stored;
                $sku            = $store->sku;
			
				$settings_array = array();
                $settings       = get_details($sku);
				
				if($settings!==false) {
					while($settingg = mysqli_fetch_assoc($settings)) {
						$setting = (object)$settingg;
						$settings_array[$setting->index] = $setting->value;
					}
				}
                if(isset($settings_array['delivery_show']) && $settings_array['delivery_show'] == "1") {
					$del = TRUE;
				}
                $categories                 = get_categories($sku);
                $meals                      = get_meals($sku);
				
                $cats_array = $meals_array  = array();
                $business_types             = isset($settings_array['business_type']) ? json_decode($settings_array['business_type']) : array();
                $address_details            = isset($settings_array['address'])       ? $settings_array['address'] : '';
                $business_types             = array_map('strtolower', $business_types);
				$food_types             = isset($settings_array['food_type']) ? json_decode($settings_array['food_type']) : array();
				$food_types             = array_map('strtolower', $food_types);
				
				if($categories!==false) {
					while($catss = mysqli_fetch_assoc($categories)){
						$cats = (object)$catss;
						if(empty($cats->name))
							continue;
						$cats_array[]     = strtolower($cats->name); 
					}
				}
				if($meals!==false) {
					while ($mealss = mysqli_fetch_assoc($meals)){
						$meal = (object)$mealss;
						if(empty($meal->name))
							continue;
						$meals_array[]    = strtolower($meal->name); 
					}
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
                        if(check_in_array(strtolower($query), $cats_array) || check_in_array(strtolower($query), $food_types) || check_in_array(strtolower($query), $meals_array)){ 
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
				
				if($store->reservation=="1") {
					$reserv = TRUE; 
				}
				
				//if($store->delivery=="1" && $del) {
				if($store->delivery=="1") {	
					$delMap = TRUE;
				}
				if(isset($settings_array['timezone'])) {
					$timezone = $settings_array['timezone'];
					if(!empty($timezone)) {
						date_default_timezone_set($timezone);
					}
				}

				if($store->packages == "Option 1") {
					$menu = TRUE;
				} else if($store->packages == "Option 2") {
					$takeaway = TRUE;
					$menu = TRUE;
				} else if($store->packages == "Option 3") {
					$seated = TRUE;
					$takeaway = TRUE;
					$menu = TRUE;
				} else if($store->packages == "Option 4") {
					$seated = TRUE;
					$takeaway = TRUE;
					$menu = TRUE;
					$pos = TRUE;
				}
               
                $data_result[$index]['id']            = $sku;
				$data_result[$index]['reservation']	  = $reserv;
				$data_result[$index]['delivery']	  = $del;
				$data_result[$index]['delivery_with_map'] = $delMap;
				$data_result[$index]['seated']	  	  = $seated;
				$data_result[$index]['takeaway']	  = $takeaway;
				$data_result[$index]['menu']	      = $menu;
				$data_result[$index]['online']	      = $online;
                $data_result[$index]['trading_name']  = isset($settings_array['trading_as']) ? $settings_array['trading_as'] : '';
                $data_result[$index]['food_type']     = array_map('ucwords', $food_types);
                $data_result[$index]['menu_url']      = $link.$store->sku.'/customer.html';
                $data_result[$index]['Views']         = $store->views;
                $data_result[$index]['image_url']     = isset($settings_array['store_logo']) ? $link.'assets/images/'.$settings_array['store_logo'] : '';
                
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
				$week_day_today                       = date( "w")-1;
                $current_hour                         = date('H');
                $open                                 = FALSE;
				$work_information['working_hours'] = []; 
				if(isset($settings_array['work_hours_from']) && $settings_array['work_hours_from']!='["0","0","0","0","0","0","0"]') {
					$froms = json_decode($settings_array['work_hours_from']);
					$tos   = json_decode($settings_array['work_hours_to']);
				
				
					$timestamp = time();
					$currentTime = date('H:i');
					
					
					if(count($froms)>1) { 
						$work_information['from'] = $froms;
						$work_information['to'] = $tos;
						foreach($froms as $k=>$v) {
							$starts = explode('_',$v);
							$ends   = explode('_',$tos[$k]);
							
							$work_hour_start_pretty = date("h:i A", strtotime($starts[1].":00"));
							$work_hour_to_pretty = date("h:i A", strtotime($ends[1].":00"));
							$work_information['working_hours'][$starts[0]] = $work_hour_start_pretty.' - '.$work_hour_to_pretty;
							$work_hour_start = $starts[1].":00";
							$work_hour_end   = $ends[1].":00";
							$week_day_start  = date('w',strtotime($starts[0]));
							$week_day_to     = date('w',strtotime($ends[0]));
							$startTime = date('H:i',strtotime($work_hour_start));
							$endTime =  date('H:i',strtotime($work_hour_end));
							if($week_day_today == $week_day_start) {
								//if($current_hour >= $work_hour_start && $current_hour < $work_hour_end){
									
								if (($startTime <= $currentTime) && ($currentTime <= $endTime)) {
									$open  = true;
								}
								$ends=[];
							}
							$ends=[];
						}
					}
				}
				if(isset($work_information['working_hours']) && count($work_information['working_hours']) == 0) {
					$work_information['working_hours'] = (object)array();
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
				
				$work_location['lat']       = '';
				$work_location['lng']       = '';
				if($geoloc['status']=='REQUEST_DENIED' || !empty($geoloc['error_message'])) {
					$work_location['lat']       = '';
					$work_location['lng']       = '';
				} else {
					if(isset($geoloc['results'][0]['formatted_address'])) {
						$location_exist = $geoloc['results'][0]['formatted_address'];
						if ($location_exist) {
							$latitude                   = $geoloc['results'][0]['geometry']['location']['lat'];
							$longitude                  = $geoloc['results'][0]['geometry']['location']['lng'];
							$work_location['lat']       = $latitude;
							$work_location['lng']       = $longitude;
						}
					}
				}
				
                $data_result[$index]['location']        = (object)$work_location; 
                
                $data_result[$index]['tag_line']   = isset($settings_array['tag_line']) ? $settings_array['tag_line'] : '';
                $data_result[$index]['categories'] = array_map('ucwords', $cats_array);
                $data_result[$index]['meals']      = array_map('ucwords', $meals_array);
                // $data_result[$index]['pagination'] = array('total' => $total_stores, 'per_page' => 10);
                ++$index;
				$reserv = FALSE;$menu = FALSE;$takeaway = FALSE;$post=FALSE;
				$delMap = FALSE;$seated = FALSE;$full = FALSE;$del=FALSE;$online=FALSE;
            }
			$result = array('success' => 1, 'data' => $data_result, 'errmsg' => '');
		}
		echo json_encode($result);
	} // searchRestaurants
	
	function get_stores($distance=array(), $get_stores = "", $start=NULL, $limit=NULL,$servicesArr=NULL) {
		global $con;
		$res1=array();
		$opt="";
		$exp = explode(',',trim($servicesArr));
		sort($exp);
		// take_away,menu,seated,delivery_map,reservation,online
		$a1 = "packages='Option 1'";$a2 = "packages='Option 2'";$a3 = "packages='Option 3'";$a4 = "packages='Option 4'";
		$a5 = "delivery='1'"; $a6 = "reservation='1'";
		$a7 = ""; // for online
		$or = " || ";
		$str="";
		foreach($exp as $k=>$v) {
			if( $v == "take_away" ) {
				$str .= $or.$a2.$or.$a3.$or.$a4.$or;
			} else if( $v == "menu" ) {
				$str .= $or.$a1.$or.$a2.$or.$a3.$or.$a4.$or;
			} else if( $v=="seated" ) {
				$str .= $or.$a3.$or.$a4.$or;
			} else if($v=="delivery_map") {
				$str .= $or.$a5.$or;
			} else if($v=="reservation") {
				$str .= $or.$a6.$or;
			} else if($v=="online") {
				$str .= "";
			}
			// condition for online is left
		}
		$ar = array_unique(explode('||',$str));
		foreach($ar as $key=>$val) {
			if(strlen($val)==2 || strlen($val)==1 || strlen($val)==0) {
				unset($ar[$key]);
			}
		}
		$option = implode('||',$ar);
		if(strlen($option) > 0) {
			$opt = " && (".$option.") ";
		}
		if(count($distance) == 0) {
			if(!is_NULL($limit)) {
				$q1 = "select distinct * from accounts where status=1 && `delete` =0 $opt limit $start, $limit ORDER BY ID ASC";
			} else {
				$q1 = "select distinct * from accounts where status=1 && `delete` =0 $opt ORDER BY ID ASC";
			}
            $res1 = mysqli_query($con,$q1);
        } else {
            $limit_text      = !is_NULL($limit) ? 'LIMIT '.$start.', '.$limit : '';
            $distance_filter = '';
            if(isset($distance['more_than']) && !empty($distance['more_than'])){
                $distance_filter = ' distance >= '.$distance['more_than'].' ';
            } else if(isset($distance['less_than']) && !empty($distance['less_than'])){
                $distance_filter = ' distance <= '.$distance['less_than'].' ';
            } else {
                $distance_filter = ' distance <= 50 ';
            }
			
			$q2 = 'SELECT distinct *, ( 3959 * acos ( cos ( radians('.$distance['lat'].') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('.$distance['lng'].') ) + sin ( radians('.$distance['lat'].') ) * sin( radians( lat ) ) ) ) AS distance FROM accounts where status=1 and `delete`=0 '.$opt.' HAVING '.$distance_filter.' ORDER BY distance';
			
			$res1 = mysqli_query($con,$q2);
        }
		
        if(is_object($res1) && $res1->num_rows > 0) {
            return $res1;
        }
        return $res1;
	} // end get_stores
	
	function getOptions() {
		// since there are hardcoded business types in settings table.
		// so no need to fetch , just return those hard coded values as array.
		// global $con;
		// $res = mysqli_query($con,"select sku from accounts where status=1 && `delete` =0 limit 1" ); 
		// if(is_object($res) && $res->num_rows > 0){
			// $r = mysqli_fetch_assoc($res);
			// $sku = $r['sku'];
		//}
		$arr = array('Kids friendly','Pub and Bar','Bakery + Cafés Casual','Quick bite','Play area','Breakfast','Lunch','Dinner','Fast Food','Fine Dining','Coffee Shops','Coffee shop','Drinks + Nightlife','Beer Garden','Bar','Bistro','Food Court','Drive through','Live entertainment','Outside area','Cocktail bar','Lounge','Brasserie','Dining','Wi-fi','Food Truck','Road House','Live sport','Gambling','Pet friendly','Social Club','Deli','Dessert Parlor','Fast Casual','Kiosk');
		if(is_array($arr) && count($arr)>0) {
			$result = array('success' => 1, 'data' => $arr, 'errmsg' => '');
		} else {
			$result = array('success' => 0, 'data' => [], 'errmsg' => 'Error in array');
		}
		
        echo json_encode($result);die;
        
	}
	
	function get_details($sku) {
		global $con;
		if(mysqli_query($con,"select 1 from `".$sku."_settings` limit 1" ) == false) {
            return FALSE;
        }
		
   		$res = mysqli_query($con,"select * from `".$sku."_settings`");
        if(is_object($res) && $res->num_rows > 0){
			return $res;
        } else {
			return FALSE;
		}
        
    }
	
	function get_categories($sku) {
		global $con;
		if(mysqli_query($con,"select 1 from `".$sku."_categories` limit 1" ) == false) {
            return FALSE;
        }
		$res = mysqli_query($con,"select * from `".$sku."_categories`");
        if(is_object($res) && $res->num_rows > 0){
            return $res;
        }
        return array();
    }
	
	function get_meals($sku) {
		global $con;
		if(mysqli_query($con,"select 1 from `".$sku."_meals` limit 1" ) == false) {
            return FALSE;
        }
		$res = mysqli_query($con,"select * from `".$sku."_meals`");
        if(is_object($res) && $res->num_rows > 0){
            return $res;
        }
        return FALSE;
    }
	
	function check_in_array($search_text, $array) {
		return array_filter($array, function($el) use ($search_text) {
			return ( strpos($el, $search_text) !== FALSE );
		});
	}
	
	// public function time() {
        // die(timezone_location_get());
        // $result     = array( "success" => 0, "data" => array("Date" => Date('Y-m-d H:i:s')), "errmsg" => "");
        // header('Content-Type: application/json');
        // die(json_encode($result));
    // }
	
	function increaseViewCount() {
        $result     = array( "success" => 0, "errmsg" => "Failed to update");
        $distance   = array();
        $input      = file_get_contents('php://input');
        $data       = json_decode($input, true);
		
        if(isset($data) && is_array($data) && count($data) > 0){
            $restaurant_id = $data['restaurant_id'];
            $out = update_views($restaurant_id);
            if($out)
                $result     = array( "success" => 1, "errmsg" => "");
             
        }
        header('Content-Type: application/json');
        die(json_encode($result));
    }
	
	function update_views($id) {
		global $con;
		$q = "update accounts set views = views+1 where sku= '$id'";
		
        return mysqli_query($con,$q);
    }
	
?>
