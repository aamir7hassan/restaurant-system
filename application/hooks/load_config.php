<?php

class LoadConfig {
    
    function load_config() {
        
        $CI =& get_instance();
        $result = $CI->db->get($CI->config->item('SITE_ID').'settings')->result(); 
        
        foreach ($result as $key => $item)
        {
            $CI->config->set_item($item->index, $item->value);
        }  
        
        if($CI->config->item('currency_code') && $CI->config->item('currency_code') !='')
            define('CURRENCY_CODE', $CI->config->item('currency_code'));
        else
            define('CURRENCY_CODE', 'R');
    }

}