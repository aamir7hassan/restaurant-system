<?php

$errors          = array();
if(isset($_POST['name'])){ 

    function hash_password($password)
    {
        if (empty($password)) 
        {
                return FALSE;
        }

        $salt = substr(md5(uniqid(rand(), true)), 0, 10);
        return  $salt . substr(sha1($salt . $password), 0, -10);
    }
    
    function clean($string) {
        $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\_]/', '', $string); // Removes special chars.
    }
    
    require_once ('database/MysqliDb.php');
     
    $db = new MysqliDb ('takki-db.cgqeqdugauga.us-east-2.rds.amazonaws.com', 'admin', 'Temp1234', 'takki'); 
    
    $name            = $db->escape (trim($_POST['name']));
    $surname         = $db->escape (trim($_POST['surname']));
    $restaurant_name = $db->escape (trim($_POST['restaurant_name']));
    $email           = $db->escape (trim($_POST['email']));
    $phone           = $db->escape (trim($_POST['phone']));
    $city            = $db->escape (trim($_POST['city']));
    $packages        = $db->escape (trim($_POST['packages']));
    $orig_password   = $password        = $db->escape (trim($_POST['password']));
    $sku             = uniqid();//clean(strtolower($restaurant_name));
    
    
    if(strlen($name) < 3)
        $errors['name'] = 'Name required. Minimum 3 characters.';
    if(strlen($surname) < 3)
        $errors['surname'] = 'Surname required. Minimum 3 characters.';
    if(strlen($restaurant_name) < 3 || $restaurant_name > 20)
        $errors['restaurant_name'] = 'Restaurant name must contain 3 to 20 characters.';
    if(empty($email))
        $errors['email'] = 'Email required';
    if(filter_var($email, FILTER_VALIDATE_EMAIL) === false)
        $errors['email'] = 'Please enter a valid email.';
    
    //if(strlen($password) < 5)
        //$errors['password'] = 'Please enter atleast 5 characters as password.';
    
  
    if(count($errors) == 0){
        
        $db->where ("sku", $sku); 
        $sku_exist = $db->getOne ("accounts");        

        if($sku_exist !== NULL)
            $errors['sku'] = 'The store name you choosed already exist. Please try another.';
        
        $db->where ("email", $email);
        $email_exist = $db->getOne ("accounts");
        
        if($email_exist !== NULL)
            $errors['email'] = 'The email you choosed already exist. Please try another.';
        
    }
    
    
    if(count($errors) == 0)
    {
		//$pass = $password;
		// by default it will show 12345678 as password
		$pass = 12345678;
        $password = hash_password(12345678);
        
        $data     = array(
                    'name'              => $name,
                    'surname'           => $surname,
                    'restaurant_name'   => $restaurant_name,
                    'email'             => $email,
                    'phone'             => $phone,
                    'city'              => $city,
                    'packages'          => $packages,
                    'sku'               => $sku,
                    'password'          => $password,
					'pass'				=> $pass,
                    'comments'          => "",
                    'status'            => 0,
                    'unique'            => clean($restaurant_name)."_".  uniqid()
        );
         
        $id = $db->insert ('accounts', $data); 
		
        
        if(is_numeric($id) && $id > 0)
        {
            $query  = "CREATE TABLE IF NOT EXISTS `".$sku."_app_sessions` (`session_id` varchar(40) NOT NULL DEFAULT '0',`ip_address` varchar(45) NOT NULL DEFAULT '0',`user_agent` varchar(120) NOT NULL,`last_activity` int(10) unsigned NOT NULL DEFAULT '0',`user_data` text NOT NULL,PRIMARY KEY (`session_id`),KEY `last_activity_idx` (`last_activity`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $result1 = $db->rawQueryValue ($query);
            
            $query = "CREATE TABLE IF NOT EXISTS `".$sku."_attributes` (`id` int(11) NOT NULL COMMENT 'unique identifier',`index` varchar(50) NOT NULL COMMENT 'Index to check attribute names',`type` varchar(10) NOT NULL,`name` varchar(150) NOT NULL COMMENT 'product attribute display text',`values` text NOT NULL,`required` int(11) NOT NULL,`sort` int(11) NOT NULL,`date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created date',PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Products attributes';";
            $result1 = $db->rawQueryValue ($query);
            
            $query = "CREATE TABLE IF NOT EXISTS `".$sku."_categories` (`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',`index` varchar(50) NOT NULL,`name` varchar(50) NOT NULL COMMENT 'category name', `sort` int(11) NOT NULL,`active` varchar(1) NOT NULL COMMENT '0 no, 1 yes',`quantity` int(11) NOT NULL, `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,PRIMARY KEY (`id`),UNIQUE KEY `index` (`index`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
            $result1 = $db->rawQueryValue ($query);
            
            $query = "CREATE TABLE IF NOT EXISTS `".$sku."_comments` (`id` int(11) NOT NULL AUTO_INCREMENT,`meal_id` int(11) NOT NULL,`order_id` int(11) NOT NULL,`comment` text NOT NULL,`date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
            $result1 = $db->rawQueryValue ($query);
            
            $query = "CREATE TABLE IF NOT EXISTS `".$sku."_groups` (`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(20) NOT NULL,`description` varchar(100) NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            $result1 = $db->rawQueryValue ($query);
            
            $query = "INSERT INTO `".$sku."_groups` (`id`, `name`, `description`) VALUES(1, 'admin', 'Super Admin'),(2, 'owner', 'Store Owner'),(3, 'waiter', 'Waiter'),(4, 'customer', 'Customer');";
            $result1 = $db->rawQueryValue ($query);
            
            $query = "CREATE TABLE IF NOT EXISTS `".$sku."_logs` (`id` int(11) NOT NULL AUTO_INCREMENT,`errno` int(2) NOT NULL,`errtype` varchar(32) NOT NULL,`errstr` text NOT NULL,`errfile` varchar(255) NOT NULL,`errline` int(4) NOT NULL,`user_agent` varchar(120) NOT NULL,`ip_address` varchar(45) NOT NULL DEFAULT '0',`time` datetime NOT NULL,PRIMARY KEY (`id`,`ip_address`,`user_agent`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            $result1 = $db->rawQueryValue ($query);

            $query  = "CREATE TABLE IF NOT EXISTS `".$sku."_meals` (`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',`index` varchar(150) NOT NULL COMMENT 'unique identifier in string format',`name` varchar(150) NOT NULL COMMENT 'meal title',`description` text NOT NULL COMMENT 'Description of meals',`price` float NOT NULL COMMENT 'Price for meal',`quantity` int(11) NOT NULL, `sort` int(11) NOT NULL,`take_away` int(1) NOT NULL,`special` int(1) NOT NULL,`special_days` varchar(250) NOT NULL,`special_from` varchar(100) NOT NULL,`special_to` varchar(100) NOT NULL,`active` INT(1) NOT NULL, `out_of_stock` INT(1) NOT NULL,`show_available` varchar(1) NOT NULL COMMENT '0 not show, 1 show',`hide_stock` varchar(1) NOT NULL COMMENT '0 yes, 1 no', `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created date', PRIMARY KEY (`id`))ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
            $result1 = $db->rawQueryValue ($query);
          
            $query = "CREATE TABLE IF NOT EXISTS `".$sku."_meal_attributes` (`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',`meal_id` int(11) NOT NULL COMMENT 'Forign key to meals table',`attribute_id` int(11) NOT NULL COMMENT 'Forign key to attribute table',PRIMARY KEY (`id`),KEY `fk_attr_attributes` (`attribute_id`),KEY `fk_meal_attributes` (`meal_id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
            $result1 = $db->rawQueryValue ($query);
            
            $query = "CREATE TABLE IF NOT EXISTS `".$sku."_meal_categories` (`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',`meal_id` int(11) NOT NULL COMMENT 'Forign key to meals table',`category_id` int(11) NOT NULL COMMENT 'Forign key to category table',PRIMARY KEY (`id`),KEY `fk_meal_categories` (`meal_id`),KEY `fk_category_categories` (`category_id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
            $result1 = $db->rawQueryValue ($query);
            
            $query = "CREATE TABLE IF NOT EXISTS `".$sku."_orders` ( `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',`table_id` int(11) DEFAULT NULL,`user_id` int(11) DEFAULT NULL,`customer_name` varchar(100) DEFAULT NULL COMMENT 'name enterd by customer',`contact_name` varchar(100) DEFAULT NULL COMMENT 'take_away contact name',
			`price` decimal(10,2) NOT NULL DEFAULT 0.00,`total_price` decimal(10,0) DEFAULT NULL,`change_for` varchar(20) DEFAULT NULL COMMENT 'change for - iff take away delivery',`tip` decimal(10,2) NOT NULL DEFAULT 0.00,`delivery_charge` decimal(10,2) NOT NULL DEFAULT 0.00,`budget` decimal(10,2) NOT NULL DEFAULT 0.00,`active` int(1) NOT NULL DEFAULT 1,`status` varchar(50) DEFAULT NULL COMMENT 'paybill',`master` int(1) NOT NULL DEFAULT 0,`self_payment` int(1) NOT NULL DEFAULT 1,`payed_by` int(11) DEFAULT NULL,`payed_by_confirm` varchar(1) DEFAULT NULL COMMENT '3 = splited , 2 request for add bill ,1 user confirmd (yes), 0 user denied (no)  for popup',`popup_shown` varchar(1) DEFAULT '0' COMMENT 'supporting colum for payed_y_confirm',`payment_method` varchar(10) DEFAULT NULL,`reserved_time` timestamp NOT NULL DEFAULT current_timestamp(),`released_time` timestamp NULL DEFAULT NULL,`billrequest_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',`under_18` int(1) DEFAULT NULL,`tendered` decimal(10,2) NOT NULL DEFAULT 0.00,`tendered_change` decimal(10,2) NOT NULL DEFAULT 0.00,`options` varchar(2) NOT NULL COMMENT '1 email, 2 print, 3 email and print, 4 none',`email` varchar(100) NOT NULL COMMENT 'email where bill will be sent.',`cell` varchar(20) NOT NULL,`address` varchar(255) NOT NULL COMMENT 'address ',`coords` varchar(255) NOT NULL COMMENT 'address coordinates for map',`passcode` varchar(10) NOT NULL,`type` varchar(20) NOT NULL COMMENT 'delivery / collection',`allocated` varchar(1) NOT NULL DEFAULT '0' COMMENT '0 free, 1 allocated',`allocated_user` int(11) NOT NULL COMMENT 'only this user can deselect the orders',`order_start` varchar(1) DEFAULT '' COMMENT '3 in transit, 4 delivered - just 3 or 4',`user_waiter_confirm` int(1) DEFAULT 0 COMMENT 'if waiter order on user behalf then this user should show popup, no processing - just notification | 2 means completed',primary key (id)) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
            $result1 = $db->rawQueryValue ($query);
            
            $query = "CREATE TABLE IF NOT EXISTS `".$sku."_order_details` (`id` int(11) NOT NULL AUTO_INCREMENT,`order_id` int(11) NOT NULL,`temp_user` int(11) DEFAULT NULL COMMENT 'if waiter added on user behalf , if null then user added order.',`meal_id` int(11) NOT NULL COMMENT 'id from meals table',`qty` int(11) NOT NULL,`meal_price` decimal(10,2) NOT NULL COMMENT 'Only meal price',`attribute_price` decimal(10,2) NOT NULL COMMENT 'All attributes total price',`attribute_price_log` varchar(250) NOT NULL COMMENT 'Attributes separate price list',`price` decimal(10,2) NOT NULL COMMENT 'price of meal+attributes',`attr_cats` text NOT NULL,`attribute` text NOT NULL,`category` varchar(250) NOT NULL,`processed` int(1) NOT NULL DEFAULT 0 COMMENT 'waiter processed or not  0-Not processed; 1-left kitchen; 2-waiter processed order, 3 in transit,   4 driver delivered it',`comments` text NOT NULL,`order_time` timestamp NOT NULL DEFAULT current_timestamp(),`waiter_process_time` datetime NOT NULL,`process_time` datetime NOT NULL,`kitchen_left` datetime NOT NULL,primary key (id)) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
            $result1 = $db->rawQueryValue ($query);
            
            $query   = "CREATE TABLE `".$sku."_food_types` (`id` int(11) NOT NULL,`name` varchar(250) NOT NULL,`status` int(11) NOT NULL,`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
            $result1 = $db->rawQueryValue ($query);
            
            $query   = "CREATE TABLE IF NOT EXISTS `".$sku."_tables` (`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',`name` varchar(150) NOT NULL COMMENT 'name of the tables',`seats` int(11) NOT NULL COMMENT 'number of seats for a table',`qr_code` varchar(250) NOT NULL COMMENT 'QR code to access table', `virtual` INT NOT NULL, `address` TEXT NOT NULL, `unique` varchar(150) NOT NULL,`date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'table added date',PRIMARY KEY (`id`),UNIQUE KEY `unique` (`unique`),UNIQUE KEY `name` (`name`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
            $result1 = $db->rawQueryValue ($query);
            
            $query   = "CREATE TABLE IF NOT EXISTS `".$sku."_logs` (`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',`waiter_id` int(11) NOT NULL,`login` timestamp NOT NULL,`logout` timestamp NOT NULL, `date` date NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
            $result1 = $db->rawQueryValue ($query);
            
            $query  = "CREATE TABLE IF NOT EXISTS `".$sku."_users` (`id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',`ip_address` varbinary(16) NOT NULL,`username` varchar(100) NOT NULL,`password` varchar(80) NOT NULL,`salt` varchar(40) DEFAULT NULL,`email` varchar(100) NOT NULL,`pass` varchar(80) NOT NULL,`activation_code` varchar(40) DEFAULT NULL,`forgotten_password_code` varchar(40) DEFAULT NULL,`forgotten_password_time` int(11) unsigned DEFAULT NULL,`remember_code` varchar(40) DEFAULT NULL,`created_on` int(11) unsigned NOT NULL,`last_login` int(11) unsigned DEFAULT NULL,`active` tinyint(1) unsigned DEFAULT NULL,`first_name` varchar(50) DEFAULT NULL,`last_name` varchar(50) DEFAULT NULL,`company` varchar(100) DEFAULT NULL,`phone` varchar(20) DEFAULT NULL,`site` varchar(50) NOT NULL COMMENT 'Site id to which this user belongs to',`waiter_float` decimal(10,2) NULL DEFAULT '0.00',`take_away` varchar(1) DEFAULT NULL  COMMENT '1 yes, 0 no',`role` varchar(10)  COMMENT 'waiter,driver', PRIMARY KEY (`id`),UNIQUE KEY `email` (`email`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            $result1 = $db->rawQueryValue ($query);
            
            $query = "INSERT INTO `".$sku."_users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`, `site`) VALUES(1, '\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0', '".$name."', '".$password."', '', '".$email."', '', '', '', '', ".time().", ".time().", 1, '".$name."', '".$surname."', '".$restaurant_name."', '".$phone."', '".$city."'), (2, '', 'adam', '15da07717fcc3ec1dc2463c38d867a5de00375bf', NULL, 'ettiene@takki.co.za', NULL, NULL, NULL, NULL, '', NULL, '1', 'Super', 'Admin', 'Nuro', NULL, '');";
            
            //echo $query;
            $result1 = $db->rawQueryValue ($query);
            $query = "INSERT INTO `".$sku."_users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`, `site`) VALUES(2, '\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0', 'Super', '15da07717fcc3ec1dc2463c38d867a5de00375bf', '', 'ettiene@takki.co.za', '', '', '', '', ".time().", ".time().", 1, '".$name."', '".$surname."', '".$restaurant_name."', '".$phone."', '".$city."'), (2, '', 'adam', '15da07717fcc3ec1dc2463c38d867a5de00375bf', NULL, 'ettiene@takki.co.za', NULL, NULL, NULL, NULL, '', NULL, '1', 'Super', 'Admin', 'Nuro', NULL, '');";
            $result1 = $db->rawQueryValue ($query);
            
            $query = "CREATE TABLE IF NOT EXISTS `".$sku."_users_groups` (`id` int(11) NOT NULL AUTO_INCREMENT,`user_id` int(11) NOT NULL,`group_id` int(11) NOT NULL,PRIMARY KEY (`id`),KEY `fk_group_groups` (`group_id`),KEY `fk_user_groups` (`user_id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            $result1 = $db->rawQueryValue ($query);
            
            $query = "INSERT INTO `".$sku."_users_groups` (`id`, `user_id`, `group_id`) VALUES(1, 1, 1), (2, 2, 1)";
            $result1 = $db->rawQueryValue ($query);
            
            $query = "CREATE TABLE IF NOT EXISTS `".$sku."_waiters` (`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',`name` varchar(100) NOT NULL COMMENT 'name of waiter',`unique` varchar(150) NOT NULL COMMENT 'unique identifier',`date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,PRIMARY KEY (`id`),UNIQUE KEY `unique` (`unique`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
            $result1 = $db->rawQueryValue ($query);
            
            $query = "CREATE TABLE IF NOT EXISTS `".$sku."_waiter_notice` (`id` int(11) NOT NULL AUTO_INCREMENT,`order_id` int(11) NOT NULL,`table_id` int(11) NOT NULL,`message` varchar(250) NOT NULL DEFAULT 'Please give attention to table',`status` int(1) NOT NULL DEFAULT '1',`date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
            $result1 = $db->rawQueryValue ($query);
			
            
            $query  = "CREATE TABLE IF NOT EXISTS `".$sku."_waiter_table_relation` (`id` int(11) NOT NULL AUTO_INCREMENT,`waiter_id` int(11) NOT NULL,`table_id` int(11) NOT NULL,PRIMARY KEY (`id`),KEY `FK_waiters_map` (`waiter_id`),KEY `FK_tables_map` (`table_id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
            $result1 = $db->rawQueryValue ($query);
			
			$query  = "CREATE TABLE IF NOT EXISTS `".$sku."_waiter_notifications` (`id` int(11) NOT NULL AUTO_INCREMENT,
			`order_id` int(11) NOT NULL,`table_id` int(11) NOT NULL,`waiter_id` int(11) NOT NULL,`type` enum('new','delivered','waiting','') NOT NULL,`status` int(11) NOT NULL,`date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
            $result1 = $db->rawQueryValue ($query);
			
			$query  = "CREATE TABLE IF NOT EXISTS `".$sku."_waiter_logs` ( `id` int(11) NOT NULL AUTO_INCREMENT,
			`waiter_id` int(11) NOT NULL,`login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',`logout` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',`date` date NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
            $result1 = $db->rawQueryValue ($query);
            
            
            $query  = "ALTER TABLE `".$sku."_meal_attributes` ADD CONSTRAINT `fk_attr_attributes` FOREIGN KEY (`attribute_id`) REFERENCES `".$sku."_attributes` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `fk_meal_attributes` FOREIGN KEY (`meal_id`) REFERENCES `".$sku."_meals` (`id`) ON DELETE CASCADE;";
            $result1 = $db->rawQueryValue ($query);
            
            $query = "ALTER TABLE `".$sku."_meal_categories`  ADD CONSTRAINT `fk_category_categories` FOREIGN KEY (`category_id`) REFERENCES `".$sku."_categories` (`id`) ON DELETE CASCADE,ADD CONSTRAINT `fk_meal_categories` FOREIGN KEY (`meal_id`) REFERENCES `".$sku."_meals` (`id`) ON DELETE CASCADE;";
            $result1 = $db->rawQueryValue ($query);
            
            $query = "ALTER TABLE `".$sku."_orders` ADD CONSTRAINT `fk_orders_table` FOREIGN KEY (`table_id`) REFERENCES `".$sku."_tables` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $result1 = $db->rawQueryValue ($query);
            
            $query = "ALTER TABLE `".$sku."_order_details` ADD CONSTRAINT `FK_orders_order` FOREIGN KEY (`order_id`) REFERENCES `".$sku."_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE, ADD CONSTRAINT `meal_order_fk` FOREIGN KEY (`meal_id`) REFERENCES `".$sku."_meals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $result1 = $db->rawQueryValue ($query);
            
            $query = "CREATE TABLE IF NOT EXISTS `".$sku."_settings` (`id` int(11) NOT NULL AUTO_INCREMENT,`index` varchar(50) NOT NULL,`value` varchar(250) NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
            $result1 = $db->rawQueryValue ($query);
            
            $query = "INSERT INTO `".$sku."_attributes` (`id`, `index`, `type`, `name`, `values`, `required`, `sort`, `date`) VALUES
            (1, 'meat_preparation', 'multi', 'Meat Preparation', '[{\"name\":\"Well Done\",\"price\":\"0.00\"},{\"name\":\"Medium\",\"price\":\"0.00\"},{\"name\":\"Medium Rare\",\"price\":\"0.00\"},{\"name\":\"Rare\",\"price\":\"20.00\"},{\"name\":\"Blue Rare\",\"price\":\"10.00\"}]', 1, 1, '2016-10-31 06:11:12'),
            (2, 'touch', 'multi', 'Touch', '[{\"name\":\"Soft\",\"price\":\"0.00\"},{\"name\":\"Medium\",\"price\":\"0.00\"},{\"name\":\"Hard\",\"price\":\"0.00\"},{\"name\":\"Rubbery\",\"price\":\"0.00\"}]', 1, 3, '2016-10-31 06:12:13'),
            (3, 'general_preparation', '', 'General Preparation', '[{\"name\":\"Grilled\",\"price\":\"0.00\"},{\"name\":\"Fried\",\"price\":\"0.00\"}]', 0, 2, '2016-10-31 06:12:58'),
            (4, 'thickness', '', 'Thickness', '[{\"name\":\"Thick\",\"price\":\"0.00\"},{\"name\":\"Medium\",\"price\":\"0.00\"},{\"name\":\"Thin\",\"price\":\"0.00\"}]', 0, 0, '2016-10-31 06:13:52'),
            (5, 'shape', '', 'Shape', '[{\"name\":\"Square\",\"price\":\"0.00\"},{\"name\":\"Round\",\"price\":\"0.00\"},{\"name\":\"Flat\",\"price\":\"0.00\"},{\"name\":\"Long\",\"price\":\"0.00\"},{\"name\":\"Triangle\",\"price\":\"0.00\"}]', 0, 0, '2016-10-31 06:14:38'),
            (6, 'additional_preparation', '', 'Additional Preparation', '[{\"name\":\"Smoked\",\"price\":\"0.00\"},{\"name\":\"Crunchy\",\"price\":\"0.00\"},{\"name\":\"Smooth\",\"price\":\"0.00\"}]', 0, 0, '2016-10-31 06:15:16'),
            (7, 'taste', '', 'Taste', '[{\"name\":\"Sweet\",\"price\":\"0.00\"},{\"name\":\"Sour\",\"price\":\"0.00\"},{\"name\":\"Bitter\",\"price\":\"0.00\"},{\"name\":\"Salty\",\"price\":\"0.00\"},{\"name\":\"Spicy\",\"price\":\"0.00\"}]', 0, 0, '2016-10-31 06:16:18'),
            (8, 'beverage_preparation', '', 'Beverage Preparation', '[{\"name\":\"Ice\",\"price\":\"0.00\"},{\"name\":\"Crushed Ice\",\"price\":\"0.00\"},{\"name\":\"Mint\",\"price\":\"0.00\"},{\"name\":\"Lemon\",\"price\":\"0.00\"}]', 0, 0, '2016-10-31 06:17:11'),
            (9, 'starch', '', 'Starch', '[{\"name\":\"Chips\",\"price\":\"0.00\"},{\"name\":\"Potato\",\"price\":\"0.00\"},{\"name\":\"Rice\",\"price\":\"0.00\"},{\"name\":\"Wedges\",\"price\":\"0.00\"}]', 0, 0, '2016-10-31 06:18:02'),
            (10, 'bread', '', 'Bread', '[{\"name\":\"Panini\",\"price\":\"0.00\"},{\"name\":\"Bun\",\"price\":\"0.00\"},{\"name\":\"Sliced\",\"price\":\"0.00\"},{\"name\":\"Hotdog\",\"price\":\"0.00\"},{\"name\":\"Brown\",\"price\":\"0.00\"},{\"name\":\"White\",\"price\":\"0.00\"},{\"name\":\"Whole wheat\",\"price\":\"0.00\"}]', 0, 0, '2016-10-31 06:19:16'),
            (11, 'color', '', 'Color', '[{\"name\":\"Yellow\",\"price\":\"0.00\"},{\"name\":\"Red\",\"price\":\"0.00\"},{\"name\":\"Green\",\"price\":\"0.00\"},{\"name\":\"Blue\",\"price\":\"0.00\"},{\"name\":\"Dark\",\"price\":\"0.00\"},{\"name\":\"Milky\",\"price\":\"0.00\"},{\"name\":\"White\",\"price\":\"0.00\"},{\"name\":\"Cream\",\"price\":\"0.00\"},{\"name\":\"Black\",\"price\":\"0.00\"},{\"name\":\"Orange\",\"price\":\"0.00\"},{\"name\":\"Pink\",\"price\":\"0.00\"},{\"name\":\"Purple\",\"price\":\"0.00\"}]', 0, 0, '2016-10-31 06:22:12'),
            (12, 'spicy', '', 'Spicy', '[{\"name\":\"Mild\",\"price\":\"0.00\"},{\"name\":\"Medium\",\"price\":\"0.00\"},{\"name\":\"Spicy\",\"price\":\"0.00\"},{\"name\":\"Hot\",\"price\":\"0.00\"},{\"name\":\"Very Hot\",\"price\":\"0.00\"},{\"name\":\"Xtra Hot\",\"price\":\"0.00\"},{\"name\":\"Peri-Peri\",\"price\":\"0.00\"},{\"name\":\"Chilli\",\"price\":\"0.00\"},{\"name\":\"Semi-Sweet\",\"price\":\"0.00\"},{\"name\":\"Continental\",\"price\":\"0.00\"},{\"name\":\"Rich\",\"price\":\"0.00\"}]', 0, 0, '2016-10-31 06:24:38'),
            (13, 'additional_flavors', '', 'Additional Flavors', '[{\"name\":\"Creamy\",\"price\":\"0.00\"},{\"name\":\"Garlic\",\"price\":\"0.00\"},{\"name\":\"Garlic Cheese\",\"price\":\"0.00\"},{\"name\":\"Cheese\",\"price\":\"0.00\"},{\"name\":\"Rich\",\"price\":\"0.00\"},{\"name\":\"Salty\",\"price\":\"0.00\"},{\"name\":\"Bitter\",\"price\":\"0.00\"}]', 0, 0, '2016-10-31 06:26:40'),
            (14, 'hot_beverages', '', 'Hot Beverages', '[{\"name\":\"Milk\",\"price\":\"0.00\"},{\"name\":\"Sweetener\",\"price\":\"0.00\"},{\"name\":\"Foam\",\"price\":\"0.00\"},{\"name\":\"Cream\",\"price\":\"0.00\"},{\"name\":\"De-caf\",\"price\":\"0.00\"}]', 0, 0, '2016-10-31 06:27:45'),
            (15, 'vegetable_sides', '', 'Vegetable Sides', '[{\"name\":\"Spinach\",\"price\":\"0.00\"},{\"name\":\"Pumpkin\",\"price\":\"0.00\"},{\"name\":\"Carrot\",\"price\":\"0.00\"},{\"name\":\"Peas\",\"price\":\"0.00\"},{\"name\":\"Mix Veggies\",\"price\":\"0.00\"},{\"name\":\"Stir Fry\",\"price\":\"0.00\"}]', 0, 0, '2016-10-31 06:28:53'),
            (16, 'country_of_flavour', '', 'Country of flavour', '[{\"name\":\"French\",\"price\":\"0.00\"},{\"name\":\"Greek\",\"price\":\"0.00\"},{\"name\":\"German\",\"price\":\"0.00\"},{\"name\":\"American\",\"price\":\"0.00\"},{\"name\":\"English\",\"price\":\"0.00\"},{\"name\":\"Indian\",\"price\":\"0.00\"},{\"name\":\"Chinese\",\"price\":\"0.00\"},{\"name\":\"Japanese\",\"price\":\"0.00\"},{\"name\":\"Tai\",\"price\":\"0.00\"},{\"name\":\"Portuguese\",\"price\":\"0.00\"},{\"name\":\"Brazilian\",\"price\":\"0.00\"},{\"name\":\"Carribean\",\"price\":\"0.00\"}]', 0, 0, '2016-10-31 06:30:56'),
            (17, 'fruits_/_flavours', '', 'Fruits / flavours', '[{\"name\":\"Apple\",\"price\":\"0.00\"},{\"name\":\"Lemon\",\"price\":\"0.00\"},{\"name\":\"Orange\",\"price\":\"0.00\"},{\"name\":\"Strawberry\",\"price\":\"0.00\"},{\"name\":\"Blue Berry\",\"price\":\"0.00\"},{\"name\":\"Cranberry\",\"price\":\"0.00\"},{\"name\":\"Banana\",\"price\":\"0.00\"},{\"name\":\"Pear\",\"price\":\"0.00\"},{\"name\":\"Grape\",\"price\":\"0.00\"},{\"name\":\"Mango\",\"price\":\"0.00\"},{\"name\":\"Chocolate\",\"price\":\"0.00\"},{\"name\":\"Bubblegum\",\"price\":\"0.00\"},{\"name\":\"Lime\",\"price\":\"0.00\"}]', 0, 0, '2016-10-31 06:32:59'),
            (18, 'mixer', '', 'Mixer', '[{\"name\":\"Coke\",\"price\":10},{\"name\":\"Ice\",\"price\":1},{\"name\":\"Soda\",\"price\":1}]', 0, 0, '2017-01-14 06:35:57'),
            (19, 'spirit_coolers', '', 'Spirit coolers', '[{\"name\":\"Coce 330ml\",\"price\":\"10.00\"},{\"name\":\"ice\",\"price\":\"0.00\"},{\"name\":\"sprite 220ml\",\"price\":\"8.00\"},{\"name\":\"juice\",\"price\":\"15.00\"},{\"name\":\"water\",\"price\":\"0.00\"}]', 0, 0, '2017-01-16 09:38:55'),
            (20, 'selected_brandy', '', 'Selected Brandy', '[{\"name\":\"KWV\",\"price\":\"20.00\"},{\"name\":\"KWV Dubble\",\"price\":\"40.00\"},{\"name\":\"Klipdrift\",\"price\":\"22.00\"}]', 0, 0, '2017-01-16 09:43:23'),
            (21, 'size', '', 'Size', '[{\"name\":\"Kiddies\",\"price\":\"0.00\"},{\"name\":\"Small\",\"price\":\"0.00\"},{\"name\":\"Medium\",\"price\":\"0.00\"},{\"name\":\"Large\",\"price\":\"0.00\"},{\"name\":\"Extra Large\",\"price\":\"0.00\"},{\"name\":\"Jumbo\",\"price\":\"0.00\"}]', 0, 0, '2017-01-17 08:39:04'),
            (22, 'beverage_size', '', 'Beverage Size', '[{\"name\":\"Small\",\"price\":\"\"},{\"name\":\"Large\",\"price\":\"\"},{\"name\":\"Draught\",\"price\":\"\"},{\"name\":\"Glass\",\"price\":\"\"},{\"name\":\"Shot\",\"price\":\"\"},{\"name\":\"Double Shot\",\"price\":\"\"},{\"name\":\"500ml\",\"price\":\"\"},{\"name\":\"400ml\",\"price\":\"\"},{\"name\":\"300ml\",\"price\":\"\"},{\"name\":\"200ml\",\"price\":\"\"},{\"name\":\"Can\",\"price\":\"\"},{\"name\":\"Glass Bottle\",\"price\":\"\"}]', 0, 0, '2017-01-17 08:50:26'),
            (23, 'test1', 'single', 'Test1', '[{\"name\":\"adam1\",\"price\":\"55.00\"},{\"name\":\"adam2\",\"price\":\"44.00\"},{\"name\":\"adam3\",\"price\":\"33.00\"}]', 0, 0, '2017-02-22 11:14:45'),
            (24, 'test2', 'multi', 'Test2', '[{\"name\":\"a\",\"price\":\"99.00\"},{\"name\":\"b\",\"price\":\"88.00\"},{\"name\":\"c\",\"price\":\"77.00\"}]', 0, 1, '2017-02-22 11:15:25');";

            $result1 = $db->rawQueryValue ($query);
            
            $query = "INSERT INTO `".$sku."_categories` (`id`, `index`, `name`, `date`) VALUES
                        (1, 'starters-cat-58235aeacf333', 'Starters', '2016-11-09 17:20:42'),
                        (2, 'appetizers-cat-58235af96d241', 'Appetizers', '2016-11-09 17:20:57'),
                        (3, 'main-meal-cat-58235b055c815', 'Main Meal', '2016-11-09 17:21:09'),
                        (4, 'beverages-cat-58235b11331ca', 'Beverages', '2016-11-09 17:21:21'),
                        (5, 'cocktails-cat-58235b1b790d9', 'Cocktails', '2016-11-09 17:21:31'),
                        (6, 'burgers-cat-58235b25b6763', 'Burgers', '2016-11-09 17:21:41'),
                        (7, 'pastas-cat-58235b328e2d1', 'Pastas', '2016-11-09 17:21:54'),
                        (8, 'pizzas-cat-58235b3d9875f', 'Pizzas', '2016-11-09 17:22:05'),
                        (9, 'chicken-cat-58235b47a76b6', 'Chicken', '2016-11-09 17:22:15'),
                        (10, 'fish-cat-58235b52a9440', 'Fish', '2016-11-09 17:22:26'),
                        (11, 'combo-cat-58235b5e64665', 'Combo', '2016-11-09 17:22:38'),
                        (12, 'pork-cat-58235b701fc57', 'Pork', '2016-11-09 17:22:56'),
                        (13, 'sandwiches-cat-58235b8072ddd', 'Sandwiches', '2016-11-09 17:23:12'),
                        (14, 'indian-cat-58235b960c9ef', 'Indian', '2016-11-09 17:23:34'),
                        (15, 'wines-cat-58235ba3e00da', 'Wines', '2016-11-09 17:23:47'),
                        (16, 'alcohol-cat-58235baf1259e', 'Alcohol', '2016-11-09 17:23:59'),
                        (17, 'dessert-cat-58235bbcc29fb', 'Dessert', '2016-11-09 17:24:12'),
                        (18, 'kiddies-meals-cat-58235bc8e4a73', 'Kiddies meals', '2016-11-09 17:24:24'),
                        (19, 'vegetarian-cat-58235bde22c25', 'Vegetarian', '2016-11-09 17:24:46'),
                        (20, 'extras-cat-58235be8c4dac', 'Extras', '2016-11-09 17:24:56'),
                        (21, 'sauces-cat-58235bf796368', 'Sauces', '2016-11-09 17:25:11');";
            $result1 = $db->rawQueryValue ($query);
            
            $message = "<h1>Thank you</h1>We will set up your account and a Takki representative will be in contact with you shortly.";
         
            
            $mail_text = "Welcome to Takki<br/><br/>
                            We are processing your application and a Takki representative will be in contact with you shortly. 
                            <br/>
                            Below is your email and password you registered with. Please keep them in a safe place for future reference.
                            <br/>
                            Email: $email<br/>
                            Password: $orig_password <br/>
                            <br/>
                            Regards<br/>
                            The Takki Team<br/>
                            ";
            
            
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: MyTakki<admin@mytakki.com>' . "\r\n";
            $headers .= 'Cc: myboss@example.com' . "\r\n";

            mail($email,'Welcome to Takki',$mail_text,$headers); 
        }
    }
    
}

