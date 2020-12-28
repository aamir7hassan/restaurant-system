<?php ob_start();  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_Model extends CI_Model
{
    
    function create_db($database, $user_name, $password)
    {   
        $dsn1      = "mysqli://root:@localhost/restaurent";
        $this->db1 = $this->load->database($dsn1, true); 
        
        if($this->db1->query('CREATE DATABASE '.$database))
            if($this->db1->query('CREATE USER '.$user_name.'@localhost IDENTIFIED BY "'.$password.'"'))
             if($this->db1->query("GRANT ALL PRIVILEGES ON $database.* TO '$user_name'@'%' WITH GRANT OPTION;"))
             {
                 return $this->create_tables($database, $user_name, $password);
             }
        return FALSE;     
    }
    
    
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('accounts');
    }

    public function create($password)
    {
        $data['name']               = $this->input->post('name');
        $data['surname']            = $this->input->post('surname');
        $data['restaurant_name']    = $this->input->post('restaurant_name');
        $data['email']              = $this->input->post('email');
        $data['phone']              = $this->input->post('phone');
        $data['city']               = $this->input->post('city');
        $data['sku']                = $this->input->post('sku');
        $data['unique']             = uniqid('-STORE-');
        
        $data['comments']           = $this->input->post('comments');
        $data['password']           = $password;
                
        $this->db->insert('accounts', $data);        
        return $this->db->insert_id();
    }
    
    public function get_account($id)
    {        
        $query = $this->db->select('sku, name, surname, restaurant_name')->from('accounts')->where('id', $id)->get();
        if($query->num_rows() > 0){
            return $query->row();
        }
        return FALSE;
    }
    
    public function get_account_by_unique($id)
    {        
        $query = $this->db->select('sku')->from('accounts')->where('unique', $id)->get();
        if($query->num_rows() > 0){
            return $query->row();
        }
        return FALSE;
    }

    public function create_tables($database, $user_name, $password)
    { 
        
        $dsn1      = "mysqli://$user_name:$password@localhost/$database";
        $this->db1 = $this->load->database($dsn1, true);  
        
        
        $query = "CREATE TABLE IF NOT EXISTS $database.`app_sessions` (
                `session_id` varchar(40) NOT NULL DEFAULT '0',
                `ip_address` varchar(45) NOT NULL DEFAULT '0',
                `user_agent` varchar(120) NOT NULL,
                `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
                `user_data` text NOT NULL,
                PRIMARY KEY (`session_id`),
                KEY `last_activity_idx` (`last_activity`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `attributes` (
                `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',
                `index` varchar(50) NOT NULL COMMENT 'Index to check attribute names',
                `type` varchar(10) NOT NULL,
                `name` varchar(150) NOT NULL COMMENT 'product attribute display text',
                `values` text NOT NULL,
				`required` int(11) NOT NULL,
				`sort` int(11) NOT NULL,
                `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created date',
                PRIMARY KEY (`id`),
                UNIQUE KEY `index` (`index`),
                KEY `text` (`name`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Products attributes' AUTO_INCREMENT=7 ;
              ";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `categories` (
                `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',
                `index` varchar(50) NOT NULL,
                `name` varchar(50) NOT NULL COMMENT 'category name',
				`sort` int(11) NOT NULL,
				`active` varchar(1) NOT NULL COMMENT '0 no, 1 yes',
				`quantity` int(11) NOT NULL,
                `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `index` (`index`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21;";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `comments` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `meal_id` int(11) NOT NULL,
                `order_id` int(11) NOT NULL,
                `comment` text NOT NULL,
                `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `groups` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(20) NOT NULL,
                `description` varchar(100) NOT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;";
        
        $this->db1->query($query);
        
        $query = "INSERT INTO `groups` (`id`, `name`, `description`) VALUES
                (1, 'admin', 'Super Admin'),
                (2, 'owner', 'Store Owner'),
                (3, 'waiter', 'Waiter'),
                (4, 'customer', 'Customer');";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `logs` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `errno` int(2) NOT NULL,
                `errtype` varchar(32) NOT NULL,
                `errstr` text NOT NULL,
                `errfile` varchar(255) NOT NULL,
                `errline` int(4) NOT NULL,
                `user_agent` varchar(120) NOT NULL,
                `ip_address` varchar(45) NOT NULL DEFAULT '0',
                `time` datetime NOT NULL,
                PRIMARY KEY (`id`,`ip_address`,`user_agent`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `meals` (
                `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',
                `index` varchar(150) NOT NULL COMMENT 'unique identifier in string format',
                `name` varchar(150) NOT NULL COMMENT 'meal title',
                `description` text NOT NULL COMMENT 'Description of meals',
                `price` float NOT NULL COMMENT 'Price for meal',
                `quantity` int(11) NOT NULL,
				`sort` int(11) NOT NULL,
				`take_away` int(1) NOT NULL,
				`special` int(1) NOT NULL,
				`special_days` varchar(250) NOT NULL,
				`special_from` varchar(100) NOT NULL,
				`special_to` varchar(100) NOT NULL,
				`active` int(1) NOT NULL,
				`out_of_stock` int(1) NOT NULL,
				`show_available` varchar(1) NOT NULL COMMENT '0 not show, 1 show',
				`hide_stock` varchar(1) NOT NULL COMMENT '0 yes, 1 no',
                `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created date',
                PRIMARY KEY (`id`),
                UNIQUE KEY `index` (`index`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `meal_attributes` (
                `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',
                `meal_id` int(11) NOT NULL COMMENT 'Forign key to meals table',
                `attribute_id` int(11) NOT NULL COMMENT 'Forign key to attribute table',
                PRIMARY KEY (`id`),
                KEY `fk_attr_attributes` (`attribute_id`),
                KEY `fk_meal_attributes` (`meal_id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `meal_categories` (
                `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',
                `meal_id` int(11) NOT NULL COMMENT 'Forign key to meals table',
                `category_id` int(11) NOT NULL COMMENT 'Forign key to category table',
                PRIMARY KEY (`id`),
                KEY `fk_meal_categories` (`meal_id`),
                KEY `fk_category_categories` (`category_id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `orders` (
                `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',
                `table_id` int(11) NOT NULL,
                `user_id` int(11) NOT NULL,
                `customer_name` varchar(100) NOT NULL COMMENT 'name enterd by customer',
				`contact_name` varchar(100) DEFAULT NULL COMMENT 'take_away contact name',
				`price` float NOT NULL DEFAULT '0',
				`total_price` decimal(10,0) DEFAULT NULL,
				`change_for` varchar(20) DEFAULT NULL COMMENT 'change for - iff take away delivery',
				`tip` decimal(10,2) NOT NULL DEFAULT 0.00,
				`delivery_charge` decimal(10,2) NOT NULL DEFAULT 0.00,
				`budget` decimal(10,2) NOT NULL DEFAULT 0.00,
				`active` int(1) NOT NULL DEFAULT '1',
                `status` varchar(50) NOT NULL COMMENT 'paybill',
                `master` int(1) NOT NULL DEFAULT '0',
                `self_payment` int(1) NOT NULL DEFAULT '1',
                `payed_by` int(11) NOT NULL,
				`payed_by_confirm` varchar(1) DEFAULT NULL COMMENT '3 = splited , 2 request for add bill ,1 user confirmd (yes), 0 user denied (no)  for popup',
				`popup_shown` varchar(1) DEFAULT '0' COMMENT 'supporting colum for payed_y_confirm',
				`payment_method` varchar(10) DEFAULT NULL,
				`reserved_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `released_time` datetime NOT NULL,
				`billrequest_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				`under_18` int(1) DEFAULT NULL,
				`tendered` decimal(10,2) NOT NULL DEFAULT 0.00,
				`tendered_change` decimal(10,2) NOT NULL DEFAULT 0.00,
				`options` varchar(2) NOT NULL COMMENT '1 email, 2 print, 3 email and print, 4 none',
				`email` varchar(100) NOT NULL COMMENT 'email where bill will be sent.',
				`cell` varchar(20) NOT NULL,
				`address` varchar(255) NOT NULL COMMENT 'address ',
				`coords` varchar(255) NOT NULL COMMENT 'address coordinates for map',
				`passcode` varchar(10) NOT NULL,
				`type` varchar(20) NOT NULL COMMENT 'delivery / collection',
				`allocated` varchar(1) NOT NULL DEFAULT '0' COMMENT '0 free, 1 allocated',
				`allocated_user` int(11) NOT NULL COMMENT 'only this user can deselect the orders',
				`order_start` varchar(1) DEFAULT '' COMMENT '3 in transit, 4 delivered - just 3 or 4',
				`user_waiter_confirm` int(1) DEFAULT 0 COMMENT 'if waiter order on user behalf then this user should show popup, no processing - just notification | 2 means completed',
                PRIMARY KEY (`id`),
                KEY `fk_orders_table` (`table_id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
              ";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `order_details` (
                `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',
                `order_id` int(11) NOT NULL,
				`temp_user` int(11) NOT NULL COMMENT 'if waiter added on user behalf , if null then user added order.',
                `meal_id` int(11) NOT NULL COMMENT 'id from meals table',
				`qty` int(11) NOT NULL,
				`meal_price` decimal(10,2) NOT NULL COMMENT 'Only meal price',
				`attribute_price` decimal(10,2) NOT NULL COMMENT 'All attributes total price',
				`attribute_price_log` varchar(250) NOT NULL COMMENT 'Attributes separate price list',
				`price` decimal(10,2) NOT NULL COMMENT 'price of meal+attributes',
				`attr_cats` text NOT NULL,
				`attribute` text NOT NULL,
				`category` varchar(250) NOT NULL,
				`processed` int(1) NOT NULL DEFAULT 0 COMMENT 'waiter processed or not  0-Not processed; 1-left kitchen; 2-waiter processed order, 3 in transit,   4 driver delivered it',
				`comments` text NOT NULL,
				`order_time` timestamp NOT NULL DEFAULT current_timestamp(),
				`waiter_process_time` datetime NOT NULL,
				`process_time` datetime NOT NULL,
				`kitchen_left` datetime NOT NULL,
                PRIMARY KEY (`id`),
                KEY `meal_order_fk` (`meal_id`),
                KEY `FK_orders_order` (`order_id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
              ";
        
        $this->db1->query($query);
		
		$query = "CREATE TABLE IF NOT EXISTS `settings` (`id` int(11) NOT NULL AUTO_INCREMENT,`index` varchar(50) NOT NULL,`value` varchar(250) NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
        $this->db1->query($query)
        
        $query = "CREATE TABLE IF NOT EXISTS `tables` (
                `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',
                `name` varchar(150) NOT NULL COMMENT 'name of the tables',
                `seats` int(11) NOT NULL COMMENT 'number of seats for a table',
                `qr_code` varchar(250) NOT NULL COMMENT 'QR code to access table',
				`virtual` int(11) NOT NULL,
				`address` text NOT NULL,
                `unique` varchar(150) NOT NULL,
                `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'table added date',
                PRIMARY KEY (`id`),
                UNIQUE KEY `unique` (`unique`),
                UNIQUE KEY `name` (`name`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `users` (
                `id` int(10) NOT NULL AUTO_INCREMENT,
                `ip_address` varbinary(16) NOT NULL,
                `username` varchar(100) NOT NULL,
                `password` varchar(80) NOT NULL,
				`pass` varchar(255) NOT NULL,
                `salt` varchar(40) DEFAULT NULL,
                `email` varchar(100) NOT NULL,
                `activation_code` varchar(40) DEFAULT NULL,
                `forgotten_password_code` varchar(40) DEFAULT NULL,
                `forgotten_password_time` int(11) unsigned DEFAULT NULL,
                `remember_code` varchar(40) DEFAULT NULL,
                `created_on` int(11) unsigned NOT NULL,
                `last_login` int(11) unsigned DEFAULT NULL,
                `active` tinyint(1) unsigned DEFAULT NULL,
                `first_name` varchar(50) DEFAULT NULL,
                `last_name` varchar(50) DEFAULT NULL,
                `company` varchar(100) DEFAULT NULL,
                `phone` varchar(20) DEFAULT NULL,
                `site` varchar(50) NOT NULL COMMENT 'Site id to which this user belongs to',
                `waiter_float` float NULL COMMENT 'waiter float',
				`take_away` varchar(1) DEFAULT 0 COMMENT '1 yes, 0 no',
				`role` varchar(10)  COMMENT 'waiter,driver', 
                PRIMARY KEY (`id`),
                UNIQUE KEY `email` (`email`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
              ";
        
        $this->db1->query($query);
        
        $query = "INSERT INTO `users` (`email`,`created_on`, `password`, `active`, `first_name`, `last_name`, `company`, `phone`, `site`, `waiter_float`) VALUES
                ('admin@admin.com', 1460453630, 'e77b2506649d6a557d233b55a475518c24cf294c',  1, 'Admin', 'User', 'Nuro', '123-123-2346', '', ''), 
                ('".$this->input->post('email')."', 1460453630, '".$password."',  1, '".$this->input->post('name')."', '".$this->input->post('surname')."', '', '".$this->input->post('phone')."', '')
                ";
        
        $this->db1->query($query);
        $query = "CREATE TABLE IF NOT EXISTS `users_groups` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `group_id` int(11) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `fk_group_groups` (`group_id`),
                KEY `fk_user_groups` (`user_id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;
              ";
        
        $this->db1->query($query);
        
        $query = "INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES(1, 1, 1), (2, 2, 2)";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `waiter_notice` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `order_id` int(11) NOT NULL,
                `table_id` int(11) NOT NULL,
                `message` varchar(250) NOT NULL DEFAULT 'Please give attention to',
                `status` int(1) NOT NULL DEFAULT '1',
                `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11;";
		$this->db1->query($query);
		
		$qeury = "CREATE TABLE IF NOT EXISTS `waiter_notifications` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `order_id` int(11) NOT NULL,
			  `table_id` int(11) NOT NULL,
			  `waiter_id` int(11) NOT NULL,
			  `type` enum('new','delivered','waiting','') NOT NULL,
			  `status` int(11) NOT NULL,
			  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$this->db1->query($query);
		
		$query  = "CREATE TABLE IF NOT EXISTS `waiter_table_relation` (`id` int(11) NOT NULL AUTO_INCREMENT,`waiter_id` int(11) NOT NULL,`table_id` int(11) NOT NULL,PRIMARY KEY (`id`),KEY `FK_waiters_map` (`waiter_id`),KEY `FK_tables_map` (`table_id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
        $this->db1->query($query);
		
        $query = "ALTER TABLE `meal_attributes`
                ADD CONSTRAINT `fk_attr_attributes` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE,
                ADD CONSTRAINT `fk_meal_attributes` FOREIGN KEY (`meal_id`) REFERENCES `meals` (`id`) ON DELETE CASCADE;";
        
        $this->db1->query($query);
        
        $query = "ALTER TABLE `meal_categories`
                ADD CONSTRAINT `fk_category_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
                ADD CONSTRAINT `fk_meal_categories` FOREIGN KEY (`meal_id`) REFERENCES `meals` (`id`) ON DELETE CASCADE;";
        
        $this->db1->query($query);
        
        $query = "ALTER TABLE `orders`
                  ADD CONSTRAINT `fk_orders_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
        
        $this->db1->query($query);
        
        $query = "ALTER TABLE `order_details`
                ADD CONSTRAINT `FK_orders_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                ADD CONSTRAINT `meal_order_fk` FOREIGN KEY (`meal_id`) REFERENCES `meals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
        
        $this->db1->query($query);
        
        $query ="CREATE TABLE `waiters` (
                `id` int(11) NOT NULL COMMENT 'unique identifier',
                `name` varchar(100) NOT NULL COMMENT 'name of waiter',
                `unique` varchar(150) NOT NULL COMMENT 'unique identifier',
                `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
              );";
        $this->db1->query($query);
        
        return TRUE;
    }
}