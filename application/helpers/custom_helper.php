<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @method price to display price in display format
 * @return price
 */

function check_in_array($search_text, $array){
    return array_filter($array, function($el) use ($search_text) {
        return ( strpos($el, $search_text) !== FALSE );
    });
    
}

function today_customers($all='') {
	$ci =& get_instance();
	
	if($all=="") {
		$today=date('Y-m-d');
		$res = $ci->dev_model->getData($this->config->item('SITE_ID')."orders",'count_array',$args=['where'=>['status'=>'paid','self_payment'=>1,'date(reserved_time)'=>$today]]);
	} else if($all!="") {
		$res = $ci->dev_model->getData($this->config->item('SITE_ID')."orders",'count_array',$args=['where'=>['status'=>'paid','self_payment'=>1]]);
	}
	
	return $res;
}

function accounts($sku) {
	$ci =& get_instance();
	$res = "0";
	if(!empty($sku)) {
		$res = $ci->dev_model->getData("accounts",'row_array',$args=['where'=>['sku'=>$sku]]);
		if($res!=NULL) {
			return $res;
		}
	} 
	return $res;
}


function address2cords($address) {
	$ci =& get_instance();
	$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $address . '&key='.$ci->config->item('google_key');
	
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
	return $work_location;
}

function driver_allowed() {
	$ci =& get_instance();
	$accounts = $ci->dev_model->getData('accounts','row_array',$args=['where'=>['sku'=>rtrim($ci->config->item('SITE_ID'),'_')]]);
	if($accounts==null) {
		return false;
	} else {
		return true;
	}
}

function is_role() {
	$ci   =& get_instance();
	$user = $ci->ion_auth->get_user_id();
	
	$res = $ci->dev_model->getData($ci->config->item('SITE_ID').'users','row_array',$args=['where'=>['id'=>$user],'select'=>['role']]);
	$val = $res['role']; 
	if($val=="waiter") {
		return "waiter";
	} elseif($val=="driver") {
		return "driver";
	} else {
		return '';
	}
	
}

function getTableId($id) {
	$ci   =& get_instance();
	$user = $ci->ion_auth->get_user_id();
	
	$res = $ci->dev_model->getData($ci->config->item('SITE_ID').'tables','row_array',$args=['where'=>['name'=>$id],'select'=>['id']]);
	if($res==null) {
		return false;
	} else {
		return $res['id'];
	}
}

function distancetime($cords) {
	$ci   =& get_instance();
	$cords = $orderinfo['coords'];
	$exp = explode(',',$cords);
	$cords = trim($exp[0]).','.trim($exp[1]);
	$url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=32.582968,74.064206&destinations=".$cords."&mode=driving&sensor=false&key=".$ci->config->item('google_key');
	$arr = [];
	$res = json_decode(file_get_contents($url),true);
	if($res['status']=="OK") {
		$arr['dist'] = $res['rows'][0]['elements'][0]['distance']['text'];
		$arr['time'] = $res['rows'][0]['elements'][0]['duration']['text'];
	}
	return $arr;
}
    
function array_to_csv_download($array, $filename = "export.csv", $delimiter=",") {
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename=export.csv;');
    $f = fopen('php://output', 'w');
    foreach ($array as $line) {
        fputcsv($f, $line, $delimiter);
    }
    fclose($f); 
    exit;
} 


function check_dates_special($data){
    if(isset($data['special_days']) && $data['special_days'] && isset($data['special_from']) && $data['special_from'] && isset($data['special_to']) && $data['special_to']){
        $today   = Date('N');
        $return1 = $return2 = $return3 = FALSE;
        
        if(in_array($today, $data['special_days'])){ 
            $return1 = TRUE;
        }
        
        $now_hour = Date('G');
        $now_min  = Date('i');
        if($now_hour > $data['special_from']['hour'] || ($now_min == $data['special_from']['hour'] && $now_min >= $data['special_from']['minute']) ){
            $return2 = TRUE;
        }
        
        if($now_hour < $data['special_to']['hour'] || ($now_min == $data['special_to']['hour'] && $now_min < $data['special_to']['minute']) ){
            $return3 = TRUE;
        }
        
        if($return1 && $return2 && $return3){
            return TRUE;
        }
        
    }
    return FALSE;
}

function price($number, $offer = ''){
    
    $without_offer = number_format((float)$number, 2, '.', '');
    if (empty($offer))
    {
        return  '<span class="main-price">Price : '.CURRENCY_CODE.$without_offer.'</span>';
    }
    
    $price      = ($number - (($number*$offer)/100));
    $with_offer = number_format((float)$price, 2, '.', '');
    
    return  '<span class="closed-price">Price : '.CURRENCY_CODE.$without_offer.'</span>'
            .'<span class="main-price">Price : '.CURRENCY_CODE.$with_offer.'<small class="off">('.$offer.'% off)</small></span>';
}

function time_took($order_time, $process_time){ 
    
    $pre_text  = $process_time == '0000-00-00 00:00:00' ? '' : '';
    $post_text = $process_time == '0000-00-00 00:00:00' ? ' ago' : ' ago';
    
    $process_time = $process_time == '0000-00-00 00:00:00' ? Date('Y-m-d H:i:s') : $process_time; 
    $seconds      = strtotime($process_time) - strtotime($order_time);
	
	define('OneMonth', 2592000);
	define('OneWeek', 604800);  
	define('OneDay', 86400);
	define('OneHour', 3600);    
	define('OneMinute', 60);
	 
	$months = floor($seconds / OneMonth);
	$weeks = floor(($seconds%OneMonth) / OneWeek);
	$days = floor(($seconds%OneWeek) / OneDay);
	$hours = floor(($seconds%OneDay) / OneHour);
	$mins = floor(($seconds%OneHour) / OneMinute);
	$secs = floor($seconds%OneMinute);
	if($months>0) {
		$time = $months." month";
	} else if($weeks>0) {
		$w = $weeks==1?' week':' weeks';
		$time = $weeks.$w;
	} else if($days >0) {
		$d = $days==1?' day':' days';
		$time = $days.$d;
	} else if($hours>0) {
		$h = $hours==1?' hour':' hours';
		$time = $hours.$h;
	} else if($mins>0) {
		$m = $mins==1?' minute':' minutes';
		$time = $mins.$m;
	} else if($secs>0) {
		$time = $secs." seconds";
	}
	return $pre_text.$time.$post_text;
}

function price_calc($number, $offer = ''){
    
    $without_offer = number_format((float)$number, 2, '.', '');
    if (empty($offer))
    {
        return $without_offer;
    }
    
    $price      = ($number - (($number*$offer)/100));
    $with_offer = number_format((float)$price, 2, '.', '');
    
    return  $with_offer;
}

/*
 * @method date to display dates in specified format
 * @param $date date in Y-m-d format
 * @return date in display format
 */

function pos_date($date = ""){
    
    if (!empty($date)):
        return date("l jS \of F Y h:i:s A", strtotime($date));
    endif;
    return;
}

/**
 * @method user_dates to return date in readable format
 * @param type $date date in timestamp format
 * @return type date
 */

function user_date($date = ""){
    if (!empty($date)):
        return date("jS  F Y h:i:s A", $date);
    endif;
    return;
}

/**
 * @method thumbnail to get product image based on either thumbnail or product exists
 * @param type $path path of the product image
 * @param type $image image name to be displayed
 * @return string image url
 */


function thumbnail($path, $image)
{
    if (empty($image))
    {
        return $path.'/not_found.jpg';
    }
    
    if (file_exists(IMAGE_PATH.'thumb'.SP.$image)){
        return $path.'/thumb/'.$image;
    }
    
    if (file_exists(IMAGE_PATH.$image)){
        return $path.'/'.$image;
    }
    
    return $path.'/not_found.jpg';
}

function deleteDirectory($dir) {
    system('rm -rf ' . escapeshellarg($dir), $retval);
    return $retval == 0; // UNIX commands return zero on success
}


/**
 * @method major_image to get product image based on either thumbnail or product exists
 * @param type $path path of the product image
 * @param type $image image name to be displayed
 * @return string image url
 */

function major_image($path, $image)
{
    if (empty($image))
    {
        return $path.'/not_found.jpg';
    }
    if (file_exists(IMAGE_PATH.$image)){
        return $path.'/'.$image;
    }
    if (file_exists(IMAGE_PATH.'thumb'.SP.$image)){
        return $path.'/thumb/'.$image;
    }
    
    return $path.'/not_found.jpg';
}

/**
 * @method type new_image(type $paramName) to set new image if product is new
 * @param string $new new or old
 * @return string  with image
 */

function new_image($new)
{
    if ( 'new' == $new)
    {
        return '<img src="'.base_url('/assets/images/new.png').'" alt="" class="new_img">';
    }
    return;
}

/**
 * @methof unique_string to generate random string
 * @param type $length
 * @return string
 */

function unique_string($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * @method type get_colour(type $paramName) to get the meal status
 * @param type $time time on which meal is ordered
 * @return type Specified class
 */

function get_colour($time)
{
    if(empty($time))
        return;
    
    $order_time     = strtotime($time);
    $current_time   = time();
    $minutes        = round(abs($order_time - $current_time) / 60,2);
    
    if ( $minutes < 5)
        return 'green';
    else if ( $minutes > 5 && $minutes <= 24 )
        return 'flash_blue';
    else if( $minutes >= 25)
        return 'flash_red';
    
   // if ( $minutes < 6)
    //    return 'blue';
    
    //if ( $minutes >= 5 )
    //    return 'flash_blue';
    
    //if ( $minutes > 25/* && $minutes < 30*/)
        return 'red';
    
    //if ( $minutes > 30)
       // return 'flash_red';
    
}

function get_colour_kitchen($time)
{
   // echo 'time '.$time;
    if(empty($time))
        return;
    
    $order_time     = strtotime($time);
    $current_time   = time();
    $minutes        = round(abs($order_time - $current_time) / 60,2);
   // if ( $minutes < 3)
     //   return 'green';
    
    //if ( $minutes < 6)
    //    return 'blue';
    
    //if ( $minutes > 6 && $minutes < 25)
    //    return 'flash_blue';
    
    if ( $minutes >= 25/* && $minutes < 30*/)
        return 'red';
    
    return 'dark';
    //if ( $minutes > 30)
       // return 'flash_red';
    
}


function write_ini_file($assoc_arr, $path, $has_sections=FALSE) { 
    $content = ""; 
    if ($has_sections) { 
        foreach ($assoc_arr as $key=>$elem) { 
            $content .= "[".$key."]\n"; 
            foreach ($elem as $key2=>$elem2) { 
                if(is_array($elem2)) 
                { 
                    for($i=0;$i<count($elem2);$i++) 
                    { 
                        $content .= $key2."[] = \"".$elem2[$i]."\"\n"; 
                    } 
                } 
                else if($elem2=="") $content .= $key2." = \n"; 
                else $content .= $key2." = \"".$elem2."\"\n"; 
            } 
        } 
    } 
    else { 
        foreach ($assoc_arr as $key=>$elem) { 
            if(is_array($elem)) 
            { 
                for($i=0;$i<count($elem);$i++) 
                { 
                    $content .= $key."[] = \"".$elem[$i]."\"\n"; 
                } 
            } 
            else if($elem=="") $content .= $key." = \n"; 
            else $content .= $key." = \"".$elem."\"\n"; 
        } 
    } 

    if (!$handle = fopen($path, 'w')) { 
        return false; 
    }

    $success = fwrite($handle, $content);
    fclose($handle); 

    return $success; 
}


function number($number){
    return number_format((float)$number, 2, '.', ''); 
}

function hash_new_password($password){
    if (empty($password)){
            return FALSE;
    }
    $salt = substr(md5(uniqid(rand(), true)), 0, 10);
    return  $salt . substr(sha1($salt . $password), 0, -10);
}